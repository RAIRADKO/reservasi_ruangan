@extends('layouts.admin')

@section('title', 'Laporan Statistik')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="bi bi-graph-up me-2"></i>
        Laporan Statistik
    </h1>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pengguna Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVisitors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pengunjung (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $monthlyVisitors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-month fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pengunjung (Hari Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayVisitors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reservasi per Ruangan</h6>
                </div>
                <div class="card-body">
                    @foreach($reservationsByRoom as $room)
                    <h4 class="small font-weight-bold">{{ $room->nama_ruangan }} <span
                            class="float-right">{{ $room->reservations_count }} Reservasi</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $reservationsByRoom->max('reservations_count') > 0 ? ($room->reservations_count / $reservationsByRoom->max('reservations_count')) * 100 : 0 }}%"
                            aria-valuenow="{{ $room->reservations_count }}" aria-valuemin="0" aria-valuemax="{{ $reservationsByRoom->max('reservations_count') }}"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reservasi per Instansi/Dinas</h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @foreach($reservationsByDinas as $dinas)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $dinas->name }}
                                <span class="badge bg-primary rounded-pill">{{ $dinas->reservations_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card .border-left-primary {
        border-left: .25rem solid #4e73df!important;
    }
    .card .border-left-success {
        border-left: .25rem solid #1cc88a!important;
    }
    .card .border-left-info {
        border-left: .25rem solid #36b9cc!important;
    }
    .text-xs {
        font-size: .7rem;
    }
</style>
@endsection