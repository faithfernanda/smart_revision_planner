<!-- views/analytics/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('analytics_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-3">
                    <i data-lucide="bar-chart-3" class="w-10 h-10 text-indigo-400"></i> <?= __('analytics_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('analytics_subtitle') ?></p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Key Metrics -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6 animate__animated animate__fadeInUp">
                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-3xl shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-indigo-200 font-medium"><?= __('completion_rate') ?></p>
                        <h3 class="text-4xl font-bold text-white mt-1"><?= $completionRate ?>%</h3>
                    </div>
                    <div class="w-16 h-16 rounded-full border-4 border-emerald-400 flex items-center justify-center text-emerald-400 font-bold text-xl">
                        <?= $completionRate ?>%
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-3xl shadow-xl">
                    <p class="text-indigo-200 font-medium"><?= __('total_sessions') ?></p>
                    <h3 class="text-4xl font-bold text-white mt-1"><?= $totalSessions ?></h3>
                    <p class="text-sm text-gray-400 mt-1"><?= __('planned_4_weeks') ?></p>
                </div>
                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-3xl shadow-xl">
                    <p class="text-indigo-200 font-medium"><?= __('next_exam') ?></p>
                    <?php if(!empty($exams)): ?>
                        <?php 
                            $nextExam = $exams[0];
                            $days = (new DateTime($nextExam['exam_date']))->diff(new DateTime())->days;
                        ?>
                        <h3 class="text-2xl font-bold text-white mt-1 truncate"><?= htmlspecialchars($nextExam['subject_name']) ?></h3>
                        <p class="text-sm text-pink-400 font-bold mt-1"><?= __('in') ?> <?= $days ?> <?= __('days') ?></p>
                    <?php else: ?>
                        <h3 class="text-2xl font-bold text-white mt-1"><?= __('none') ?></h3>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Chart: Distribution by Subject -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-xl animate__animated animate__fadeInUp animate__delay-1s">
                <h3 class="text-xl font-bold text-white mb-6"><?= __('effort_distribution') ?></h3>
                <canvas id="subjectChart" height="250"></canvas>
            </div>

            <!-- Exam Readiness List -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-xl animate__animated animate__fadeInUp animate__delay-1s">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <i data-lucide="crosshair" class="w-5 h-5 text-pink-400"></i> <?= __('readiness_state') ?>
                </h3>
                <div class="space-y-6">
                    <?php foreach($subjectCompletion as $stat): ?>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-white font-medium"><?= htmlspecialchars($stat['name']) ?></span>
                                <span class="text-indigo-200 text-xs"><?= $stat['completed_sessions'] ?>/<?= $stat['total_sessions'] ?> <?= __('sessions_count') ?></span>
                            </div>
                            <div class="w-full bg-gray-700/50 rounded-full h-2.5 overflow-hidden border border-white/5">
                                <div class="h-2.5 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(129,140,248,0.5)]" 
                                     style="width: <?= $stat['completion_rate'] ?>%; background-color: <?= $stat['color'] ?>"></div>
                            </div>
                            <div class="text-right mt-1">
                                <span class="text-xs font-bold" style="color: <?= $stat['color'] ?>"><?= $stat['completion_rate'] ?>% <?= __('ready') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if(empty($subjectCompletion)): ?>
                        <p class="text-indigo-300 italic"><?= __('analytics_empty_msg') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Recommendations Section -->
                <div class="mt-10 pt-8 border-t border-white/10">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-indigo-300 mb-4"><?= __('ai_tips') ?></h4>
                    <div class="space-y-4">
                        <?php foreach($recommendations as $rec): ?>
                            <?php 
                                $bgClass = 'bg-indigo-500/10 border-indigo-500/20 text-indigo-100';
                                $iconColor = 'text-indigo-400';
                                if ($rec['type'] === 'critical') {
                                    $bgClass = 'bg-red-500/10 border-red-500/20 text-red-100';
                                    $iconColor = 'text-red-400';
                                } elseif ($rec['type'] === 'warning') {
                                    $bgClass = 'bg-amber-500/10 border-amber-500/20 text-amber-100';
                                    $iconColor = 'text-amber-400';
                                } elseif ($rec['type'] === 'success') {
                                    $bgClass = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-100';
                                    $iconColor = 'text-emerald-400';
                                }
                            ?>
                            <div class="flex gap-3 p-4 rounded-2xl border <?= $bgClass ?> animate__animated animate__fadeInLeft">
                                <i data-lucide="<?= $rec['icon'] ?>" class="w-5 h-5 flex-shrink-0 <?= $iconColor ?>"></i>
                                <p class="text-xs leading-relaxed"><?= $rec['message'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('subjectChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $chartLabels ?>,
                datasets: [{
                    label: '<?= __('revision_hours') ?>',
                    data: <?= $chartData ?>,
                    backgroundColor: <?= $chartColors ?>,
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 14 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#e0e7ff' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#e0e7ff' }
                    }
                }
            }
        });
    </script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
