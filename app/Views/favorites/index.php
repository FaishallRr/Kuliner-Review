<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<style>*{font-family:'Inter',system-ui,-apple-system,sans-serif}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}@keyframes fadeIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}.animate-fade-in{animation:fadeIn .3s ease-out}</style>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8 animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 4.95 5.5 5.5 0 0116.313 3C19.286 3 21.75 5.322 21.75 8.25c0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Favorit Saya</h1>
        </div>
        <span class="bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-semibold px-3 py-1.5 rounded-xl shadow-sm">
            <?= count($favorites) ?> tempat
        </span>
    </div>

    <?php if (empty($favorites)): ?>
    <div class="text-center py-20 animate-fade-in">
        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
        </div>
        <p class="text-gray-400 text-lg font-medium mb-2">Belum ada tempat favorit.</p>
        <a href="/places" class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Jelajahi tempat kuliner
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($favorites as $fav): ?>
        <a href="/places/<?= $fav['place_id'] ?>" class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden group animate-fade-in">
            <?php if (!empty($fav['image'])): ?>
            <div class="h-40 overflow-hidden">
                <img src="<?= esc($fav['image']) ?>" alt="<?= esc($fav['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <?php else: ?>
            <div class="h-40 bg-gradient-to-br from-orange-50 to-amber-50 flex items-center justify-center">
                <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5A1.5 1.5 0 003.75 21z"/></svg>
            </div>
            <?php endif; ?>
            <div class="p-5">
                <span class="inline-block bg-orange-50 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-lg mb-3"><?= esc($fav['category_name'] ?? 'Tanpa Kategori') ?></span>
                <h3 class="font-bold text-gray-800 group-hover:text-orange-600 transition-colors line-clamp-2"><?= esc($fav['name']) ?></h3>
                <p class="text-gray-400 text-sm mt-2 flex items-start gap-1.5 line-clamp-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    <?= esc($fav['address']) ?>
                </p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>