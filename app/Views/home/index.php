<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-amber-500 overflow-hidden">
    <div class="absolute inset-0 opacity-[0.12]">
        <div class="absolute top-8 left-8 w-72 h-72 bg-white rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-8 right-8 w-96 h-96 bg-amber-300 rounded-full blur-3xl animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-orange-300 rounded-full blur-3xl"></div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-slate-50 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/15 backdrop-blur-sm rounded-full text-orange-100 text-sm font-medium mb-6 animate-fade-in ring-1 ring-white/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Jelajahi kuliner terbaik di sekitar UDINUS Semarang
        </div>
        <h1 class="text-4xl sm:text-6xl font-extrabold text-white leading-[1.1] tracking-tight animate-slide-down">
            Temukan Kuliner<br class="hidden sm:block"> <span class="text-amber-200">Terfavorit</span> di Sekitar Kampus
        </h1>
        <p class="mt-5 text-orange-100/90 text-lg sm:text-xl max-w-2xl mx-auto animate-slide-down leading-relaxed">
            Dari angkringan legendaris hingga <em>hidden gem</em> — semua ada di sini dengan review terpercaya dari komunitas
        </p>

        <form method="GET" action="/places" class="mt-10 max-w-2xl mx-auto animate-slide-down">
            <div class="flex flex-col sm:flex-row gap-3 bg-white/90 backdrop-blur-md rounded-2xl p-1.5 shadow-lg shadow-orange-900/20 ring-1 ring-white/30">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari tempat kuliner..."
                        class="w-full pl-11 pr-4 py-3.5 text-slate-700 rounded-xl border-0 focus:ring-2 focus:ring-orange-500/40 focus:outline-none placeholder:text-slate-400 bg-transparent">
                </div>
                <select name="category" class="w-full sm:w-auto px-4 py-3.5 text-slate-700 rounded-xl border-0 focus:ring-2 focus:ring-orange-500/40 focus:outline-none bg-white/50 text-sm appearance-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($category ?? '') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-primary w-full sm:w-auto px-7 py-3.5 bg-gradient-to-r from-orange-600 to-amber-500 text-white font-semibold rounded-xl shadow-sm whitespace-nowrap min-h-[48px]">
                    Cari
                </button>
            </div>
        </form>

        <?php if (session()->get('isLoggedIn')): ?>
        <div class="mt-6 animate-fade-in">
            <a href="/places/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/15 text-white font-medium rounded-xl hover:bg-white/25 transition-all duration-200 ring-1 ring-white/20 hover:ring-white/40 backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Tempat Baru
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if (!empty($categories) && count($categories) > 0): ?>
    <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-2 scrollbar-hide">
        <a href="/" class="flex-shrink-0 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-sm hover:shadow-md hover:scale-105">Semua</a>
        <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
        <a href="/places?category=<?= $cat['id'] ?>" class="flex-shrink-0 px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 bg-white text-slate-600 hover:bg-orange-50 hover:text-orange-600 ring-1 ring-slate-900/5 hover:ring-orange-200 shadow-sm hover:shadow-md hover:scale-105">
            <?= esc($cat['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($places as $place): ?>
        <a href="/places/<?= $place['id'] ?>" class="card-hover group bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden animate-fade-in">
            <?php if (!empty($place['image']) && file_exists(WRITEPATH . 'uploads/' . $place['image'])): ?>
            <div class="relative overflow-hidden">
                <img src="/uploads/<?= esc($place['image']) ?>" alt="<?= esc($place['name']) ?>" class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <?php if (!empty($place['is_closed'])): ?>
                <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-bold rounded-lg bg-red-500/90 backdrop-blur-sm text-white shadow-lg">Tutup</span>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="relative bg-gradient-to-br from-orange-50 to-amber-50 h-52 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                <svg class="w-20 h-20 text-orange-300/60 group-hover:text-orange-400/80 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18v18H3zM12 8v4m0 0v4m0-4h4m-4 0H8"/></svg>
                <?php if (!empty($place['is_closed'])): ?>
                <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-bold rounded-lg bg-red-500/90 backdrop-blur-sm text-white shadow-lg">Tutup</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="p-5">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-50 to-amber-50 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-lg ring-1 ring-orange-200/50"><?= esc($place['category_name']) ?></span>
                    <?php if (!empty($place['avg_rating'])): ?>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg ring-1 ring-amber-200/50">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?= number_format($place['avg_rating'], 1) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <h3 class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors duration-200 text-lg leading-snug"><?= esc($place['name']) ?></h3>
                <p class="text-slate-500 text-sm mt-1.5 line-clamp-2 leading-relaxed"><?= esc($place['address']) ?></p>
                <div class="flex items-center gap-2 mt-3 text-slate-400 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span><?= esc($place['contributor_name']) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>

        <?php if (empty($places)): ?>
        <div class="col-span-full text-center py-20">
            <svg class="w-20 h-20 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <p class="text-xl font-semibold text-slate-400">Tidak ada tempat kuliner ditemukan</p>
            <p class="text-slate-400 mt-1">Coba kata kunci atau kategori lain</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
