<?php $pageTitle = 'Configurações'; ?>
<?php $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/'); ?>

<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Configurações</h1>

    <!-- User settings -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-5">Dados do Médico</h2>
        <form action="<?= $appUrl ?>/settings/user" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required
                        class="form-input">
                </div>
                <div>
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                        class="form-input">
                </div>
                <div>
                    <label class="form-label">Telefone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        class="form-input phone-input">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    Salvar Dados
                </button>
            </div>
        </form>
    </div>

    <!-- Password change -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-5">Alterar Senha</h2>
        <form action="<?= $appUrl ?>/settings/password" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Senha Atual</label>
                    <input type="password" name="current_password" required class="form-input" placeholder="••••••••">
                </div>
                <div>
                    <label class="form-label">Nova Senha</label>
                    <input type="password" name="new_password" required minlength="8" class="form-input"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="form-label">Confirmar Senha</label>
                    <input type="password" name="confirm_password" required class="form-input" placeholder="••••••••">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    Alterar Senha
                </button>
            </div>
        </form>
    </div>

    <!-- Clinic settings -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-base font-semibold text-slate-700 dark:text-slate-200 mb-5">Dados da Clínica</h2>
        <form action="<?= $appUrl ?>/settings/clinic" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Logo upload -->
            <div class="flex items-center gap-6">
                <div
                    class="relative group w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                    <?php if (!empty($clinic['logo'])): ?>
                        <img src="<?= $appUrl . '/' . htmlspecialchars($clinic['logo']) ?>" alt="Logo"
                            class="w-full h-full object-contain p-1" id="logo-preview">
                    <?php else: ?>
                        <img id="logo-preview" src="" class="w-full h-full object-contain p-1 hidden">
                        <span id="logo-placeholder" class="text-slate-400 text-xs text-center px-2">Sem logo</span>
                    <?php endif; ?>
                    <label for="logo"
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center text-white text-xs font-medium rounded-2xl">Trocar</label>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Logotipo da Clínica</p>
                    <p class="text-xs text-slate-400 mt-1">PNG, JPG, SVG · máx 2MB</p>
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden"
                        onchange="previewLogo(this)">
                    <label for="logo"
                        class="mt-2 inline-flex items-center gap-1.5 text-xs text-primary-600 hover:text-primary-700 cursor-pointer font-medium">
                        Selecionar logo
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Nome da Clínica</label>
                    <input type="text" name="clinic_name" value="<?= htmlspecialchars($clinic['name'] ?? '') ?>"
                        class="form-input">
                </div>
                <div>
                    <label class="form-label">CNPJ</label>
                    <input type="text" name="cnpj" value="<?= htmlspecialchars($clinic['cnpj'] ?? '') ?>"
                        class="form-input">
                </div>
                <div>
                    <label class="form-label">Telefone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($clinic['phone'] ?? '') ?>"
                        class="form-input phone-input">
                </div>
                <div>
                    <label class="form-label">Cidade</label>
                    <input type="text" name="city" value="<?= htmlspecialchars($clinic['city'] ?? '') ?>"
                        class="form-input">
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <input type="text" name="state" value="<?= htmlspecialchars($clinic['state'] ?? '') ?>"
                        maxlength="2" class="form-input">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Endereço Completo</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($clinic['address'] ?? '') ?>"
                        class="form-input">
                </div>
                <div>
                    <label class="form-label">Website</label>
                    <input type="url" name="website" value="<?= htmlspecialchars($clinic['website'] ?? '') ?>"
                        placeholder="https://..." class="form-input">
                </div>
                <div>
                    <label class="form-label">Instagram</label>
                    <input type="text" name="instagram" value="<?= htmlspecialchars($clinic['instagram'] ?? '') ?>"
                        placeholder="@clinica" class="form-input">
                </div>
                <div>
                    <label class="form-label">Facebook</label>
                    <input type="text" name="facebook" value="<?= htmlspecialchars($clinic['facebook'] ?? '') ?>"
                        class="form-input">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl text-sm shadow-md transition-all">
                    Salvar Dados da Clínica
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
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const p = document.getElementById('logo-preview');
                p.src = e.target.result;
                p.classList.remove('hidden');
                const ph = document.getElementById('logo-placeholder');
                if (ph) ph.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>