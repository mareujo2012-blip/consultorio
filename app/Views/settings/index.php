<?php $pageTitle = 'Enterprise Settings'; ?>

<div class="space-y-8 animate-fade-in max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Enterprise Settings</h1>
            <p class="text-slate-500 font-medium mt-1">Configure as diretrizes e credenciais da sua operação médica.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.location.reload()"
                class="bg-white border border-slate-200 px-5 py-2.5 rounded-2xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Resetar
            </button>
        </div>
    </div>

    <!-- Main Settings Container -->
    <div class="premium-card rounded-[2.5rem] p-10 space-y-12">

        <!-- Section: Perfil do Profissional -->
        <div class="space-y-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-xl font-display font-bold text-slate-900">Perfil do Profissional</h2>
            </div>

            <form action="<?= $appUrl ?>/settings/user" method="POST"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nome
                        Completo</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                        placeholder="Dr. Nome Exemplo">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">E-mail de
                        Acesso</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                        placeholder="email@exemplo.com">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Telefone
                        Principal</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all phone-input"
                        placeholder="(00) 00000-0000">
                </div>

                <div class="md:col-span-2 lg:col-span-3 flex justify-end">
                    <button type="submit"
                        class="btn-primary px-8 py-3.5 rounded-2xl text-xs font-bold shadow-xl active:scale-95 transition-all">Atualizar
                        Perfil</button>
                </div>
            </form>
        </div>

        <div class="h-px bg-slate-100 rounded-full"></div>

        <!-- Section: Segurança -->
        <div class="space-y-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-xl font-display font-bold text-slate-900">Segurança da Conta</h2>
            </div>

            <form action="<?= $appUrl ?>/settings/password" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Senha
                        Atual</label>
                    <input type="password" name="current_password" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm focus:outline-none transition-all"
                        placeholder="••••••••">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nova
                        Senha</label>
                    <input type="password" name="new_password" required minlength="8"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm focus:outline-none transition-all"
                        placeholder="Minimo 8 caracteres">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Confirmar
                        Senha</label>
                    <input type="password" name="confirm_password" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm focus:outline-none transition-all"
                        placeholder="Repita a nova senha">
                </div>

                <div class="md:col-span-3 flex justify-end">
                    <button type="submit"
                        class="bg-slate-900 text-white px-8 py-3.5 rounded-2xl text-xs font-bold hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-200">Trocar
                        Credenciais</button>
                </div>
            </form>
        </div>

        <div class="h-px bg-slate-100 rounded-full"></div>

        <!-- Section: Identidade da Clínica -->
        <div class="space-y-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-xl font-display font-bold text-slate-900">Identidade da Clínica</h2>
            </div>

            <form action="<?= $appUrl ?>/settings/clinic" method="POST" enctype="multipart/form-data"
                class="space-y-10">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <!-- Logo Picker -->
                <div class="flex items-center gap-8 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                    <div
                        class="relative group w-24 h-24 rounded-[1.8rem] overflow-hidden bg-white flex items-center justify-center shadow-inner border border-slate-100">
                        <?php if (!empty($clinic['logo'])): ?>
                            <img src="<?= $appUrl . '/' . htmlspecialchars($clinic['logo']) ?>" alt="Logo"
                                class="w-full h-full object-contain p-2" id="logo-preview">
                        <?php else: ?>
                            <img id="logo-preview" src="" class="w-full h-full object-contain p-2 hidden">
                            <span id="logo-placeholder"
                                class="text-slate-400 text-[10px] font-bold uppercase tracking-tighter text-center">No
                                Logo</span>
                        <?php endif; ?>
                        <label for="logo"
                            class="absolute inset-0 bg-blue-600/80 opacity-0 group-hover:opacity-100 transition-all cursor-pointer flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest">Update</label>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-800">Logotipo Institucional</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Visível em documentos e receitas
                            (PNG/JPG/SVG)</p>
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden"
                            onchange="previewLogo(this)">
                        <label for="logo"
                            class="mt-4 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Subir Arquivo
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Razão
                            Social / Nome Fantasia</label>
                        <input type="text" name="clinic_name" value="<?= htmlspecialchars($clinic['name'] ?? '') ?>"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">CNPJ</label>
                        <input type="text" name="cnpj" value="<?= htmlspecialchars($clinic['cnpj'] ?? '') ?>"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                            placeholder="00.000.000/0000-00">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Telefone
                            Comercial</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($clinic['phone'] ?? '') ?>"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all phone-input">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Cidade</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($clinic['city'] ?? '') ?>"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Estado
                            (UF)</label>
                        <input type="text" name="state" value="<?= htmlspecialchars($clinic['state'] ?? '') ?>"
                            maxlength="2"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all uppercase">
                    </div>
                    <div class="md:col-span-2 lg:col-span-3 space-y-2">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Endereço
                            Geográfico</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($clinic['address'] ?? '') ?>"
                            class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all"
                            placeholder="Rua, Número, Bairro">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="btn-primary px-10 py-4 rounded-2xl text-xs font-bold transition-all shadow-xl active:scale-95">Salvar
                        Configurações Globais</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const p = document.getElementById('logo-preview');
                p.src = e.target.result;
                p.classList.remove('hidden');
                const ph = document.getElementById('logo-placeholder');
                if (ph) ph.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>