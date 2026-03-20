<!-- views/auth/login.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('login') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 min-h-screen flex items-center justify-center p-4">
    
    <!-- Decorative background elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>

    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-md relative z-10 animate__animated animate__fadeInUp">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center justify-center gap-3"><?= __('welcome_login') ?> <i data-lucide="sparkles" class="w-8 h-8 text-indigo-400"></i></h1>
            <p class="text-indigo-200 text-sm"><?= __('login_subtitle') ?></p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-100 p-4 rounded-xl mb-6 text-sm flex items-center gap-2 animate__animated animate__shakeX">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['registered'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-green-100 p-4 rounded-xl mb-6 text-sm flex items-center gap-2 animate__animated animate__bounceIn">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?= __('verification_code_sent') ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['verified'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-green-100 p-4 rounded-xl mb-6 text-sm flex items-center gap-2 animate__animated animate__bounceIn">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <?= __('email_verified_success') ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['reset'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-green-100 p-4 rounded-xl mb-6 text-sm flex items-center gap-2 animate__animated animate__bounceIn">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <?= __('password_reset_success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= url('/login') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <div>
                <label class="block text-indigo-200 text-sm font-bold mb-2 ml-1"><?= __('email') ?></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-300 group-focus-within:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                    <input type="email" name="email" required 
                           class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 pl-10 pr-4 placeholder-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-inner"
                           placeholder="<?= __('email_placeholder') ?>">
                </div>
            </div>
            <div>
                <label class="block text-indigo-200 text-sm font-bold mb-2 ml-1"><?= __('password') ?></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-300 group-focus-within:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" name="password" required 
                           class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 pl-10 pr-4 placeholder-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-inner"
                           placeholder="<?= __('password_placeholder') ?>">
                </div>
                <div class="text-right mt-2">
                    <a href="<?= url('/forgot-password') ?>" class="text-xs text-indigo-300 hover:text-white transition-colors"><?= __('forgot_password') ?? 'Mot de passe oublié ?' ?></a>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold py-3.5 px-4 rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                <?= __('login') ?>
            </button>
        </form>
        
        <div class="mt-8 text-center border-t border-white/10 pt-6">
            <p class="text-sm text-indigo-200">
                <?= __('no_account') ?> 
                <a href="<?= url('/register') ?>" class="text-white font-bold hover:text-pink-300 transition-colors underline-offset-2 hover:underline"><?= __('register') ?></a>
            </p>
        </div>
    </div>

    <style>
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob {
            0% { transform: scale(1); }
            33% { transform: scale(1.1); }
            66% { transform: scale(0.9); }
            100% { transform: scale(1); }
        }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
