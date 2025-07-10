<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\BlockedDate;
use App\Models\Visitor;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = RoomInfo::all();
        // --- PERBAIKAN: Mengganti firstOrFail() dengan first() ---
        // Ini mencegah error 404 jika tabel room_infos masih kosong.
        $room = $rooms->first();
        $totalRooms = $rooms->count();

        // --- PENGAMAN: Menangani kasus jika tidak ada ruangan sama sekali ---
        // Jika belum ada data ruangan, halaman home akan tetap tampil dengan data kosong.
        if (!$room) {
            return view('home', [
                'room' => null,
                'rooms' => collect(), // Mengirim collection kosong
                'reservationDates' => [],
                'manualBlockedDates' => [],
                'fullDates' => [],
                'todayVisitors' => Visitor::whereDate('visit_date', today())->count(),
                'monthlyVisitors' => Visitor::whereMonth('visit_date', today()->month)->whereYear('visit_date', today()->year)->count(),
                'totalVisitors' => Visitor::count(),
                'todayEvents' => collect() // Mengirim collection kosong
            ]);
        }

        // Jam operasional dari config
        $operatingStart = Carbon::parse(config('room.operating_hours.start', '08:00'));
        $operatingEnd = Carbon::parse(config('room.operating_hours.end', '16:00'));

        // Ambil semua reservasi yang disetujui
        $allReservations = Reservation::where('status', Reservation::STATUS_APPROVED)
            ->orderBy('tanggal')
            ->orderBy('room_info_id')
            ->orderBy('jam_mulai')
            ->get();

        $reservationsByDate = $allReservations->groupBy(function ($reservation) {
            return $reservation->tanggal->format('Y-m-d');
        });

        $fullDates = [];
        $reservationDates = [];

        foreach ($reservationsByDate as $date => $reservationsOnDate) {
            $roomsBookedSolid = 0;
            $reservationsByRoom = $reservationsOnDate->groupBy('room_info_id');

            if ($totalRooms > 0 && count($reservationsByRoom) === $totalRooms) {
                foreach ($reservationsByRoom as $roomId => $roomReservations) {
                    $mergedTimes = [];

                    foreach ($roomReservations as $res) {
                        $start = Carbon::parse($res->jam_mulai);
                        $end = Carbon::parse($res->jam_selesai);

                        if (empty($mergedTimes)) {
                            $mergedTimes[] = ['start' => $start, 'end' => $end];
                            continue;
                        }

                        $merged = false;
                        foreach ($mergedTimes as $key => $mergedTime) {
                            if ($start <= $mergedTime['end'] && $end >= $mergedTime['start']) {
                                $mergedTimes[$key]['start'] = min($start, $mergedTime['start']);
                                $mergedTimes[$key]['end'] = max($end, $mergedTime['end']);
                                $merged = true;
                                break;
                            }
                        }
                        if (!$merged) {
                            $mergedTimes[] = ['start' => $start, 'end' => $end];
                        }
                    }

                    if (count($mergedTimes) === 1) {
                        $bookedStart = $mergedTimes[0]['start'];
                        $bookedEnd = $mergedTimes[0]['end'];

                        if ($bookedStart <= $operatingStart && $bookedEnd >= $operatingEnd) {
                            $roomsBookedSolid++;
                        }
                    }
                }
            }

            if ($totalRooms > 0 && $roomsBookedSolid === $totalRooms) {
                $fullDates[] = $date;
            } else {
                $reservationDates[] = $date;
            }
        }

        // Tanggal diblokir manual
        $manualBlockedDates = BlockedDate::pluck('date')->map(function ($date) {
            return $date->format('Y-m-d');
        })->values()->all();

        $reservationDates = array_diff($reservationDates, $manualBlockedDates, $fullDates);

        // Statistik pengunjung dari tabel Visitor
        $todayVisitors = Visitor::whereDate('visit_date', today())->count();
        $monthlyVisitors = Visitor::whereMonth('visit_date', today()->month)
                                  ->whereYear('visit_date', today()->year)
                                  ->count();
        $totalVisitors = Visitor::count();

        $todayEvents = Reservation::with('roomInfo')
            ->where('status', Reservation::STATUS_APPROVED)
            ->whereDate('tanggal', today())
            ->get();

        return view('home', compact(
            'room', 'rooms', 'reservationDates', 'manualBlockedDates', 'fullDates',
            'todayVisitors', 'monthlyVisitors', 'totalVisitors', 'todayEvents'
        ));
    }

    public function showReservationsByDate($date)
    {
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(404, 'Format tanggal tidak valid');
        }

        $reservations = Reservation::with(['user', 'roomInfo'])
            ->where('tanggal', $date)
            ->where('status', Reservation::STATUS_APPROVED)
            ->orderBy('jam_mulai')
            ->get();

        $rooms = RoomInfo::all();

        return view('reservations.date', compact('reservations', 'date', 'rooms'));
    }

    public function showReservationsByDateAndRoom($date, $roomId = null)
    {
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(404, 'Format tanggal tidak valid');
        }

        $query = Reservation::with(['user', 'roomInfo'])
            ->where('tanggal', $date)
            ->where('status', Reservation::STATUS_APPROVED);

        if ($roomId) {
            $query->where('room_info_id', $roomId);
            $room = RoomInfo::findOrFail($roomId);
        } else {
            $room = null;
        }

        $reservations = $query->orderBy('jam_mulai')->get();
        $rooms = RoomInfo::all();

        return view('reservations.date', compact('reservations', 'date', 'room', 'rooms'));
    }
}