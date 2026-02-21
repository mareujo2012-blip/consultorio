<?php $pageTitle = 'Painel de Gestão'; ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Painel de Gestão</h1>
            <p class="text-slate-500 font-medium mt-1">Bem-vindo, doutor. Aqui está o resumo da sua clínica.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= $appUrl ?>/financial/export" target="_blank"
                class="bg-white border border-slate-200 px-5 py-2.5 rounded-2xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">Relatório
                Mensal</a>
            <a href="<?= $appUrl ?>/appointments/create"
                class="btn-primary px-6 py-2.5 rounded-2xl text-xs font-bold transition-all inline-block">Nova
                Consulta</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Total Patients -->
        <div class="premium-card rounded-3xl p-6 flex items-center gap-5 border-b-4 border-b-blue-500">
            <div
                class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
                <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total de Pacientes</p>
                <p class="text-3xl font-display font-bold text-slate-900"><?= number_format($totalPatients) ?></p>
            </div>
        </div>

        <!-- Appointments count -->
        <div class="premium-card rounded-3xl p-6 flex items-center gap-5 border-b-4 border-b-emerald-500">
            <div
                class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0 border border-emerald-100">
                <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Consultas (Mês)</p>
                <p class="text-3xl font-display font-bold text-slate-900"><?= number_format($appointmentsMonth) ?></p>
            </div>
        </div>

        <!-- Revenue -->
        <div class="premium-card rounded-3xl p-6 flex items-center gap-5 border-b-4 border-b-indigo-500">
            <div
                class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center flex-shrink-0 border border-indigo-100">
                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Faturamento Bruto</p>
                <p class="text-2xl font-display font-bold text-slate-900">R$
                    <?= number_format($revenueMonth, 2, ',', '.') ?>
                </p>
            </div>
        </div>

        <!-- Ticket Medio -->
        <div class="premium-card rounded-3xl p-6 flex items-center gap-5 border-b-4 border-b-amber-500">
            <div
                class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center flex-shrink-0 border border-amber-100">
                <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Média por Paciente</p>
                <p class="text-2xl font-display font-bold text-slate-900">R$
                    <?= number_format($avgTicket, 2, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Appointments Chart -->
        <div class="premium-card rounded-[2.5rem] p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-display font-bold text-lg text-slate-800">Crescimento de Consultas</h3>
                <span
                    class="text-[10px] font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full uppercase tracking-widest border border-blue-100">30
                    dias</span>
            </div>
            <div class="h-[300px]">
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="premium-card rounded-[2.5rem] p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-display font-bold text-lg text-slate-800">Saúde Financeira</h3>
                <span
                    class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full uppercase tracking-widest border border-emerald-100">Performance</span>
            </div>
            <div class="h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="premium-card rounded-[2.5rem] p-8">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-display font-bold text-xl text-slate-900 tracking-tight">Registro de Atendimentos</h3>
            <a href="/appointments"
                class="text-xs font-bold text-blue-600 hover:bg-blue-50 transition-all border border-blue-100 px-5 py-2.5 rounded-2xl">Acessar
                Tudo</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 pb-4">Paciente</th>
                        <th class="px-6 pb-4">Data / Hora</th>
                        <th class="px-6 pb-4 text-right">Valor</th>
                        <th class="px-6 pb-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($recentAppointments as $apt): ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                        <?= strtoupper(substr($apt['patient_name'], 0, 1)) ?>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-700"><?= htmlspecialchars($apt['patient_name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span
                                    class="text-xs font-medium text-slate-500"><?= date('d/m/Y • H:i', strtotime($apt['appointment_date'])) ?></span>
                            </td>
                            <td class="px-6 py-5 text-right font-display font-bold text-slate-900 text-base">
                                R$ <?= number_format((float) $apt['value'], 2, ',', '.') ?>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span
                                    class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-widest">
                                    Confirmado
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = 'Plus Jakarta Sans';

    const labels = <?= json_encode($chartLabels) ?>;
    const appointments = <?= json_encode($chartAppoints) ?>;
    const revenue = <?= json_encode($chartRevenue) ?>;

    // Chart 1
    new Chart(document.getElementById('appointmentsChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: appointments,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                barThickness: 16,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: 'bold', size: 10 } } },
                y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Chart 2
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: revenue,
                borderColor: '#10b981',
                borderWidth: 4,
                fill: true,
                backgroundColor: (ctx) => {
                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
                    return gradient;
                },
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: 'bold', size: 10 } } },
                y: { grid: { color: '#f1f5f9' }, beginAtZero: true }
            }
        }
    });
</script>