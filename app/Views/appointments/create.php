<?php $pageTitle = 'Novo Atendimento'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-2xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="<?= $appUrl ?>/appointments" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
            <?= $patient ? 'Novo Atendimento — ' . htmlspecialchars($patient['name']) : 'Novo Atendimento' ?>
        </h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <form action="<?= $appUrl ?>/appointments" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Patient search -->
            <div>
                <label class="form-label">Paciente *</label>
                <?php if ($patient): ?>
                    <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                    <div
                        class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-600">
                        <div
                            class="w-9 h-9 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center font-bold text-primary-600 text-sm">
                            <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800 dark:text-slate-200 text-sm">
                                <?= htmlspecialchars($patient['name']) ?>
                            </p>
                            <p class="text-xs text-slate-400">CPF:
                                <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? '') ?>
                            </p>
                        </div>
                        <a href="<?= $appUrl ?>/appointments/create"
                            class="ml-auto text-xs text-slate-400 hover:text-slate-600">Trocar →</a>
                    </div>
                <?php else: ?>
                    <div class="relative">
                        <input type="text" id="patient-search" placeholder="Digite o nome do paciente..." class="form-input"
                            autocomplete="off">
                        <input type="hidden" name="patient_id" id="patient_id_input">
                        <div id="patient-results"
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600 shadow-lg hidden max-h-60 overflow-y-auto">
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Data *</label>
                    <input type="date" name="appointment_date" value="<?= date('Y-m-d') ?>" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Hora *</label>
                    <input type="time" name="appointment_time" value="<?= date('H:i') ?>" required class="form-input">
                </div>
            </div>

            <!-- Value & Payment -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Valor da Consulta (R$)</label>
                    <input type="text" name="value" placeholder="0,00" class="form-input currency-input"
                        inputmode="numeric">
                </div>
                <div>
                    <label class="form-label">Forma de Pagamento</label>
                    <select name="payment_method" class="form-input">
                        <option value="">Selecione</option>
                        <option>Dinheiro</option>
                        <option>Cartão de Crédito</option>
                        <option>Cartão de Débito</option>
                        <option>PIX</option>
                        <option>Plano de Saúde</option>
                        <option>Transferência</option>
                    </select>
                </div>
            </div>

            <!-- Admin notes -->
            <div>
                <label class="form-label">Observações Administrativas</label>
                <textarea name="admin_notes" rows="3" placeholder="Notas internas sobre o atendimento..."
                    class="form-input resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="<?= $appUrl ?>/appointments"
                    class="px-5 py-2.5 text-sm text-slate-600 hover:text-slate-800">Cancelar</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    Registrar Atendimento
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label {
        @apply block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1.5;
    }

    .form-input {
        @apply w-full border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all;
    }
</style>

<script>
    const patientSearch = document.getElementById('patient-search');
    const patientResults = document.getElementById('patient-results');
    const patientIdInput = document.getElementById('patient_id_input');
    let debounce;

    if (patientSearch) {
        patientSearch.addEventListener('input', function () {
            clearTimeout(debounce);
            const q = this.value.trim();
            if (q.length < 2) { patientResults.classList.add('hidden'); return; }
            debounce = setTimeout(async () => {
                const res = await fetch(`<?= $appUrl ?>/api/patients/search?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                patientResults.innerHTML = '';
                if (data.length === 0) {
                    patientResults.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400">Nenhum resultado</div>';
                } else {
                    data.forEach(p => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2.5 text-sm cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-b border-slate-100 dark:border-slate-700 last:border-0';
                        div.innerHTML = `<span class="font-medium">${p.name}</span> <span class="text-slate-400 text-xs">· CPF: ${p.cpf}</span>`;
                        div.addEventListener('click', () => {
                            patientIdInput.value = p.id;
                            patientSearch.value = p.name;
                            patientResults.classList.add('hidden');
                        });
                        patientResults.appendChild(div);
                    });
                }
                patientResults.classList.remove('hidden');
            }, 300);
        });
        document.addEventListener('click', e => {
            if (!patientSearch.contains(e.target)) patientResults.classList.add('hidden');
        });
    }
</script>