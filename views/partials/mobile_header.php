<!-- views/partials/mobile_header.php -->
<div class="lg:hidden flex items-center justify-between p-4 bg-indigo-900 text-white sticky top-0 z-30 shadow-md">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center text-white">
            <i data-lucide="book-open" class="w-5 h-5"></i>
        </div>
        <span class="font-bold text-lg tracking-tight"><?= site_name() ?></span>
    </div>
    <div class="flex items-center gap-2">
        <div class="flex bg-white/10 p-1 rounded-lg mr-2">
            <a href="<?= url('/lang?lang=fr') ?>" class="px-2 py-1 rounded text-[10px] font-bold <?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'bg-indigo-500 text-white' : 'text-indigo-200' ?>">FR</a>
            <a href="<?= url('/lang?lang=en') ?>" class="px-2 py-1 rounded text-[10px] font-bold <?= ($_SESSION['lang'] ?? 'fr') === 'en' ? 'bg-indigo-500 text-white' : 'text-indigo-300' ?>">EN</a>
        </div>
        <button id="mobile-menu-open" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </div>
</div>

<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 opacity-0"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('mobile-menu-open');
    const closeBtn = document.getElementById('mobile-menu-close');

    function toggleSidebar(show) {
        if (show) {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
            sidebar.classList.remove('-translate-x-full');
        } else {
            overlay.classList.remove('opacity-100');
            sidebar.classList.add('-translate-x-full');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }

    openBtn?.addEventListener('click', () => toggleSidebar(true));
    closeBtn?.addEventListener('click', () => toggleSidebar(false));
    overlay?.addEventListener('click', () => toggleSidebar(false));
});
</script>
