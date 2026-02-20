<?php $pageTitle = 'Atendimentos'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Atendimentos</h1>
            <p class="text-sm text-slate-500"><?= number_format($pagination['total']) ?> atendimento(s) registrado(s)</p>
        </div>
        <a href="<?= $appUrl ?>/appointments/create"
           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm shadow-md transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Atendimento
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-750 border-b border-slate-200 dark:border-slate-700">
                <tr class="text-left text-xs text-slate-400 uppercase tracking-wider">
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Paciente</th>
                    <th class="px-5 py-3">Data e Hora</th>
                    <th class="px-5 py-3 hidden md:table-cell">Pagamento</th>
                    <th class="px-5 py-3 text-right">Valor</th>
                    <th class="px-5 py-3">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach ($appointments as $apt): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                    <td class="px-5 py-3 text-slate-400 text-xs font-mono">#<?= $apt['id'] ?></td>
                    <td class="px-5 py-3">
                        <a href="<?= $appUrl ?>/patients/<?= $apt['patient_id'] ?>" class="font-medium text-slate-800 dark:text-slate-200 hover:text-primary-600">
                            <?= htmlspecialchars($apt['patient_name']) ?>
                        </a>
                    </td>
                    <td class="px-5 py-3 text-slate-500 text-xs"><?= date('d/m/Y H:i', strtotime($apt['appointment_date'])) ?></td>
                    <td class="px-5 py-3 hidden md:table-cell">
                        <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                            <?= htmlspecialchars($apt['payment_method'] ?: '—') ?>
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-emerald-600">R$ <?= number_format((float)$apt['value'], 2, ',', '.') ?></td>
                    <td class="px-5 py-3">
                        <a href="<?= $appUrl ?>/appointments/<?= $apt['id'] ?>" class="text-primary-600 hover:text-primary-800 text-xs font-medium">
                            Ver →
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($appointments)): ?>
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">Nenhum atendimento registrado ainda.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($pagination['last_page'] > 1): ?>
        <div class="border-t border-slate-200 dark:border-slate-700 px-5 py-3 flex items-center justify-between">
            <p class="text-xs text-slate-400">Página <?= $pagination['current_page'] ?> de <?= $pagination['last_page'] ?></p>
            <div class="flex gap-1">
                <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                    <a href="?page=<?= $i ?>"
                       class="px-3 py-1 rounded-lg text-xs <?= $i === $pagination['current_page'] ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
