<?php $pageTitle = 'Paciente: ' . htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="<?= $appUrl ?>/patients"
                class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex items-center gap-4">
                <?php if (!empty($patient['photo'])): ?>
                    <img src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>" alt=""
                        class="w-20 h-20 rounded-[2rem] object-cover ring-4 ring-white shadow-xl">
                <?php else: ?>
                    <div
                        class="w-20 h-20 rounded-[2rem] bg-blue-50 text-blue-600 flex items-center justify-center text-3xl font-black border border-blue-100 shadow-xl">
                        <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight leading-tight">
                        <?= htmlspecialchars($patient['name']) ?></h1>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Reg:
                            <?= str_pad($patient['id'], 5, '0', STR_PAD_LEFT) ?></span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Início:
                            <?= date('d/m/Y', strtotime($patient['created_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= $appUrl ?>/appointments/create?patient_id=<?= $patient['id'] ?>"
                class="btn-primary px-6 py-3.5 rounded-2xl text-xs font-bold shadow-xl active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Novo Atendimento
            </a>
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>/records"
                class="bg-slate-900 text-white px-6 py-3.5 rounded-2xl text-xs font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 active:scale-95 transition-all">Prontuário
                Digital</a>
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>/edit"
                class="bg-white border border-slate-200 px-6 py-3.5 rounded-2xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">Editar
                Perfil</a>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar: Info Section -->
        <div class="lg:col-span-4 space-y-8">
            <div class="premium-card rounded-[2.5rem] p-8 space-y-8">
                <div class="space-y-6">
                    <p
                        class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-50 pb-4">
                        Dados de Identificação</p>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">CPF /
                                Documento</p>
                            <p class="text-sm font-black text-slate-700 font-mono">
                                <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? 'Não informado') ?>
                            </p>
                        </div>

                        <?php if (!empty($patient['birth_date'])): ?>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Data de
                                    Nascimento</p>
                                <p class="text-sm font-black text-slate-700">
                                    <?= date('d/m/Y', strtotime($patient['birth_date'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Gênero</p>
                            <p class="text-sm font-black text-slate-700">
                                <?= match ($patient['sex'] ?? '') { 'M' => 'Masculino', 'F' => 'Feminino', 'O' => 'Outro', default => 'Não especificado'} ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <p
                        class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-50 pb-4">
                        Canais de Contato</p>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">E-mail
                                Institucional</p>
                            <p class="text-sm font-black text-blue-600 truncate">
                                <?= htmlspecialchars($patient['email'] ?: 'Não cadastrado') ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Telefone /
                                WhatsApp</p>
                            <p class="text-sm font-black text-slate-700">
                                <?= htmlspecialchars($patient['phone'] ?: 'Não informado') ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <p
                        class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-50 pb-4">
                        Localização</p>
                    <p class="text-sm font-medium text-slate-600 leading-relaxed">
                        <?= htmlspecialchars(implode(', ', array_filter([$patient['address'], $patient['city'], $patient['state']]))) ?: 'Endereço não preenchido' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content: History -->
        <div class="lg:col-span-8 space-y-8">
            <div class="premium-card rounded-[2.5rem] p-10">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-2xl font-display font-black text-slate-900 tracking-tight">Cronograma de
                            Atendimentos</h3>
                    </div>
                    <span
                        class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest">Histórico
                        Ativo</span>
                </div>

                <?php
                $appointmentModel = new \App\Models\Appointment();
                $appts = $appointmentModel->listByPatient((int) $patient['id']);
                ?>

                <?php if (empty($appts)): ?>
                    <div class="py-20 text-center bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100">
                        <p class="text-slate-400 font-bold">Nenhum registro de atendimento encontrado para este paciente.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($appts as $apt): ?>
                            <a href="<?= $appUrl ?>/appointments/<?= $apt['id'] ?>"
                                class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-white hover:border-blue-200 hover:shadow-xl hover:shadow-blue-600/5 transition-all group">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900">
                                            <?= date('d/m/Y • H:i', strtotime($apt['appointment_date'])) ?></p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                            <?= htmlspecialchars($apt['payment_method'] ?: 'Método não informado') ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <span
                                        class="text-lg font-display font-black text-slate-900 group-hover:text-emerald-600 transition-colors">R$
                                        <?= number_format((float) $apt['value'], 2, ',', '.') ?></span>
                                    <div
                                        class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-blue-600 group-hover:border-blue-100 transition-all">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Notes/Observations -->
            <?php if (!empty($patient['notes'])): ?>
                <div class="premium-card rounded-[2.5rem] p-10 bg-blue-600 text-white shadow-2xl shadow-blue-600/20">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] mb-4 opacity-70">Observações Médicas
                        Restritas</p>
                    <div class="text-base font-medium leading-relaxed italic">
                        "<?= htmlspecialchars($patient['notes']) ?>"
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>