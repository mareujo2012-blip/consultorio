<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>
        <?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>ControleConsultório
    </title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
</head>

<body class="min-h-screen bg-[#f8fafc] text-slate-900 font-sans selection:bg-blue-500/10">

    <div class="flex h-full min-h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <?php include __DIR__ . '/../partials/topbar.php'; ?>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash messages -->
                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg shadow-sm"
                        x-data="{show:true}" x-show="show">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <?= htmlspecialchars($_SESSION['flash_success']) ?>
                        <button onclick="this.parentElement.remove()"
                            class="ml-auto text-emerald-500 hover:text-emerald-700">✕</button>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div
                        class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <?= htmlspecialchars($_SESSION['flash_error']) ?>
                        <button onclick="this.parentElement.remove()"
                            class="ml-auto text-red-500 hover:text-red-700">✕</button>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <?= $content ?>
            </main>
        </div>
    </div>

    <?php include __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>