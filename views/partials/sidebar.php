<?php require_once __DIR__ . '/../../models/User.php'; ?>
<!-- Side Navigation -->
<aside id="app-sidebar" class="fixed lg:sticky lg:top-0 lg:h-screen inset-y-0 left-0 w-72 bg-gradient-to-b from-indigo-900 via-purple-900 to-indigo-900 text-white flex flex-col shadow-2xl z-50 transition-all duration-300 ease-in-out transform -translate-x-full lg:translate-x-0">
    <div class="p-8 text-center border-b border-white/10 relative overflow-hidden group">
        <!-- Close Button (Mobile Only) -->
        <button id="mobile-menu-close" class="lg:hidden absolute top-4 left-4 p-2 hover:bg-white/10 rounded-lg transition-colors">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        
        <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="flex justify-center mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                <i data-lucide="book-open" class="w-7 h-7 text-white"></i>
            </div>
        </div>
        <h1 class="text-2xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-pink-200 drop-shadow-sm transform group-hover:scale-105 transition-transform duration-300">
            <?= site_name() ?>
        </h1>
        <!-- Notification Bell -->
        <div class="absolute top-4 right-4 animate__animated animate__pulse animate__infinite">
            <a href="<?= url('/profile') ?>#notifications" class="relative inline-block group">
                <i data-lucide="bell" class="w-6 h-6 text-indigo-200 filter drop-shadow-md group-hover:scale-110 transition-all"></i>
                <span id="notif-badge" class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-indigo-900 shadow-lg">0</span>
            </a>
        </div>
    </div>
    
    <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
        <?php
        $currentUri = $_SERVER['REQUEST_URI'];
        $isAdmin = User::isAdmin();

        $userItems = [
            url('/dashboard') => ['icon' => 'layout-dashboard', 'label' => __('dashboard')],
            url('/schedule') => ['icon' => 'calendar', 'label' => __('interactive_schedule')],
            url('/analytics') => ['icon' => 'bar-chart-3', 'label' => __('progression')],
            url('/subjects') => ['icon' => 'book', 'label' => __('subjects')],
            url('/exams') => ['icon' => 'graduation-cap', 'label' => __('exams')],
            url('/availability') => ['icon' => 'clock', 'label' => __('availability')],
            url('/pomodoro') => ['icon' => 'timer', 'label' => __('pomodoro')],
            url('/profile') => ['icon' => 'user', 'label' => __('profile')],
        ];

        // Admin Items
        $adminItems = [
            url('/admin') => ['icon' => 'shield-check', 'label' => __('admin_dashboard')],
            url('/admin/users') => ['icon' => 'users', 'label' => __('users')],
            url('/admin/settings') => ['icon' => 'settings', 'label' => __('settings')],
        ];
        ?>

        <?php
        $inspectUserId = (isset($_GET['user_id']) && $isAdmin) ? (int)$_GET['user_id'] : null;
        $showUserSection = !$isAdmin || $inspectUserId;
        ?>

        <?php if ($isAdmin): ?>
            <h3 class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-5 opacity-70"><?= __('management') ?></h3>
            <?php foreach ($adminItems as $uri => $item): ?>
                <?php $isActive = strpos($currentUri, $uri) === 0; ?>
                <a href="<?= $uri ?>" 
                   class="flex items-center gap-4 px-5 py-3 rounded-xl transition-all duration-300 group
                          <?= $isActive 
                               ? 'bg-pink-500/20 text-white shadow-lg border-l-4 border-pink-500 backdrop-blur-sm' 
                               : 'text-indigo-200 hover:bg-white/5 hover:text-white' ?>">
                    <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5 <?= $isActive ? 'text-pink-400' : 'group-hover:scale-110 transition-transform' ?>"></i>
                    <span class="font-medium text-sm"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>

            <?php if ($showUserSection): ?>
                <div class="pt-4 mt-4 border-t border-white/10">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-5 opacity-70"><?= __('inspection') ?></h3>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($showUserSection): ?>
            <?php if (!$isAdmin): ?>
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-5 opacity-70"><?= __('learning') ?></h3>
            <?php endif; ?>
            <?php foreach ($userItems as $uri => $item): ?>
                <?php 
                    $targetUri = $uri;
                    if ($inspectUserId) {
                        $targetUri .= (strpos($uri, '?') !== false ? '&' : '?') . 'user_id=' . $inspectUserId;
                    }
                    $isActive = strpos($currentUri, $uri) === 0; 
                ?>
                <a href="<?= $targetUri ?>" 
                   class="flex items-center gap-4 px-5 py-3 rounded-xl transition-all duration-300 group
                          <?= $isActive 
                               ? 'bg-white/10 text-white shadow-lg border-l-4 border-pink-500 backdrop-blur-sm' 
                               : 'text-indigo-200 hover:bg-white/5 hover:text-white' ?>">
                    <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5 <?= $isActive ? 'text-indigo-400' : 'group-hover:scale-110 transition-transform' ?>"></i>
                    <span class="font-medium text-sm"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>

    <div class="p-6 border-t border-white/10 bg-black/20 backdrop-blur-md space-y-3">
        <a href="<?= url('/profile') ?>" class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all group">
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white shadow-inner text-xs">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-xs font-bold truncate"><?= $_SESSION['user_name'] ?? __('user') ?></p>
            </div>
            <i data-lucide="settings" class="w-4 h-4 text-indigo-400 group-hover:rotate-90 transition-transform"></i>
        </a>

        <!-- Language Switcher -->
        <div class="flex items-center justify-center bg-white/5 p-1 rounded-xl border border-white/10">
            <a href="<?= url('/lang?lang=fr') ?>" 
               class="flex-1 text-center py-1.5 rounded-lg text-[10px] font-bold transition-all <?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'bg-indigo-500 text-white shadow-lg' : 'text-indigo-300 hover:text-white hover:bg-white/5' ?>">
               FR
            </a>
            <a href="<?= url('/lang?lang=en') ?>" 
               class="flex-1 text-center py-1.5 rounded-lg text-[10px] font-bold transition-all <?= ($_SESSION['lang'] ?? 'fr') === 'en' ? 'bg-indigo-500 text-white shadow-lg' : 'text-indigo-300 hover:text-white hover:bg-white/5' ?>">
               EN
            </a>
        </div>

        <form action="<?= url('/logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="w-full group relative flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-red-600/80 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 shadow-lg hover:shadow-red-500/30 overflow-hidden">
                <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                <span class="relative flex items-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span><?= __('logout') ?></span>
                </span>
            </button>
        </form>
    </div>
