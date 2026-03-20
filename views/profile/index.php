<!-- views/profile/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('profile_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-3">
                    <i data-lucide="user" class="w-10 h-10 text-indigo-400"></i> <?= __('profile_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('profile_subtitle') ?></p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate__animated animate__fadeInUp">
            
            <!-- Informations Personnelles -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl">
                <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4"><?= __('my_info') ?></h2>
                
                <form action="<?= url('/profile/update') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-indigo-200 mb-2 font-medium"><?= __('firstname') ?></label>
                            <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required 
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-indigo-200 mb-2 font-medium"><?= __('lastname') ?></label>
                            <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required 
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('email') ?></label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required 
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-indigo-200 mb-2 font-medium"><?= __('study_level') ?></label>
                            <select name="study_level" class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all [&>option]:text-black">
                                <option value="BTS" <?= ($user['study_level'] == 'BTS' || $user['study_level'] == 'Lycee') ? 'selected' : '' ?>><?= __('high_school') ?></option>
                                <option value="Licence" <?= $user['study_level'] == 'Licence' ? 'selected' : '' ?>><?= __('bachelor') ?></option>
                                <option value="Master" <?= $user['study_level'] == 'Master' ? 'selected' : '' ?>><?= __('master') ?></option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-indigo-200 mb-2 font-medium"><?= __('major') ?></label>
                            <input type="text" name="major" value="<?= htmlspecialchars($user['major']) ?>" required 
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-indigo-300 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl font-bold shadow-lg transform hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i> <?= __('update_button') ?>
                    </button>
                    
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                        <div class="text-green-300 text-center font-bold bg-green-500/10 py-2 rounded-lg flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> <?= __('info_updated') ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Sécurité -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl h-fit">
                <h2 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4"><?= __('security') ?></h2>
                
                <form action="<?= url('/profile/password') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('current_password') ?></label>
                        <input type="password" name="current_password" required 
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('new_password') ?></label>
                        <input type="password" name="new_password" required minlength="6"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-indigo-200 mb-2 font-medium"><?= __('confirm_password') ?></label>
                        <input type="password" name="confirm_password" required minlength="6"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-xl font-bold shadow-lg transform hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-5 h-5"></i> <?= __('change_password_button') ?>
                    </button>

                    <?php if(isset($_GET['error'])): ?>
                        <?php if($_GET['error'] == 'password_mismatch'): ?>
                            <div class="text-red-300 text-center font-bold bg-red-500/10 py-2 rounded-lg"><?= __('password_mismatch') ?></div>
                        <?php elseif($_GET['error'] == 'wrong_password'): ?>
                            <div class="text-red-300 text-center font-bold bg-red-500/10 py-2 rounded-lg"><?= __('wrong_password') ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'password_updated'): ?>
                        <div class="text-green-300 text-center font-bold bg-green-500/10 py-2 rounded-lg flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> <?= __('password_updated') ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Notifications -->
            <div id="notifications" class="md:col-span-2 bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl mt-8">
                <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i data-lucide="bell" class="w-6 h-6 text-indigo-400"></i> <?= __('my_notifications') ?>
                    </h2>
                    <button onclick="markAllRead()" class="text-sm text-indigo-300 hover:text-white transition-colors"><?= __('mark_all_read') ?></button>
                </div>

                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php if (empty($notifications)): ?>
                        <p class="text-indigo-200 text-center py-8"><?= __('no_notifications') ?></p>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <div class="p-4 rounded-2xl border transition-all <?= $notif['is_read'] ? 'bg-white/5 border-white/5' : 'bg-white/20 border-white/20 shadow-lg' ?>">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-white"><?= htmlspecialchars($notif['title']) ?></h3>
                                    <span class="text-xs text-indigo-300"><?= date('d/m H:i', strtotime($notif['created_at'])) ?></span>
                                </div>
                                <p class="text-indigo-100 text-sm"><?= htmlspecialchars($notif['message']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        function markAllRead() {
            fetch('<?= url("/notifications/read-all") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    csrf_token: '<?= generate_csrf_token() ?>'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(err => console.error('Error marking as read:', err));
        }

        // Auto mark as read when visiting profile - Disabled to prevent immediate disappearance
        /*
        window.addEventListener('DOMContentLoaded', () => {
             if (window.location.hash === '#notifications') {
                markAllRead();
             }
        });
        */
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
