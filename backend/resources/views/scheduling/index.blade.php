@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Jadwal Teknisi</h1>
            <p class="text-gray-400">Kalender penugasan dan instalasi.</p>
        </div>
        <div>
            <a href="{{ route('work-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Work Order Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Legend -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h3 class="text-white font-semibold mb-4">Legend Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full bg-teal-500"></div>
                        <span class="text-gray-300 text-sm">Scheduled (Terjadwal)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                        <span class="text-gray-300 text-sm">In Progress (Dikerjakan)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        <span class="text-gray-300 text-sm">Completed (Selesai)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                        <span class="text-gray-300 text-sm">Pending (Menunggu)</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h3 class="text-white font-semibold mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="bg-gray-700/50 rounded-lg p-3">
                        <div class="text-gray-400 text-xs mb-1">Hari Ini</div>
                        <div class="text-2xl font-bold text-white">{{ \App\Models\WorkOrder::whereDate('scheduled_date', today())->count() }} <span class="text-sm font-normal text-gray-500">Tasks</span></div>
                    </div>
                    <div class="bg-gray-700/50 rounded-lg p-3">
                        <div class="text-gray-400 text-xs mb-1">Minggu Ini</div>
                        <div class="text-2xl font-bold text-white">{{ \App\Models\WorkOrder::whereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()])->count() }} <span class="text-sm font-normal text-gray-500">Tasks</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Area -->
        <div class="lg:col-span-3">
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-xl">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            themeSystem: 'standard',
            height: 'auto',
            aspectRatio: 1.8,
            events: '{{ route("scheduling.events") }}',
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
            eventDidMount: function(info) {
                // Tooltip logic can go here (e.g. using Tippy.js)
                info.el.title = info.event.title + '\n' + 
                                'Teknisi: ' + info.event.extendedProps.technician + '\n' +
                                'Type: ' + info.event.extendedProps.type;
            }
        });
        calendar.render();
    });
</script>

<style>
    /* Dark Mode Overrides for FullCalendar */
    :root {
        --fc-border-color: #374151;
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: #1f2937;
        --fc-neutral-text-color: #d1d5db;
        --fc-list-event-hover-bg-color: #374151;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: var(--fc-border-color);
    }
    .fc-col-header-cell-cushion {
        color: #9ca3af; /* text-gray-400 */
        text-decoration: none !important;
    }
    .fc-daygrid-day-number {
        color: #d1d5db; /* text-gray-300 */
        text-decoration: none !important;
    }
    .fc-button-primary {
        background-color: #2563eb !important; /* bg-blue-600 */
        border-color: #2563eb !important;
    }
    .fc-button-primary:hover {
        background-color: #1d4ed8 !important; /* bg-blue-700 */
        border-color: #1d4ed8 !important;
    }
    .fc-button-active {
        background-color: #1e40af !important; /* bg-blue-800 */
        border-color: #1e40af !important;
    }
    .fc-toolbar-title {
        color: white;
    }
    .fc-day-today {
        background-color: rgba(37, 99, 235, 0.1) !important;
    }
</style>
@endpush
