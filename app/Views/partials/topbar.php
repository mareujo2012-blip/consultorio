<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>
<header
    class="bg-white/80 backdrop-blur-md border-b border-slate-200/50 px-8 py-4 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-6">
        <!-- Mobile menu toggle -->
        <button class="lg:hidden p-2 rounded-xl bg-slate-100 text-slate-500 hover:text-blue-600 transition-all"
            onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Caminho / Seção
            </h2>
            <p class="text-sm font-extrabold text-slate-900 leading-none">
                <?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></p>
        </div>
    </div>

    <div class="flex items-center gap-6">
        <div class="hidden sm:flex flex-col items-end">
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-tighter">Status do Sistema</span>
            <span class="text-xs font-bold text-slate-400"><?= date('d M, Y') ?></span>
        </div>
        <div class="h-8 w-[1px] bg-slate-200 hidden sm:block"></div>
        <a href="<?= $appUrl ?>/patients/create"
            class="hidden sm:flex items-center gap-2 btn-primary text-white text-xs font-bold px-5 py-2.5 rounded-2xl transition-all shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Novo Registro
        </a>
    </div>
</header>