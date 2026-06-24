<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="/places" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-orange-600 transition-all duration-200 mb-6 group">
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar
    </a>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden animate-fade-in card-hover">
        <?php if (!empty($place['image']) && file_exists(WRITEPATH . 'uploads/' . $place['image'])): ?>
        <div class="relative overflow-hidden">
            <img src="/uploads/<?= esc($place['image']) ?>" alt="<?= esc($place['name']) ?>" class="w-full h-64 sm:h-80 object-cover hover:scale-105 transition-transform duration-700 ease-out">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
        </div>
        <?php else: ?>
        <div class="bg-gradient-to-br from-orange-50 to-amber-50 h-64 sm:h-80 flex items-center justify-center">
            <svg class="w-28 h-28 text-orange-300/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18v18H3zM12 8v4m0 0v4m0-4h4m-4 0H8"/></svg>
        </div>
        <?php endif; ?>

        <div class="p-6 sm:p-8">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-50 to-amber-50 text-orange-700 text-xs font-semibold px-3 py-1 rounded-lg ring-1 ring-orange-200/50"><?= esc($place['category_name']) ?></span>
                <?php foreach ($tags as $tag): ?>
                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-xs font-semibold px-3 py-1 rounded-lg ring-1 ring-sky-200/50"><?= esc($tag['name']) ?></span>
                <?php endforeach; ?>
                <?php if (!empty($place['is_closed'])): ?>
                <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-lg ring-1 ring-red-200/50">Tutup Permanen</span>
                <?php endif; ?>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-2 tracking-tight"><?= esc($place['name']) ?></h1>

            <div class="flex items-start gap-1.5 text-slate-500 text-sm mb-5">
                <svg class="w-4 h-4 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="leading-relaxed"><?= esc($place['address']) ?></span>
            </div>

            <?php if ($place['description']): ?>
            <div class="bg-slate-50/80 rounded-xl p-4 ring-1 ring-slate-900/5 mb-5">
                <p class="text-slate-600 text-sm leading-relaxed"><?= esc($place['description']) ?></p>
            </div>
            <?php endif; ?>

            <?php if ($place['latitude'] && $place['longitude']): ?>
            <div id="map" class="w-full h-72 rounded-xl ring-1 ring-slate-900/5 mb-5 shadow-sm overflow-hidden"></div>
            <?php endif; ?>

            <div class="flex flex-wrap items-center gap-4 pt-5 border-t border-slate-100">
                <div class="flex items-center gap-1.5 text-sm">
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <strong class="text-slate-800"><?= number_format($avgRating, 1) ?></strong>
                    <span class="text-slate-400">/5</span>
                    <span class="text-slate-400">(<?= $reviewCount ?> ulasan)</span>
                </div>
                <div class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Oleh <?= esc($place['contributor_name']) ?></span>
                </div>
                <?php if (session()->get('isLoggedIn')): ?>
                <form action="/places/<?= $place['id'] ?>/favorite" method="POST" class="ml-auto">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 <?= $isFavorited ? 'bg-red-50 text-red-600 ring-1 ring-red-200 hover:bg-red-100' : 'bg-slate-50 text-slate-600 ring-1 ring-slate-200 hover:bg-red-50 hover:text-red-500 hover:ring-red-200' ?> hover:scale-105">
                        <?php if ($isFavorited): ?>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        Difavoritkan
                        <?php else: ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Favoritkan
                        <?php endif; ?>
                    </button>
                </form>
                <?php if (session()->get('user_id') == $place['user_id'] && empty($place['is_closed'])): ?>
                <form action="/places/<?= $place['id'] ?>/mark-closed" method="POST" onsubmit="return confirm('Tandai tempat ini sebagai tutup permanen?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium bg-slate-50 text-slate-600 ring-1 ring-slate-200 hover:bg-red-50 hover:text-red-600 hover:ring-red-200 transition-all duration-200 hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Tandai Tutup
                    </button>
                </form>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-10">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-sm shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Ulasan (<?= $reviewCount ?>)</h2>
        </div>

        <?php if (session()->get('isLoggedIn')): ?>
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-6 mb-6 animate-fade-in card-hover">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Tulis Ulasan</h3>
            <form action="/places/<?= $place['id'] ?>/reviews" method="POST">
                <?= csrf_field() ?>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm text-slate-500">Rating:</span>
                    <div class="flex gap-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <label class="cursor-pointer group">
                            <input type="radio" name="rating" value="<?= $i ?>" required class="peer sr-only">
                            <svg class="w-7 h-7 text-slate-200 group-hover:text-amber-400 peer-checked:text-amber-400 transition-all duration-200 peer-checked:scale-110 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <textarea name="comment" rows="3" placeholder="Bagikan pengalaman Anda..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm resize-none placeholder:text-slate-400"></textarea>
                <div class="flex justify-end mt-3">
                    <button type="submit" class="btn-primary px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm text-sm">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <?php foreach ($reviews as $review): ?>
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5 mb-3 animate-fade-in card-hover">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-amber-400 flex items-center justify-center text-white text-sm font-bold shrink-0 shadow-sm">
                        <?= strtoupper(substr($review['full_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <span class="font-semibold text-slate-800 text-sm"><?= esc($review['full_name']) ?></span>
                        <div class="flex items-center gap-0.5 mt-0.5">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-4 h-4 <?= $i <= $review['rating'] ? 'text-amber-400' : 'text-slate-200' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-xs text-slate-400"><?= date('d M Y', strtotime($review['created_at'])) ?></span>
                    <?php if (session()->get('isLoggedIn') && $review['user_id'] == session()->get('user_id')): ?>
                    <div class="flex items-center gap-1">
                        <?php
                        $reviewAge = time() - strtotime($review['created_at']);
                        $canEdit = $reviewAge < 86400;
                        ?>
                        <?php if ($canEdit): ?>
                        <a href="/reviews/<?= $review['id'] ?>/edit" class="text-xs font-medium text-sky-600 hover:text-sky-700 transition-colors px-1.5 py-0.5 rounded-lg hover:bg-sky-50">Edit</a>
                        <?php endif; ?>
                        <form action="/reviews/<?= $review['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Hapus ulasan ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-600 transition-colors px-1.5 py-0.5 rounded-lg hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($review['comment']): ?>
            <p class="text-slate-600 text-sm mt-3 leading-relaxed"><?= esc($review['comment']) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <?php if (empty($reviews)): ?>
        <div class="text-center py-14">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <p class="text-slate-400 font-medium">Belum ada ulasan. Jadilah yang pertama!</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($place['latitude'] && $place['longitude']): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = <?= esc($place['latitude']) ?>;
    var lng = <?= esc($place['longitude']) ?>;
    var map = L.map('map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup('<?= esc($place['name']) ?>').openPopup();
    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
