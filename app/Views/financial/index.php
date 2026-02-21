<?php $pageTitle = 'Intelligence Financeira'; ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Intelligence Financeira</h1>
            <p class="text-slate-500 font-medium mt-1"><?= count($entries) ?> atendimento(s) no período selecionado</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= $appUrl ?>/financial/export?from=<?= $from ?>&to=<?= $to ?>" target="_blank"
                class="bg-white border border-slate-200 px-5 py-2.5 rounded-2xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">Exportar
                PDF</a>
        </div>
    </div>

    <!-- Filter form -->
    <div class="premium-card rounded-[2rem] p-6">
        <form method="GET" action="<?= $appUrl ?>/financial" class="flex flex-wrap items-end gap-6">
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">De</label>
                <input type="date" name="from" value="<?= htmlspecialchars($from) ?>"
                    class="input-premium rounded-xl px-4 py-3 text-sm focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Até</label>
                <input type="date" name="to" value="<?= htmlspecialchars($to) ?>"
                    class="input-premium rounded-xl px-4 py-3 text-sm focus:outline-none transition-all">
            </div>

            <!-- Quick filters -->
            <div class="flex gap-2 mb-0.5">
                <?php
                $today = date('Y-m-d');
                $quickFilters = [
                    'Hoje' => ['from' => $today, 'to' => $today],
                    'Semana' => ['from' => date('Y-m-d', strtotime('monday this week')), 'to' => date('Y-m-d', strtotime('sunday this week'))],
                    'Mês' => ['from' => date('Y-m-01'), 'to' => date('Y-m-t')],
                    'Ano' => ['from' => date('Y-01-01'), 'to' => date('Y-12-31')],
                ];
                foreach ($quickFilters as $label => $q): ?>
                    <a href="?from=<?= $q['from'] ?>&to=<?= $q['to'] ?>"
                        class="px-4 py-3 text-xs font-bold rounded-xl border border-slate-100 bg-slate-50 text-slate-500 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-600/20 transition-all">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <button type="submit"
                class="btn-primary px-8 py-3 rounded-xl text-xs font-bold transition-all shadow-lg active:scale-95 ml-auto">
                Atualizar Dados
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="premium-card rounded-3xl p-6 border-l-4 border-l-slate-200">
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">Volatilidade (Atendimentos)
            </p>
            <p class="text-3xl font-display font-black text-slate-900"><?= $count ?></p>
        </div>
        <div class="premium-card rounded-3xl p-6 border-l-4 border-l-emerald-500">
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">Receita Consolidada</p>
            <p class="text-3xl font-display font-black text-emerald-600">R$ <?= number_format($total, 2, ',', '.') ?>
            </p>
        </div>
        <div class="premium-card rounded-3xl p-6 border-l-4 border-l-blue-500">
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">Performance por Paciente</p>
            <p class="text-3xl font-display font-black text-blue-600">R$ <?= number_format($avgTicket, 2, ',', '.') ?>
            </p>
        </div>
    </div>

    <!-- Chart -->
    <div class="premium-card rounded-[2.5rem] p-8">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-display font-bold text-lg text-slate-800">Evolução do Faturamento</h3>
            <span
                class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full uppercase tracking-widest border border-emerald-100">Live
                analytics</span>
        </div>
        <div class="h-[300px]">
            <canvas id="financialChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="premium-card rounded-[2.5rem] p-8 overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-display font-bold text-xl text-slate-900 tracking-tight">Extrato Detalhado</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 pb-4">Data do Registro</th>
                        <th class="px-6 pb-4">Paciente</th>
                        <th class="px-6 pb-4 hidden md:table-cell">CPF Identificado</th>
                        <th class="px-6 pb-4 text-right">Valor Líquido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($entries as $e): ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-5 text-xs font-medium text-slate-500">
                                <?= date('d/m/Y • H:i', strtotime($e['appointment_date'])) ?>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <?= strtoupper(substr($e['patient_name'], 0, 1)) ?>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">
                                        <a href="<?= $appUrl ?>/patients/<?= $e['patient_id'] ?>"
                                            class="hover:text-blue-600">
                                            <?= htmlspecialchars($e['patient_name']) ?>
                                        </a>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5 hidden md:table-cell text-slate-400 font-mono text-[10px]">
                                <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $e['patient_cpf'] ?? '') ?>
                            </td>
                            <td class="px-6 py-5 text-right font-display font-bold text-slate-900 text-base">
                                R$ <?= number_format((float) $e['value'], 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if (!empty($entries)): ?>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-right font-display font-medium text-slate-400">Total
                                Acumulado</td>
                            <td class="px-6 py-8 text-right font-display font-black text-emerald-600 text-2xl">R$
                                <?= number_format($total, 2, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<script>
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = 'Plus Jakarta Sans';

    new Chart(document.getElementById('financialChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                data: <?= json_encode($chartRevenue) ?>,
                backgroundColor: '#10b981',
                borderRadius: 8,
                barThickness: 16,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: 'bold', size: 10 } } },
                y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') } }
            }
        }
    });
</script>