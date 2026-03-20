<!-- views/auth/verify_reset_code.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-md relative z-10 animate__animated animate__fadeInUp">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center justify-center gap-3">Code de Sécurité <i data-lucide="shield-check" class="w-8 h-8 text-indigo-400"></i></h1>
            <p class="text-indigo-200 text-sm">Un code a été envoyé à <b><?= htmlspecialchars($email) ?></b>.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-100 p-4 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= url('/verify-reset-code') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="email" value="<?= $email ?>">
            <div>
                <label class="block text-indigo-200 text-sm font-bold mb-2 ml-1">Saisir le code à 6 chiffres</label>
                <input type="text" name="code" required maxlength="6"
                       class="w-full bg-indigo-900/50 border border-indigo-700 text-white text-center text-2xl tracking-[1em] rounded-xl py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner"
                       placeholder="000000">
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold py-3.5 px-4 rounded-xl hover:shadow-lg transition-all duration-200">
                Valider le code
            </button>
        </form>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