</aside>

<script>
    // Global Notification Logic
    document.addEventListener('DOMContentLoaded', () => {
        console.log("Notification system initialized");
        
        let notifiedSessions = [];

        function updateBadgeUI(count) {
            const badge = document.getElementById('notif-badge');
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        function checkUpcomingSession() {
            const checkUrl = '<?= url("/schedule/check-upcoming") ?>';
            console.log("Checking upcoming sessions at:", checkUrl);
            
            fetch(checkUrl)
                .then(res => res.json())
                .then(data => {
                    console.log("Check result:", data);
                    
                    // Sync badge count if provided
                    if (data.unread_count !== undefined) {
                        updateBadgeUI(data.unread_count);
                    }

                    if (data.success && data.session) {
                        const sess = data.session;
                        if (!notifiedSessions.includes(sess.id)) {
                            notifiedSessions.push(sess.id);
                            
                            // Play sound
                            const audio = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');
                            audio.play().catch(e => console.log('Audio play blocked', e));
                        }
                    }
                })
                .catch(err => console.error('Error checking sessions:', err));
        }

        setInterval(checkUpcomingSession, 60000);
        checkUpcomingSession();

        // Notification Badge Logic
        function updateNotifCount() {
            fetch('<?= url("/notifications/count") ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.count !== undefined) {
                        updateBadgeUI(data.count);
                    }
                })
                .catch(err => console.error('Error fetching count:', err));
        }

        setInterval(updateNotifCount, 30000);
        updateNotifCount();

        // Ensure icons are created for the sidebar
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
