<?php $pageTitle = 'Novo Paciente'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-3xl mx-auto space-y-5">
    <div class="flex items-center gap-3">
        <a href="<?= $appUrl ?>/patients" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Novo Paciente</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <form action="<?= $appUrl ?>/patients" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Photo upload -->
            <div class="flex items-center gap-6">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-700 overflow-hidden"
                        id="photo-preview-container">
                        <img id="photo-preview" src="" alt="" class="w-full h-full object-cover hidden">
                        <div id="photo-placeholder"
                            class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <label for="photo"
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center text-white text-xs font-medium rounded-2xl">
                            Trocar foto
                        </label>
                    </div>
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" class="hidden"
                        onchange="previewPhoto(this)">
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Foto do Paciente</p>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG ou WebP — máx 5MB</p>
                    <label for="photo"
                        class="mt-2 inline-flex items-center gap-1.5 text-xs text-primary-600 hover:text-primary-700 cursor-pointer font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Selecionar imagem
                    </label>
                </div>
            </div>

            <hr class="border-slate-200 dark:border-slate-700">

            <!-- Personal fields -->
            <div>
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Dados Pessoais</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Nome Completo *</label>
                        <input type="text" name="name" required placeholder="Nome do paciente" class="form-input"
                            maxlength="200">
                    </div>
                    <div>
                        <label class="form-label">CPF *</label>
                        <input type="text" name="cpf" required placeholder="000.000.000-00" class="form-input cpf-input"
                            maxlength="14">
                    </div>
                    <div>
                        <label class="form-label">Data de Nascimento</label>
                        <input type="date" name="birth_date" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Sexo</label>
                        <select name="sex" class="form-input">
                            <option value="">Selecione</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                            <option value="O">Outro</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Telefone / WhatsApp</label>
                        <input type="text" name="phone" placeholder="(00) 00000-0000" class="form-input phone-input"
                            maxlength="15">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" placeholder="paciente@email.com" class="form-input">
                    </div>
                </div>
            </div>

            <hr class="border-slate-200 dark:border-slate-700">

            <!-- Address -->
            <div>
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Endereço</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Logradouro</label>
                        <input type="text" name="address" placeholder="Rua, número, complemento" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Cidade</label>
                        <input type="text" name="city" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Estado</label>
                        <input type="text" name="state" maxlength="2" placeholder="SP" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">CEP</label>
                        <input type="text" name="zip" placeholder="00000-000" class="form-input" maxlength="9">
                    </div>
                </div>
            </div>

            <hr class="border-slate-200 dark:border-slate-700">

            <div>
                <label class="form-label">Observações</label>
                <textarea name="notes" rows="3" class="form-input resize-none"
                    placeholder="Alergias, histórico importante..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="<?= $appUrl ?>/patients"
                    class="px-5 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-slate-800 transition-colors">Cancelar</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    Cadastrar Paciente
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
            reader.onload = (e) => {
                const preview = document.getElementById('photo-preview');
                const placeholder = document.getElementById('photo-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>