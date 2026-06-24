<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tempat Kuliner</h1>
                <p class="text-sm text-slate-500">Temukan tempat makan terbaik di sekitar kampus</p>
            </div>
        </div>
        <?php if (session()->get('isLoggedIn')): ?>
        <a href="/places/create" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tempat
        </a>
        <?php endif; ?>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5 mb-8 animate-fade-in">
        <form method="GET" action="/places">
            <div class="flex flex-col sm:flex-row gap-3 mb-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari nama, alamat, atau kategori..."
                        class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400">
                </div>
                <button type="submit" class="btn-primary w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm text-sm whitespace-nowrap min-h-[44px]">
                    Cari
                </button>
                <a href="/places" class="btn-outline w-full sm:w-auto px-4 py-2.5 bg-slate-50 text-slate-600 font-medium rounded-xl ring-1 ring-slate-200 hover:ring-orange-200 hover:text-orange-600 hover:bg-orange-50/30 transition-all text-sm text-center min-h-[44px] flex items-center justify-center">Reset</a>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                <select name="category" class="w-full sm:w-auto sm:flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? '') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="tag" class="w-full sm:w-auto sm:flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                    <option value="">Semua Tag</option>
                    <?php foreach ($tags as $tag): ?>
                    <option value="<?= $tag['id'] ?>" <?= ($selectedTag ?? '') == $tag['id'] ? 'selected' : '' ?>><?= esc($tag['name']) ?> (<?= $tag['place_count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <select name="min_rating" class="w-full sm:w-auto sm:flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                    <option value="">Semua Rating</option>
                    <option value="4" <?= ($selectedRating ?? '') == '4' ? 'selected' : '' ?>>4+ Bintang</option>
                    <option value="3" <?= ($selectedRating ?? '') == '3' ? 'selected' : '' ?>>3+ Bintang</option>
                    <option value="2" <?= ($selectedRating ?? '') == '2' ? 'selected' : '' ?>>2+ Bintang</option>
                    <option value="1" <?= ($selectedRating ?? '') == '1' ? 'selected' : '' ?>>1+ Bintang</option>
                </select>
            </div>
        </form>
    </div>

    <?php if (!empty($keyword) || !empty($selectedCategory) || !empty($selectedTag) || !empty($selectedRating)): ?>
    <div class="mb-6 flex flex-wrap items-center gap-2 text-sm text-slate-500">
        <span class="text-xs font-medium text-slate-400">Filter aktif:</span>
        <?php if (!empty($keyword)): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium ring-1 ring-orange-200/50">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            "<?= esc($keyword) ?>"
            <a href="/places?<?= http_build_query(array_filter(['category' => $selectedCategory, 'tag' => $selectedTag, 'min_rating' => $selectedRating])) ?>" class="hover:text-orange-900 hover:scale-110 transition-all">&times;</a>
        </span>
        <?php endif; ?>
        <?php if (!empty($selectedCategory)): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-700 rounded-lg text-xs font-medium ring-1 ring-sky-200/50">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            <?= esc($categories[array_search($selectedCategory, array_column($categories, 'id'))]['name'] ?? '') ?>
            <a href="/places?<?= http_build_query(array_filter(['q' => $keyword, 'tag' => $selectedTag, 'min_rating' => $selectedRating])) ?>" class="hover:text-sky-900 hover:scale-110 transition-all">&times;</a>
        </span>
        <?php endif; ?>
        <?php if (!empty($selectedTag)): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium ring-1 ring-emerald-200/50">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            <?= esc($tags[array_search($selectedTag, array_column($tags, 'id'))]['name'] ?? '') ?>
            <a href="/places?<?= http_build_query(array_filter(['q' => $keyword, 'category' => $selectedCategory, 'min_rating' => $selectedRating])) ?>" class="hover:text-emerald-900 hover:scale-110 transition-all">&times;</a>
        </span>
        <?php endif; ?>
        <?php if (!empty($selectedRating)): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg text-xs font-medium ring-1 ring-purple-200/50">
            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <?= $selectedRating ?>+ Bintang
            <a href="/places?<?= http_build_query(array_filter(['q' => $keyword, 'category' => $selectedCategory, 'tag' => $selectedTag])) ?>" class="hover:text-purple-900 hover:scale-110 transition-all">&times;</a>
        </span>
        <?php endif; ?>
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
            <p class="text-slate-400 mt-1">Coba kata kunci, kategori, tag, atau rating lain</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="mt-8">
        <?= $pager->links('default', 'default_template') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
