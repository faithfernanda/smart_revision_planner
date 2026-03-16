<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('print') ?> <?= __('schedule_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-container { box-shadow: none !important; border: none !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .table-container { border: 1px solid #e2e8f0 !important; border-radius: 0 !important; }
            th { background-color: #f1f5f9 !important; color: #1e293b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .status-badge { border: 1px solid #e2e8f0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        .status-badge {
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body class="p-8 md:p-12">
    <div class="max-w-5xl mx-auto print-container bg-white shadow-2xl rounded-[2.5rem] overflow-hidden border border-gray-100 animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="px-10 py-12 bg-indigo-600 flex justify-between items-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <h1 class="text-3xl font-black tracking-tight"><?= __('revision_schedule') ?></h1>
                </div>
                <p class="text-indigo-100 font-medium opacity-90"><?= __('session_of') ?> <span class="text-white font-bold"><?= htmlspecialchars($user_name) ?></span></p>
            </div>
            <div class="text-right relative z-10">
                <button onclick="window.print()" class="no-print mb-4 flex items-center px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 transform">
                    <i data-lucide="printer" class="w-5 h-5 mr-2"></i>
                    <?= __('print') ?>
                </button>
                <p class="text-xs font-black uppercase tracking-widest text-indigo-200 opacity-80">
                    <?= date('d') ?> <?= __(strtolower(date('F'))) ?> <?= date('Y') ?>
                </p>
            </div>
        </div>

        <!-- Calendar Content -->
        <div class="p-8">
            <?php
            // Group sessions by date
            $groupedSessions = [];
            $subjects = [];
            foreach ($sessions as $session) {
                $date = date('Y-m-d', strtotime($session['start_datetime']));
                $groupedSessions[$date][] = $session;
                $subjects[$session['subject_name']] = $session['color'];
            }

            // Determine date range
            if (!empty($sessions)) {
                $dates = array_keys($groupedSessions);
                $firstSessionDate = new DateTime(min($dates));
                $lastSessionDate = new DateTime(max($dates));
                
                // Start from the Monday of the first session's week
                $start = clone $firstSessionDate;
                $start->modify('Monday this week');
                
                // End on the Sunday of the last session's week
                $end = clone $lastSessionDate;
                $end->modify('Sunday this week');
                
                $interval = new DateInterval('P1D');
                $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
            } else {
                $period = [];
            }
            ?>

            <?php if(empty($sessions)): ?>
                <div class="py-20 text-center text-slate-400 italic bg-slate-50 rounded-3xl border border-gray-100">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <i data-lucide="calendar-x" class="w-8 h-8 opacity-20"></i>
                        </div>
                        <p class="text-lg font-medium"><?= __('no_session_scheduled') ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-[2rem] border border-gray-200 overflow-hidden shadow-sm">
                    <!-- Day Headers -->
                    <div class="grid grid-cols-7 bg-indigo-50/50 border-b border-gray-200">
                        <?php foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day): ?>
                            <div class="py-4 text-center text-[0.7rem] font-black uppercase tracking-widest text-indigo-900/60 border-r border-gray-100 last:border-0">
                                <?= __($day) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 divide-x divide-gray-100">
                        <?php 
                        $rowCount = 0;
                        foreach ($period as $date): 
                            $dateStr = $date->format('Y-m-d');
                            $isFirstOfMonth = $date->format('j') == 1;
                        ?>
                            <div class="min-h-[140px] p-2 bg-white border-b border-gray-100 flex flex-col group transition-colors hover:bg-slate-50/50">
                                <div class="flex justify-between items-start mb-2 px-1">
                                    <span class="text-[0.6rem] font-black uppercase tracking-tighter text-slate-300">
                                        <?= $isFirstOfMonth ? $date->format('M') : '' ?>
                                    </span>
                                    <span class="text-sm font-black text-slate-800 bg-slate-100 w-7 h-7 flex items-center justify-center rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        <?= $date->format('j') ?>
                                    </span>
                                </div>

                                <div class="flex-1 space-y-1.5 overflow-hidden">
                                    <?php if (isset($groupedSessions[$dateStr])): ?>
                                        <?php foreach ($groupedSessions[$dateStr] as $session): ?>
                                            <div class="px-2 py-1.5 rounded-lg border-l-[3px] shadow-sm flex flex-col gap-0.5" 
                                                 style="border-color: <?= $session['color'] ?>; background-color: <?= $session['color'] ?>10">
                                                <span class="text-[0.65rem] font-black leading-tight text-slate-800 truncate">
                                                    <?= htmlspecialchars($session['subject_name']) ?>
                                                </span>
                                                <div class="flex items-center gap-1 opacity-60">
                                                    <i data-lucide="clock" class="w-2.5 h-2.5"></i>
                                                    <span class="text-[0.55rem] font-extrabold uppercase tracking-tight">
                                                        <?= date('H:i', strtotime($session['start_datetime'])) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-10 flex flex-wrap gap-4 items-center justify-center">
                    <?php foreach ($subjects as $name => $color): ?>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-100 rounded-xl shadow-sm">
                            <div class="w-2.5 h-2.5 rounded-full" style="background-color: <?= $color ?>"></div>
                            <span class="text-xs font-black text-slate-600 truncate"><?= htmlspecialchars($name) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-slate-50 flex justify-between items-center text-slate-400 text-[0.65rem] font-black uppercase tracking-widest opacity-60">
                <p>Smart Revision Planner &copy; <?= date('Y') ?></p>
                <div class="flex items-center gap-2">
                    <i data-lucide="zap" class="w-3 h-3 text-indigo-400"></i>
                    <?= __('automatically_generated') ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
