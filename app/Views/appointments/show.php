<?php $pageTitle = 'Prontuário de Atendimento #' . $appointment['id']; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/appointments"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Atendimento
                    #<?= str_pad($appointment['id'], 4, '0', STR_PAD_LEFT) ?></h1>
                <p class="text-slate-500 font-medium mt-1">
                    <?= date('d/m/Y • H:i', strtotime($appointment['appointment_date'])) ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= $appUrl ?>/prescriptions/appointment/<?= $appointment['id'] ?>"
                class="bg-violet-600 text-white px-6 py-3 rounded-2xl text-xs font-bold shadow-lg shadow-violet-200 hover:bg-violet-700 active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Gerar Receituário
            </a>
            <a href="<?= $appUrl ?>/patients/<?= $appointment['patient_id'] ?>/records"
                class="bg-slate-900 text-white px-6 py-3 rounded-2xl text-xs font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 active:scale-95 transition-all">
                Prontuário Histórico
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Patient Card -->
            <div class="premium-card rounded-[2rem] p-6 border-l-4 border-l-blue-500">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Paciente Identificado</p>
                <div class="flex items-center gap-4">
                    <?php if (!empty($appointment['patient_photo'])): ?>
                        <img src="<?= $appUrl . '/' . htmlspecialchars($appointment['patient_photo']) ?>"
                            class="w-16 h-16 rounded-2xl object-cover ring-4 ring-slate-50 shadow-sm">
                    <?php else: ?>
                        <div
                            class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-black border border-blue-100 shadow-sm">
                            <?= strtoupper(substr($appointment['patient_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h4 class="font-display font-bold text-slate-900 leading-tight">
                            <?= htmlspecialchars($appointment['patient_name']) ?></h4>
                        <p class="text-xs font-mono text-slate-400 mt-1">
                            <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $appointment['patient_cpf'] ?? '') ?>
                        </p>
                        <a href="<?= $appUrl ?>/patients/<?= $appointment['patient_id'] ?>"
                            class="inline-block mt-2 text-[10px] font-black uppercase text-blue-600 hover:underline">Ver
                            Perfil Completo</a>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="premium-card rounded-[2rem] p-6 space-y-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Resumo Financeiro</p>
                <div class="flex justify-between items-end">
                    <span class="text-xs font-bold text-slate-500 uppercase">Honorários</span>
                    <span class="text-2xl font-display font-black text-emerald-600">R$
                        <?= number_format((float) $appointment['value'], 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-50">
                    <span class="text-xs font-bold text-slate-500 uppercase">Método</span>
                    <span
                        class="text-xs font-black text-slate-700 uppercase tracking-wide"><?= htmlspecialchars($appointment['payment_method'] ?: 'A confirmar') ?></span>
                </div>
                <?php if (!empty($appointment['admin_notes'])): ?>
                    <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100 mt-2">
                        <p class="text-[10px] font-bold text-amber-600 uppercase mb-1">Observação Interna</p>
                        <p class="text-xs text-amber-700 leading-relaxed">
                            <?= htmlspecialchars($appointment['admin_notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Clinical Notes Area -->
        <div class="lg:col-span-8 space-y-6">
            <div class="premium-card rounded-[2.5rem] p-8">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-display font-bold text-slate-900">Registro Clínico</h3>
                </div>

                <!-- Fast Entry Form -->
                <form action="<?= $appUrl ?>/medical-records" method="POST"
                    class="space-y-6 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                    <input type="hidden" name="patient_id" value="<?= $appointment['patient_id'] ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Especialidade
                                da Entrada</label>
                            <select name="entry_type"
                                class="input-premium w-full rounded-xl px-4 py-3 text-sm font-bold focus:outline-none transition-all">
                                <option value="anamnese">Anamnese / Histórico</option>
                                <option value="exame">Exame Físico</option>
                                <option value="hipotese">Hipótese Diagnóstica</option>
                                <option value="conduta">Conduta Terapêutica</option>
                                <option value="evolucao">Evolução do Caso</option>
                                <option value="adendo">Adendo Oficial</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Descrição
                            Detalhada</label>
                        <textarea name="content" rows="6" required
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm focus:outline-none transition-all resize-none"
                            placeholder="Digite as observações clínicas aqui..."></textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="bg-blue-600 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Certificar e Salvar
                        </button>
                    </div>
                </form>

                <!-- Timeline of Records -->
                <div class="mt-12 space-y-6">
                    <h4
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100 pb-4">
                        Entradas Confirmadas</h4>

                    <?php if (empty($records)): ?>
                        <div
                            class="py-12 text-center bg-slate-50/30 rounded-[2rem] border-2 border-dashed border-slate-100">
                            <p class="text-sm font-medium text-slate-400">Nenhum registro clínico vinculado a este
                                atendimento.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($records as $r): ?>
                                <div
                                    class="bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-50">
                                        <span
                                            class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600">
                                            <?= htmlspecialchars($r['entry_type']) ?>
                                        </span>
                                        <div class="text-right">
                                            <p class="text-[10px] font-black text-slate-900">
                                                <?= date('d/m/Y • H:i', strtotime($r['created_at'])) ?></p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">
                                                <?= htmlspecialchars($r['created_by_name'] ?? 'Autor desconhecido') ?></p>
                                        </div>
                                    </div>
                                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap font-medium">
                                        <?= htmlspecialchars($r['content']) ?>
                                    </div>
                                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                        <div class="flex items-center gap-2 opacity-30">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <span
                                                class="text-[8px] font-mono text-slate-500 uppercase tracking-tighter">Blockchain
                                                Verified: <?= substr($r['content_hash'], 0, 16) ?>...</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>