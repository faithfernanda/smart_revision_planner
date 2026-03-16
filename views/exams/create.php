<!-- views/exams/create.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('add_exam') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=2574&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10 flex items-center justify-center">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 md:p-10 rounded-3xl shadow-2xl w-full max-w-xl animate__animated animate__zoomIn">
            <h2 class="text-3xl font-bold text-white mb-8 text-center flex items-center justify-center gap-3">
                <i data-lucide="calendar" class="w-8 h-8 text-pink-400"></i> <?= __('add_exam') ?>
            </h2>
            
            <?php if(isset($error)): ?>
                <div class="bg-red-500/20 text-red-200 p-4 rounded-xl mb-6 border border-red-500/30"><?= $error ?></div>
            <?php endif; ?>

            <form action="<?= url('/exams/create') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('subject') ?></label>
                    <select name="subject_id" required class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all cursor-pointer">
                        <option value="" disabled selected><?= __('select_subject') ?></option>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>" class="bg-indigo-900 text-white"><?= htmlspecialchars($subject['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('date') ?></label>
                        <input type="date" name="date" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('time') ?></label>
                        <input type="time" name="time" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all [color-scheme:dark]">
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <a href="<?= url('/exams') ?>" class="w-1/3 py-3 rounded-xl border border-white/20 text-indigo-200 font-bold hover:bg-white/10 text-center transition-all"><?= __('cancel') ?></a>
                    <button type="submit" class="w-2/3 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-pink-500/40 hover:scale-[1.02] transition-all">
                        <?= __('save_exam') ?>
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
