<!-- views/exams/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('exams_title') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col lg:flex-row min-h-screen font-sans bg-[url('https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=2574&auto=format&fit=crop')] bg-cover bg-fixed">
    
    <div class="fixed inset-0 bg-indigo-900/80 backdrop-blur-sm z-0"></div>

    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-10 relative z-10">
        <?php include __DIR__ . '/../partials/admin_inspection_banner.php'; ?>
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-3">
                    <i data-lucide="calendar" class="w-10 h-10 text-indigo-400"></i> <?= __('exams_title') ?>
                </h1>
                <p class="text-indigo-200 mt-2"><?= __('exams_subtitle') ?></p>
            </div>
            <?php if(!$is_admin_view): ?>
                <a href="<?= url('/exams/create') ?>" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-indigo-50 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i> <?= __('add_exam') ?>
                </a>
            <?php endif; ?>
        </header>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl overflow-hidden shadow-2xl animate__animated animate__fadeInUp">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white/5 text-indigo-200 uppercase text-xs tracking-wider font-bold border-b border-white/10">
                        <tr>
                            <th class="px-8 py-5"><?= __('subject') ?></th>
                            <th class="px-8 py-5"><?= __('date') ?></th>
                            <th class="px-8 py-5"><?= __('time') ?></th>
                            <th class="px-8 py-5"><?= __('difficulty_coef') ?></th>
                            <th class="px-8 py-5 text-right"><?= __('days_remaining') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php if(empty($exams)): ?>
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-indigo-300 italic">
                                    <?= __('no_exams_msg') ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($exams as $exam): ?>
                            <?php
                                $examDate = new DateTime($exam['exam_date']);
                                $now = new DateTime();
                                $interval = $now->diff($examDate);
                                $daysLeft = $interval->invert ? 0 : $interval->days;
                                $badgeColor = $daysLeft < 7 ? 'bg-red-500/20 text-red-200' : ($daysLeft < 14 ? 'bg-yellow-500/20 text-yellow-200' : 'bg-emerald-500/20 text-emerald-200');
                            ?>
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-8 py-5">
                                    <span class="font-bold text-white text-lg"><?= htmlspecialchars($exam['subject_name']) ?></span>
                                </td>
                                <td class="px-8 py-5 text-white font-medium">
                                    <?= date('d M Y', strtotime($exam['exam_date'])) ?>
                                </td>
                                <td class="px-8 py-5 text-indigo-200">
                                    <?= substr($exam['exam_time'], 0, 5) ?>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex gap-2">
                                        <span class="px-2 py-1 bg-white/10 rounded text-xs text-white">Diff: <?= $exam['difficulty_level'] ?></span>
                                        <span class="px-2 py-1 bg-white/10 rounded text-xs text-white">Coef: <?= $exam['coefficient'] ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold <?= $badgeColor ?>">
                                        <?= $daysLeft ?> <?= __('days') ?>
                                    </span>
                                    <div class="inline-flex gap-2 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <?php if(!$is_admin_view): ?>
                                            <a href="<?= url('/exams/edit?id=' . $exam['id']) ?>" class="text-white hover:text-indigo-300 transition-colors" title="<?= __('edit') ?>">
                                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                                            </a>
                                            <form action="<?= url('/exams/delete') ?>" method="POST" onsubmit="return confirm('<?= __('confirm_delete_exam') ?>');" class="inline">
                                                <input type="hidden" name="id" value="<?= $exam['id'] ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="<?= __('delete') ?>">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
