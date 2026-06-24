<?= $this->extend('layouts/clean') ?>
<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50/80 via-white to-amber-50/80 py-12 px-4 relative overflow-hidden">
    <!-- Decorative circles -->
    <div class="absolute top-20 left-10 w-64 h-64 bg-orange-200/20 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-20 right-10 w-80 h-80 bg-amber-200/20 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>

    <div class="max-w-md w-full relative animate-slide-up">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5 group">
                <svg class="w-10 h-10 text-orange-500 group-hover:scale-110 transition-transform duration-300" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="15" fill="url(#logoGrad)" stroke="#f97316" stroke-width="0.5"/>
                    <path d="M12 10c0-2 2-4 4-4s4 2 4 4c0 1.5-.8 2.8-2 3.5v4.5a2 2 0 01-2 2h0a2 2 0 01-2-2v-4.5c-1.2-.7-2-2-2-3.5z" fill="white" stroke="#f97316" stroke-width="0.5"/>
                    <path d="M13 21h6l-1 4h-4l-1-4z" fill="#f97316" opacity="0.2"/>
                    <defs><linearGradient id="logoGrad" x1="0" y1="0" x2="32" y2="32"><stop offset="0%" stop-color="#f97316"/><stop offset="100%" stop-color="#d97706"/></linearGradient></defs>
                </svg>
                <span class="text-2xl font-extrabold bg-gradient-to-r from-orange-600 via-orange-500 to-amber-500 bg-clip-text text-transparent tracking-tight">KulinerReview</span>
            </a>
            <p class="text-slate-500 mt-2">Masuk ke akun Anda</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="animate-fade-in mb-4 bg-red-50/90 backdrop-blur-sm ring-1 ring-red-200/60 text-red-700 px-4 py-3.5 rounded-2xl flex items-center gap-2.5 shadow-sm">
                <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-sm font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="animate-fade-in mb-4 bg-emerald-50/90 backdrop-blur-sm ring-1 ring-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-2xl flex items-center gap-2.5 shadow-sm">
                <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-sm font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-8 card-hover">
            <form action="/login" method="POST">
                <?= csrf_field() ?>
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="<?= old('email') ?>" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400"
                        placeholder="email@contoh.com">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400"
                        placeholder="Minimal 6 karakter">
                </div>
                <button type="submit" class="btn-primary w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white py-2.5 px-4 rounded-xl font-semibold text-sm shadow-sm">
                    Masuk
                </button>
            </form>
            <p class="mt-5 text-center text-sm text-slate-500">
                Belum punya akun? <a href="/register" class="font-semibold text-orange-600 hover:text-orange-700 transition-colors">Daftar sekarang</a>
            </p>
            <div class="mt-5 p-4 bg-slate-50/80 rounded-xl ring-1 ring-slate-900/5">
                <p class="text-xs font-semibold text-slate-500 mb-2.5">Akun Demo:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5 text-xs text-slate-500">
                    <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-orange-400 shrink-0"></span><span class="font-medium text-slate-700">Admin:</span> admin@udinus.ac.id</div>
                    <div><span class="font-medium text-slate-700">Pass:</span> admin123</div>
                    <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span><span class="font-medium text-slate-700">Kontributor:</span> budi@student.udinus.ac.id</div>
                    <div><span class="font-medium text-slate-700">Pass:</span> contrib123</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
