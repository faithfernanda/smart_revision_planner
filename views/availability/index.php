<!-- views/availability/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('availability_title') ?> - Smart Revision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe?q=80&w=2668&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-3">
                    <i data-lucide="clock" class="w-10 h-10 text-indigo-400"></i> <?= __('availability_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('availability_subtitle') ?></p>
            </div>
            <?php if(!$is_admin_view): ?>
                <a href="<?= url('/availability/create') ?>" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-indigo-50 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i> <?= __('add_slot') ?>
                </a>
            <?php endif; ?>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate__animated animate__fadeInUp">
            <?php if(empty($availabilities)): ?>
                <div class="col-span-full border-2 border-dashed border-white/20 rounded-3xl p-12 text-center">
                    <p class="text-indigo-200 text-xl font-medium mb-4"><?= __('no_availability_msg') ?></p>
                    <p class="text-indigo-300 mb-6 max-w-md mx-auto"><?= __('availability_desc') ?></p>
                    <?php if(!$is_admin_view): ?>
                        <a href="<?= url('/availability/create') ?>" class="inline-block px-8 py-3 bg-white/10 text-white rounded-xl hover:bg-white/20 font-bold transition-all">
                            <?= __('configure_now') ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach($availabilities as $day => $slots): ?>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl shadow-xl flex flex-col h-full hover:bg-white/15 transition-all">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/50 flex items-center justify-center">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white"><?= __(strtolower($day)) ?></h3>
                        </div>
                        <ul class="space-y-3 flex-1">
                            <?php foreach($slots as $slot): ?>
                                <li class="flex items-center justify-between bg-indigo-900/40 p-3 rounded-xl border border-indigo-500/20 group hover:border-indigo-400/50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                        <span class="text-indigo-100 font-mono tracking-wide">
                                            <?= substr($slot['start_time'], 0, 5) ?> - <?= substr($slot['end_time'], 0, 5) ?>
                                        </span>
                                    </div>
                                    <?php if(!$is_admin_view): ?>
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                            <form action="<?= url('/availability/delete') ?>" method="POST" onsubmit="return confirm('<?= __('delete_slot_confirm') ?>');">
                                                <input type="hidden" name="id" value="<?= $slot['id'] ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="<?= __('delete') ?>">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
