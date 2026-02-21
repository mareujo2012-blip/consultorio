<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Página Não Encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Outfit:wght@700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
        }

        .font-display {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="text-center animate-fade-in">
        <p class="text-[12rem] font-display font-black text-slate-200 leading-none">404</p>
        <div class="-mt-12">
            <h1 class="text-3xl font-display font-extrabold text-slate-900">Perdido no Sistema?</h1>
            <p class="text-slate-500 font-medium mt-3 max-w-sm mx-auto">O módulo ou prontuário solicitado não foi
                localizado em nossa base de dados.</p>
            <a href="/dashboard"
                class="mt-10 inline-flex items-center gap-3 px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-slate-200 active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                Regressar ao Início
            </a>
        </div>
    </div>
</body>

</html>