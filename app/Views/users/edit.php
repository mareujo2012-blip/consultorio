<?php $pageTitle = 'Editar Profissional'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/users"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Editar Credencial</h1>
                <p class="text-slate-500 font-medium mt-1">Atualize os dados e privilégios deste usuário.</p>
            </div>
        </div>
    </div>

    <div class="premium-card rounded-[2.5rem] p-10">
        <form action="<?= $appUrl ?>/users/<?= $user['id'] ?>" method="POST" enctype="multipart/form-data"
            class="space-y-10">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="flex items-center gap-3 border-b border-slate-100 pb-8">
                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                <h3 class="text-xl font-display font-bold text-slate-900">Fotografia 1000x1000 Premium</h3>
            </div>

            <div class="relative group w-28 h-28 mx-auto md:mx-0">
                <div class="w-28 h-28 rounded-[2rem] bg-slate-50 border border-slate-200 shadow-inner overflow-hidden"
                    id="photo-preview-container">
                    <?php if (!empty($user['photo'])): ?>
                        <img id="photo-preview" src="<?= $appUrl . '/' . htmlspecialchars($user['photo']) ?>" alt=""
                            class="w-full h-full object-cover">
                    <?php else: ?>
                        <img id="photo-preview" src="" alt="" class="w-full h-full object-cover hidden">
                        <div id="photo-placeholder"
                            class="w-full h-full flex items-center justify-center text-blue-600 font-display font-black text-4xl">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <label for="photo"
                        class="absolute inset-0 bg-blue-600/80 opacity-0 group-hover:opacity-100 transition-all cursor-pointer flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest">Update</label>
                </div>
                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" class="hidden"
                    onchange="window.initCropper(this, 'photo-preview', 'photo-placeholder')">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="md:col-span-2 lg:col-span-3 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nome
                        Completo *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">E-mail
                        (Login) *</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Telefone
                        Fixo/Celular</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all phone-input">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nível de
                        Acesso *</label>
                    <select name="role" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                        <option value="secretary" <?= $user['role'] === 'secretary' ? 'selected' : '' ?>>Recepcionista /
                            Secretária</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Médico / Administrador
                        </option>
                    </select>
                </div>
            </div>

            <div class="h-px bg-slate-100 rounded-full my-8"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-center">
                <div class="space-y-2 lg:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alterar
                        Senha de Acesso</label>
                    <input type="password" name="password" placeholder="(Deixe em branco para não alterar)"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                </div>
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="active" class="sr-only peer" value="1" <?= $user['active'] ? 'checked' : '' ?>>
                        <div
                            class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600">
                        </div>
                        <span class="ml-3 text-sm font-bold text-slate-700">Conta Ativa</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-6 pt-4 border-t border-slate-100 mt-8">
                <a href="<?= $appUrl ?>/users"
                    class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Descartar</a>
                <button type="submit"
                    class="btn-primary px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all">Confirmar
                    Alterações</button>
            </div>
        </form>
    </div>
</div>