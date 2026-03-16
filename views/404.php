<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <!-- Page d'erreur 404 personnalisée avec une esthétique moderne -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('404_title') ?> - <?= site_name() ?></title>
    <!-- Chargement de Tailwind CSS pour le stylisage -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bibliothèque d'icônes Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Bibliothèque d'animations CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-indigo-900 min-h-screen flex items-center justify-center overflow-auto relative">
    
    <!-- Éléments décoratifs d'arrière-plan (sphères floues animeés) -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-600 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <!-- Contenu principal du message d'erreur -->
    <div class="text-center relative z-10 p-8">
        <!-- Titre 404 stylisé avec un dégradé et une icône de recherche -->
        <h1 class="text-[150px] font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600 animate__animated animate__zoomIn leading-none drop-shadow-2xl flex items-center justify-center gap-4">
            4<i data-lucide="search-x" class="w-32 h-32 text-pink-500 inline-block"></i>4
        </h1>
        <p class="text-3xl text-white font-bold mt-4 animate__animated animate__fadeInUp animate__delay-1s">
            <?= __('404_message') ?>
        </p>
        <p class="text-indigo-200 mt-2 text-lg animate__animated animate__fadeInUp animate__delay-1s">
            <?= __('404_subtitle') ?>
        </p>
        
        <!-- Bouton de redirection vers l'accueil -->
        <div class="mt-12 animate__animated animate__fadeInUp animate__delay-2s">
            <a href="/" class="group relative inline-flex items-center gap-3 px-8 py-4 bg-white text-indigo-900 font-bold rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden">
                <span class="absolute inset-0 bg-gradient-to-r from-indigo-100 to-purple-100 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span class="relative flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    <?= __('back_to_home') ?>
                </span>
            </a>
        </div>
    </div>

    <!-- Initialisation des icônes Lucide -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
