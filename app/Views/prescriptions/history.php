<?php $pageTitle = 'Histórico: ' . htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex items-center gap-4">
                <?php if (!empty($patient['photo'])): ?>
                    <img src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>" alt=""
                        class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white shadow-md">
                <?php else: ?>
                    <div
                        class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-black border border-blue-100 shadow-sm">
                        <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Crono-Prescrições
                    </h1>
                    <p class="text-slate-500 font-medium mt-1">Acervo de <?= count($prescriptions) ?> documentos
                        vinculados ao paciente.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table Container -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h3 class="text-xl font-display font-bold text-slate-900">Histórico Oficial</h3>
            </div>
        </div>

        <?php if (empty($prescriptions)): ?>
            <div class="py-24 text-center">
                <div
                    class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-slate-400 font-bold">Nenhuma prescrição registrada até o momento.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                            <th class="px-8 py-5">Cronologia</th>
                            <th class="px-8 py-5">Sumário da Prescrição</th>
                            <th class="px-8 py-5 text-right">Acesso</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($prescriptions as $p): ?>
                            <tr class="group hover:bg-slate-50/30 transition-all">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-slate-900 leading-none mb-1"><?= date('d/m/Y', strtotime($p['appointment_date'])) ?></span>
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= date('H:i', strtotime($p['appointment_date'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-sm font-medium text-slate-600 italic line-clamp-1">
                                        "<?= htmlspecialchars(substr($p['content'], 0, 100)) ?>..."
                                    </p>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="<?= $appUrl ?>/prescriptions/<?= $p['id'] ?>/pdf" target="_blank"
                                        class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-red-600 hover:text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                        Visualizar PDF
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Documentos protegidos por sigilo
                    médico</p>
            </div>
        <?php endif; ?>
    </div>
</div>