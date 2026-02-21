<?php $pageTitle = 'Novo Profissional'; ?>
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
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Nova Credencial</h1>
                <p class="text-slate-500 font-medium mt-1">Autorize o acesso de um novo médico ou atendente.</p>
            </div>
        </div>
    </div>

    <div class="premium-card rounded-[2.5rem] p-10">
        <form action="<?= $appUrl ?>/users" method="POST" class="space-y-10">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="md:col-span-2 lg:col-span-3 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nome
                        Completo *</label>
                    <input type="text" name="name" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">E-mail
                        (Login) *</label>
                    <input type="email" name="email" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Telefone
                        Fixo/Celular</label>
                    <input type="text" name="phone"
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all phone-input">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nível de
                        Acesso *</label>
                    <select name="role" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none transition-all">
                        <option value="secretary">Recepcionista / Secretária</option>
                        <option value="admin">Médico / Administrador</option>
                    </select>
                </div>
            </div>

            <div class="h-px bg-slate-100 rounded-full"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="space-y-2 lg:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Senha de
                        Acesso Temporária *</label>
                    <input type="password" name="password" required
                        class="input-premium w-full rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none transition-all">
                </div>
            </div>

            <div class="flex items-center justify-end gap-6 pt-4 border-t border-slate-100">
                <a href="<?= $appUrl ?>/users"
                    class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Descartar</a>
                <button type="submit"
                    class="btn-primary px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all">Criar
                    Acesso</button>
            </div>
        </form>
    </div>
</div>