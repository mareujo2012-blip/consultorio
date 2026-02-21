<?php $pageTitle = 'Novo Atendimento'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/appointments"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Novo Atendimento</h1>
                <p class="text-slate-500 font-medium mt-1">
                    <?= $patient ? 'Protocolo para ' . htmlspecialchars($patient['name']) : 'Inicie um novo registro clínico na plataforma.' ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="premium-card rounded-[2.5rem] p-10">
        <form action="<?= $appUrl ?>/appointments" method="POST" class="space-y-10">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Patient Selection Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-lg font-display font-bold text-slate-800">Paciente Vinculado</h3>
                </div>

                <div class="relative">
                    <?php if ($patient): ?>
                        <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                        <div class="flex items-center gap-4 p-5 bg-blue-50/50 rounded-2xl border border-blue-100 shadow-sm">
                            <div
                                class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-blue-200">
                                <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-black text-slate-800 text-base leading-tight">
                                    <?= htmlspecialchars($patient['name']) ?></p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">CPF:
                                    <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? '') ?>
                                </p>
                            </div>
                            <a href="<?= $appUrl ?>/appointments/create"
                                class="ml-auto text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 px-4 py-2 bg-white rounded-xl shadow-sm border border-blue-50">Alterar</a>
                        </div>
                    <?php else: ?>
                        <div class="group relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" id="patient-search"
                                placeholder="Pesquise por nome ou documento do paciente..."
                                class="input-premium w-full pl-12 pr-6 py-4 rounded-2xl text-sm font-medium focus:outline-none transition-all"
                                autocomplete="off">
                            <input type="hidden" name="patient_id" id="patient_id_input">
                            <div id="patient-results"
                                class="absolute z-20 mt-2 w-full bg-white rounded-2xl border border-slate-200 shadow-2xl hidden max-h-72 overflow-y-auto custom-scrollbar">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="h-px bg-slate-100 rounded-full"></div>

            <!-- Schedule & Financial Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Timeline -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-lg font-display font-bold text-slate-800">Cronograma</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Data
                                da Consulta</label>
                            <input type="date" name="appointment_date" value="<?= date('Y-m-d') ?>" required
                                class="input-premium w-full rounded-xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Hora
                                de Início</label>
                            <input type="time" name="appointment_time" value="<?= date('H:i') ?>" required
                                class="input-premium w-full rounded-xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Financial -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                        <h3 class="text-lg font-display font-bold text-slate-800">Financeiro</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Honorários
                                (R$)</label>
                            <input type="text" name="value" placeholder="00,00"
                                class="input-premium w-full rounded-xl px-5 py-4 text-sm font-black text-emerald-600 focus:outline-none transition-all currency-input"
                                inputmode="numeric">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Forma
                                de Recebimento</label>
                            <select name="payment_method"
                                class="input-premium w-full rounded-xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                                <option value="">Não informado</option>
                                <option>PIX</option>
                                <option>Cartão de Crédito</option>
                                <option>Cartão de Débito</option>
                                <option>Dinheiro</option>
                                <option>Convênio / Plano</option>
                                <option>Transferência</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-px bg-slate-100 rounded-full"></div>

            <!-- Notes Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-slate-400 rounded-full"></div>
                    <h3 class="text-lg font-display font-bold text-slate-800">Notas de Gestão</h3>
                </div>
                <textarea name="admin_notes" rows="4"
                    placeholder="Observações internas, convênios ou particularidades do faturamento..."
                    class="input-premium w-full rounded-[2rem] px-6 py-5 text-sm font-medium focus:outline-none transition-all resize-none"></textarea>
            </div>

            <!-- CTA -->
            <div class="flex items-center justify-end gap-6 pt-4">
                <a href="<?= $appUrl ?>/appointments"
                    class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Descartar</a>
                <button type="submit"
                    class="btn-primary px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all">
                    Finalizar Atendimento
                </button>
            </div>
        </form>
    </div>
</div>

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
                    patientResults.innerHTML = '<div class="px-6 py-4 text-sm font-bold text-slate-400 italic">Nenhum paciente identificado</div>';
                } else {
                    data.forEach(p => {
                        const div = document.createElement('div');
                        div.className = 'px-6 py-4 flex items-center gap-4 cursor-pointer hover:bg-blue-50 transition-all border-b border-slate-50 last:border-0 group';
                        div.innerHTML = `
                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black group-hover:bg-blue-600 group-hover:text-white transition-all">${p.name.charAt(0)}</div>
                            <div>
                                <p class="text-sm font-black text-slate-800 group-hover:text-blue-600 transition-colors">${p.name}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1">CPF: ${p.cpf}</p>
                            </div>
                        `;
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