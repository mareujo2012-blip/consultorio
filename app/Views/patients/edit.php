<?php $pageTitle = 'Editar Perfil: ' . htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Editar Paciente</h1>
                <p class="text-slate-500 font-medium mt-1">Atualize as informações cadastrais e de contato.</p>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="premium-card rounded-[2.5rem] p-10">
        <form action="<?= $appUrl ?>/patients/<?= $patient['id'] ?>" method="POST" enctype="multipart/form-data"
            class="space-y-12">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Identity Section -->
            <div class="space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-display font-bold text-slate-900">Identidade Atualizada</h3>
                </div>

                <div
                    class="flex flex-col md:flex-row items-center gap-10 bg-slate-50/50 p-8 rounded-[2rem] border border-slate-100">
                    <div class="relative group">
                        <div class="w-28 h-28 rounded-[2rem] bg-white border border-slate-100 shadow-inner overflow-hidden"
                            id="photo-preview-container">
                            <?php if (!empty($patient['photo'])): ?>
                                <img id="photo-preview" src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>"
                                    alt="" class="w-full h-full object-cover">
                            <?php else: ?>
                                <img id="photo-preview" src="" alt="" class="w-full h-full object-cover hidden">
                                <div id="photo-placeholder"
                                    class="w-full h-full flex items-center justify-center text-blue-600 font-display font-black text-3xl">
                                    <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <label for="photo"
                                class="absolute inset-0 bg-blue-600/80 opacity-0 group-hover:opacity-100 transition-all cursor-pointer flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest">Update</label>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                            class="hidden" onchange="window.initCropper(this, 'photo-preview', 'photo-placeholder')">
                    </div>
                </div>
            </div>
            <div class="h-px bg-slate-100 rounded-full"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="md:col-span-2 lg:col-span-3 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nome
                        Completo *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($patient['name']) ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Documento
                        CPF *</label>
                    <input type="text" name="cpf" required
                        value="<?= htmlspecialchars(preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? '')) ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all cpf-input">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Data de
                        Nascimento</label>
                    <input type="date" name="birth_date" value="<?= htmlspecialchars($patient['birth_date'] ?? '') ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Gênero</label>
                    <select name="sex"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                        <option value="">Selecione</option>
                        <option value="M" <?= ($patient['sex'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= ($patient['sex'] ?? '') === 'F' ? 'selected' : '' ?>>Feminino</option>
                        <option value="O" <?= ($patient['sex'] ?? '') === 'O' ? 'selected' : '' ?>>Outro</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Fone
                        Celular</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($patient['phone'] ?? '') ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all phone-input">
                </div>
                <div class="lg:col-span-2 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">E-mail de
                        Contato</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($patient['email'] ?? '') ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                </div>
            </div>
    </div>
    <div class="h-px bg-slate-100 rounded-full"></div>
    <div class="space-y-8">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
            <h3 class="text-xl font-display font-bold text-slate-900">Endereço e Prontuário</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="md:col-span-2 lg:col-span-3 space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Endereço de
                    Residência</label>
                <input type="text" name="address" value="<?= htmlspecialchars($patient['address'] ?? '') ?>"
                    class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Cidade</label>
                <input type="text" name="city" value="<?= htmlspecialchars($patient['city'] ?? '') ?>"
                    class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">UF</label>
                <input type="text" name="state" value="<?= htmlspecialchars($patient['state'] ?? '') ?>" maxlength="2"
                    class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all uppercase">
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">CEP</label>
                <input type="text" name="zip" value="<?= htmlspecialchars($patient['zip'] ?? '') ?>"
                    class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
            </div>
        </div>
        <div class="space-y-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Notas Clínicas /
                Alergias</label>
            <textarea name="notes" rows="4"
                class="input-premium w-full rounded-[2rem] px-6 py-5 text-sm font-medium focus:outline-none transition-all resize-none"><?= htmlspecialchars($patient['notes'] ?? '') ?></textarea>
        </div>
    </div>
    <div class="flex items-center justify-end gap-6 pt-4">
        <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>"
            class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Descartar</a>
        <button type="submit"
            class="btn-primary px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all">Confirmar
            Alterações</button>
    </div>
    </form>
</div>
</div>