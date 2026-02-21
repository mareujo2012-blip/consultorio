<!-- Login Card -->
<div class="premium-card rounded-[2.5rem] p-10 shadow-xl shadow-blue-900/5">
    <!-- Header -->
    <div class="text-center mb-10">
        <div
            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 shadow-lg shadow-blue-500/30 mb-6 transition-transform hover:scale-105 duration-300">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight mb-2">ControleConsultório</h1>
        <p class="text-slate-500 font-medium text-sm">Gestão Médica Simplificada e Atraente</p>
    </div>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium"><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <form action="/login" method="POST" class="space-y-6" id="login-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <!-- Email Field -->
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700 ml-1">E-mail Profissional</label>
            <input type="email" name="email" id="email" required autocomplete="email"
                class="input-premium w-full text-slate-900 placeholder-slate-400 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all"
                placeholder="nome@exemplo.com">
        </div>

        <!-- Password Field -->
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700 ml-1">Senha de Acesso</label>
            <div class="relative group">
                <input type="password" name="password" id="password" required
                    class="input-premium w-full text-slate-900 placeholder-slate-400 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all pr-12"
                    placeholder="••••••••">
                <button type="button" onclick="togglePass()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors">
                    <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Captcha -->
        <div class="bg-blue-50/50 rounded-2xl p-5 border border-blue-100/50">
            <label class="block text-xs font-bold text-blue-600 uppercase tracking-widest mb-3 text-center">
                Verificação Humana: <span class="text-blue-800"><?= htmlspecialchars($captchaQuestion ?? '') ?></span>
            </label>
            <input type="number" name="captcha" id="captcha" required
                class="w-full bg-white border border-blue-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-center text-xl font-bold"
                placeholder="?">
        </div>

        <button type="submit" id="submit-btn"
            class="btn-primary w-full font-bold py-4 rounded-2xl text-base transition-all active:scale-[0.98]">
            Acessar Sistema
        </button>
    </form>
</div>

<div class="mt-8 text-center">
    <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest">
        © <?= date('Y') ?> ControleConsultório <br>
        <span class="text-blue-500/40 italic capitalize tracking-normal font-medium">Ambiente Médico Seguro</span>
    </p>
</div>

<script>
    function togglePass() {
        const p = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (p.type === 'password') {
            p.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.047m4.528-5.283A13.945 13.945 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-4.225-4.225L3 3m11.225 11.225A3 3 0 0015 12V11a3 3 0 10-3 3h1z" />';
        } else {
            p.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
</script>