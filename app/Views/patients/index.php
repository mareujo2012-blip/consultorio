<?php $pageTitle = 'Pacientes'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pacientes</h1>
            <p class="text-sm text-slate-500"><?= number_format($pagination['total']) ?> paciente(s) cadastrado(s)</p>
        </div>
        <a href="<?= $appUrl ?>/patients/create"
           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-5 py-2.5 rounded-xl text-sm shadow-md transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Paciente
        </a>
    </div>

    <!-- Search -->
    <form method="GET" action="<?= $appUrl ?>/patients" class="flex gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                   placeholder="Buscar por nome, CPF, e-mail ou telefone..."
                   class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-medium transition-colors">
            Buscar
        </button>
        <?php if ($search): ?>
            <a href="<?= $appUrl ?>/patients" class="px-4 py-2.5 text-sm text-red-500 hover:text-red-700 flex items-center">Limpar</a>
        <?php endif; ?>
    </form>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-750">
                <tr class="text-left text-xs text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                    <th class="px-5 py-3">Paciente</th>
                    <th class="px-5 py-3 hidden md:table-cell">CPF</th>
                    <th class="px-5 py-3 hidden lg:table-cell">Telefone</th>
                    <th class="px-5 py-3 hidden xl:table-cell">Nascimento</th>
                    <th class="px-5 py-3">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach ($patients as $p): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($p['photo'])): ?>
                                <img src="<?= $appUrl . '/' . htmlspecialchars($p['photo']) ?>" alt="" class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-200">
                            <?php else: ?>
                                <div class="w-9 h-9 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 font-semibold text-sm">
                                    <?= strtoupper(substr($p['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-medium text-slate-800 dark:text-slate-100"><?= htmlspecialchars($p['name']) ?></p>
                                <p class="text-xs text-slate-400"><?= htmlspecialchars($p['email'] ?: '—') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 hidden md:table-cell text-slate-500 font-mono text-xs">
                        <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $p['cpf'] ?? '') ?>
                    </td>
                    <td class="px-5 py-3 hidden lg:table-cell text-slate-500">
                        <?= htmlspecialchars($p['phone'] ? '(' . substr($p['phone'],0,2) . ') ' . substr($p['phone'],2,5) . '-' . substr($p['phone'],7) : '—') ?>
                    </td>
                    <td class="px-5 py-3 hidden xl:table-cell text-slate-500">
                        <?= !empty($p['birth_date']) ? date('d/m/Y', strtotime($p['birth_date'])) : '—' ?>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="<?= $appUrl ?>/patients/<?= $p['id'] ?>" class="text-primary-600 hover:text-primary-800 text-xs font-medium">Ver</a>
                            <span class="text-slate-300">|</span>
                            <a href="<?= $appUrl ?>/patients/<?= $p['id'] ?>/edit" class="text-slate-500 hover:text-slate-700 text-xs">Editar</a>
                            <span class="text-slate-300">|</span>
                            <a href="<?= $appUrl ?>/appointments/create?patient_id=<?= $p['id'] ?>" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">+ Atendimento</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($patients)): ?>
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">
                        <?= $search ? 'Nenhum resultado para "' . htmlspecialchars($search) . '"' : 'Nenhum paciente cadastrado ainda.' ?>
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($pagination['last_page'] > 1): ?>
        <div class="border-t border-slate-200 dark:border-slate-700 px-5 py-3 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                Página <?= $pagination['current_page'] ?> de <?= $pagination['last_page'] ?>
                (<?= number_format($pagination['total']) ?> resultados)
            </p>
            <div class="flex gap-1">
                <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                    <?php $q = http_build_query(['search' => $search, 'page' => $i]); ?>
                    <a href="?<?= $q ?>"
                       class="px-3 py-1 rounded-lg text-xs <?= $i === $pagination['current_page'] ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
