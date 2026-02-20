<?php $pageTitle = 'Prontuário — ' . htmlspecialchars($patient['name']); ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Prontuário</h1>
                <p class="text-sm text-slate-500">
                    <?= htmlspecialchars($patient['name']) ?> ·
                    <?= count($entries) ?> entrada(s)
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>/records/pdf" target="_blank"
                class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Exportar PDF
            </a>
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Patient card -->
    <div
        class="bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-800 rounded-2xl p-4 flex items-center gap-4">
        <?php if (!empty($patient['photo'])): ?>
            <img src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>"
                class="w-14 h-14 rounded-xl object-cover ring-2 ring-white">
        <?php else: ?>
            <div
                class="w-14 h-14 rounded-xl bg-primary-200 dark:bg-primary-900/60 flex items-center justify-center font-bold text-primary-700 text-xl">
                <?= strtoupper(substr($patient['name'], 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div>
            <p class="font-bold text-slate-800 dark:text-slate-100 text-lg">
                <?= htmlspecialchars($patient['name']) ?>
            </p>
            <p class="text-sm text-slate-500">
                CPF:
                <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? '') ?>
                <?php if (!empty($patient['birth_date'])): ?>
                    · Nascimento:
                    <?= date('d/m/Y', strtotime($patient['birth_date'])) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Timeline -->
    <?php if (empty($entries)): ?>
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center text-slate-400 shadow-sm border border-slate-200 dark:border-slate-700">
            Nenhuma entrada no prontuário ainda.
        </div>
    <?php else: ?>
        <div class="relative">
            <!-- Vertical line -->
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-700 ml-0.5"></div>

            <div class="space-y-6 pl-16">
                <?php foreach ($entries as $entry): ?>
                    <div class="relative">
                        <!-- Dot -->
                        <div
                            class="absolute -left-10 mt-1 w-3 h-3 rounded-full bg-primary-500 ring-4 ring-white dark:ring-slate-900">
                        </div>

                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 uppercase tracking-wide">
                                        <?= htmlspecialchars($entry['entry_type']) ?>
                                    </span>
                                    <?php if (isset($entry['appointment_value'])): ?>
                                        <span class="text-xs text-emerald-600 font-medium">R$
                                            <?= number_format((float) $entry['appointment_value'], 2, ',', '.') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right text-xs text-slate-400 flex-shrink-0">
                                    <p class="font-medium">
                                        <?= date('d/m/Y H:i', strtotime($entry['created_at'])) ?>
                                    </p>
                                    <p>Dr(a).
                                        <?= htmlspecialchars($entry['created_by_name'] ?? '—') ?>
                                    </p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
                                <?= htmlspecialchars($entry['content']) ?>
                            </p>
                            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <p class="text-xs text-slate-300 dark:text-slate-600 font-mono">
                                    <?= $entry['content_hash'] ?>
                                </p>
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
        .no-print {
            display: none !important;
        }

        body {
            background: white;
        }

        main {
            padding: 0 !important;
        }
    }
</style>