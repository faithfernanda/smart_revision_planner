<!-- views/errors/maintenance.php -->
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('maintenance_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-indigo-950 flex items-center justify-center min-h-screen font-sans text-white p-6">
    <div class="max-w-2xl w-full text-center animate__animated animate__fadeIn">
        <div class="mb-10 inline-block">
            <div class="text-indigo-400 animate__animated animate__swing animate__infinite animate__slow"><i data-lucide="wrench" class="w-24 h-24"></i></div>
        </div>
        
        <h1 class="text-5xl font-black mb-6 bg-clip-text text-transparent bg-gradient-to-r from-pink-400 to-purple-300">
            <?= __('maintenance_title') ?>
        </h1>
        
        <div class="bg-white/10 backdrop-blur-xl p-8 rounded-3xl border border-white/10 shadow-2xl mb-10">
            <p class="text-xl text-indigo-100 leading-relaxed">
                <?= __('maintenance_message') ?>
            </p>
        </div>
        
        <div class="flex items-center justify-center gap-4 text-indigo-300">
            <span class="w-2 h-2 bg-pink-500 rounded-full animate-ping"></span>
            <span class="text-sm font-bold uppercase tracking-widest"><?= __('maintenance_subtitle') ?></span>
        </div>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <div class="mt-12 bg-pink-500/20 p-4 rounded-2xl border border-pink-500/30">
                <p class="text-pink-300 text-sm font-bold">
                    🔧 <?= __('admin_message') ?> : <a href="<?= url('/admin') ?>" class="underline hover:text-white transition-colors"><?= __('admin_access_dashboard') ?></a>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
