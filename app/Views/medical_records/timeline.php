<?php $pageTitle = 'Prontuário: ' . htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-8 animate-fade-in no-print">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>"
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Prontuário Evolutivo</h1>
                <p class="text-slate-500 font-medium mt-1"><?= count($entries) ?> registros cronológicos certificados.
                </p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>/records/pdf" target="_blank"
                class="bg-rose-600 text-white px-6 py-3 rounded-2xl text-xs font-bold shadow-lg shadow-rose-200 hover:bg-rose-700 active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Exportar LAUDO PDF
            </a>
            <button onclick="window.print()"
                class="bg-white border border-slate-200 text-slate-600 px-6 py-3 rounded-2xl text-xs font-bold shadow-sm hover:bg-slate-50 active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Patient Highlight Card -->
    <div
        class="premium-card rounded-[2rem] p-6 border-l-4 border-l-blue-600 flex flex-col md:flex-row items-center gap-6">
        <?php if (!empty($patient['photo'])): ?>
            <img src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>"
                class="w-20 h-20 rounded-[2rem] object-cover ring-4 ring-slate-50 shadow-md">
        <?php else: ?>
            <div
                class="w-20 h-20 rounded-[2rem] bg-blue-50 text-blue-600 flex items-center justify-center text-3xl font-black border border-blue-100 shadow-sm">
                <?= strtoupper(substr($patient['name'], 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div class="text-center md:text-left flex-1">
            <h2 class="text-2xl font-display font-black text-slate-900 leading-tight">
                <?= htmlspecialchars($patient['name']) ?></h2>
            <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mt-2">
                <span
                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-lg">CPF:
                    <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? 'N/I') ?></span>
                <?php if (!empty($patient['birth_date'])): ?>
                    <span
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-lg">Nasc:
                        <?= date('d/m/Y', strtotime($patient['birth_date'])) ?></span>
                <?php endif; ?>
                <span
                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg">Status:
                    Acompanhamento Ativo</span>
            </div>
        </div>
    </div>

    <!-- Timeline Execution -->
    <?php if (empty($entries)): ?>
        <div
            class="premium-card rounded-[3rem] p-24 text-center border-2 border-dashed border-slate-100 bg-transparent shadow-none">
            <p class="text-slate-300 font-bold text-lg">Inicie o histórico clínico deste paciente através de um novo
                atendimento.</p>
        </div>
    <?php else: ?>
        <div class="relative pb-12">
            <!-- Central Line -->
            <div
                class="absolute left-6 md:left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-600 via-blue-200 to-transparent md:-translate-x-1/2 opacity-20 rounded-full">
            </div>

            <div class="space-y-12 relative">
                <?php foreach ($entries as $index => $entry): ?>
                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-0">
                        <!-- Left Side (Standard Desktop) -->
                        <div
                            class="w-full md:w-1/2 <?= $index % 2 === 0 ? 'md:pr-16 md:text-right' : 'md:order-2 md:pl-16 md:text-left' ?>">
                            <div class="hidden md:block">
                                <span
                                    class="text-xs font-black text-slate-900"><?= date('d/m/Y', strtotime($entry['created_at'])) ?></span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <?= date('H:i', strtotime($entry['created_at'])) ?></p>
                            </div>
                        </div>

                        <!-- Central Dot -->
                        <div
                            class="absolute left-6 md:left-1/2 w-4 h-4 rounded-full bg-blue-600 ring-4 ring-white shadow-lg md:-translate-x-1/2 z-10">
                        </div>

                        <!-- Right Side / Content Card -->
                        <div class="w-full md:w-1/2 <?= $index % 2 === 0 ? 'md:order-2 md:pl-16' : 'md:pr-16' ?>">
                            <div
                                class="premium-card rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 hover:shadow-blue-600/5 transition-all border border-slate-100 group">
                                <div class="flex items-center justify-between mb-6">
                                    <span
                                        class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        <?= htmlspecialchars($entry['entry_type']) ?>
                                    </span>
                                    <div class="md:hidden text-right">
                                        <span
                                            class="text-[10px] font-black text-slate-900"><?= date('d/m/Y', strtotime($entry['created_at'])) ?></span>
                                    </div>
                                </div>
                                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap font-medium">
                                    <?= htmlspecialchars($entry['content']) ?>
                                </div>
                                <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[8px] font-black text-slate-400">
                                            MD</div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Assinado:
                                            Dr(a). <?= htmlspecialchars($entry['created_by_name'] ?? 'Clínica') ?></span>
                                    </div>
                                    <div class="flex items-center gap-1 opacity-20 hover:opacity-100 transition-opacity cursor-help"
                                        title="Blockchain Certificate: <?= $entry['content_hash'] ?>">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span
                                            class="text-[8px] font-mono font-bold text-slate-500 uppercase"><?= substr($entry['content_hash'], 0, 12) ?>...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    @media print {

        aside,
        header,
        nav,
        .no-print {
            display: none !important;
        }

        main {
            padding: 0 !important;
            width: 100% !important;
            margin: 0 !important;
        }

        .premium-card {
            border: 1px solid #eee !important;
            box-shadow: none !important;
            break-inside: avoid;
        }

        body {
            background: white !important;
        }
    }
</style>