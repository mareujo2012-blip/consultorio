<?php $pageTitle = 'Receitas — ' . htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Histórico de Receitas</h1>
            <p class="text-sm text-slate-500">
                <?= htmlspecialchars($patient['name']) ?> ·
                <?= count($prescriptions) ?> receita(s)
            </p>
        </div>
    </div>

    <div
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <?php if (empty($prescriptions)): ?>
            <div class="py-12 text-center text-slate-400 text-sm">Nenhuma receita emitida ainda.</div>
        <?php else: ?>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-750 border-b border-slate-200 dark:border-slate-700">
                    <tr class="text-left text-xs text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3">Data</th>
                        <th class="px-5 py-3">Conteúdo (resumo)</th>
                        <th class="px-5 py-3">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php foreach ($prescriptions as $p): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                            <td class="px-5 py-3 text-slate-500 text-xs whitespace-nowrap">
                                <?= date('d/m/Y', strtotime($p['appointment_date'])) ?>
                            </td>
                            <td class="px-5 py-3 text-slate-700 dark:text-slate-300 max-w-xs truncate">
                                <?= htmlspecialchars(substr($p['content'], 0, 80)) ?>...
                            </td>
                            <td class="px-5 py-3">
                                <a href="<?= $appUrl ?>/prescriptions/<?= $p['id'] ?>/pdf" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs text-primary-600 hover:text-primary-800 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>