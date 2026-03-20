<!-- views/auth/forgot_password.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('forgot_password') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-md relative z-10 animate__animated animate__fadeInUp">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center justify-center gap-3">Récupération <i data-lucide="key" class="w-8 h-8 text-indigo-400"></i></h1>
            <p class="text-indigo-200 text-sm">Saisissez votre email Gmail pour recevoir un code de réinitialisation.</p>
        </div>
        
        <form action="<?= url('/forgot-password') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <div>
                <label class="block text-indigo-200 text-sm font-bold mb-2 ml-1">Email Gmail</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="h-5 w-5 text-indigo-300"></i>
                    </div>
                    <input type="email" name="email" required 
                           class="w-full bg-indigo-900/50 border border-indigo-700 text-white rounded-xl py-3 pl-10 pr-4 placeholder-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner"
                           placeholder="votre@gmail.com">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold py-3.5 px-4 rounded-xl hover:shadow-lg transition-all duration-200">
                Envoyer le code
            </button>
        </form>
        
        <div class="mt-8 text-center border-t border-white/10 pt-6">
            <a href="<?= url('/login') ?>" class="text-sm text-indigo-200 hover:text-white transition-colors flex items-center justify-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la connexion
            </a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
