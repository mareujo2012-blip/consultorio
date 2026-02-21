<?php
$appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$currentPath = ltrim(str_replace($basePath, '', parse_url($currentUri, PHP_URL_PATH)), '/');

function navActive(string $prefix, string $currentPath): string
{
    return str_starts_with($currentPath, $prefix) || $currentPath === $prefix ? 'active' : '';
}

$clinic = (new \App\Models\ClinicSettings())->getSettings();
$clinicName = $clinic['name'] ?? 'ControleConsultório';
?>
<aside
    class="w-72 bg-white text-slate-600 flex flex-col h-[calc(100vh-2rem)] fixed inset-y-4 left-4 z-30 rounded-3xl border border-slate-200/50 shadow-xl shadow-slate-200/50 transition-all duration-300"
    id="sidebar">
    <!-- Logo -->
    <div class="flex items-center gap-4 px-6 py-8">
        <div
            class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/20 transition-transform hover:rotate-3">
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </div>
        <div>
            <h1 class="font-display font-extrabold text-lg text-slate-900 tracking-tight leading-tight">
                <?= htmlspecialchars($clinicName) ?>
            </h1>
            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-blue-600/60">Gestão Inteligente</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-2 space-y-2 overflow-y-auto custom-scrollbar">
        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Monitoramento</p>

        <a href="<?= $appUrl ?>/dashboard"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group <?= navActive('dashboard', $currentPath) === 'active' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' ?>">
            <svg class="w-5 h-5 <?= navActive('dashboard', $currentPath) === 'active' ? '' : 'group-hover:scale-110 transition-transform' ?>"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <a href="<?= $appUrl ?>/patients"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group <?= navActive('patients', $currentPath) === 'active' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' ?>">
            <svg class="w-5 h-5 <?= navActive('patients', $currentPath) === 'active' ? '' : 'group-hover:scale-110 transition-transform' ?>"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Pacientes
        </a>

        <a href="<?= $appUrl ?>/appointments"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group <?= navActive('appointments', $currentPath) === 'active' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' ?>">
            <svg class="w-5 h-5 <?= navActive('appointments', $currentPath) === 'active' ? '' : 'group-hover:scale-110 transition-transform' ?>"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Atendimentos
        </a>

        <a href="<?= $appUrl ?>/financial"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group <?= navActive('financial', $currentPath) === 'active' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' ?>">
            <svg class="w-5 h-5 <?= navActive('financial', $currentPath) === 'active' ? '' : 'group-hover:scale-110 transition-transform' ?>"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Financeiro
        </a>

        <div class="pt-6">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Administração</p>
            <a href="<?= $appUrl ?>/settings"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group <?= navActive('settings', $currentPath) === 'active' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' ?>">
                <svg class="w-5 h-5 <?= navActive('settings', $currentPath) === 'active' ? '' : 'group-hover:scale-110 transition-transform' ?>"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                </svg>
                Configurações
            </a>
            <?php if (($_SESSION['user_role'] ?? 'secretary') === 'admin'): ?>
                <a href="<?= $appUrl ?>/users"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group <?= navActive('users', $currentPath) === 'active' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' ?>">
                    <svg class="w-5 h-5 <?= navActive('users', $currentPath) === 'active' ? '' : 'group-hover:scale-110 transition-transform' ?>"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Profissionais
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- User Section -->
    <div class="p-4">
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600 shadow-sm border border-blue-200">
                    <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 truncate">
                        <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                    </p>
                    <p class="text-[10px] text-slate-500 truncate">
                        <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>
                    </p>
                </div>
                <a href="<?= $appUrl ?>/logout" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</aside>
<!-- Spacer for fixed sidebar -->
<div class="w-80 flex-shrink-0 hide-on-mobile"></div>