<!-- views/auth/register.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('register') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-lg relative z-10 animate__animated animate__fadeInUp">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center justify-center gap-3"><?= __('register') ?> <i data-lucide="user-plus" class="w-8 h-8 text-indigo-400"></i></h1>
            <p class="text-indigo-200 text-sm"><?= __('register_subtitle') ?></p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-100 p-4 rounded-xl mb-6 text-sm"><?= $error ?></div>
        <?php endif; ?>

        <form action="<?= url('/register') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-indigo-200 text-xs font-bold mb-1 ml-1 uppercase"><?= __('firstname') ?></label>
                    <input type="text" name="firstname" required class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-indigo-200 text-xs font-bold mb-1 ml-1 uppercase"><?= __('lastname') ?></label>
                    <input type="text" name="lastname" required class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-indigo-200 text-xs font-bold mb-1 ml-1 uppercase"><?= __('email') ?></label>
                <input type="email" name="email" required class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-indigo-200 text-xs font-bold mb-1 ml-1 uppercase"><?= __('password') ?></label>
                <input type="password" name="password" required class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-indigo-200 text-xs font-bold mb-1 ml-1 uppercase"><?= __('study_level') ?></label>
                    <input type="text" name="study_level" placeholder="<?= __('level_placeholder') ?>" class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-indigo-200 text-xs font-bold mb-1 ml-1 uppercase"><?= __('major') ?></label>
                    <input type="text" name="major" placeholder="<?= __('major_placeholder') ?>" class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
            </div>

            <button type="submit" class="w-full mt-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold py-3.5 px-4 rounded-xl hover:shadow-lg hover:shadow-pink-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                <?= __('register_free') ?>
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-indigo-200">
                <?= __('have_account') ?> 
                <a href="<?= url('/login') ?>" class="text-white font-bold hover:text-pink-300 transition-colors"><?= __('login') ?></a>
            </p>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
