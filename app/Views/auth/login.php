<?php $pageTitle = 'Login'; ?>
<div class="w-full max-w-md">
    <!-- Card -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-8">
        <!-- Logo/Icon -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 shadow-lg shadow-blue-500/30 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">ControleConsultório</h1>
            <p class="text-blue-200 text-sm mt-1">Sistema de Gestão Médica</p>
        </div>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-4 bg-red-500/20 border border-red-400/30 text-red-200 px-4 py-3 rounded-lg text-sm">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-5" id="login-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-blue-100 mb-1.5">E-mail</label>
                <input type="email" name="email" id="email" required autocomplete="email"
                    class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300/60 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all"
                    placeholder="seu@email.com">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-blue-100 mb-1.5">Senha</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300/60 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10 transition-all"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePass()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white">
                        <svg id="eye-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Captcha -->
            <div>
                <label class="block text-sm font-medium text-blue-100 mb-1.5">Verificação:
                    <?= htmlspecialchars($captchaQuestion ?? '') ?>
                </label>
                <input type="number" name="captcha" id="captcha" required
                    class="w-full bg-white/10 border border-white/20 text-white placeholder-blue-300/60 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
                    placeholder="Digite o resultado">
            </div>

            <button type="submit" id="submit-btn"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-lg transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-400/40 text-sm">
                Entrar no Sistema
            </button>
        </form>
    </div>

    <p class="text-center text-blue-300/50 text-xs mt-6">
        ©
        <?= date('Y') ?> ControleConsultório — Sistema seguro com HTTPS
    </p>
</div>

<script>
    function togglePass() {
        const p = document.getElementById('password');
        p.type = p.type === 'password' ? 'text' : 'password';
    }
    document.getElementById('login-form').addEventListener('submit', function () {
        document.getElementById('submit-btn').textContent = 'Autenticando...';
        document.getElementById('submit-btn').disabled = true;
    });
</script>