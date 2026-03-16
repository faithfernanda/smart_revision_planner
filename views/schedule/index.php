<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('schedule_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- FullCalendar CSS and JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <style>
        .fc-event { cursor: pointer; transition: transform 0.2s; border: none !important; }
        .fc-event:hover { transform: scale(1.02); }
        .fc { --fc-border-color: rgba(255, 255, 255, 0.1); --fc-page-bg-color: transparent; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid rgba(255, 255, 255, 0.1); }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid rgba(255, 255, 255, 0.1); }
        .fc .fc-list-day-cushion, .fc .fc-timegrid-slot-label-cushion { color: #e0e7ff; }
        .fc .fc-col-header-cell-cushion { color: #fff; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; padding: 10px 0; }
        .fc-timegrid-axis-cushion, .fc-timegrid-slot-label-cushion { color: #a5b4fc !important; }
        .fc-toolbar-title { color: white !important; font-weight: 800 !important; }
        .fc-button-primary { background-color: rgba(255, 255, 255, 0.1) !important; border: 1px solid rgba(255, 255, 255, 0.2) !important; color: white !important; font-weight: 600 !important; text-transform: capitalize !important; }
        .fc-button-primary:hover { background-color: rgba(255, 255, 255, 0.2) !important; }
        .fc-button-active { background-color: #6366f1 !important; border-color: #6366f1 !important; }
        .modal { transition: opacity 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe?q=80&w=2668&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>
        
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-3">
                    <i data-lucide="calendar" class="w-10 h-10 text-indigo-400"></i> <?= __('schedule_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('schedule_subtitle') ?></p>
            </div>
            <div class="flex space-x-4">
                <a href="<?= url('/schedule/print') ?>" target="_blank" class="px-6 py-3 bg-white/10 text-white font-bold rounded-xl border border-white/20 shadow-lg hover:bg-white/20 transition-all flex items-center gap-2">
                    <i data-lucide="printer" class="w-5 h-5"></i> <?= __('print') ?>
                </a>
                <?php if (!$is_admin_view): ?>
                <form action="<?= url('/dashboard/generate') ?>" method="POST" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-indigo-50 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i> <?= __('generate_schedule') ?>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </header>

        <?php if ($is_admin_view): ?>
        <div class="bg-amber-400/20 backdrop-blur-md border border-amber-400/30 p-4 mb-8 rounded-2xl animate__animated animate__headShake flex items-center gap-3">
            <i data-lucide="alert-circle" class="text-amber-300"></i>
            <p class="text-amber-100"><?= __('inspecting_schedule_msg') ?> <strong><?= htmlspecialchars($user_name) ?></strong> (<?= __('read_only') ?>)</p>
        </div>
        <?php endif; ?>

        <!-- Calendar Container -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl p-8 animate__animated animate__fadeInUp">
            <div id='calendar' class="min-h-[750px]"></div>
        </div>
    </main>

    <!-- Event Detail Modal -->
    <div id="eventModal" class="fixed inset-0 bg-indigo-950/60 backdrop-blur-md hidden items-center justify-center z-50 p-4 opacity-0 transition-opacity">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-2xl font-black text-gray-800" id="modalSubject"><?= __('details') ?></h3>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="p-8 space-y-6">
                <div class="flex items-start">
                    <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 rounded-2xl text-indigo-600 mr-4 shrink-0 shadow-sm">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1"><?= __('hours') ?></p>
                        <p class="font-bold text-gray-800" id="modalTime"></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-12 h-12 flex items-center justify-center bg-emerald-100 rounded-2xl text-emerald-600 mr-4 shrink-0 shadow-sm">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-1"><?= __('status') ?></p>
                        <p class="font-bold text-gray-800 text-lg" id="modalStatus"></p>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-gray-50 rounded-b-3xl flex gap-4">
                <button onclick="closeModal()" class="flex-1 px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-100 transition-all font-bold">
                    <?= __('close') ?>
                </button>
                <?php if (!$is_admin_view): ?>
                <button id="completeBtn" class="flex-1 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 font-bold">
                    <?= __('complete') ?>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        let calendar;
        let currentEvent = null;

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                allDaySlot: false,
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00',
                expandRows: true,
                handleWindowResize: true,
                windowResizeDelay: 100,
                locale: '<?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'fr' : 'en-gb' ?>',
                slotMinTime: '07:00:00',
                slotMaxTime: '23:00:00',
                firstDay: 1,
                editable: <?= $is_admin_view ? 'false' : 'true' ?>,
                selectable: <?= $is_admin_view ? 'false' : 'true' ?>,
                events: '<?= url('/schedule/events') ?>',
                eventClick: function(info) {
                    openModal(info.event);
                },
                eventDrop: function(info) {
                    updateEvent(info.event);
                },
                eventResize: function(info) {
                    updateEvent(info.event);
                },
                eventContent: function(arg) {
                    return {
                        html: `<div class="p-1 h-full flex flex-col justify-center overflow-hidden">
                                <div class="font-bold text-xs leading-tight">${arg.event.title}</div>
                               </div>`
                    };
                }
            });
            calendar.render();
        });

        function openModal(event) {
            currentEvent = event;
            const modal = document.getElementById('eventModal');
            document.getElementById('modalSubject').textContent = event.title;
            
            const locale = '<?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'fr-FR' : 'en-US' ?>';
            const start = event.start.toLocaleTimeString(locale, {hour: '2-digit', minute:'2-digit'});
            const end = event.end ? event.end.toLocaleTimeString(locale, {hour: '2-digit', minute:'2-digit'}) : '';
            const date = event.start.toLocaleDateString(locale, {weekday: 'long', day: 'numeric', month: 'long'});
            
            document.getElementById('modalTime').textContent = `${date}, ${start} - ${end}`;
            
            const status = event.extendedProps.status === 'completed' ? '<?= __('completed_status') ?>' : '<?= __('todo_status') ?>';
            document.getElementById('modalStatus').textContent = status;

            const completeBtn = document.getElementById('completeBtn');
            if (completeBtn) {
                if (event.extendedProps.status === 'completed') {
                    completeBtn.style.display = 'none';
                } else {
                    completeBtn.style.display = 'block';
                    completeBtn.onclick = () => markAsComplete(event.id);
                }
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                lucide.createIcons();
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function markAsComplete(sessionId) {
            fetch('<?= url('/schedule/complete') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    id: sessionId,
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentEvent.setExtendedProp('status', 'completed');
                    currentEvent.setProp('backgroundColor', '#10b981');
                    closeModal();
                }
            });
        }

        function updateEvent(event) {
            fetch('<?= url('/schedule/update') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: event.id,
                    start: event.start.toISOString(),
                    end: event.end ? event.end.toISOString() : null,
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    calendar.refetchEvents();
                    alert('<?= __('update_error') ?>');
                }
            });
        }
    </script>
</body>
</html>
