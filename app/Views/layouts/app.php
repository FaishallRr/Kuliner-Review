<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' — ' : '' ?>KulinerReview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-12px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-slide-down { animation: slideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        .animate-shimmer { background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .nav-link { position: relative; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .nav-link::after { content: ''; position: absolute; bottom: -1px; left: 50%; width: 0; height: 2px; background: linear-gradient(90deg, #f97316, #f59e0b); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); transform: translateX(-50%); border-radius: 1px; }
        .nav-link:hover::after, .nav-link.active::after { width: 60%; }
        .card-hover { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.08); }
        .btn-primary { transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(249,115,22,0.35); }
        .btn-outline { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .btn-outline:hover { transform: translateY(-1px); border-color: #f97316; color: #f97316; background: rgba(249,115,22,0.04); }
        .flash-dismiss { animation: slideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        input, select, textarea { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        input:focus, select:focus, textarea:focus { transform: translateY(-1px); }
        .mobile-nav a { transition: all 0.2s ease; border-left: 3px solid transparent; }
        .mobile-nav a:hover, .mobile-nav a.active { border-left-color: #f97316; background: rgba(249,115,22,0.06); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white/70 backdrop-blur-xl border-b border-slate-200/60 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2.5 group">
                        <svg class="w-8 h-8 text-orange-500 group-hover:scale-110 transition-transform duration-300" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="15" fill="url(#logoGrad)" stroke="#f97316" stroke-width="0.5"/>
                            <path d="M12 10c0-2 2-4 4-4s4 2 4 4c0 1.5-.8 2.8-2 3.5v4.5a2 2 0 01-2 2h0a2 2 0 01-2-2v-4.5c-1.2-.7-2-2-2-3.5z" fill="white" stroke="#f97316" stroke-width="0.5"/>
                            <circle cx="16" cy="10.5" r="1.5" fill="#f97316" opacity="0.3"/>
                            <path d="M13 21h6l-1 4h-4l-1-4z" fill="#f97316" opacity="0.2"/>
                            <defs><linearGradient id="logoGrad" x1="0" y1="0" x2="32" y2="32"><stop offset="0%" stop-color="#f97316"/><stop offset="100%" stop-color="#d97706"/></linearGradient></defs>
                        </svg>
                        <span class="text-xl font-extrabold bg-gradient-to-r from-orange-600 via-orange-500 to-amber-500 bg-clip-text text-transparent tracking-tight">KulinerReview</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-0.5">
                    <a href="/" class="nav-link px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-orange-600 rounded-lg hover:bg-orange-50/50 transition-all duration-200">Beranda</a>
                    <a href="/places" class="nav-link px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-orange-600 rounded-lg hover:bg-orange-50/50 transition-all duration-200">Tempat Kuliner</a>
                    <?php if (session()->get('isLoggedIn')): ?>
                        <a href="/favorites" class="nav-link px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-orange-600 rounded-lg hover:bg-orange-50/50 transition-all duration-200">Favorit</a>
                        <a href="/notifications" class="nav-link px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-orange-600 rounded-lg hover:bg-orange-50/50 transition-all duration-200 relative">
                            Notifikasi
                            <?php if (session()->get('isLoggedIn')): ?>
                            <?php $notifModel = new \App\Models\NotificationModel(); $unreadCount = $notifModel->countUnread(session()->get('user_id')); ?>
                            <?php if ($unreadCount > 0): ?>
                            <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[20px] h-5 text-[11px] font-bold text-white bg-gradient-to-r from-red-500 to-rose-500 rounded-full px-1 shadow-lg animate-fade-in"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                            <?php endif; ?>
                            <?php endif; ?>
                        </a>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <a href="/admin" class="btn-primary ml-3 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg shadow-sm">Admin</a>
                        <?php endif; ?>
                        <div class="ml-3 flex items-center gap-2.5 pl-3.5 border-l border-slate-200">
                            <a href="/dashboard" class="group relative">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 via-orange-500 to-amber-500 flex items-center justify-center text-white text-sm font-bold shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-200">
                                    <?= strtoupper(substr(session()->get('full_name'), 0, 1)) ?>
                                </div>
                            </a>
                            <span class="text-sm font-medium text-slate-700"><?= esc(session()->get('full_name')) ?></span>
                            <a href="/logout" class="text-sm text-slate-400 hover:text-red-500 transition-colors duration-200 ml-0.5 hover:scale-105 inline-block">Keluar</a>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2 ml-3 pl-3.5 border-l border-slate-200">
                            <a href="/login" class="btn-outline px-4 py-2 text-sm font-medium text-slate-600 rounded-lg border border-slate-200 hover:border-orange-300 hover:text-orange-600 hover:bg-orange-50/30">Masuk</a>
                            <a href="/register" class="btn-primary px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg shadow-sm">Daftar</a>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden'); this.querySelector('svg').classList.toggle('hidden'); this.querySelector('svg + svg').classList.toggle('hidden')" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-lg px-4 pb-5 pt-2 space-y-0.5 mobile-nav">
            <a href="/" class="block py-2.5 pl-3 text-sm font-medium text-slate-600 rounded-r-lg">Beranda</a>
            <a href="/places" class="block py-2.5 pl-3 text-sm font-medium text-slate-600 rounded-r-lg">Tempat Kuliner</a>
            <?php if (session()->get('isLoggedIn')): ?>
                <a href="/favorites" class="block py-2.5 pl-3 text-sm font-medium text-slate-600 rounded-r-lg">Favorit</a>
                <a href="/notifications" class="block py-2.5 pl-3 text-sm font-medium text-slate-600 rounded-r-lg relative">
                    Notifikasi
                    <?php $notifModel = new \App\Models\NotificationModel(); $unreadCountMb = $notifModel->countUnread(session()->get('user_id')); ?>
                    <?php if ($unreadCountMb > 0): ?>
                    <span class="ml-2 inline-flex items-center justify-center min-w-[18px] h-4.5 text-[10px] font-bold text-white bg-gradient-to-r from-red-500 to-rose-500 rounded-full px-1"><?= $unreadCountMb > 9 ? '9+' : $unreadCountMb ?></span>
                    <?php endif; ?>
                </a>
                <?php if (session()->get('role') === 'admin'): ?>
                    <a href="/admin" class="block py-2.5 pl-3 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-r-lg mx-3">Admin Panel</a>
                <?php endif; ?>
                <a href="/dashboard" class="block py-2.5 pl-3 text-sm font-medium text-slate-600 rounded-r-lg">Dashboard</a>
                <div class="border-t border-slate-100 mt-3 pt-3 space-y-1">
                    <div class="flex items-center gap-2 px-3">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white text-xs font-bold"><?= strtoupper(substr(session()->get('full_name'), 0, 1)) ?></div>
                        <span class="text-sm text-slate-500"><?= esc(session()->get('full_name')) ?></span>
                    </div>
                    <a href="/logout" class="block py-2.5 pl-3 text-sm font-medium text-red-500 rounded-r-lg">Keluar</a>
                </div>
            <?php else: ?>
                <div class="border-t border-slate-100 mt-3 pt-3 flex gap-2 px-3">
                    <a href="/login" class="flex-1 text-center py-2.5 text-sm font-medium text-slate-600 rounded-lg border border-slate-200 hover:border-orange-300 transition-all">Masuk</a>
                    <a href="/register" class="flex-1 text-center py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg shadow-sm hover:shadow-md transition-all">Daftar</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <main class="flex-1">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="max-w-7xl mx-auto px-4 mt-4 animate-slide-down flash-dismiss" x-data="{ show: true }" x-show="show">
                <div class="bg-emerald-50/90 backdrop-blur-sm ring-1 ring-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-2xl flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-sm font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                    <button onclick="this.closest('[x-data]').remove()" class="w-7 h-7 rounded-full bg-emerald-100/50 hover:bg-emerald-200/50 flex items-center justify-center text-emerald-500 hover:text-emerald-700 transition-all shrink-0">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="max-w-7xl mx-auto px-4 mt-4 animate-slide-down flash-dismiss">
                <div class="bg-red-50/90 backdrop-blur-sm ring-1 ring-red-200/60 text-red-700 px-4 py-3.5 rounded-2xl flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-sm font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="w-7 h-7 rounded-full bg-red-100/50 hover:bg-red-200/50 flex items-center justify-center text-red-500 hover:text-red-700 transition-all shrink-0">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <footer class="bg-white border-t border-slate-200/80 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <svg class="w-6 h-6 text-orange-500" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="15" fill="url(#logoGradF)" stroke="#f97316" stroke-width="0.5"/>
                            <path d="M12 10c0-2 2-4 4-4s4 2 4 4c0 1.5-.8 2.8-2 3.5v4.5a2 2 0 01-2 2h0a2 2 0 01-2-2v-4.5c-1.2-.7-2-2-2-3.5z" fill="white" stroke="#f97316" stroke-width="0.5"/>
                            <defs><linearGradient id="logoGradF" x1="0" y1="0" x2="32" y2="32"><stop offset="0%" stop-color="#f97316"/><stop offset="100%" stop-color="#d97706"/></linearGradient></defs>
                        </svg>
                        <span class="text-lg font-bold bg-gradient-to-r from-orange-600 to-amber-500 bg-clip-text text-transparent">KulinerReview</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">Temukan dan bagikan pengalaman kuliner terbaik di Semarang. Review jujur dari pecinta kuliner sejati.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Navigasi</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-sm text-slate-500 hover:text-orange-600 transition-colors duration-200 hover:translate-x-0.5 inline-block">Beranda</a></li>
                        <li><a href="/places" class="text-sm text-slate-500 hover:text-orange-600 transition-colors duration-200 hover:translate-x-0.5 inline-block">Tempat Kuliner</a></li>
                        <?php if (session()->get('isLoggedIn')): ?>
                        <li><a href="/favorites" class="text-sm text-slate-500 hover:text-orange-600 transition-colors duration-200 hover:translate-x-0.5 inline-block">Favorit</a></li>
                        <li><a href="/dashboard" class="text-sm text-slate-500 hover:text-orange-600 transition-colors duration-200 hover:translate-x-0.5 inline-block">Dashboard</a></li>
                        <?php else: ?>
                        <li><a href="/login" class="text-sm text-slate-500 hover:text-orange-600 transition-colors duration-200 hover:translate-x-0.5 inline-block">Masuk</a></li>
                        <li><a href="/register" class="text-sm text-slate-500 hover:text-orange-600 transition-colors duration-200 hover:translate-x-0.5 inline-block">Daftar</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Kontak</h3>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>PWL UDINUS</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Semarang, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-100 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-xs text-slate-400">&copy; <?= date('Y') ?> KulinerReview — Pemrograman Web Lanjut UDINUS</p>
                <p class="text-xs text-slate-400">Dibuat dengan <span class="text-red-400">&hearts;</span> untuk pecinta kuliner</p>
            </div>
        </div>
    </footer>
</body>
</html>
