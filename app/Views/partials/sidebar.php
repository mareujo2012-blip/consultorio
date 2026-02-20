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
$logoPath = !empty($clinic['logo']) ? $appUrl . '/' . $clinic['logo'] : null;
$clinicName = $clinic['name'] ?? 'ControleConsultório';
?>
<aside
    class="w-64 bg-slate-800 text-white flex flex-col h-full min-h-screen fixed inset-y-0 left-0 z-30 shadow-xl transition-transform duration-300"
    id="sidebar">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700">
        <?php if ($logoPath): ?>
            <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo" class="w-10 h-10 rounded-lg object-cover">
        <?php else: ?>
            <div class="w-10 h-10 rounded-lg bg-primary-600 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
        <?php endif; ?>
        <div>
            <h1 class="font-bold text-sm leading-tight">
                <?= htmlspecialchars($clinicName) ?>
            </h1>
            <p class="text-xs text-slate-400">Sistema Médico</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Principal</p>

        <a href="<?= $appUrl ?>/dashboard" class="sidebar-link <?= navActive('dashboard', $currentPath) ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2" />
                <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2" />
                <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2" />
                <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2" />
            </svg>
            Dashboard
        </a>

        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-4 mb-2">Clínica</p>

        <a href="<?= $appUrl ?>/patients" class="sidebar-link <?= navActive('patients', $currentPath) ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Pacientes
        </a>

        <a href="<?= $appUrl ?>/appointments" class="sidebar-link <?= navActive('appointments', $currentPath) ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Atendimentos
        </a>

        <a href="<?= $appUrl ?>/financial" class="sidebar-link <?= navActive('financial', $currentPath) ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Financeiro
        </a>

        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-4 mb-2">Sistema</p>

        <a href="<?= $appUrl ?>/settings" class="sidebar-link <?= navActive('settings', $currentPath) ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Configurações
        </a>
    </nav>

    <!-- User info bottom -->
    <div class="border-t border-slate-700 px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-xs font-bold">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                </p>
                <p class="text-xs text-slate-400 truncate">
                    <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>
                </p>
            </div>
            <a href="<?= $appUrl ?>/logout" class="text-slate-400 hover:text-red-400 transition-colors" title="Sair">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </a>
        </div>
    </div>
</aside>
<!-- Spacer for fixed sidebar -->
<div class="w-64 flex-shrink-0"></div>