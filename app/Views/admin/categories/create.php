<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Kategori</h1>
        <p class="text-sm text-slate-500 mt-1">Buat kategori baru untuk tempat kuliner</p>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-6 sm:p-8 max-w-xl card-hover">
        <form action="/admin/categories" method="POST">
            <?= csrf_field() ?>

            <div class="mb-5">
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori</label>
                <input type="text" name="name" id="name" value="<?= old('name') ?>" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm placeholder:text-slate-400"
                    placeholder="Contoh: Rumah Makan">
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all text-sm resize-none placeholder:text-slate-400"
                    placeholder="Deskripsi singkat kategori..."><?= old('description') ?></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
                <a href="/admin/categories" class="btn-outline px-6 py-2.5 bg-slate-50 text-slate-600 rounded-xl text-sm font-medium ring-1 ring-slate-200 hover:ring-orange-200 hover:text-orange-600 hover:bg-orange-50/30 transition-all">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
