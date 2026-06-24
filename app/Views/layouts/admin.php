<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' — ' : '' ?>Admin — KulinerReview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-12px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .animate-slide-down { animation: slideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .sidebar-nav a { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1rem; font-size: 0.875rem; color: #64748b; border-radius: 0.625rem; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); text-decoration: none; }
        .sidebar-nav a:hover { background: #fff7ed; color: #ea580c; }
        .sidebar-nav a.active { background: #fff7ed; color: #ea580c; font-weight: 600; }
        .sidebar-nav a svg { width: 20px; height: 20px; flex-shrink: 0; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.3); z-index: 40; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { display: block; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
            .sidebar.open { transform: translateX(0); }
        }
        .stat-card { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.06); }
        .badge-pill { transition: all 0.2s ease; }
        table th { position: sticky; top: 0; z-index: 10; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <aside id="sidebar" class="sidebar fixed top-0 left-0 w-64 h-full bg-white border-r border-slate-200/80 z-50 shadow-lg flex flex-col lg:translate-x-0">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <a href="/admin" class="flex items-center gap-2.5 no-underline">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-sm shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.914a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <span class="text-base font-bold text-slate-800 block leading-tight">KulinerReview</span>
                    <span class="text-[11px] text-slate-400 font-medium">Admin Panel</span>
                </div>
            </a>
            <button onclick="closeSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 py-3 overflow-y-auto sidebar-nav">
            <div class="px-4 mb-1">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block px-1 mb-1">Menu Utama</span>
            </div>
            <div class="px-3 space-y-0.5">
                <a href="/admin">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Dashboard
                </a>
            </div>

            <div class="px-4 mt-4 mb-1">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block px-1 mb-1">Kelola Data</span>
            </div>
            <div class="px-3 space-y-0.5">
                <a href="/admin/places">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Tempat Kuliner
                </a>
                <a href="/admin/places/create">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Tempat
                </a>
                <?php
                    $pendingModel = new \App\Models\PlaceModel();
                    $pendingCount = $pendingModel->where('status', 'pending')->countAllResults();
                ?>
                <a href="/admin/places/pending">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Moderasi
                    <?php if ($pendingCount > 0): ?>
                    <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 text-[11px] font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-full px-1.5 shadow-sm"><?= $pendingCount > 9 ? '9+' : $pendingCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="/admin/categories">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L12.159 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    Kategori
                </a>
                <a href="/admin/tags">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L12.159 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    Tag
                </a>
                <a href="/admin/reviews">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
                    Ulasan
                </a>
                <a href="/admin/users">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106a12.318 12.318 0 01-2.625.372c-1.465 0-2.855-.238-4.125-.674m0 0a9.38 9.38 0 01-2.625-.372M8.25 19.031a9.38 9.38 0 002.625.372M8.25 19.031V18.5a4.125 4.125 0 017.533-2.493M8.25 19.031a9.38 9.38 0 01-4.125-.674M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    Pengguna
                </a>
            </div>

            <div class="px-4 mt-4 mb-1">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block px-1 mb-1">Lainnya</span>
            </div>
            <div class="px-3">
                <a href="/places">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    Lihat Situs
                </a>
            </div>
        </nav>
        <div class="p-4 border-t border-slate-100">
            <a href="/logout" class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-red-500 rounded-lg hover:bg-red-50 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                Keluar
            </a>
        </div>
    </aside>

    <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="lg:ml-64 min-h-screen">
        <header class="lg:hidden sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-4 py-3 flex items-center justify-between">
            <button onclick="openSidebar()" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="/admin" class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.914a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-sm font-bold text-slate-800">Admin</span>
            </a>
            <div class="w-10"></div>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mx-4 sm:mx-6 mt-4 animate-slide-down">
                <div class="bg-emerald-50/90 backdrop-blur-sm ring-1 ring-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-2xl flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-sm font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="w-7 h-7 rounded-full bg-emerald-100/50 hover:bg-emerald-200/50 flex items-center justify-center text-emerald-500 hover:text-emerald-700 transition-all shrink-0">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mx-4 sm:mx-6 mt-4 animate-slide-down">
                <div class="bg-red-50/90 backdrop-blur-sm ring-1 ring-red-200/60 text-red-700 px-4 py-3.5 rounded-2xl flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
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
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        document.querySelectorAll('#sidebar .sidebar-nav a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) closeSidebar();
            });
            if (link.href === window.location.href) link.classList.add('active');
        });
    </script>
</body>
</html>
