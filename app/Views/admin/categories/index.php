<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kategori</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola kategori tempat kuliner</p>
        </div>
        <a href="/admin/categories/create" class="btn-primary inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <?php if (empty($categories)): ?>
            <div class="p-8 text-center text-slate-400">Belum ada kategori.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50/80">
                            <th class="px-6 py-3.5">Nama</th>
                            <th class="px-6 py-3.5">Slug</th>
                            <th class="px-6 py-3.5">Jumlah Tempat</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-orange-50/20 transition-colors duration-150">
                            <td class="px-6 py-3.5 text-sm font-medium text-slate-800"><?= esc($cat['name']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($cat['slug']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= $cat['place_count'] ?></td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    <a href="/admin/categories/<?= $cat['id'] ?>/edit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition ring-1 ring-amber-200/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form action="/admin/categories/<?= $cat['id'] ?>" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition ring-1 ring-red-200/50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
