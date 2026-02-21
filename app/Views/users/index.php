<?php $pageTitle = 'Profissionais'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Profissionais e Atendentes
            </h1>
            <p class="text-slate-500 font-medium mt-1">Gerencie os acessos ao sistema e os perfis médicos.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= $appUrl ?>/users/create"
                class="btn-primary px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Novo Profissional
            </a>
        </div>
    </div>

    <!-- Users List Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($users as $user): ?>
            <div
                class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-600/5 transition-all group relative overflow-hidden flex flex-col items-center text-center">
                <?php if (!$user['active']): ?>
                    <div
                        class="absolute top-0 right-0 left-0 bg-red-100 text-red-600 text-[10px] font-black uppercase tracking-widest text-center py-1">
                        Inativo</div>
                <?php endif; ?>

                <div
                    class="w-24 h-24 rounded-[2rem] bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center font-display font-black text-3xl mb-4 mt-2 overflow-hidden shadow-inner">
                    <?php if (!empty($user['photo'])): ?>
                        <img src="<?= $appUrl . '/' . htmlspecialchars($user['photo']) ?>" alt=""
                            class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <h3 class="text-lg font-display font-bold text-slate-900 leading-tight mb-1">
                    <?= htmlspecialchars($user['name']) ?>
                </h3>
                <p class="text-xs font-medium text-slate-500 mb-4">
                    <?= htmlspecialchars($user['email']) ?>
                </p>

                <div class="flex items-center gap-2 mb-6">
                    <span
                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?= $user['role'] === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' ?>">
                        <?= $user['role'] === 'admin' ? 'Médico Admin' : 'Secretaria' ?>
                    </span>
                </div>

                <div class="mt-auto w-full pt-4 border-t border-slate-50 flex gap-2">
                    <a href="<?= $appUrl ?>/users/<?= $user['id'] ?>/edit"
                        class="flex-1 py-3 text-xs font-bold text-slate-500 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors uppercase tracking-widest">
                        Editar Perfil
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>