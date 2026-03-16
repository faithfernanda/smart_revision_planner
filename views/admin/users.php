<!-- views/admin/users.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les utilisateurs - Administration</title>
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
                <h1 class="text-3xl md:text-4xl font-extrabold drop-shadow-md flex items-center gap-3">
                    <i data-lucide="users" class="w-10 h-10 text-indigo-400"></i> Gestion des Utilisateurs
                </h1>
                <p class="text-indigo-200 mt-2">Liste complète des comptes enregistrés.</p>
            </div>
            <a href="<?= url('/admin') ?>" class="px-6 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all">Retour Dashboard</a>
        </header>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 text-green-300 rounded-2xl animate__animated animate__headShake">
                Utilisateur supprimé avec succès !
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 text-red-300 rounded-2xl animate__animated animate__headShake">
                <?= $_GET['error'] == 'cannot_delete_self' ? "Vous ne pouvez pas supprimer votre propre compte." : "Une erreur est survenue." ?>
            </div>
        <?php endif; ?>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl overflow-x-auto animate__animated animate__fadeInUp">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 text-indigo-200">
                        <th class="p-5 font-bold uppercase text-xs">ID</th>
                        <th class="p-5 font-bold uppercase text-xs">Nom Complet</th>
                        <th class="p-5 font-bold uppercase text-xs">Email</th>
                        <th class="p-5 font-bold uppercase text-xs">Rôle</th>
                        <th class="p-5 font-bold uppercase text-xs">Date Inscription</th>
                        <th class="p-5 font-bold uppercase text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach($users as $user): ?>
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="p-5 text-indigo-300 font-mono text-sm"><?= $user['id'] ?></td>
                        <td class="p-5 font-semibold"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                        <td class="p-5 text-indigo-100"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="p-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase <?= $user['role'] === 'admin' ? 'bg-pink-500 text-white' : 'bg-indigo-500/30 text-indigo-200' ?>">
                                <?= $user['role'] ?>
                            </span>
                        </td>
                        <td class="p-5 text-sm text-indigo-300"><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td class="p-5 flex items-center gap-4">
                            <?php if($user['id'] != $_SESSION['user_id']): ?>
                                <a href="<?= url('/dashboard') ?>?user_id=<?= $user['id'] ?>" class="text-indigo-400 hover:text-indigo-300 hover:scale-110 transition-all flex items-center gap-2">
                                    <i data-lucide="eye" class="w-4 h-4"></i> Dashboard
                                </a>
                                <form action="<?= url('/admin/users/delete') ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="text-red-400 hover:text-red-300 hover:scale-110 transition-all flex items-center gap-2">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-indigo-400 italic">(Vous)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
