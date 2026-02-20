<?php $pageTitle = htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/patients" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <!-- Patient avatar -->
            <?php if (!empty($patient['photo'])): ?>
                <img src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>" alt=""
                    class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-700 shadow-md">
            <?php else: ?>
                <div
                    class="w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 font-bold text-2xl shadow-md">
                    <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                    <?= htmlspecialchars($patient['name']) ?>
                </h1>
                <p class="text-sm text-slate-400">
                    CPF:
                    <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? '') ?>
                    <?php if (!empty($patient['birth_date'])): ?>
                        ·
                        <?= date('d/m/Y', strtotime($patient['birth_date'])) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="<?= $appUrl ?>/appointments/create?patient_id=<?= $patient['id'] ?>"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Atendimento
            </a>
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>/records"
                class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Prontuário
            </a>
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>/edit"
                class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </a>
        </div>
    </div>

    <!-- Info grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Patient details card -->
        <div
            class="md:col-span-1 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 space-y-4">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Informações</h3>

            <?php $fields = [
                'E-mail' => $patient['email'],
                'Telefone' => $patient['phone'] ? '(' . substr($patient['phone'], 0, 2) . ') ' . substr($patient['phone'], 2, 5) . '-' . substr($patient['phone'], 7) : null,
                'Sexo' => match ($patient['sex'] ?? '') { 'M' => 'Masculino', 'F' => 'Feminino', 'O' => 'Outro', default => null},
                'Endereço' => implode(', ', array_filter([$patient['address'], $patient['city'], $patient['state'], $patient['zip']])),
                'Notas' => $patient['notes'],
            ]; ?>

            <?php foreach ($fields as $label => $value): ?>
                <?php if (!empty($value)): ?>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">
                            <?= $label ?>
                        </p>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-0.5">
                            <?= htmlspecialchars($value) ?>
                        </p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div>
                <p class="text-xs text-slate-400 font-medium">Cadastrado em</p>
                <p class="text-sm text-slate-700 dark:text-slate-300 mt-0.5">
                    <?= !empty($patient['created_at']) ? date('d/m/Y', strtotime($patient['created_at'])) : '—' ?>
                </p>
            </div>
        </div>

        <!-- Recent appointments -->
        <div
            class="md:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Atendimentos</h3>
                <a href="<?= $appUrl ?>/appointments/create?patient_id=<?= $patient['id'] ?>"
                    class="text-xs text-primary-600 hover:underline">+ novo</a>
            </div>

            <?php
            $appointmentModel = new \App\Models\Appointment();
            $appts = $appointmentModel->listByPatient((int) $patient['id']);
            ?>

            <?php if (empty($appts)): ?>
                <div class="py-12 text-center text-slate-400 text-sm">Nenhum atendimento registrado.</div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($appts as $apt): ?>
                        <div
                            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                    <?= date('d/m/Y H:i', strtotime($apt['appointment_date'])) ?>
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    <?= htmlspecialchars($apt['payment_method'] ?: '') ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-semibold text-emerald-600 text-sm">R$
                                    <?= number_format((float) $apt['value'], 2, ',', '.') ?>
                                </span>
                                <a href="<?= $appUrl ?>/appointments/<?= $apt['id'] ?>"
                                    class="text-xs text-primary-600 hover:underline">Ver →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>