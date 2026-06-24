<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Ringkasan data platform KulinerReview</p>
    </div>

    <?php if ($pendingCount > 0): ?>
    <div class="mb-6 bg-gradient-to-r from-amber-50/80 to-orange-50/80 ring-1 ring-amber-200/60 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-amber-800 font-medium text-sm">Ada <strong><?= $pendingCount ?></strong> tempat kuliner menunggu persetujuan.</p>
        </div>
        <a href="/admin/places/pending" class="btn-primary inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-medium rounded-xl shadow-sm whitespace-nowrap">
            Moderasi sekarang
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.914a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Total Tempat</span>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $totalPlaces ?></p>
        </div>

        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Menunggu</span>
            </div>
            <p class="text-3xl font-bold text-amber-600"><?= $pendingCount ?></p>
        </div>

        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-400 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Disetujui</span>
            </div>
            <p class="text-3xl font-bold text-emerald-600"><?= $approvedCount ?></p>
        </div>

        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Ditolak</span>
            </div>
            <p class="text-3xl font-bold text-red-600"><?= $rejectedCount ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Pengguna</span>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $totalUsers ?></p>
        </div>

        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Kategori</span>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $totalCategories ?></p>
        </div>

        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Tag</span>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $totalTags ?></p>
        </div>

        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-500">Ulasan</span>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $totalReviews ?></p>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">Tempat Terbaru</h2>
            <a href="/admin/places" class="text-sm text-orange-600 hover:text-orange-700 font-medium transition-colors">Lihat semua</a>
        </div>
        <?php if (empty($recentPlaces)): ?>
            <div class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.914a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p>Belum ada tempat kuliner.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50/80">
                            <th class="px-6 py-3.5">Nama</th>
                            <th class="px-6 py-3.5">Kategori</th>
                            <th class="px-6 py-3.5">Kontributor</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($recentPlaces as $p): ?>
                        <tr class="hover:bg-orange-50/20 transition-colors duration-150">
                            <td class="px-6 py-3.5 text-sm font-medium text-slate-800"><?= esc($p['name']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($p['category_name']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($p['contributor_name']) ?></td>
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
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-400"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
