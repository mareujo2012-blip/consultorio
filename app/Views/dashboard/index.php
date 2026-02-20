<?php $pageTitle = 'Dashboard'; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Dashboard</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Visão geral da clínica — <?= date('d \d\e F \d\e Y') ?></p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- Total Patients -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total de Pacientes</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white"><?= number_format($totalPatients) ?></p>
            </div>
        </div>

        <!-- Appointments this month -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Atendimentos (mês)</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white"><?= number_format($appointmentsMonth) ?></p>
            </div>
        </div>

        <!-- Revenue this month -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Faturamento (mês)</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white">R$ <?= number_format($revenueMonth, 2, ',', '.') ?></p>
            </div>
        </div>

        <!-- Avg ticket -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Ticket Médio</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white">R$ <?= number_format($avgTicket, 2, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        <!-- Appointments chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <h3 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-4">Atendimentos — Últimos 30 dias</h3>
            <canvas id="appointmentsChart" height="220"></canvas>
        </div>

        <!-- Revenue chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <h3 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-4">Faturamento — Últimos 30 dias</h3>
            <canvas id="revenueChart" height="220"></canvas>
        </div>
    </div>

    <!-- Recent appointments -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-slate-700 dark:text-slate-200">Atendimentos Recentes</h3>
            <a href="/appointments" class="text-sm text-primary-600 hover:underline">Ver todos →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                        <th class="pb-2 pr-4">Paciente</th>
                        <th class="pb-2 pr-4">Data</th>
                        <th class="pb-2 pr-4">Valor</th>
                        <th class="pb-2">Pagamento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php foreach ($recentAppointments as $apt): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                        <td class="py-2.5 pr-4 font-medium text-slate-800 dark:text-slate-200">
                            <a href="/patients/<?= $apt['patient_id'] ?>" class="hover:text-primary-600">
                                <?= htmlspecialchars($apt['patient_name']) ?>
                            </a>
                        </td>
                        <td class="py-2.5 pr-4 text-slate-500"><?= date('d/m/Y H:i', strtotime($apt['appointment_date'])) ?></td>
                        <td class="py-2.5 pr-4 font-semibold text-emerald-600">R$ <?= number_format((float)$apt['value'], 2, ',', '.') ?></td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                <?= htmlspecialchars($apt['payment_method'] ?: '—') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentAppointments)): ?>
                        <tr><td colspan="4" class="py-8 text-center text-slate-400">Nenhum atendimento ainda</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const labels = <?= json_encode($chartLabels) ?>;
const appointments = <?= json_encode($chartAppoints) ?>;
const revenue = <?= json_encode($chartRevenue) ?>;

// Appointments Chart
new Chart(document.getElementById('appointmentsChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Atendimentos',
            data: appointments,
            backgroundColor: 'rgba(37, 99, 235, 0.15)',
            borderColor: 'rgba(37, 99, 235, 0.8)',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Revenue Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Faturamento (R$)',
            data: revenue,
            borderColor: 'rgba(124, 58, 237, 0.9)',
            backgroundColor: 'rgba(124, 58, 237, 0.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: 'rgba(124, 58, 237, 1)',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: (v) => 'R$ ' + v.toLocaleString('pt-BR')
                }
            }
        }
    }
});
</script>
