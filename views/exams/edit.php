<!-- views/exams/edit.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('edit_exam') ?> - Smart Revision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 w-full overflow-auto p-4 md:p-10 relative z-10 flex flex-col items-center justify-center">
        <div class="w-full max-w-lg animate__animated animate__zoomIn">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 md:p-8 rounded-3xl shadow-2xl">
                <h2 class="text-3xl font-extrabold text-white mb-6 text-center flex items-center justify-center gap-3">
                    <i data-lucide="edit-3" class="w-8 h-8 text-pink-400"></i> <?= __('edit_exam') ?>
                </h2>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-xl mb-6">
                        <?= __('generic_error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= url('/exams/update') ?>" method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $exam['id'] ?>">
                    <?= csrf_field() ?>
                    
                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('subject') ?></label>
                        <select name="subject_id" required class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all [&>option]:text-black">
                            <?php foreach($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>" <?= $subject['id'] == $exam['subject_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('exam_date') ?></label>
                        <input type="date" name="date" required value="<?= $exam['exam_date'] ?>"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all [color-scheme:dark]">
                    </div>

                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('time') ?></label>
                        <input type="time" name="time" required value="<?= $exam['exam_time'] ?>"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all [color-scheme:dark]">
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="<?= url('/exams') ?>" class="flex-1 py-3 text-center text-white bg-white/10 hover:bg-white/20 rounded-xl font-bold transition-all">
                            <?= __('cancel') ?>
                        </a>
                        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-xl font-bold shadow-lg transform hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i> <?= __('save') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
