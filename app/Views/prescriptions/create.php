<?php $pageTitle = 'Receita Médica'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-3xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="<?= $appUrl ?>/appointments/<?= $appointment['id'] ?>" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Receita Médica</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <!-- Patient info pill -->
        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700 rounded-xl mb-5">
            <div
                class="w-9 h-9 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center font-bold text-primary-600">
                <?= strtoupper(substr($appointment['patient_name'], 0, 1)) ?>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    <?= htmlspecialchars($appointment['patient_name']) ?>
                </p>
                <p class="text-xs text-slate-400">
                    <?= date('d/m/Y H:i', strtotime($appointment['appointment_date'])) ?>
                </p>
            </div>
            <?php if ($existingPrescription): ?>
                <span
                    class="ml-auto text-xs text-amber-600 bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 rounded-full font-medium">Receita
                    já cadastrada</span>
            <?php endif; ?>
        </div>

        <form action="<?= $appUrl ?>/prescriptions" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
            <input type="hidden" name="patient_id" value="<?= $appointment['patient_id'] ?>">

            <div>
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1.5">Conteúdo da
                    Receita</label>
                <textarea name="content" rows="14" required
                    class="w-full border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-mono focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder="Prescrição médica..."><?= $existingPrescription ? htmlspecialchars($existingPrescription['content']) : '' ?></textarea>
                <p class="text-xs text-slate-400 mt-1">O layout com logo e dados da clínica será adicionado
                    automaticamente ao PDF.</p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="<?= $appUrl ?>/appointments/<?= $appointment['id'] ?>"
                    class="px-5 py-2.5 text-sm text-slate-600 hover:text-slate-800">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Salvar e Gerar PDF
                </button>
            </div>
        </form>
    </div>
</div>