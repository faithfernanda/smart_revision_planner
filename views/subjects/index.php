<!-- views/subjects/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('subjects_title') ?> - Smart Revision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?> 

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-3">
                    <i data-lucide="book" class="w-10 h-10 text-indigo-400"></i> <?= __('subjects_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('subjects_subtitle') ?></p>
            </div>
            <?php if(!$is_admin_view): ?>
                <a href="<?= url('/subjects/create') ?>" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-indigo-50 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i> <?= __('add_subject') ?>
                </a>
            <?php endif; ?>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate__animated animate__fadeInUp">
            <?php foreach($subjects as $subject): ?>
                <div class="group bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl shadow-xl hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold text-white shadow-lg" style="background-color: <?= $subject['color'] ?>">
                            <?= substr($subject['name'], 0, 1) ?>
                        </div>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-white border border-white/20">
                            <?= __('coefficient') ?>: <?= $subject['coefficient'] ?>
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-200 transition-colors"><?= htmlspecialchars($subject['name']) ?></h3>
                    
                    <div class="space-y-3 mt-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-indigo-200"><?= __('difficulty') ?></span>
                            <div class="flex gap-1">
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <div class="w-1.5 h-1.5 rounded-full <?= $i <= $subject['difficulty_level'] ? 'bg-pink-400' : 'bg-white/20' ?>"></div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-indigo-200"><?= __('target_grade') ?></span>
                            <span class="font-bold text-emerald-300"><?= $subject['target_grade'] ?>/20</span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <?php if(!$is_admin_view): ?>
                        <div class="mt-4 pt-4 border-t border-white/10 flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="<?= url('/subjects/edit?id=' . $subject['id']) ?>" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 text-white transition-colors" title="<?= __('edit') ?>">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="<?= url('/subjects/delete') ?>" method="POST" onsubmit="return confirm('<?= __('confirm_delete_subject') ?>');">
                                <input type="hidden" name="id" value="<?= $subject['id'] ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-red-500/20 rounded-lg hover:bg-red-500/40 text-red-200 transition-colors" title="<?= __('delete') ?>">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <?php if(!$is_admin_view): ?>
                <!-- Add New Card (Empty State) -->
                <a href="<?= url('/subjects/create') ?>" class="group border-2 border-dashed border-white/20 p-6 rounded-3xl flex flex-col items-center justify-center text-center hover:bg-white/5 transition-all duration-300 min-h-[200px]">
                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="plus" class="w-8 h-8"></i>
                    </div>
                    <p class="text-indigo-200 font-medium"><?= __('add_subject') ?></p>
                </a>
            <?php endif; ?>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
