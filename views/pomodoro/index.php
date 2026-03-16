<!-- views/pomodoro/index.php -->
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('pomodoro') ?> - <?= site_name() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .timer-circle {
            transition: stroke-dashoffset 1s linear;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col lg:flex-row min-h-screen">
    <?php include __DIR__ . '/../partials/mobile_header.php'; ?>
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-center justify-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <i data-lucide="timer" class="w-10 h-10 text-indigo-600"></i> <?= __('focus_timer') ?>
        </h1>

        <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md text-center">
            
            <div class="mb-6">
                <label for="subject-select" class="block text-left text-gray-600 font-bold mb-2"><?= __('subject_to_revise') ?></label>
                <select id="subject-select" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value=""><?= __('choose_subject') ?></option>
                    <?php if(!empty($subjects)): ?>
                        <?php foreach($subjects as $subject): ?>
                            <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="relative w-64 h-64 mx-auto mb-8">
                <!-- SVG Circle for progress -->
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="128" cy="128" r="120" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200" />
                    <circle id="progress-ring" cx="128" cy="128" r="120" stroke="currentColor" stroke-width="8" fill="transparent" class="text-indigo-600 timer-circle" />
                </svg>
                <!-- Time Display -->
                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center flex-col">
                    <span id="timer-display" class="text-6xl font-bold text-gray-800">25:00</span>
                    <span id="mode-display" class="text-gray-500 uppercase tracking-widest mt-2 text-sm"><?= __('work') ?></span>
                </div>
            </div>

            <div class="flex gap-4 justify-center">
                <button id="start-btn" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold text-lg hover:bg-indigo-700 transition shadow-lg">
                    <?= __('start') ?>
                </button>
                <button id="reset-btn" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-bold text-lg hover:bg-gray-300 transition">
                    <?= __('reset') ?>
                </button>
            </div>

            <div class="mt-8 flex justify-center gap-2">
                <button onclick="setMode(25, '<?= __('work') ?>')" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium hover:bg-indigo-200">Pomodoro 25m</button>
                <button onclick="setMode(5, '<?= __('break') ?>')" class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium hover:bg-green-200">Pause 5m</button>
                <button onclick="setMode(15, '<?= __('long_break') ?>')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium hover:bg-blue-200">Pause 15m</button>
            </div>
        </div>
    </main>

    <script>
        let timeLeft = 25 * 60;
        let totalTime = 25 * 60;
        let timerId = null;
        let isRunning = false;
        let currentMode = '<?= __('work') ?>'; // Track mode
        
        const timerDisplay = document.getElementById('timer-display');
        const startBtn = document.getElementById('start-btn');
        const progressRing = document.getElementById('progress-ring');
        const circumference = 2 * Math.PI * 120; // r=120

        progressRing.style.strokeDasharray = `${circumference} ${circumference}`;
        progressRing.style.strokeDashoffset = 0;

        function setProgress(percent) {
            const offset = circumference - percent / 100 * circumference;
            progressRing.style.strokeDashoffset = offset;
        }

        function updateDisplay() {
            const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            const s = (timeLeft % 60).toString().padStart(2, '0');
            timerDisplay.textContent = `${m}:${s}`;
            
            const percent = ((totalTime - timeLeft) / totalTime) * 100;
            setProgress(percent);
        }

        function saveSession() {
            const subjectId = document.getElementById('subject-select').value;
            if (!subjectId || currentMode !== '<?= __('work') ?>') return;

            const duration = Math.round(totalTime / 60);

            fetch('<?= url("/pomodoro/save") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    subject_id: subjectId,
                    duration: duration,
                    csrf_token: '<?= generate_csrf_token() ?>'
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Oscar-worthy notification
                     new Notification("<?= __('bravo') ?>", { body: "<?= __('session_saved') ?>" });
                }
            })
            .catch(err => console.error(err));
        }

        function startTimer() {
            if (isRunning) {
                clearInterval(timerId);
                isRunning = false;
                startBtn.textContent = '<?= __('resume') ?>';
                return;
            }

            const subjectSelect = document.getElementById('subject-select');
            if (currentMode === '<?= __('work') ?>' && subjectSelect.value === "") {
                if (!confirm("<?= __('no_subject_confirm') ?>")) {
                    return;
                }
            }

            if (Notification.permission !== "denied") {
                Notification.requestPermission();
            }

            isRunning = true;
            startBtn.textContent = '<?= __('pause') ?>';
            
            timerId = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    updateDisplay();
                } else {
                    clearInterval(timerId);
                    isRunning = false;
                    startBtn.textContent = '<?= __('start') ?>';
                    
                    // Logic when finished
                    new Notification("<?= __('pomodoro_finished') ?>", { body: "<?= __('time_up') ?>" });
                    const audio = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');
                    audio.play();

                    if (currentMode === '<?= __('work') ?>') {
                        saveSession();
                    }
                }
            }, 1000); // Speed up for debug if needed, keep 1000 for prod
        }

        function resetTimer() {
            clearInterval(timerId);
            isRunning = false;
            startBtn.textContent = '<?= __('start') ?>';
            timeLeft = totalTime;
            updateDisplay();
            setProgress(0);
        }

        function setMode(minutes, modeName) {
            resetTimer();
            totalTime = minutes * 60;
            timeLeft = totalTime;
            currentMode = modeName;
            document.getElementById('mode-display').textContent = modeName;
            updateDisplay();
        }

        startBtn.addEventListener('click', startTimer);
        document.getElementById('reset-btn').addEventListener('click', resetTimer);
        
        // Init
        updateDisplay();
    </script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
