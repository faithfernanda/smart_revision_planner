<!-- views/dashboard/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('dashboard') ?> - Smart Revision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .gradient-text {
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans flex flex-col lg:flex-row min-h-screen text-gray-800 bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=2629&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <!-- Overlay for readability -->
    <div class="fixed inset-0 bg-indigo-50/90 z-0 pointer-events-none"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 w-full overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div class="animate__animated animate__slideInLeft">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 drop-shadow-sm flex items-center gap-3">
                    <?= __('hello') ?>, <span class="gradient-text"><?= htmlspecialchars($user_name) ?></span> <i data-lucide="sparkles" class="w-8 h-8 text-indigo-500"></i>
                </h1>
                <p class="text-indigo-600 mt-2 font-medium"><?= __('ready_session') ?></p>
            </div>
            
            <?php if(!isset($is_admin_view) || !$is_admin_view): ?>
            <form action="<?= url('/dashboard/generate') ?>" method="POST" class="animate__animated animate__slideInRight">
                <?= csrf_field() ?>
                <button type="submit" class="group relative px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl shadow-xl hover:shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-in-out -translate-x-full"></div>
                    <div class="flex items-center gap-3 font-bold text-lg">
                        <i data-lucide="zap" class="w-6 h-6 animate-pulse"></i>
                        <?= __('generate_planning') ?>
                    </div>
                </button>
            </form>
            <?php endif; ?>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Card 1 -->
            <div class="glass-panel p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-100 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <i data-lucide="book" class="w-8 h-8"></i>
                    </div>
                    <span class="text-sm font-bold text-indigo-400 uppercase tracking-wider"><?= __('subjects') ?></span>
                </div>
                <p class="text-5xl font-extrabold text-gray-800 group-hover:text-indigo-600 transition-colors"><?= count($subjects) ?></p>
                <p class="text-gray-500 mt-2 text-sm font-medium"><?= __('active_subjects') ?></p>
            </div>

            <!-- Card 2 -->
            <div class="glass-panel p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                 <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-pink-100 rounded-2xl text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                        <i data-lucide="calendar" class="w-8 h-8"></i>
                    </div>
                    <span class="text-sm font-bold text-pink-400 uppercase tracking-wider"><?= __('exams') ?></span>
                </div>
                <p class="text-5xl font-extrabold text-gray-800 group-hover:text-pink-600 transition-colors"><?= count($exams) ?></p>
                <p class="text-gray-500 mt-2 text-sm font-medium"><?= __('upcoming_exams_card') ?></p>
            </div>

            <!-- Card 3 -->
            <div class="glass-panel p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <i data-lucide="check-circle" class="w-8 h-8"></i>
                    </div>
                    <span class="text-sm font-bold text-emerald-400 uppercase tracking-wider">Sessions</span>
                </div>
                <p class="text-5xl font-extrabold text-gray-800 group-hover:text-emerald-600 transition-colors"><?= count($revisions) ?></p>
                <p class="text-gray-500 mt-2 text-sm font-medium"><?= __('planned_sessions') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Upcoming Exams -->
            <div class="glass-panel p-8 rounded-3xl shadow-lg flex flex-col h-full animate__animated animate__fadeInUp bg-white/60">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-6 h-6 text-pink-500"></i> <?= __('upcoming_exams') ?>
                </h3>
                
                <?php if(empty($exams)): ?>
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-400 py-10">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-lg"><?= __('no_exams') ?></p>
                    </div>
                <?php else: ?>
                    <ul class="space-y-4">
                        <?php foreach($exams as $exam): ?>
                            <li class="group flex items-center justify-between p-5 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-pink-300 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-400 to-red-500 text-white flex items-center justify-center font-bold text-xl shadow-lg">
                                        <?= substr($exam['subject_name'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-gray-800 group-hover:text-pink-600 transition-colors"><?= htmlspecialchars($exam['subject_name']) ?></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="px-2 py-0.5 rounded-md bg-gray-100 text-xs font-semibold text-gray-500">Diff: <?= $exam['difficulty_level'] ?></span>
                                            <span class="px-2 py-0.5 rounded-md bg-pink-50 text-xs font-semibold text-pink-500">Coef: <?= $exam['coefficient'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-gray-800"><?= date('d M', strtotime($exam['exam_date'])) ?></p>
                                    <p class="text-sm font-medium text-gray-500"><?= substr($exam['exam_time'], 0, 5) ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Vision Schedule -->
            <div class="glass-panel p-8 rounded-3xl shadow-lg flex flex-col h-full animate__animated animate__fadeInUp animate__delay-1s bg-white/60">
                <div class="flex justify-between items-center mb-6">
                     <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="list-todo" class="w-6 h-6 text-indigo-500"></i> <?= __('schedule') ?>
                    </h3>
                    <a href="<?= url('/subjects') ?>" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition"><?= __('manage') ?> &rarr;</a>
                </div>

                <?php if(empty($revisions)): ?>
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-400 py-10 text-center">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <p class="text-lg mb-4"><?= __('no_sessions') ?></p>
                        <div class="space-x-2">
                             <a href="<?= url('/availability') ?>" class="inline-block px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">1. <?= __('availability') ?></a>
                             <form action="<?= url('/dashboard/generate') ?>" method="POST" class="inline">
                                <button class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-200 transition">2. <?= __('save') ?></button>
                             </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex-1 overflow-y-auto pr-2 space-y-4">
                         <?php foreach($revisions as $session): ?>
                            <?php 
                                $start = new DateTime($session['start_datetime']);
                                $end = new DateTime($session['end_datetime']);
                                $duration = $start->diff($end)->format('%h:%I');
                                $isToday = $start->format('Y-m-d') === date('Y-m-d');
                            ?>
                            <div class="relative group p-5 bg-white rounded-2xl border-l-[6px] shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-[1.01]" 
                                 style="border-color: <?= $session['color'] ?>">
                                
                                <div class="flex justify-between items-center relative z-10">
                                    <div class="flex items-start gap-4">
                                        <div class="flex flex-col items-center justify-center w-14 h-14 rounded-xl bg-gray-50 text-gray-700 font-bold leading-tight">
                                            <span class="text-xs uppercase text-gray-400"><?= $start->format('M') ?></span>
                                            <span class="text-xl"><?= $start->format('d') ?></span>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors"><?= htmlspecialchars($session['subject_name']) ?></h4>
                                            <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 font-medium">
                                                <i data-lucide="clock" class="w-4 h-4 text-indigo-400"></i>
                                                <?= $start->format('H:i') ?> - <?= $end->format('H:i') ?>
                                                <span class="px-2 py-0.5 bg-gray-100 rounded text-xs ml-2"><?= $duration ?>h</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if($isToday): ?>
                                        <div class="flex flex-col items-end">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold leading-none text-green-800 bg-green-100 animate-pulse">
                                                <?= __('today') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
