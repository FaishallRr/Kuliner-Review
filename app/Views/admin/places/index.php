<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tempat Kuliner</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola semua tempat kuliner</p>
        </div>
        <a href="/admin/places/create" class="btn-primary inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tempat
        </a>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <?php if (empty($places)): ?>
            <div class="p-8 text-center text-slate-400">Belum ada tempat kuliner.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50/80">
                            <th class="px-6 py-3.5">Nama</th>
                            <th class="px-6 py-3.5">Kategori</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Kontributor</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($places as $p): ?>
                        <tr class="hover:bg-orange-50/20 transition-colors duration-150">
                            <td class="px-6 py-3.5 text-sm font-medium text-slate-800"><?= esc($p['name']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($p['category_name']) ?></td>
                            <td class="px-6 py-3.5">
                                <?php
                                    $statusColors = [
                                        'approved' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
                                        'pending'  => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
                                        'rejected' => 'bg-red-100 text-red-700 ring-1 ring-red-200',
                                    ];
                                    $colorClass = $statusColors[$p['status']] ?? 'bg-slate-100 text-slate-700 ring-1 ring-slate-200';
                                    $statusLabels = [
                                        'approved' => 'Disetujui',
                                        'pending'  => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                    ];
                                    $statusLabel = $statusLabels[$p['status']] ?? ucfirst($p['status']);
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?>"><?= $statusLabel ?></span>
                                <?php if (!empty($p['is_closed'])): ?>
                                <span class="ml-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-white ring-1 ring-slate-800/50">Ditutup</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($p['contributor_name']) ?></td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    <?php if (!empty($p['is_closed'])): ?>
                                        <form action="/admin/places/<?= $p['id'] ?>/approve-closed" method="POST" class="inline" onsubmit="return confirm('Setujui tempat ini dibuka kembali?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg hover:bg-emerald-100 transition ring-1 ring-emerald-200/50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Buka
                                            </button>
                                        </form>
                                        <form action="/admin/places/<?= $p['id'] ?>/reject-closed" method="POST" class="inline" onsubmit="return confirm('Tolak pembatalan tutup permanen?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition ring-1 ring-red-200/50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="/admin/places/<?= $p['id'] ?>/edit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition ring-1 ring-amber-200/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <a href="/places/<?= $p['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-50 text-sky-700 text-xs font-medium rounded-lg hover:bg-sky-100 transition ring-1 ring-sky-200/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat
                                    </a>
                                    <form action="/admin/places/<?= $p['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Hapus tempat ini?')">
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

    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="mt-6">
        <?= $pager->links('default', 'default_template') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
