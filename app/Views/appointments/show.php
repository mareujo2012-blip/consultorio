<?php $pageTitle = 'Atendimento #' . $appointment['id']; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?= $appUrl ?>/appointments" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Atendimento #
                    <?= $appointment['id'] ?>
                </h1>
                <p class="text-sm text-slate-500">
                    <?= date('d/m/Y H:i', strtotime($appointment['appointment_date'])) ?>
                </p>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="<?= $appUrl ?>/prescriptions/appointment/<?= $appointment['id'] ?>"
                class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Receita
            </a>
            <a href="<?= $appUrl ?>/patients/<?= $appointment['patient_id'] ?>/records"
                class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                Prontuário Completo
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Appointment details -->
        <div class="lg:col-span-1 space-y-4">
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Paciente</h3>
                <a href="<?= $appUrl ?>/patients/<?= $appointment['patient_id'] ?>"
                    class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                    <?php if (!empty($appointment['patient_photo'])): ?>
                        <img src="<?= $appUrl . '/' . htmlspecialchars($appointment['patient_photo']) ?>"
                            class="w-12 h-12 rounded-xl object-cover">
                    <?php else: ?>
                        <div
                            class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center font-bold text-primary-600 text-lg">
                            <?= strtoupper(substr($appointment['patient_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($appointment['patient_name']) ?>
                        </p>
                        <p class="text-xs text-slate-400">CPF:
                            <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $appointment['patient_cpf'] ?? '') ?>
                        </p>
                    </div>
                </a>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 space-y-3">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Financeiro</h3>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Valor</span>
                    <span class="font-bold text-emerald-600 text-lg">R$
                        <?= number_format((float) $appointment['value'], 2, ',', '.') ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Pagamento</span>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        <?= htmlspecialchars($appointment['payment_method'] ?: '—') ?>
                    </span>
                </div>
                <?php if (!empty($appointment['admin_notes'])): ?>
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Notas</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            <?= htmlspecialchars($appointment['admin_notes']) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Medical Record entries -->
        <div
            class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <h3 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-4">Prontuário deste Atendimento
            </h3>

            <!-- Add entry form -->
            <form action="<?= $appUrl ?>/medical-records" method="POST"
                class="mb-6 p-4 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-600">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                <input type="hidden" name="patient_id" value="<?= $appointment['patient_id'] ?>">

                <div class="flex gap-3 mb-3">
                    <div class="flex-1">
                        <label class="text-xs font-medium text-slate-500 mb-1 block">Tipo de Entrada</label>
                        <select name="entry_type"
                            class="w-full text-sm border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="anamnese">Anamnese</option>
                            <option value="exame">Exame Físico</option>
                            <option value="hipotese">Hipótese Diagnóstica</option>
                            <option value="conduta">Conduta</option>
                            <option value="adendo">Adendo / Correção</option>
                            <option value="evolucao">Evolução</option>
                        </select>
                    </div>
                </div>
                <textarea name="content" rows="4" required
                    class="w-full text-sm border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none mb-3"
                    placeholder="Descreva a entrada do prontuário..."></textarea>
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Salvar Entrada (Imutável)
                    </button>
                </div>
            </form>

            <!-- Existing entries -->
            <?php if (empty($records)): ?>
                <div class="py-8 text-center text-slate-400 text-sm">Nenhuma entrada no prontuário ainda.</div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($records as $r): ?>
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-750">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <span
                                        class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
                                        <?= htmlspecialchars($r['entry_type']) ?>
                                    </span>
                                </div>
                                <div class="text-right text-xs text-slate-400">
                                    <p>
                                        <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                                    </p>
                                    <p>por
                                        <?= htmlspecialchars($r['created_by_name'] ?? '—') ?>
                                    </p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap">
                                <?= htmlspecialchars($r['content']) ?>
                            </p>
                            <p class="mt-2 text-xs text-slate-300 dark:text-slate-600 font-mono truncate">SHA256:
                                <?= $r['content_hash'] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>