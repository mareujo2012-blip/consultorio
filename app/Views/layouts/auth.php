<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>ControleConsultório
    </title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
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
</head>

<body class="min-h-screen bg-[#f8fafc] flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Efeitos de fundo sutis -->
    <div
        class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-100/50 rounded-full mix-blend-multiply filter blur-[100px] opacity-70">
    </div>
    <div
        class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-50/50 rounded-full mix-blend-multiply filter blur-[100px] opacity-70">
    </div>

    <div class="w-full max-w-md relative z-10 animate-fade-in">
        <?= $content ?>
    </div>
</body>

</html>