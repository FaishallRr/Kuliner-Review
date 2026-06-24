<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3.5 mb-8 animate-fade-in">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-sm shrink-0">
            <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Saya</h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 animate-fade-in">
        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5 text-center">
            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center mx-auto mb-3 ring-1 ring-sky-200/50">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $myPlacesCount ?></p>
            <p class="text-slate-500 text-sm mt-1">Total Tempat</p>
        </div>
        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5 text-center">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mx-auto mb-3 ring-1 ring-emerald-200/50">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $approvedCount ?></p>
            <p class="text-slate-500 text-sm mt-1">Disetujui</p>
        </div>
        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5 text-center">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mx-auto mb-3 ring-1 ring-amber-200/50">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $pendingCount ?></p>
            <p class="text-slate-500 text-sm mt-1">Menunggu</p>
        </div>
        <div class="stat-card bg-white rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-5 text-center">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center mx-auto mb-3 ring-1 ring-red-200/50">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-3xl font-bold text-slate-800"><?= $rejectedCount ?></p>
            <p class="text-slate-500 text-sm mt-1">Ditolak</p>
        </div>
    </div>

    <?php if (!empty($notifications)): ?>
    <div class="bg-gradient-to-r from-amber-50/80 to-orange-50/80 ring-1 ring-amber-200/60 rounded-2xl p-5 mb-8 animate-fade-in">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="font-semibold text-amber-800">Notifikasi Terbaru</h3>
        </div>
        <?php foreach (array_slice($notifications, 0, 3) as $notif): ?>
        <div class="text-sm text-amber-700 py-1.5 flex items-start gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 shrink-0"></span>
            <?= esc($notif['message']) ?>
        </div>
        <?php endforeach; ?>
        <?php if (count($notifications) > 3): ?>
        <a href="/notifications" class="inline-flex items-center gap-1 text-amber-600 text-sm font-medium hover:text-amber-800 mt-2 transition-colors">
            Lihat semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-5 animate-fade-in">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            <h2 class="text-lg font-semibold text-slate-700">Tempat Kuliner Saya</h2>
        </div>
        <a href="/places/create" class="btn-primary inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Tempat
        </a>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden animate-fade-in">
        <?php if (!empty($recentPlaces)): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($recentPlaces as $place): ?>
                    <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                        <td class="px-5 py-3.5 text-sm font-medium text-slate-800"><?= esc($place['name']) ?></td>
                        <td class="px-5 py-3.5">
                            <?php if ($place['status'] === 'approved'): ?>
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1 rounded-lg ring-1 ring-emerald-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Disetujui
                            </span>
                            <?php elseif ($place['status'] === 'pending'): ?>
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-lg ring-1 ring-amber-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Menunggu
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-medium px-2.5 py-1 rounded-lg ring-1 ring-red-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Ditolak
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-slate-400"><?= date('d M Y', strtotime($place['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <p class="text-slate-400 text-lg font-medium">Belum ada tempat kuliner.</p>
            <a href="/places/create" class="mt-3 inline-flex items-center gap-1 text-orange-600 hover:text-orange-700 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah tempat pertama
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
