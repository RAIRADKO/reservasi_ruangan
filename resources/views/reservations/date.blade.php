@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-gradient-primary text-white p-3 p-md-4 rounded-3 shadow-sm">
                <div class="mb-3 mb-md-0 text-center text-md-start">
                    <h1 class="mb-1 fw-bold h4 h-md-3">
                        <i class="bi bi-calendar-event me-2"></i>
                        Reservasi pada Tanggal
                    </h1>
                    <p class="mb-0 fs-5 opacity-90">
                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                    </p>
                    @if(isset($room))
                        <small class="opacity-75">
                            <i class="bi bi-door-open me-1"></i>
                            Ruangan: {{ $room->nama_ruangan }}
                        </small>
                    @endif
                </div>
                <a href="{{ route('home') }}" class="btn btn-light btn-sm btn-md-lg shadow-sm mt-2 mt-md-0">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali ke Kalender
                </a>
            </div>
        </div>
    </div>

    @if(isset($rooms) && $rooms->count() > 1)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <label for="room-filter" class="form-label fw-bold">
                                <i class="bi bi-funnel me-2"></i>Filter Ruangan:
                            </label>
                        </div>
                        <div class="col-12 col-md-9">
                            <select id="room-filter" class="form-select" onchange="filterByRoom()">
                                <option value="{{ route('reservations.date', ['date' => $date]) }}">Semua Ruangan</option>
                                @foreach($rooms as $roomOption)
                                    <option
                                        value="{{ route('reservations.date.room', ['date' => $date, 'room' => $roomOption->id]) }}"
                                        {{ (isset($room) && $room->id == $roomOption->id) ? 'selected' : '' }}>
                                        {{ $roomOption->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!isset($room) && $reservations->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h4 class="mb-0 fw-bold h5">
                        <i class="bi bi-door-closed me-2 text-primary"></i>
                        Status Ruangan
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $roomReservations = $reservations->groupBy('room_info_id');
                            $allRooms = $rooms ?? collect();
                            $workHoursStart = '08:00:00';
                            $workHoursEnd = '16:00:00';
                        @endphp

                        @foreach($allRooms as $roomItem)
                        @php
                            $reservationsForRoom = $roomReservations->get($roomItem->id, collect());

                            $status = 'available';
                            $statusText = 'Tersedia';
                            $icon = 'bi-door-open-fill';
                            $color = 'success';

                            if ($reservationsForRoom->isNotEmpty()) {
                                $minStart = $reservationsForRoom->min('jam_mulai');
                                $maxEnd = $reservationsForRoom->max('jam_selesai');

                                if ($minStart <= $workHoursStart && $maxEnd >= $workHoursEnd) {
                                    $status = 'full';
                                    $statusText = 'Penuh';
                                    $icon = 'bi-door-closed-fill';
                                    $color = 'danger';
                                } else {
                                    $status = 'partial';
                                    $statusText = 'Sebagian';
                                    $icon = 'bi-door-closed';
                                    $color = 'warning';
                                }
                            }
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="room-status-card p-3 rounded-3 h-100
                                bg-{{ $color }}-subtle border-{{ $color }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $roomItem->nama_ruangan }}</h6>
                                        <small class="text-{{ $color }} fw-bold">
                                            <i class="bi {{ $icon }} me-1"></i>
                                            @if($status === 'full')
                                                Penuh ({{ $reservationsForRoom->count() }} Reservasi)
                                            @elseif($status === 'partial')
                                                Sebagian Terpakai ({{ $reservationsForRoom->count() }} Reservasi)
                                            @else
                                                Tersedia Penuh
                                            @endif
                                        </small>
                                    </div>
                                    <div class="status-icon">
                                        <i class="bi {{ $icon }} text-{{ $color }} fs-4"></i>
                                    </div>
                                </div>

                                @if($reservationsForRoom->isNotEmpty())
                                <div class="mt-2">
                                    <small class="text-muted">Waktu reservasi:</small>
                                    <div class="reserved-times mt-1">
                                        @foreach($reservationsForRoom as $res)
                                        <span class="badge bg-{{ $color }} bg-opacity-75 me-1 mb-1">
                                            {{ date('H:i', strtotime($res->jam_mulai)) }}-{{ date('H:i', strtotime($res->jam_selesai)) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($reservations->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="text-muted h5 mb-2">Tidak Ada Reservasi</h3>
                    <p class="text-muted">Tidak ada reservasi yang disetujui pada tanggal ini.</p>
                </div>
            </div>
        </div>
    @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h4 class="mb-0 fw-bold h5">
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                            Jadwal Visual Ruangan
                            @if(isset($room))
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-2">
                                    {{ $room->nama_ruangan }}
                                </span>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="schedule-timeline-wrapper" style="overflow-x: auto;">
                            <div class="schedule-timeline position-relative" style="min-width: 600px;">
                                @php
                                    $startHour = 8;
                                    $endHour = 17;
                                    $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
                                @endphp

                                <div class="time-markers mb-3">
                                    @for($hour = $startHour; $hour <= $endHour; $hour++)
                                        <div class="time-marker"
                                             style="left: {{ (($hour - $startHour) / ($endHour - $startHour)) * 100 }}%;">
                                            {{ sprintf('%02d:00', $hour) }}
                                        </div>
                                    @endfor
                                </div>

                                <div class="hour-lines position-absolute top-0 start-0 w-100 h-100">
                                    @for($hour = $startHour; $hour <= $endHour; $hour++)
                                        <div class="hour-line position-absolute top-0 bottom-0"
                                             style="left: {{ (($hour - $startHour) / ($endHour - $startHour)) * 100 }}%;"></div>
                                    @endfor
                                </div>

                                <div class="reservations-timeline position-relative">
                                    @foreach($reservations as $index => $reservation)
                                    @php
                                        $startTime = \Carbon\Carbon::parse($reservation->jam_mulai);
                                        $endTime = \Carbon\Carbon::parse($reservation->jam_selesai);
                                        $startPosition = (($startTime->hour - $startHour) * 60 + $startTime->minute) / (($endHour - $startHour) * 60);
                                        $duration = $startTime->diffInMinutes($endTime) / (($endHour - $startHour) * 60);
                                        $colorClass = $colors[$index % count($colors)];
                                        $ruanganNama = $reservation->roomInfo->nama_ruangan ?? 'N/A';
                                        $jamMulai = date('H:i', strtotime($reservation->jam_mulai));
                                        $jamSelesai = date('H:i', strtotime($reservation->jam_selesai));
                                        $instansi = $reservation->dinas->name ?? 'N/A';
                                    @endphp
                                    <div class="reservation-block {{ $colorClass }} shadow-sm mb-3 rounded-3 position-relative p-2"
                                         style="left: {{ $startPosition * 100 }}%; width: {{ $duration * 100 }}%; min-height: 60px;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#reservationModal"
                                         data-reservation-id="{{ $reservation->id }}"
                                         data-reservation-nama="{{ $reservation->nama }}"
                                         data-reservation-instansi="{{ $instansi }}"
                                         data-reservation-keperluan="{{ $reservation->keperluan }}"
                                         data-reservation-ruangan="{{ $ruanganNama }}"
                                         data-reservation-jam-mulai="{{ $jamMulai }}"
                                         data-reservation-jam-selesai="{{ $jamSelesai }}"
                                         data-reservation-tanggal="{{ \Carbon\Carbon::parse($reservation->tanggal)->isoFormat('dddd, D MMMM Y') }}"
                                         data-reservation-durasi="{{ $startTime->diff($endTime)->h }} jam {{ $startTime->diff($endTime)->i }} menit"
                                         data-reservation-status="Disetujui">
                                        <div class="reservation-content text-white">
                                            <div class="reservation-title fw-bold">
                                                <i class="bi bi-person-fill me-1"></i>
                                                {{ $reservation->nama }}
                                            </div>
                                            <div class="reservation-details mt-1">
                                                <small class="reservation-room d-block opacity-90">
                                                    <i class="bi bi-door-closed-fill me-1"></i>
                                                    {{ Str::limit($ruanganNama, 15) }}
                                                </small>
                                                <small class="reservation-time d-block opacity-90">
                                                    <i class="bi bi-clock-fill me-1"></i>
                                                    {{ $jamMulai }} - {{ $jamSelesai }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h4 class="mb-0 fw-bold h5">
                            <i class="bi bi-table me-2 text-primary"></i>
                            Detail Reservasi
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-clock me-2"></i>Waktu
                                        </th>
                                        <th class="border-0 fw-bold py-3 d-none d-md-table-cell">
                                            <i class="bi bi-door-open me-2"></i>Ruangan
                                        </th>
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-person me-2"></i>Nama
                                        </th>
                                        <th class="border-0 fw-bold py-3 d-none d-md-table-cell">
                                            <i class="bi bi-journal-text me-2"></i>Keperluan
                                        </th>
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-hourglass-split me-2"></i>Durasi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $index => $reservation)
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="badge {{ $colors[$index % count($colors)] }} me-2" style="width: 4px; height: 20px;"></span>
                                                <strong>
                                                    {{ date('H:i', strtotime($reservation->jam_mulai)) }} -
                                                    {{ date('H:i', strtotime($reservation->jam_selesai)) }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td class="py-3 d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-door-closed-fill text-primary me-2"></i>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                    {{ $reservation->roomInfo->nama_ruangan ?? 'Tidak diketahui' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($reservation->nama, 0, 1)) }}
                                                </div>
                                                <strong>{{ $reservation->nama }}</strong>
                                            </div>
                                        </td>
                                        <td class="py-3 d-none d-md-table-cell">
                                            <span class="text-muted">{{ $reservation->keperluan }}</span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $start = \Carbon\Carbon::parse($reservation->jam_mulai);
                                                $end = \Carbon\Carbon::parse($reservation->jam_selesai);
                                                $duration = $start->diff($end);
                                            @endphp
                                            <span class="badge bg-light text-dark">
                                                {{ $duration->h }} jam {{ $duration->i }} menit
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


<!-- Modal dengan ukuran yang diperkecil -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <!-- Header dengan gradient - diperkecil -->
      <div class="modal-header bg-gradient-primary text-white border-0 p-3">
        <div class="d-flex align-items-center">
          <div class="modal-icon me-2">
            <i class="bi bi-calendar-check-fill fs-5"></i>
          </div>
          <div>
            <h6 class="modal-title mb-0" id="reservationModalLabel">Detail Reservasi</h6>
            <small class="opacity-75">Informasi reservasi</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Body dengan layout yang lebih kompak -->
      <div class="modal-body p-3">
        <!-- Section Info Pemesan -->
        <div class="info-section mb-3">
          <div class="section-header mb-2">
            <h6 class="fw-bold text-primary mb-0 fs-6">
              <i class="bi bi-person-circle me-1"></i>
              Informasi Pemesan
            </h6>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <div class="info-item d-flex align-items-center">
                <div class="info-icon me-2">
                  <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-person-fill"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Nama</small>
                  <strong class="fs-7" id="modalNama">-</strong>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="info-item d-flex align-items-center">
                <div class="info-icon me-2">
                  <div class="icon-circle bg-success bg-opacity-10 text-success">
                    <i class="bi bi-building"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Instansi</small>
                  <strong class="fs-7" id="modalInstansi">-</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Info Reservasi -->
        <div class="info-section mb-3">
          <div class="section-header mb-2">
            <h6 class="fw-bold text-primary mb-0 fs-6">
              <i class="bi bi-calendar-event me-1"></i>
              Detail Reservasi
            </h6>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <div class="info-item d-flex align-items-center mb-2">
                <div class="info-icon me-2">
                  <div class="icon-circle bg-info bg-opacity-10 text-info">
                    <i class="bi bi-door-open-fill"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Ruangan</small>
                  <strong class="fs-7" id="modalRuangan">-</strong>
                </div>
              </div>
              <div class="info-item d-flex align-items-center">
                <div class="info-icon me-2">
                  <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-calendar-day-fill"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Tanggal</small>
                  <strong class="fs-7" id="modalTanggal">-</strong>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="info-item d-flex align-items-center mb-2">
                <div class="info-icon me-2">
                  <div class="icon-circle bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-clock-fill"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Waktu</small>
                  <strong class="fs-7">
                    <span id="modalJamMulai">-</span> - <span id="modalJamSelesai">-</span>
                  </strong>
                </div>
              </div>
              <div class="info-item d-flex align-items-center">
                <div class="info-icon me-2">
                  <div class="icon-circle bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-hourglass-split"></i>
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block">Durasi</small>
                  <strong class="fs-7" id="modalDurasi">-</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Keperluan -->
        <div class="info-section mb-3">
          <div class="section-header mb-2">
            <h6 class="fw-bold text-primary mb-0 fs-6">
              <i class="bi bi-journal-text me-1"></i>
              Keperluan
            </h6>
          </div>
          <div class="keperluan-box p-2 bg-light rounded-3">
            <p class="mb-0 fs-7" id="modalKeperluan">-</p>
          </div>
        </div>

        <!-- Section Status -->
        <div class="info-section">
          <div class="section-header mb-2">
            <h6 class="fw-bold text-primary mb-0 fs-6">
              <i class="bi bi-check-circle me-1"></i>
              Status
            </h6>
          </div>
          <div class="status-display d-flex align-items-center">
            <div class="status-icon me-2">
              <div class="icon-circle bg-success bg-opacity-10 text-success">
                <i class="bi bi-check-circle-fill"></i>
              </div>
            </div>
            <div>
              <span class="badge bg-success px-2 py-1" id="modalStatus">Disetujui</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="modal-footer border-0 p-3 bg-light">
        <button type="button" class="btn btn-primary btn-sm px-3" data-bs-dismiss="modal">
          <i class="bi bi-check-lg me-1"></i>
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<style>
/* Styles untuk modal yang diperkecil */
.modal-content {
  border-radius: 0.75rem;
  overflow: hidden;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);
}

.modal-icon {
  width: 35px;
  height: 35px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-section {
  transition: all 0.3s ease;
}

.section-header {
  position: relative;
  padding-bottom: 4px;
}

.section-header::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 1px;
  background: linear-gradient(90deg, #0066cc, #004499);
  border-radius: 1px;
}

.info-item {
  transition: all 0.3s ease;
  padding: 4px;
  border-radius: 6px;
}

.info-item:hover {
  background-color: rgba(0, 102, 204, 0.05);
  transform: translateX(3px);
}

.icon-circle {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.info-item:hover .icon-circle {
  transform: scale(1.05);
}

.keperluan-box {
  border-left: 3px solid #0066cc;
  transition: all 0.3s ease;
}

.keperluan-box:hover {
  background-color: #e3f2fd !important;
  transform: translateX(3px);
}

.status-display {
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #e0e0e0;
  transition: all 0.3s ease;
}

.status-display:hover {
  background-color: rgba(40, 167, 69, 0.05);
  border-color: #28a745;
}

.modal-footer {
  border-radius: 0 0 0.75rem 0.75rem;
}

.btn-primary {
  border-radius: 6px;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 10px rgba(0, 102, 204, 0.3);
}

/* Font size utilities */
.fs-7 {
  font-size: 0.875rem;
}

/* Animasi untuk modal */
.modal.fade .modal-dialog {
  transform: scale(0.9) translateY(-30px);
  transition: all 0.3s ease;
}

.modal.show .modal-dialog {
  transform: scale(1) translateY(0);
}

/* Responsive */
@media (max-width: 768px) {
  .modal-dialog {
    margin: 1rem;
  }
  
  .modal-body .row {
    margin: 0;
  }
  
  .info-item {
    margin-bottom: 0.5rem !important;
  }
  
  .modal-header {
    padding: 1rem !important;
  }
  
  .modal-header .modal-title {
    font-size: 1rem;
  }
  
  .modal-icon {
    width: 30px;
    height: 30px;
  }
  
  .modal-icon i {
    font-size: 1rem;
  }
  
  .icon-circle {
    width: 24px;
    height: 24px;
    font-size: 0.8rem;
  }
  
  .info-section {
    padding: 0 !important;
  }
  
  .modal-footer {
    padding: 1rem !important;
    text-align: center;
  }
  
  .fs-7 {
    font-size: 0.8rem;
  }
}

/* Loading animation untuk saat modal dibuka */
.modal-body .info-item {
  opacity: 0;
  animation: fadeInUp 0.4s ease forwards;
}

.modal-body .info-item:nth-child(1) { animation-delay: 0.05s; }
.modal-body .info-item:nth-child(2) { animation-delay: 0.1s; }
.modal-body .info-item:nth-child(3) { animation-delay: 0.15s; }
.modal-body .info-item:nth-child(4) { animation-delay: 0.2s; }

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Hover effect untuk close button */
.btn-close-white:hover {
  transform: rotate(90deg);
  transition: transform 0.3s ease;
}

/* Custom scrollbar untuk modal body jika terlalu panjang */
.modal-body::-webkit-scrollbar {
  width: 4px;
}

.modal-body::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 2px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
@endsection

@section('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);
    }

    .schedule-timeline-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .schedule-timeline {
        min-height: 200px;
        background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0.5rem;
        padding: 20px;
        position: relative;
        min-width: 600px;
    }

    .time-markers {
        position: relative;
        height: 30px;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .time-marker {
        position: absolute;
        top: 0;
        transform: translateX(-50%);
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        background: white;
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .reservations-timeline {
        position: relative;
    }

    .reservation-block {
        position: absolute;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden; /* Prevent content overflow */
        line-height: 1.3;
    }

    .reservation-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        z-index: 10;
    }

    .reservation-content {
        padding: 0.2rem;
    }

    .reservation-title {
        font-size: 0.8rem;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .reservation-details {
        font-size: 0.7rem;
    }

    .reservation-details small {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 3px solid #0d6efd !important; 
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .room-status-card {
        border: 2px solid;
        transition: all 0.3s ease;
    }

    .room-status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .reserved-times {
        max-height: 60px;
        overflow-y: auto;
    }

    .reserved-times::-webkit-scrollbar {
        width: 4px;
    }

    .reserved-times::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .reserved-times::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .reserved-times::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Garis vertikal setiap jam */
    .hour-lines {
        pointer-events: none;
    }

    .hour-line {
        width: 1px;
        background-color: rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .schedule-timeline {
            padding: 15px;
            min-width: 100%;
        }

        .reservation-block {
            min-height: 50px !important;
            padding: 0.2rem !important;
        }

        .reservation-title {
            font-size: 0.75rem;
        }

        .reservation-details {
            font-size: 0.65rem;
        }

        /* Sembunyikan ruangan jika terlalu sempit di mobile */
        .reservation-room {
            display: none !important;
        }

        .time-marker {
            font-size: 0.7rem;
            padding: 1px 4px;
        }

        .table th, .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }

        .avatar-circle {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }

        .card-header h4 {
            font-size: 1.1rem;
        }

        .bg-gradient-primary h1 {
            font-size: 1.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .bg-gradient-primary h1 {
            font-size: 1.3rem !important;
        }

        .bg-gradient-primary p {
            font-size: 0.9rem;
        }

        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .room-status-card {
            padding: 15px !important;
        }

        .reservation-block {
            min-height: 45px !important;
        }

        .reservation-title {
            font-size: 0.7rem;
        }

        .reservation-details {
            font-size: 0.6rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function filterByRoom() {
        const select = document.getElementById('room-filter');
        const url = select.value;
        if (url) {
            window.location.href = url;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var reservationModal = document.getElementById('reservationModal');
        reservationModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            var nama = button.getAttribute('data-reservation-nama');
            var instansi = button.getAttribute('data-reservation-instansi');
            var ruangan = button.getAttribute('data-reservation-ruangan');
            var tanggal = button.getAttribute('data-reservation-tanggal');
            var jamMulai = button.getAttribute('data-reservation-jam-mulai');
            var jamSelesai = button.getAttribute('data-reservation-jam-selesai');
            var durasi = button.getAttribute('data-reservation-durasi');
            var keperluan = button.getAttribute('data-reservation-keperluan');
            var status = button.getAttribute('data-reservation-status');

            // Update the modal's content.
            var modalTitle = reservationModal.querySelector('.modal-title');
            var modalNama = reservationModal.querySelector('#modalNama');
            var modalInstansi = reservationModal.querySelector('#modalInstansi');
            var modalRuangan = reservationModal.querySelector('#modalRuangan');
            var modalTanggal = reservationModal.querySelector('#modalTanggal');
            var modalJamMulai = reservationModal.querySelector('#modalJamMulai');
            var modalJamSelesai = reservationModal.querySelector('#modalJamSelesai');
            var modalDurasi = reservationModal.querySelector('#modalDurasi');
            var modalKeperluan = reservationModal.querySelector('#modalKeperluan');
            var modalStatus = reservationModal.querySelector('#modalStatus');

            modalTitle.textContent = 'Detail Reservasi: ' + nama;
            modalNama.textContent = nama;
            modalInstansi.textContent = instansi;
            modalRuangan.textContent = ruangan;
            modalTanggal.textContent = tanggal;
            modalJamMulai.textContent = jamMulai;
            modalJamSelesai.textContent = jamSelesai;
            modalDurasi.textContent = durasi;
            modalKeperluan.textContent = keperluan;
            modalStatus.textContent = status;
        });
    });
</script>
@endsection