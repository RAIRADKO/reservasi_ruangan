@extends('layouts.admin')

@section('title', 'Manajemen Kalender')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bi bi-calendar3 me-2"></i>
                        Manajemen Kalender Ketersediaan
                    </h1>
                    <p class="text-muted mb-0 mt-1">Kelola ketersediaan tanggal untuk reservasi</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="bi bi-question-circle me-1"></i>
                        Bantuan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-success mb-1">Tersedia</h6>
                            <p class="card-text text-muted small mb-0">Tanggal terbuka untuk reservasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-danger mb-1">Diblokir Manual</h6>
                            <p class="card-text text-muted small mb-0">Tanggal yang Anda blokir secara manual</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-x-fill text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-warning mb-1">Penuh Otomatis</h6>
                            <p class="card-text text-muted small mb-0">Tanggal dengan reservasi penuh</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-light border border-primary shadow-sm mb-4">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="bi bi-lightbulb-fill text-primary fs-5"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="alert-heading text-primary mb-2">Cara Menggunakan</h6>
                <ul class="mb-0 text-muted small">
                    <li>Klik pada tanggal untuk memblokir atau membuka blokir</li>
                    <li>Tanggal merah = diblokir manual, tidak bisa direservasi</li>
                    <li>Tanggal dengan reservasi tidak terpengaruh blokir manual</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Kalender Ketersediaan
                </h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i>
                        Tersedia
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-circle-fill text-danger me-1" style="font-size: 8px;"></i>
                        Diblokir
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div id="calendar-management"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="bi bi-question-circle me-2"></i>
                    Bantuan Manajemen Kalender
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Fitur Utama</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Blokir tanggal secara manual
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Buka blokir tanggal yang sudah diblokir
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Lihat status ketersediaan real-time
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Cara Penggunaan</h6>
                        <ol class="list-unstyled">
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">1</span>
                                Klik pada tanggal di kalender
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">2</span>
                                Konfirmasi aksi blokir/buka blokir
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">3</span>
                                Tanggal akan berubah status secara otomatis
                            </li>
                        </ol>
                    </div>
                </div>
                <hr>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Penting:</strong> Tanggal yang sudah memiliki reservasi tidak dapat diblokir secara manual dan akan tetap tersedia untuk tamu yang sudah melakukan reservasi.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.5); z-index: 1060;">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="bg-white rounded p-4 text-center">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0">Memproses permintaan...</p>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="confirmationModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center" id="confirmationModalBody">
                </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn" id="confirmActionButton">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    
    .fc {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .fc-header-toolbar {
        margin-bottom: 1.5rem !important;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .fc-button-primary {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        border-radius: 0.375rem !important;
        font-weight: 500 !important;
        padding: 0.5rem 1rem !important;
        transition: all 0.2s ease-in-out !important;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
    }
    
    .fc-button-primary:hover {
        background-color: #0b5ed7 !important;
        border-color: #0a58ca !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3) !important;
    }
    
    .fc-button-primary:not(:disabled).fc-button-active,
    .fc-button-primary:not(:disabled):active {
        background-color: #0a58ca !important;
        border-color: #0a53be !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    
    
    .fc-daygrid-day {
        transition: all 0.3s ease-in-out;
        border: 1px solid #e9ecef !important;
        border-radius: 0.375rem;
        margin: 2px;
        overflow: hidden;
    }
    
    .fc-daygrid-day-frame {
        padding: 8px;
        min-height: 80px;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        background: white;
    }
    
    .fc-daygrid-day-frame:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        z-index: 10;
    }
    
    .fc-daygrid-day-number {
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 4px 8px !important;
        border-radius: 50% !important;
        transition: all 0.3s ease-in-out !important;
        background: rgba(0,0,0,0.03);
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    
    .fc-day-blocked {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        color: white !important;
        position: relative;
        overflow: hidden;
    }
    
    .fc-day-blocked::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 2px,
            rgba(255,255,255,0.1) 2px,
            rgba(255,255,255,0.1) 4px
        );
        pointer-events: none;
    }
    
    .fc-day-blocked .fc-daygrid-day-number {
        background: rgba(255,255,255,0.2) !important;
        color: white !important;
        font-weight: 700 !important;
        border: 1px solid rgba(255,255,255,0.3);
    }
    
    .fc-day-blocked:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%) !important;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
    }
    
    
    .fc-day-today {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%) !important;
        border: 2px solid #ffc107 !important;
        position: relative;
    }
    
    .fc-day-today::after {
        content: 'Hari Ini';
        position: absolute;
        top: 2px;
        right: 2px;
        background: #ffc107;
        color: #212529;
        font-size: 9px;
        font-weight: bold;
        padding: 2px 4px;
        border-radius: 3px;
        z-index: 5;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background: #ffc107 !important;
        color: #212529 !important;
        font-weight: 700 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    
    .fc-day-sun, .fc-day-sat {
        background: rgba(108, 117, 125, 0.08) !important;
        position: relative;
    }
    
    .fc-day-sun .fc-daygrid-day-number::after,
    .fc-day-sat .fc-daygrid-day-number::after {
        content: '';
        position: absolute;
        top: -4px;
        right: -4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #6c757d;
    }
    
    .fc-day-sun .fc-daygrid-day-number::after {
        background: #dc3545;
    }
    
    .fc-day-sat .fc-daygrid-day-number::after {
        background: #0d6efd;
    }
    
    
    .fc-day-fully-booked {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
        color: #212529 !important;
        position: relative;
    }
    
    .fc-day-fully-booked .fc-daygrid-day-number {
        background: rgba(255,255,255,0.3) !important;
        color: #212529 !important;
        font-weight: 700 !important;
    }
    
    .fc-day-fully-booked:hover {
        background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%) !important;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(255, 193, 7, 0.4);
    }
    
    
    .fc-day-past .fc-daygrid-day-number {
        color: #adb5bd !important;
        opacity: 0.7;
    }
    
    .fc-day-past {
        opacity: 0.85;
    }
    
    .fc-day-past .fc-daygrid-day-frame {
        background: #f8f9fa;
    }
    
    
    @keyframes statusChange {
        0% { transform: scale(1); box-shadow: 0 0 0 rgba(0,0,0,0); }
        50% { transform: scale(1.03); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        100% { transform: scale(1); box-shadow: 0 0 0 rgba(0,0,0,0); }
    }
    
    .fc-day-status-change {
        animation: statusChange 0.4s ease-in-out;
    }
    
    
    .fc-daygrid-event-dot {
        border-color: #0d6efd !important;
        margin-right: 2px;
    }
    
    
    @media (max-width: 768px) {
        .fc-header-toolbar {
            flex-direction: column !important;
            gap: 1rem;
            padding: 1rem;
        }
        
        .fc-toolbar-chunk {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        
        .fc-toolbar-title {
            font-size: 1.25rem !important;
            text-align: center;
            margin: 0.5rem 0;
        }
        
        .fc-daygrid-day-frame {
            min-height: 60px;
            padding: 4px;
        }
        
        .fc-daygrid-day-number {
            width: 24px;
            height: 24px;
            font-size: 0.75rem !important;
        }
        
        .fc-day-today::after {
            font-size: 7px;
            padding: 1px 3px;
        }
    }
    
    
    .fc-col-header-cell {
        padding: 8px 4px !important;
        background: #f1f3f5 !important;
    }
    
    .fc-col-header-cell-cushion {
        font-weight: 600 !important;
        color: #495057;
    }
    
    
    .fc-day-today .fc-daygrid-day-number {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
    
    
    .fc-event {
        font-size: 0.85rem;
        padding: 2px 4px;
        border-radius: 3px;
        margin-bottom: 2px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar-management');
    const loadingOverlay = document.getElementById('loadingOverlay');
    let manuallyBlockedDates = @json($blockedDates);

    const confirmationModalEl = document.getElementById('confirmationModal');
    const confirmationModal = new bootstrap.Modal(confirmationModalEl);
    const confirmActionButton = document.getElementById('confirmActionButton');
    const confirmationModalBody = document.getElementById('confirmationModalBody');
    const confirmationModalLabel = document.getElementById('confirmationModalLabel');

    function showLoading() {
        loadingOverlay.classList.remove('d-none');
    }

    function hideLoading() {
        loadingOverlay.classList.add('d-none');
    }

    function showToast(message, type = 'success') {
        // Create toast element
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Add to toast container or create one
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Remove from DOM after hiding
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    function getEventSources() {
        return [{
            events: manuallyBlockedDates.map(function(date) {
                return {
                    title: 'Diblokir Manual',
                    start: date,
                    allDay: true,
                    display: 'background',
                    color: '#dc3545',
                    className: 'blocked-event'
                };
            }),
        }];
    }

    function updateDayAppearance() {
        // Add blocked class to manually blocked dates
        document.querySelectorAll('.fc-daygrid-day').forEach(function(dayEl) {
            const dateStr = dayEl.getAttribute('data-date');
            if (dateStr && manuallyBlockedDates.includes(dateStr)) {
                dayEl.classList.add('fc-day-blocked');
            } else {
                dayEl.classList.remove('fc-day-blocked');
            }
        });
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        eventSources: getEventSources(),
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        locale: 'id',
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan'
        },
        dayHeaderFormat: { weekday: 'short' },
        titleFormat: { year: 'numeric', month: 'long' },
        firstDay: 1, // Start week on Monday
        
        datesSet: function() {
            // Update day appearance after calendar renders
            setTimeout(updateDayAppearance, 100);
        },
        
        dateClick: function(info) {
            const dateStr = info.dateStr;
            const clickedDate = new Date(dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Prevent clicking on past dates
            if (clickedDate < today) {
                showToast('Tidak dapat mengubah status tanggal yang sudah berlalu', 'warning');
                return;
            }
            
            const isBlocked = manuallyBlockedDates.includes(dateStr);
            const action = isBlocked ? 'MEMBUKA BLOKIR' : 'MEMBLOKIR';
            const url = isBlocked ? '{{ route("admin.blocked-dates.destroy") }}' : '{{ route("admin.blocked-dates.store") }}';
            const method = isBlocked ? 'DELETE' : 'POST';

            // Set modal content
            confirmationModalLabel.innerHTML = `<i class="bi bi-calendar-question me-2"></i> Konfirmasi Aksi`;
            confirmationModalBody.innerHTML = `Apakah Anda yakin ingin <strong>${action}</strong> tanggal <strong>${dateStr}</strong>?<br><br><small class="text-muted">${isBlocked ? 'Tanggal ini akan kembali tersedia untuk reservasi.' : 'Tanggal ini tidak akan bisa direservasi oleh pengguna.'}</small>`;

            // Change confirm button color based on action
            if (isBlocked) {
                confirmActionButton.className = 'btn btn-success';
                confirmActionButton.innerHTML = `<i class="bi bi-unlock-fill me-1"></i> Ya, Buka Blokir`;
            } else {
                confirmActionButton.className = 'btn btn-danger';
                confirmActionButton.innerHTML = `<i class="bi bi-lock-fill me-1"></i> Ya, Blokir`;
            }

            // Show the modal
            confirmationModal.show();
            
            // Define the action for the confirm button using a one-time event listener
            confirmActionButton.onclick = () => {
                confirmationModal.hide();

                const dayEl = info.dayEl;
                dayEl.classList.add('fc-day-status-change');
                showLoading();

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ date: dateStr })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.error) {
                        showToast('Error: ' + data.error, 'danger');
                    } else {
                        showToast(data.message, 'success');
                        
                        // Update local data
                        if (isBlocked) {
                            manuallyBlockedDates = manuallyBlockedDates.filter(d => d !== dateStr);
                        } else {
                            manuallyBlockedDates.push(dateStr);
                        }
                        
                        // Update calendar
                        calendar.removeAllEventSources();
                        calendar.addEventSource(getEventSources()[0]);
                        
                        // Update day appearance
                        setTimeout(updateDayAppearance, 100);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan. Silakan coba lagi.', 'danger');
                })
                .finally(() => {
                    // Remove animation class
                    setTimeout(() => {
                        dayEl.classList.remove('fc-day-status-change');
                    }, 300);
                });
            };
        }
    });

    calendar.render();
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection