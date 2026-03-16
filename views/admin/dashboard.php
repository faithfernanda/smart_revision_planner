<!-- views/admin/dashboard.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('admin_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-indigo-950 flex flex-col lg:flex-row min-h-screen font-sans text-white">
    
    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-extrabold drop-shadow-md flex items-center gap-3">
                    <i data-lucide="shield" class="w-10 h-10 text-indigo-400"></i> <?= __('admin_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('app_overview') ?></p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10 animate__animated animate__fadeInUp">
            <!-- Stat 1 -->
            <div class="bg-indigo-900/30 backdrop-blur-md p-8 rounded-3xl border border-white/10 hover:border-pink-500/30 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                        <i data-lucide="users" class="w-7 h-7 text-pink-400"></i>
                    </div>
                    <span class="text-xs font-bold text-pink-400 uppercase tracking-wider"><?= __('users') ?></span>
                </div>
                <div class="text-5xl font-black"><?= $totalUsers ?></div>
                <div class="text-indigo-300 text-sm mt-1"><?= __('total_users') ?></div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-indigo-900/30 backdrop-blur-md p-8 rounded-3xl border border-white/10 hover:border-indigo-500/30 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                        <i data-lucide="book-open" class="w-7 h-7 text-indigo-400"></i>
                    </div>
                    <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider"><?= __('exams') ?></span>
                </div>
                <div class="text-5xl font-black text-indigo-100"><?= $totalSessions ?></div>
                <div class="text-indigo-300 text-sm mt-1"><?= __('sessions_generated') ?></div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-indigo-900/30 backdrop-blur-md p-8 rounded-3xl border border-white/10 hover:border-<?= $health['ok'] ? 'green' : 'red' ?>-500/30 transition-all group relative">
                <div class="flex items-center justify-between mb-4">
                        <i data-lucide="<?= $health['ok'] ? 'check-circle' : 'alert-triangle' ?>" class="w-7 h-7 text-<?= $health['ok'] ? 'green' : 'red' ?>-400"></i>
                    <span class="text-xs font-bold text-<?= $health['ok'] ? 'green' : 'red' ?>-400 uppercase tracking-wider"><?= __('system') ?></span>
                </div>
                <div class="text-5xl font-black text-<?= $health['ok'] ? 'green' : 'red' ?>-400">
                    <?= $health['ok'] ? 'OK' : 'ERR' ?>
                </div>
                <div class="text-indigo-300 text-sm mt-1">
                    <?= $health['ok'] ? __('operational') : count($health['messages']) . ' ' . __('errors_detected') ?>
                </div>
                
                <?php if (!$health['ok']): ?>
                    <div class="absolute top-full left-0 mt-2 w-full bg-red-900/90 backdrop-blur-md p-4 rounded-2xl border border-red-500/30 text-xs text-red-200 z-10 shadow-2xl hidden group-hover:block">
                        <ul class="list-disc ml-4">
                            <?php foreach ($health['messages'] as $msg): ?>
                                <li><?= htmlspecialchars($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-indigo-900/40 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl overflow-hidden mb-8 animate__animated animate__fadeInUp">
            <div class="px-8 py-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-3">
                        <i data-lucide="history" class="w-6 h-6 text-indigo-400"></i> <?= __('recent_activity') ?>
                    </h2>
                    <p class="text-indigo-300 text-sm mt-1"><?= __('user_actions_desc') ?></p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-indigo-400 text-xs font-bold uppercase tracking-wider bg-black/20">
                            <th class="px-8 py-4"><?= __('user') ?></th>
                            <th class="px-8 py-4"><?= __('action') ?></th>
                            <th class="px-8 py-4"><?= __('details') ?></th>
                            <th class="px-8 py-4"><?= __('date') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-xs font-bold">
                                            <?= strtoupper(substr($log['firstname'], 0, 1)) ?>
                                        </div>
                                        <span class="font-medium text-sm text-indigo-100"><?= $log['firstname'] . ' ' . $log['lastname'] ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        <?= strpos($log['action'], 'DELETE') !== false ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 
                                           (strpos($log['action'], 'CREATE') !== false ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                           'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30') ?>">
                                        <?= $log['action'] ?>
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-sm text-indigo-200"><?= $log['details'] ?></td>
                                <td class="px-8 py-4 text-xs text-indigo-400"><?= date('d/m H:i', strtotime($log['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-indigo-400 italic"><?= __('no_activity') ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate__animated animate__fadeInUp">
            <div class="bg-indigo-900/40 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl">
                <h2 class="text-2xl font-bold mb-6 border-b border-white/10 pb-4 flex items-center gap-3">
                    <i data-lucide="help-circle" class="w-6 h-6 text-indigo-400"></i> <?= __('support_guide') ?>
                </h2>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 font-bold text-indigo-400">1</div>
                        <div>
                            <h4 class="font-bold text-indigo-100"><?= __('check_logs') ?></h4>
                            <p class="text-sm text-indigo-300"><?= __('check_logs_desc') ?></p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 font-bold text-indigo-400">2</div>
                        <div>
                            <h4 class="font-bold text-indigo-100"><?= __('use_inspection') ?></h4>
                            <p class="text-sm text-indigo-300"><?= __('use_inspection_desc') ?></p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 font-bold text-indigo-400">3</div>
                        <div>
                            <h4 class="font-bold text-indigo-100"><?= __('system_status') ?></h4>
                            <p class="text-sm text-indigo-300"><?= __('system_status_desc') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-900/40 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl">
                <h2 class="text-2xl font-bold mb-6 border-b border-white/10 pb-4 flex items-center gap-3">
                    <i data-lucide="zap" class="w-6 h-6 text-indigo-400"></i> <?= __('quick_actions') ?>
                </h2>
                <div class="grid grid-cols-1 gap-4">
                    <a href="<?= url('/admin/users') ?>" class="p-6 bg-indigo-500/20 border border-indigo-400/30 rounded-2xl hover:bg-indigo-500/40 transition-all group">
                        <h4 class="text-xl font-bold mb-2 group-hover:translate-x-1 transition-transform flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5"></i> <?= __('manage_users') ?>
                        </h4>
                        <p class="text-sm text-indigo-200"><?= __('manage_users_desc') ?></p>
                    </a>
                    <a href="<?= url('/admin/settings') ?>" class="p-6 bg-purple-500/20 border border-purple-400/30 rounded-2xl hover:bg-purple-500/40 transition-all group">
                        <h4 class="text-xl font-bold mb-2 group-hover:translate-x-1 transition-transform flex items-center gap-2">
                            <i data-lucide="settings" class="w-5 h-5"></i> <?= __('system_settings') ?>
                        </h4>
                        <p class="text-sm text-indigo-200"><?= __('system_settings_desc') ?></p>
                    </a>
                </div>
            </div>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
