<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\BlockedDate;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingCount = Reservation::where('status', Reservation::STATUS_PENDING)->count();
        $approvedCount = Reservation::where('status', Reservation::STATUS_APPROVED)->count();
        
        $reservations = Reservation::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'reservations'));
    }

    public function reservations()
    {
        $reservations = Reservation::with('user')
            ->orderBy('tanggal', 'desc')
            ->paginate(10); // Menggunakan paginasi
            
        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Reservation::statusOptions()))],
        ]);
        
        $reservation->update(['status' => $request->status]);
        
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Reservasi berhasil dihapus.');
    }

    public function editRoom()
    {
        $room = RoomInfo::firstOrFail(); // Menggunakan firstOrFail untuk memastikan data ada
        return view('admin.room.edit', compact('room'));
    }

    public function updateRoom(Request $request)
    {
        $room = RoomInfo::firstOrFail();
        
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);
        
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($room->foto) {
                Storage::delete('public/' . $room->foto);
            }
            
            $path = $request->file('foto')->store('room_photos', 'public');
            $data['foto'] = $path;
        }
        
        $room->update($data);
        
        return redirect()->route('admin.room.edit')->with('success', 'Informasi ruangan berhasil diperbarui.');
    }

    public function showCalendarManagement()
    {
        $blockedDates = BlockedDate::pluck('date')->map(function ($date) {
            return $date->format('Y-m-d');
        });

        return view('admin.calendar.management', compact('blockedDates'));
    }

    public function storeBlockedDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d|unique:blocked_dates,date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        BlockedDate::create(['date' => $request->date]);

        return response()->json(['message' => 'Tanggal berhasil diblokir.']);
    }

    public function destroyBlockedDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d|exists:blocked_dates,date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        BlockedDate::where('date', $request->date)->delete();

        return response()->json(['message' => 'Blokir tanggal berhasil dibuka.']);
    }

}