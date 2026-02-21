<?php $pageTitle = 'Hub de Pacientes'; ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Hub de Pacientes</h1>
            <p class="text-slate-500 font-medium mt-1">Gerenciamento inteligente de
                <?= number_format($pagination['total']) ?> registros</p>
        </div>
        <a href="<?= $appUrl ?>/patients/create"
            class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold transition-all shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Novo Cadastro
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="premium-card rounded-[2rem] p-4">
        <form method="GET" action="<?= $appUrl ?>/patients" class="flex gap-4">
            <div class="flex-1 relative group">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                    placeholder="Buscar por nome, CPF ou identificador único..."
                    class="input-premium w-full pl-12 pr-6 py-4 rounded-[1.5rem] text-sm focus:outline-none transition-all">
            </div>
            <button type="submit"
                class="bg-slate-900 text-white px-8 py-4 rounded-[1.5rem] text-xs font-bold hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-200">
                Filtrar Resultados
            </button>
            <?php if ($search): ?>
                <a href="<?= $appUrl ?>/patients"
                    class="px-4 py-4 text-xs font-bold text-red-500 hover:bg-red-50 rounded-xl transition-all flex items-center">Limpar
                    Filtros</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Results Table -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                    <th class="px-8 py-6">Perfil do Paciente</th>
                    <th class="px-8 py-6 hidden md:table-cell">Identificação / CPF</th>
                    <th class="px-8 py-6 hidden lg:table-cell">Contato Direto</th>
                    <th class="px-8 py-6 text-right">Ações Rápidas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($patients as $p): ?>
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-default">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <?php if (!empty($p['photo'])): ?>
                                    <img src="<?= $appUrl . '/' . htmlspecialchars($p['photo']) ?>" alt=""
                                        class="w-12 h-12 rounded-2xl object-cover ring-4 ring-slate-100 group-hover:ring-blue-100 transition-all shadow-sm">
                                <?php else: ?>
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 font-display font-black text-lg border border-blue-100 transition-all group-hover:bg-blue-600 group-hover:text-white group-hover:scale-105">
                                        <?= strtoupper(substr($p['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p
                                        class="text-sm font-extrabold text-slate-800 transition-colors group-hover:text-blue-600">
                                        <?= htmlspecialchars($p['name']) ?></p>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                        <?= htmlspecialchars($p['email'] ?: 'E-mail não informado') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 hidden md:table-cell">
                            <span
                                class="text-xs font-mono text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $p['cpf'] ?? '---.---.--- --') ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 hidden lg:table-cell">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <?= htmlspecialchars($p['phone'] ?: 'Sem telefone') ?>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= $appUrl ?>/patients/<?= $p['id'] ?>"
                                    class="p-2.5 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all"
                                    title="Ver Prontuário">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="<?= $appUrl ?>/appointments/create?patient_id=<?= $p['id'] ?>"
                                    class="flex items-center gap-2 bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-[10px] font-bold uppercase transition-all hover:bg-emerald-600 hover:text-white shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Atendimento
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Footer / Pagination -->
        <?php if ($pagination['last_page'] > 1): ?>
            <div class="bg-slate-50/50 px-8 py-6 flex items-center justify-between border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Página <?= $pagination['current_page'] ?> de <?= $pagination['last_page'] ?>
                    <span class="mx-2 opacity-30">|</span>
                    Total: <?= number_format($pagination['total']) ?>
                </p>
                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"
                            class="w-10 h-10 flex items-center justify-center rounded-xl text-xs font-bold transition-all <?= $i === $pagination['current_page'] ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-white text-slate-500 hover:bg-slate-100 border border-slate-200' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>