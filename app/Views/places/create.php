<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="/places" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-orange-600 transition-all duration-200 mb-6 group">
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="flex items-center gap-3.5 mb-6">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-sm shrink-0">
            <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Tempat Kuliner</h1>
            <p class="text-sm text-slate-500">Isi informasi tempat makan yang ingin Anda tambahkan</p>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-6 sm:p-8 animate-fade-in card-hover">
        <div class="flex items-start gap-3 mb-6 p-4 rounded-xl bg-amber-50/80 ring-1 ring-amber-200/50">
            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <p class="text-sm text-amber-700">Tempat yang Anda tambahkan akan ditinjau admin sebelum ditampilkan secara publik.</p>
        </div>

        <form action="/places" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-5">
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Tempat <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="<?= old('name') ?>" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="Contoh: Warung Bu Sri">
            </div>

            <div class="mb-5">
                <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" name="address" id="address" value="<?= old('address') ?>" required
                        class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="Jl. Contoh No.1, Semarang">
                    <button type="button" id="btn-geocode" class="btn-primary inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-sky-500 to-blue-500 text-white text-sm font-medium rounded-xl shadow-sm whitespace-nowrap hover:from-sky-600 hover:to-blue-600 hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Cari Lokasi
                    </button>
                </div>
            </div>

            <div id="geocode-status" class="text-xs text-slate-400 mb-3 hidden animate-fade-in"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-2">
                <div>
                    <label for="latitude" class="block text-sm font-semibold text-slate-700 mb-1.5">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="<?= old('latitude') ?>" step="any"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="-6.9785">
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-semibold text-slate-700 mb-1.5">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="<?= old('longitude') ?>" step="any"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="110.4085">
                </div>
            </div>

            <div id="map" class="w-full h-64 rounded-xl ring-1 ring-slate-900/5 mb-5 shadow-sm overflow-hidden"></div>

            <div class="mb-5">
                <label for="image" class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Tempat</label>
                <div class="relative">
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 file:cursor-pointer file:transition-all">
                </div>
                <p class="text-xs text-slate-400 mt-1.5">Format: JPG, PNG, WebP. Maks 2MB.</p>
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm resize-none placeholder:text-slate-400" placeholder="Ceritakan tentang tempat ini..."><?= old('description') ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2.5">Tag</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                    <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 ring-1 ring-slate-200 hover:ring-orange-300 hover:bg-orange-50/50 cursor-pointer transition-all duration-200 has-[:checked]:ring-orange-400 has-[:checked]:bg-orange-50 has-[:checked]:ring-2">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" class="rounded text-orange-600 focus:ring-orange-500">
                        <span class="text-sm text-slate-600"><?= esc($tag['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex gap-3 pt-5 border-t border-slate-100">
                <button type="submit" class="btn-primary flex-1 px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm">
                    Simpan
                </button>
                <a href="/places" class="btn-outline px-6 py-2.5 bg-slate-50 text-slate-600 font-medium rounded-xl ring-1 ring-slate-200 hover:ring-orange-200 hover:text-orange-600 hover:bg-orange-50/30 transition-all">Batal</a>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var defaultLat = -6.9785;
    var defaultLng = 110.4085;
    var latInput = document.getElementById('latitude');
    var lngInput = document.getElementById('longitude');

    var lat = latInput.value ? parseFloat(latInput.value) : defaultLat;
    var lng = lngInput.value ? parseFloat(lngInput.value) : defaultLng;

    var map = L.map('map').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        var pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(7);
        lngInput.value = pos.lng.toFixed(7);
    });

    document.getElementById('btn-geocode').addEventListener('click', function() {
        var address = document.getElementById('address').value;
        if (!address) return;
        var status = document.getElementById('geocode-status');
        status.textContent = 'Mencari lokasi...';
        status.classList.remove('hidden');

        fetch('/geocode?q=' + encodeURIComponent(address))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    latInput.value = data.latitude;
                    lngInput.value = data.longitude;
                    var newLat = parseFloat(data.latitude);
                    var newLng = parseFloat(data.longitude);
                    marker.setLatLng([newLat, newLng]);
                    map.setView([newLat, newLng], 16);
                    status.className = 'text-xs text-emerald-600 mb-3 animate-fade-in';
                    status.textContent = data.display_name;
                } else {
                    status.className = 'text-xs text-red-500 mb-3 animate-fade-in';
                    status.textContent = 'Lokasi tidak ditemukan.';
                }
            })
            .catch(function() {
                status.className = 'text-xs text-red-500 mb-3 animate-fade-in';
                status.textContent = 'Gagal menghubungi layanan geocoding.';
            });
    });

    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>
<?= $this->endSection() ?>
