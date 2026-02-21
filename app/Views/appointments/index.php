<?php $pageTitle = 'Atendimento Clínico'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Atendimento Clínico</h1>
            <p class="text-slate-500 font-medium mt-1">Histórico de <?= number_format($pagination['total']) ?> sessões realizadas</p>
        </div>
        <a href="<?= $appUrl ?>/appointments/create"
           class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold transition-all shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Agendar Nova Consulta
        </a>
    </div>

    <!-- Results Table -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                    <th class="px-8 py-6">Protocolo</th>
                    <th class="px-8 py-6">Paciente</th>
                    <th class="px-8 py-6 hidden md:table-cell">Cronologia</th>
                    <th class="px-8 py-6 hidden lg:table-cell">Método / Valor</th>
                    <th class="px-8 py-6 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($appointments as $apt): ?>
                <tr class="group hover:bg-slate-50/50 transition-all">
                    <td class="px-8 py-5">
                        <span class="text-xs font-mono text-slate-400 font-bold">#<?= str_pad($apt['id'], 4, '0', STR_PAD_LEFT) ?></span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-black border border-blue-100">
                                <?= strtoupper(substr($apt['patient_name'], 0, 1)) ?>
                            </div>
                            <a href="<?= $appUrl ?>/patients/<?= $apt['patient_id'] ?>" class="text-sm font-bold text-slate-700 hover:text-blue-600 transition-colors">
                                <?= htmlspecialchars($apt['patient_name']) ?>
                            </a>
                        </div>
                    </td>
                    <td class="px-8 py-5 hidden md:table-cell">
                        <span class="text-xs font-medium text-slate-500"><?= date('d/m/Y • H:i', strtotime($apt['appointment_date'])) ?></span>
                    </td>
                    <td class="px-8 py-5 hidden lg:table-cell">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter"><?= htmlspecialchars($apt['payment_method'] ?: 'Pendente') ?></span>
                            <span class="text-sm font-black text-emerald-600">R$ <?= number_format((float)$apt['value'], 2, ',', '.') ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <a href="<?= $appUrl ?>/appointments/<?= $apt['id'] ?>" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Ver Detalhes
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($appointments)): ?>
                    <tr><td colspan="5" class="px-8 py-20 text-center">
                        <p class="text-slate-400 font-medium">Nenhum atendimento registrado no sistema.</p>
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($pagination['last_page'] > 1): ?>
        <div class="bg-slate-50/50 px-8 py-6 flex items-center justify-between border-t border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Página <?= $pagination['current_page'] ?> de <?= $pagination['last_page'] ?></p>
            <div class="flex gap-2">
                <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                    <a href="?page=<?= $i ?>"
                       class="w-10 h-10 flex items-center justify-center rounded-xl text-xs font-bold transition-all <?= $i === $pagination['current_page'] ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-white text-slate-500 hover:bg-slate-100 border border-slate-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
