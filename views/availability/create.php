<!-- views/availability/create.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('add_slot') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe?q=80&w=2668&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10 flex items-center justify-center">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 md:p-10 rounded-3xl shadow-2xl w-full max-w-xl animate__animated animate__zoomIn">
            <h2 class="text-3xl font-bold text-white mb-8 text-center flex items-center justify-center gap-3">
                <i data-lucide="clock" class="w-8 h-8 text-emerald-400"></i> <?= __('add_slot') ?>
            </h2>
            
            <?php if(isset($error)): ?>
                <div class="bg-red-500/20 text-red-200 p-4 rounded-xl mb-6 border border-red-500/30"><?= $error ?></div>
            <?php endif; ?>

            <form action="<?= url('/availability/create') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('day') ?></label>
                    <div class="relative">
                        <select name="day" required class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all cursor-pointer appearance-none">
                            <option value="1" class="bg-indigo-900"><?= __('monday') ?></option>
                            <option value="2" class="bg-indigo-900"><?= __('tuesday') ?></option>
                            <option value="3" class="bg-indigo-900"><?= __('wednesday') ?></option>
                            <option value="4" class="bg-indigo-900"><?= __('thursday') ?></option>
                            <option value="5" class="bg-indigo-900"><?= __('friday') ?></option>
                            <option value="6" class="bg-indigo-900"><?= __('saturday') ?></option>
                            <option value="0" class="bg-indigo-900"><?= __('sunday') ?></option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-300">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('start_time') ?></label>
                        <input type="time" name="start_time" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-indigo-200 text-sm font-bold mb-2 uppercase tracking-wide"><?= __('end_time') ?></label>
                        <input type="time" name="end_time" required 
                               class="w-full bg-indigo-900/50 border border-indigo-500/30 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all [color-scheme:dark]">
                    </div>
                </div>

                <div class="p-4 bg-indigo-500/10 border border-indigo-500/20 text-indigo-200 text-sm rounded-xl">
                    💡 <strong><?= __('availability_tip_title') ?></strong> <?= __('availability_tip_text') ?>
                </div>

                <div class="pt-4 flex gap-4">
                    <a href="<?= url('/availability') ?>" class="w-1/3 py-3 rounded-xl border border-white/20 text-indigo-200 font-bold hover:bg-white/10 text-center transition-all"><?= __('cancel') ?></a>
                    <button type="submit" class="w-2/3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-emerald-500/40 hover:scale-[1.02] transition-all">
                        <?= __('save_slot') ?>
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
