<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>
<header
    class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-3 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-4">
        <!-- Mobile menu toggle -->
        <button class="lg:hidden text-slate-500 hover:text-slate-700"
            onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">
            <?= htmlspecialchars($pageTitle ?? 'Painel') ?>
        </h2>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs text-slate-400">
            <?= date('d/m/Y H:i') ?>
        </span>
        <a href="<?= $appUrl ?>/patients/create"
            class="hidden sm:flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Paciente
        </a>
    </div>
</header>