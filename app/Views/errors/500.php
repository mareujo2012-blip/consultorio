<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro no Sistema — ControleConsultório</title>
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

        .premium-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div
            class="mb-8 inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-red-50 border border-red-100 shadow-xl shadow-red-500/10">
            <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-4xl font-display font-black text-slate-900 tracking-tight mb-4">Algo deu errado!</h1>
        <p class="text-slate-500 font-medium leading-relaxed mb-10">
            Ocorreu uma instabilidade interna ao processar sua requisição. Nossa equipe técnica já foi notificada
            silenciosamente.
        </p>

        <div class="space-y-4">
            <a href="/dashboard"
                class="w-full inline-flex items-center justify-center gap-3 px-8 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] text-sm font-bold transition-all shadow-xl shadow-blue-500/25 active:scale-95">
                Voltar para o Painel
            </a>

            <button onclick="window.location.reload()"
                class="w-full inline-flex items-center justify-center gap-3 px-8 py-5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-[2rem] text-sm font-bold transition-all active:scale-95">
                Tentar Novamente
            </button>
        </div>

        <p class="mt-12 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">
            Ambiente Seguro &bull; Id do Erro:
            <?= substr(md5((string) time()), 0, 8) ?>
        </p>
    </div>
</body>

</html>