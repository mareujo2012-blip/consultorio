<?php $pageTitle = 'Editar Paciente'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-3xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>"
            class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Editar Paciente</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <form action="<?= $appUrl ?>/patients/<?= $patient['id'] ?>" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Photo -->
            <div class="flex items-center gap-6">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-100">
                        <?php if (!empty($patient['photo'])): ?>
                            <img id="photo-preview" src="<?= $appUrl . '/' . htmlspecialchars($patient['photo']) ?>" alt=""
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            <img id="photo-preview" src="" alt="" class="w-full h-full object-cover hidden">
                            <div id="photo-placeholder"
                                class="w-full h-full flex items-center justify-center text-slate-400 text-3xl font-bold">
                                <?= strtoupper(substr($patient['name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <label for="photo"
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center text-white text-xs font-medium rounded-2xl">
                            Trocar foto
                        </label>
                    </div>
                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden"
                        onchange="previewPhoto(this)">
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Foto do Paciente</p>
                    <label for="photo"
                        class="mt-2 inline-flex items-center gap-1.5 text-xs text-primary-600 hover:text-primary-700 cursor-pointer font-medium">
                        Alterar imagem
                    </label>
                </div>
            </div>

            <hr class="border-slate-200 dark:border-slate-700">

            <div>
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Dados Pessoais</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Nome Completo *</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($patient['name']) ?>"
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">CPF *</label>
                        <input type="text" name="cpf" required
                            value="<?= htmlspecialchars(preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $patient['cpf'] ?? '')) ?>"
                            class="form-input cpf-input">
                    </div>
                    <div>
                        <label class="form-label">Data de Nascimento</label>
                        <input type="date" name="birth_date"
                            value="<?= htmlspecialchars($patient['birth_date'] ?? '') ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Sexo</label>
                        <select name="sex" class="form-input">
                            <option value="">Selecione</option>
                            <option value="M" <?= ($patient['sex'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
                            <option value="F" <?= ($patient['sex'] ?? '') === 'F' ? 'selected' : '' ?>>Feminino</option>
                            <option value="O" <?= ($patient['sex'] ?? '') === 'O' ? 'selected' : '' ?>>Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Telefone / WhatsApp</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($patient['phone'] ?? '') ?>"
                            class="form-input phone-input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($patient['email'] ?? '') ?>"
                            class="form-input">
                    </div>
                </div>
            </div>

            <hr class="border-slate-200 dark:border-slate-700">

            <div>
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Endereço</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Logradouro</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($patient['address'] ?? '') ?>"
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Cidade</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($patient['city'] ?? '') ?>"
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Estado</label>
                        <input type="text" name="state" value="<?= htmlspecialchars($patient['state'] ?? '') ?>"
                            maxlength="2" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">CEP</label>
                        <input type="text" name="zip" value="<?= htmlspecialchars($patient['zip'] ?? '') ?>"
                            class="form-input">
                    </div>
                </div>
            </div>

            <div>
                <label class="form-label">Observações</label>
                <textarea name="notes" rows="3"
                    class="form-input resize-none"><?= htmlspecialchars($patient['notes'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="<?= $appUrl ?>/patients/<?= $patient['id'] ?>"
                    class="px-5 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-800">Cancelar</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label {
        @apply block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1.5;
    }

    .form-input {
        @apply w-full border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all;
    }
</style>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const p = document.getElementById('photo-preview');
                p.src = e.target.result;
                p.classList.remove('hidden');
                const ph = document.getElementById('photo-placeholder');
                if (ph) ph.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>