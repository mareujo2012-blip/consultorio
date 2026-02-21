<?php $pageTitle = 'Novo Cadastro de Paciente'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/patients"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Novo Cadastro</h1>
                <p class="text-slate-500 font-medium mt-1">Registre um novo perfil de paciente na plataforma.</p>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="premium-card rounded-[2.5rem] p-10">
        <form action="<?= $appUrl ?>/patients" method="POST" enctype="multipart/form-data" class="space-y-12">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Identity Section -->
            <div class="space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-display font-bold text-slate-900">Identidade e Perfil</h3>
                </div>

                <div
                    class="flex flex-col md:flex-row items-center gap-10 bg-slate-50/50 p-8 rounded-[2rem] border border-slate-100">
                    <div class="relative group">
                        <div class="w-28 h-28 rounded-[2rem] bg-white border border-slate-100 shadow-inner overflow-hidden"
                            id="photo-preview-container">
                            <img id="photo-preview" src="" alt="" class="w-full h-full object-cover hidden">
                            <div id="photo-placeholder"
                                class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <label for="photo"
                                class="absolute inset-0 bg-blue-600/80 opacity-0 group-hover:opacity-100 transition-all cursor-pointer flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest">Update</label>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                            class="hidden" onchange="window.initCropper(this, 'photo-preview', 'photo-placeholder')">
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-800 leading-tight">Biometria Facial</p>
                        <p class="text-xs text-slate-400 font-medium mt-1">Anexe uma imagem para identificação rápida.
                        </p>
                        <label for="photo"
                            class="mt-4 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Explorar Arquivo
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="md:col-span-2 lg:col-span-3 space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nome
                            Completo do Paciente *</label>
                        <input type="text" name="name" required
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all"
                            placeholder="Ex: Maria Oliveira da Silva">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Cadastro
                            de Pessoa Física (CPF) *</label>
                        <input type="text" name="cpf" required
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all cpf-input"
                            placeholder="000.000.000-00">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nascimento</label>
                        <input type="date" name="birth_date"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Gênero
                            Biológico</label>
                        <select name="sex"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                            <option value="">Não especificado</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                            <option value="O">Outro</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">WhatsApp /
                            Telefone</label>
                        <input type="text" name="phone"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all phone-input"
                            placeholder="(00) 00000-0000">
                    </div>
                    <div class="lg:col-span-2 space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">E-mail
                            para Notificações</label>
                        <input type="email" name="email"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                            placeholder="paciente@dominio.com">
                    </div>
                </div>
            </div>

            <div class="h-px bg-slate-100 rounded-full"></div>

            <!-- Localization Section -->
            <div class="space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xl font-display font-bold text-slate-900">Localização e Notas</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="md:col-span-2 lg:col-span-3 space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Logradouro
                            / Endereço</label>
                        <input type="text" name="address"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                            placeholder="Rua, Número, Bairro...">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Cidade</label>
                        <input type="text" name="city"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">UF
                            (Estado)</label>
                        <input type="text" name="state" maxlength="2"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all uppercase"
                            placeholder="SP">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">CEP</label>
                        <input type="text" name="zip"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                            placeholder="00000-000">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Notas
                        Clínicas Iniciais</label>
                    <textarea name="notes" rows="4"
                        class="input-premium w-full rounded-[2rem] px-6 py-5 text-sm font-medium focus:outline-none transition-all resize-none"
                        placeholder="Alergias, condições crônicas ou observações importantes de triagem..."></textarea>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex items-center justify-end gap-6 pt-4">
                <a href="<?= $appUrl ?>/patients"
                    class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Descartar</a>
                <button type="submit"
                    class="btn-primary px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all">
                    Efetivar Cadastro
                </button>
            </div>
        </form>
    </div>
</div>