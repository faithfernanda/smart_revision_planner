<?php if(isset($is_admin_view) && $is_admin_view): ?>
    <div class="bg-indigo-600 text-white p-4 rounded-2xl mb-8 flex items-center justify-between shadow-lg animate__animated animate__fadeInDown border border-indigo-400">
        <div class="flex items-center gap-3">
            <i data-lucide="shield-check" class="w-6 h-6"></i>
            <div>
                <p class="font-bold uppercase tracking-wider text-xs opacity-75">Mode Inspection Administrateur</p>
                <p class="text-sm font-medium">Vous visualisez les données de : <span class="bg-white/20 px-2 py-0.5 rounded font-bold"><?= htmlspecialchars($user_name ?? 'l\'utilisateur') ?></span></p>
            </div>
        </div>
        <a href="<?= url('/admin/users') ?>" class="px-5 py-2 bg-white/20 hover:bg-white/30 rounded-xl transition-all text-xs font-bold uppercase tracking-widest">Quitter</a>
    </div>
<?php endif; ?>
