<?php $pageTitle = 'Prescrição Digital'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/appointments/<?= $appointment['id'] ?>"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Prescrição Digital</h1>
                <p class="text-slate-500 font-medium mt-1">Gere receitas médicas oficiais com certificação da clínica.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Formulation Container -->
    <div class="premium-card rounded-[2.5rem] p-10">
        <!-- Contextual Header -->
        <div class="flex items-center gap-5 p-6 bg-slate-50/50 rounded-[1.8rem] border border-slate-100 mb-10">
            <div
                class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-blue-200">
                <?= strtoupper(substr($appointment['patient_name'], 0, 1)) ?>
            </div>
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-0.5">Paciente Vinculado</p>
                <div class="flex items-center gap-3">
                    <p class="text-lg font-black text-slate-800 leading-tight">
                        <?= htmlspecialchars($appointment['patient_name']) ?></p>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <p class="text-xs font-medium text-slate-500">
                        <?= date('d/m/Y', strtotime($appointment['appointment_date'])) ?></p>
                </div>
            </div>
            <?php if ($existingPrescription): ?>
                <div
                    class="ml-auto flex items-center gap-2 bg-amber-50 text-amber-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-100">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Histórico Existente
                </div>
            <?php endif; ?>
        </div>

        <form action="<?= $appUrl ?>/prescriptions" method="POST" class="space-y-8">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
            <input type="hidden" name="patient_id" value="<?= $appointment['patient_id'] ?>">

            <div class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Corpo da Prescrição
                        Médica</label>
                    <span class="text-[10px] font-bold text-slate-400 italic">O papel timbrado será gerado
                        automaticamente</span>
                </div>

                <div class="relative group">
                    <textarea name="content" rows="18" required
                        class="input-premium w-full rounded-[2rem] px-8 py-8 text-base font-black text-slate-700 focus:outline-none transition-all resize-none shadow-sm font-mono placeholder-slate-200"
                        placeholder="1. Medicamento X (Apresentação)............ 01 caixa&#10;Tomar 01 comprimido VO a cada 12 horas por 07 dias.&#10;&#10;2. Medicação Y................................ 02 frascos"><?= $existingPrescription ? htmlspecialchars($existingPrescription['content']) : '' ?></textarea>

                    <div class="absolute bottom-6 right-6 opacity-20 pointer-events-none">
                        <svg class="w-16 h-16 text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 21l-1.45-1.34C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.16L12 21z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex items-center justify-end gap-6 pt-4">
                <a href="<?= $appUrl ?>/appointments/<?= $appointment['id'] ?>"
                    class="text-[10px] font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Descartar</a>
                <button type="submit"
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Finalizar e Emitir Receita
                </button>
            </div>
        </form>
    </div>
</div>