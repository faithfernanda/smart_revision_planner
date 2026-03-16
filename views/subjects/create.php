<!-- views/subjects/create.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('add_subject') ?> - Smart Revision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10 flex items-center justify-center">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 md:p-10 rounded-3xl shadow-2xl w-full max-w-2xl animate__animated animate__zoomIn">
            <h2 class="text-3xl font-bold text-white mb-8 text-center flex items-center justify-center gap-3">
                <i data-lucide="book" class="w-8 h-8 text-indigo-400"></i> <?= __('add_subject') ?>
            </h2>
            
            <form action="<?= url('/subjects/create') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('subject_name') ?></label>
                    <input type="text" name="name" required 
                           class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all placeholder-indigo-400/50"
                           placeholder="<?= __('placeholder_subject') ?>">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('difficulty') ?> (1-10)</label>
                        <input type="number" name="difficulty" min="1" max="10" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all"
                               placeholder="5">
                        <p class="text-xs text-indigo-300 mt-1">1 = <?= __('easy') ?>, 10 = <?= __('hard') ?></p>
                    </div>
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('coefficient') ?></label>
                        <input type="number" name="coefficient" min="1" value="1" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('target') ?></label>
                        <input type="number" name="target" min="0" max="20" step="0.5" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all"
                               placeholder="15">
                    </div>
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('color') ?></label>
                        <div class="relative h-12 w-full">
                            <input type="color" name="color" value="#6366f1" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full h-full rounded-xl border border-indigo-500/30 flex items-center justify-center text-white/50 text-sm pointer-events-none bg-indigo-900/50">
                                <?= __('choose_color') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <a href="<?= url('/subjects') ?>" class="w-1/3 py-3 rounded-xl border border-white/20 text-indigo-200 font-bold hover:bg-white/10 text-center transition-all"><?= __('cancel') ?></a>
                    <button type="submit" class="w-2/3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-indigo-500/40 hover:scale-[1.02] transition-all">
                        <?= __('save_subject') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
