<!-- views/home.php -->
<!-- Page d'accueil publique de l'application -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= site_name() ?> - <?= __('tagline') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bibliothèque d'icônes Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
    <style>
        /* Motif d'arrière-plan pour la section Hero */
        .hero-pattern {
            background-color: #312e81;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234338ca' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        /* Navigation à effet de verre (Glassmorphism) */
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <!-- Barre de Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo et Nom de l'application -->
                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                    </div>
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600"><?= site_name() ?></span>
                </div>
                <!-- Liens d'accès au compte -->
                <div class="flex items-center space-x-3 md:space-x-6">
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <a href="<?= url('/lang?lang=fr') ?>" class="px-2 py-1 rounded text-[10px] font-bold <?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-indigo-600' ?>">FR</a>
                        <a href="<?= url('/lang?lang=en') ?>" class="px-2 py-1 rounded text-[10px] font-bold <?= ($_SESSION['lang'] ?? 'fr') === 'en' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-indigo-600' ?>">EN</a>
                    </div>
                    <a href="<?= url('/login') ?>" class="text-xs md:text-base text-gray-600 hover:text-indigo-600 font-semibold transition-colors"><?= __('login') ?></a>
                    <a href="<?= url('/register') ?>" class="bg-indigo-600 text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full text-xs md:text-base font-bold shadow-lg hover:shadow-indigo-500/30 hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                        <?= __('register') ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Section Hero (Entête accrocheuse) -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-pattern">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-indigo-900/90"></div>
        
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 z-10 text-center lg:text-left grid lg:grid-cols-2 gap-12 items-center">
            
            <!-- Contenu Texte de la section Hero -->
            <div class="animate__animated animate__fadeInLeft">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 text-xs md:text-sm font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    <?= __('new_v2') ?>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-white leading-tight mb-6 tracking-tight">
                    <?= __('hero_title') ?>
                </h1>
                <p class="text-xl text-indigo-100 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    <?= __('hero_subtitle') ?>
                </p>
                <!-- Boutons d'appel à l'action (CTA) -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="<?= url('/register') ?>" class="px-8 py-4 bg-white text-indigo-900 font-bold rounded-xl shadow-xl hover:shadow-2xl hover:bg-gray-100 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i data-lucide="rocket" class="w-5 h-5"></i> <?= __('cta_start') ?>
                    </a>
                    <a href="#features" class="px-8 py-4 bg-transparent border-2 border-indigo-400 text-indigo-100 font-bold rounded-xl hover:bg-indigo-800/50 transition-all flex items-center justify-center">
                        <?= __('cta_learn_more') ?>
                    </a>
                </div>
            </div>

            <!-- Illustration Graphique (Mockup interactif) -->
            <div class="relative animate__animated animate__fadeInRight animate__delay-1s hidden lg:block">
                <!-- Décorations lumineuses floues -->
                <div class="absolute top-0 right-0 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                
                <!-- Carte simulant le tableau de bord -->
                <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-500">
                    <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                    <!-- Faux éléments d'interface pour illustration -->
                    <div class="space-y-4">
                        <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-4 flex items-center justify-between">
                            <div>
                                <div class="h-2 w-20 bg-white/30 rounded mb-2"></div>
                                <div class="h-6 w-32 bg-white rounded"></div>
                            </div>
                            <div class="h-12 w-12 bg-white/20 rounded-full"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="h-32 bg-white/5 rounded-2xl p-4 border border-white/10">
                                <div class="h-8 w-8 bg-pink-500 rounded-lg mb-4"></div>
                                <div class="h-3 w-16 bg-white/20 rounded"></div>
                            </div>
                            <div class="h-32 bg-white/5 rounded-2xl p-4 border border-white/10">
                                <div class="h-8 w-8 bg-emerald-500 rounded-lg mb-4"></div>
                                <div class="h-3 w-16 bg-white/20 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section des Fonctionnalités -->
    <section id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm"><?= __('features_title') ?></span>
                <h2 class="text-4xl font-extrabold text-gray-900 mt-2"><?= __('features_title') ?></h2>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Fonctionnalité 1 : Planification -->
                <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-indigo-50 transition-colors duration-300 border border-gray-100 hover:border-indigo-100">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform shadow-sm">
                        <i data-lucide="calendar" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= __('feature_1_title') ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?= __('feature_1_desc') ?>
                    </p>
                </div>

                <!-- Fonctionnalité 2 : Pomodoro -->
                <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-indigo-50 transition-colors duration-300 border border-gray-100 hover:border-indigo-100">
                    <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform shadow-sm">
                        <i data-lucide="timer" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= __('feature_2_title') ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?= __('feature_2_desc') ?>
                    </p>
                </div>

                <!-- Fonctionnalité 3 : Statistiques -->
                <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-indigo-50 transition-colors duration-300 border border-gray-100 hover:border-indigo-100">
                    <div class="w-16 h-16 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform shadow-sm">
                        <i data-lucide="bar-chart-3" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= __('feature_3_title') ?></h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?= __('feature_3_desc') ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pied de page (Footer) -->
    <footer class="bg-gray-900 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="text-white font-bold text-2xl mb-4 md:mb-0"><?= site_name() ?></div>
            <div class="text-gray-400 text-sm">
                &copy; <?= date('Y') ?> <?= site_name() ?>. <?= __('footer_rights') ?>
            </div>
        </div>
    </footer>

    <style>
        /* Animation personnalisée pour les décorations de fond */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
    <script>
        /* Génération des icônes Lucide */
        lucide.createIcons();
    </script>
</body>
</html>
