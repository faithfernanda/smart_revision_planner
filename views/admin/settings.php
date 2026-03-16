<!-- views/admin/settings.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres Système - Smart Revision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-indigo-950 flex flex-col lg:flex-row min-h-screen font-sans text-white">
    
    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold drop-shadow-md flex items-center gap-4">
                    <i data-lucide="settings" class="w-10 h-10 text-indigo-400"></i> Paramètres Système
                </h1>
                <p class="text-indigo-200 mt-2">Configurez le comportement global de l'application.</p>
            </div>
            <a href="<?= url('/admin') ?>" class="px-6 py-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/10 text-sm font-bold whitespace-nowrap">
                ← Retour au Dashboard
            </a>
        </header>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <div class="mb-8 p-4 bg-green-500/20 border border-green-500/30 text-green-400 rounded-2xl animate__animated animate__headShake flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-400"></i> Paramètres mis à jour avec succès !
            </div>
        <?php endif; ?>

        <form action="<?= url('/admin/settings/update') ?>" method="POST" class="max-w-4xl animate__animated animate__fadeInUp">
            <?= csrf_field() ?>
            
            <div class="space-y-8">
                <!-- Maintenance Mode Card -->
                <div class="bg-indigo-900/40 backdrop-blur-xl p-8 rounded-3xl border border-white/10 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <i data-lucide="wrench" class="w-6 h-6 text-indigo-400"></i>
                            <div>
                                <h3 class="text-xl font-bold">Mode Maintenance</h3>
                                <p class="text-indigo-300 text-sm mt-1">Si activé, seuls les administrateurs peuvent accéder à l'application.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="on" class="sr-only peer" <?= $settings['maintenance_mode'] === 'on' ? 'checked' : '' ?>>
                            <div class="w-14 h-7 bg-indigo-900/50 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                    </div>
                </div>

                <!-- Site Configuration Card -->
                <div class="bg-indigo-900/40 backdrop-blur-xl p-8 rounded-3xl border border-white/10 shadow-2xl">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                        <i data-lucide="tag" class="w-6 h-6 text-indigo-400"></i> Identité du site
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-indigo-300 text-sm font-bold mb-2 ml-1">Nom de l'application</label>
                            <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name']) ?>" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:outline-none focus:border-pink-500 transition-all text-lg font-medium">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-10 py-4 bg-gradient-to-r from-pink-500 to-rose-500 rounded-2xl font-bold text-xl shadow-xl hover:shadow-pink-500/30 hover:scale-105 transition-all active:scale-95 flex items-center gap-3">
                        <i data-lucide="save" class="w-6 h-6"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
