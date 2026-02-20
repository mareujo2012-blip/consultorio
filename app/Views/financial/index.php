<?php $pageTitle = 'Financeiro'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Controle Financeiro</h1>
            <p class="text-sm text-slate-500"><?= count($entries) ?> atendimento(s) no período selecionado</p>
        </div>
    </div>

    <!-- Filter form -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <form method="GET" action="<?= $appUrl ?>/financial" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wide">De</label>
                <input type="date" name="from" value="<?= htmlspecialchars($from) ?>"
                       class="border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wide">Até</label>
                <input type="date" name="to" value="<?= htmlspecialchars($to) ?>"
                       class="border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <!-- Quick filters -->
            <div class="flex gap-2">
                <?php
                $today = date('Y-m-d');
                $quickFilters = [
                    'Hoje'     => ['from' => $today, 'to' => $today],
                    'Semana'   => ['from' => date('Y-m-d', strtotime('monday this week')), 'to' => $today],
                    'Mês'      => ['from' => date('Y-m-01'), 'to' => $today],
                    'Ano'      => ['from' => date('Y-01-01'), 'to' => $today],
                ];
                foreach ($quickFilters as $label => $q): ?>
                    <a href="?from=<?= $q['from'] ?>&to=<?= $q['to'] ?>"
                       class="px-3 py-2.5 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-md">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-xs text-slate-400 uppercase tracking-wide font-medium">Total Atendimentos</p>
            <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1"><?= $count ?></p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-xs text-slate-400 uppercase tracking-wide font-medium">Faturamento Total</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1">R$ <?= number_format($total, 2, ',', '.') ?></p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
            <p class="text-xs text-slate-400 uppercase tracking-wide font-medium">Ticket Médio</p>
            <p class="text-3xl font-bold text-violet-600 mt-1">R$ <?= number_format($avgTicket, 2, ',', '.') ?></p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <h3 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-4">Evolução do Faturamento</h3>
        <canvas id="financialChart" height="120"></canvas>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-750 border-b border-slate-200 dark:border-slate-700">
                <tr class="text-left text-xs text-slate-400 uppercase tracking-wider">
                    <th class="px-5 py-3">Data</th>
                    <th class="px-5 py-3">Paciente</th>
                    <th class="px-5 py-3 hidden md:table-cell">CPF</th>
                    <th class="px-5 py-3 hidden lg:table-cell">Pagamento</th>
                    <th class="px-5 py-3 text-right">Valor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach ($entries as $e): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                    <td class="px-5 py-3 text-slate-500 text-xs"><?= date('d/m/Y H:i', strtotime($e['appointment_date'])) ?></td>
                    <td class="px-5 py-3 font-medium text-slate-800 dark:text-slate-200">
                        <a href="<?= $appUrl ?>/patients/<?= $e['patient_id'] ?>" class="hover:text-primary-600">
                            <?= htmlspecialchars($e['patient_name']) ?>
                        </a>
                    </td>
                    <td class="px-5 py-3 hidden md:table-cell text-slate-400 font-mono text-xs">
                        <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $e['patient_cpf'] ?? '') ?>
                    </td>
                    <td class="px-5 py-3 hidden lg:table-cell text-slate-500"><?= htmlspecialchars($e['payment_method'] ?: '—') ?></td>
                    <td class="px-5 py-3 text-right font-bold text-emerald-600">R$ <?= number_format((float)$e['value'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($entries)): ?>
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Nenhum resultado para o período selecionado.</td></tr>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($entries)): ?>
            <tfoot class="bg-slate-50 dark:bg-slate-750 border-t border-slate-200 dark:border-slate-700">
                <tr>
                    <td colspan="4" class="px-5 py-3 text-sm font-semibold text-slate-600 dark:text-slate-300">Total</td>
                    <td class="px-5 py-3 text-right font-bold text-emerald-600 text-base">R$ <?= number_format($total, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<script>
new Chart(document.getElementById('financialChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Faturamento (R$)',
            data: <?= json_encode($chartRevenue) ?>,
            backgroundColor: 'rgba(16, 185, 129, 0.15)',
            borderColor: 'rgba(16, 185, 129, 0.8)',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') }
            }
        }
    }
});
</script>
