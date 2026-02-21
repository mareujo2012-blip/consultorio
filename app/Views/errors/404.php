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
            background: radial-gradient(circle at top right, #f8fafc, #eff6ff);
        }

        .font-display {
            font-family: 'Outfit', sans-serif;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 overflow-hidden">
    <div class="max-w-xl w-full text-center relative">
        <!-- Floating 404 background -->
        <p
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[15rem] md:text-[20rem] font-display font-black text-blue-500/5 select-none z-0">
            404
        </p>

        <div class="relative z-10">
            <div
                class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-[2.5rem] bg-blue-600 shadow-2xl shadow-blue-500/40 animate-float">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <h1 class="text-4xl font-display font-black text-slate-900 tracking-tight mb-4">Perdido no Consultório?</h1>
            <p class="text-slate-500 font-medium leading-relaxed mb-10 max-w-sm mx-auto">
                Não conseguimos localizar o dossiê ou a sala que você está procurando. Talvez o endereço tenha expirado
                ou foi movido.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/dashboard"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 py-5 bg-slate-900 hover:bg-slate-800 text-white rounded-[2rem] text-sm font-bold transition-all shadow-xl shadow-slate-200 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Painel Principal
                </a>

                <button onclick="history.back()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 py-5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-[2rem] text-sm font-bold transition-all active:scale-95">
                    Voltar Anterior
                </button>
            </div>

            <div class="mt-16 pt-8 border-t border-slate-100 flex items-center justify-center gap-8">
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    <p
                        class="text-[10px] font-medium text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                        Sistema Online</p>
                </div>
                <div class="text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Dúvidas?</p>
                    <p class="text-[10px] font-medium text-blue-500">Suporte Técnico</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>