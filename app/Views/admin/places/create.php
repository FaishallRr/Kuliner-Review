<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Tempat Kuliner</h1>
        <p class="text-sm text-slate-500 mt-1">Buat tempat kuliner baru sebagai admin</p>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-6 sm:p-8 max-w-3xl card-hover">
        <form action="/admin/places" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-5">
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Tempat</label>
                <input type="text" name="name" id="name" value="<?= old('name') ?>" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400">
            </div>

            <div class="mb-5">
                <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                <select name="category_id" id="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                <div class="flex gap-2">
                    <input type="text" name="address" id="address" value="<?= old('address') ?>" required
                        class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="Jl. Contoh No.1, Semarang">
                    <button type="button" id="btn-geocode" class="btn-primary inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-sky-500 to-blue-500 text-white text-sm font-medium rounded-xl shadow-sm whitespace-nowrap hover:from-sky-600 hover:to-blue-600 hover:shadow-md">
                        Cari Lokasi
                    </button>
                </div>
            </div>
            <div id="geocode-status" class="text-xs text-slate-400 mb-3 hidden animate-fade-in"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                <div>
                    <label for="latitude" class="block text-sm font-semibold text-slate-700 mb-1.5">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="<?= old('latitude') ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="-6.9785">
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-semibold text-slate-700 mb-1.5">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="<?= old('longitude') ?>"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400" placeholder="110.4085">
                </div>
            </div>
            <div id="map" class="w-full h-64 rounded-xl ring-1 ring-slate-900/5 mb-5 shadow-sm overflow-hidden"></div>

            <div class="mb-5">
                <label for="image" class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Tempat</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 file:cursor-pointer file:transition-all">
                <p class="text-xs text-slate-400 mt-1.5">Format: JPG, PNG, WebP. Maks 2MB.</p>
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm resize-none placeholder:text-slate-400"><?= old('description') ?></textarea>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2.5">Tag</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                    <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 ring-1 ring-slate-200 hover:ring-orange-300 hover:bg-orange-50/50 cursor-pointer transition-all duration-200 has-[:checked]:ring-orange-400 has-[:checked]:bg-orange-50 has-[:checked]:ring-2">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
                            <?= is_array(old('tags')) && in_array($tag['id'], old('tags')) ? 'checked' : '' ?>
                            class="rounded text-orange-600 focus:ring-orange-500">
                        <span class="text-sm text-slate-600"><?= esc($tag['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-5">
                <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                    <option value="approved" <?= old('status', 'approved') === 'approved' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="pending" <?= old('status') === 'pending' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="rejected" <?= old('status') === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
                <a href="/admin/places" class="btn-outline px-6 py-2.5 bg-slate-50 text-slate-600 rounded-xl text-sm font-medium ring-1 ring-slate-200 hover:ring-orange-200 hover:text-orange-600 hover:bg-orange-50/30 transition-all">Batal</a>
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
