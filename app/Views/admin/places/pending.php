<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Moderasi Tempat</h1>
        <p class="text-sm text-slate-500 mt-1">Setujui atau tolak tempat yang diajukan kontributor</p>
    </div>

    <?php if (empty($places)): ?>
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-8 text-center text-slate-400">
            Tidak ada tempat yang menunggu moderasi.
        </div>
    <?php else: ?>
        <div class="space-y-5">
            <?php foreach ($places as $p): ?>
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm p-6 card-hover">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 lg:gap-6">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-slate-800"><?= esc($p['name']) ?></h3>
                        <div class="flex flex-wrap items-center gap-3 mt-1.5 text-sm text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V3a1 1 0 011-1h3z"/></svg>
                                <?= esc($p['category_name']) ?>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <?= esc($p['contributor_name']) ?>
                            </span>
                        </div>
                        <div class="mt-3 space-y-1.5 text-sm text-slate-600 bg-slate-50/80 rounded-xl p-4 ring-1 ring-slate-900/5">
                            <p><strong class="text-slate-700">Alamat:</strong> <?= esc($p['address']) ?></p>
                            <p><strong class="text-slate-700">Deskripsi:</strong> <span class="line-clamp-2"><?= esc($p['description']) ?></span></p>
                            <p><strong class="text-slate-700">Koordinat:</strong> <?= esc($p['latitude']) ?>, <?= esc($p['longitude']) ?></p>
                        </div>
                    </div>
                    <div class="flex flex-row lg:flex-col gap-2 shrink-0">
                        <form action="/admin/places/<?= $p['id'] ?>/approve" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-primary w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-medium rounded-xl shadow-sm hover:from-emerald-600 hover:to-green-600 hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Setujui
                            </button>
                        </form>
                        <details class="group">
                            <summary class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-rose-600 transition-all duration-200 shadow-sm cursor-pointer select-none list-none hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Tolak
                            </summary>
                            <div class="mt-3 bg-white rounded-xl p-4 ring-1 ring-slate-900/5 shadow-sm animate-fade-in">
                                <form action="/admin/places/<?= $p['id'] ?>/reject" method="POST">
                                    <?= csrf_field() ?>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alasan Penolakan</label>
                                    <textarea name="rejection_note" rows="3" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Tuliskan alasan penolakan..."></textarea>
                                    <button type="submit" class="btn-primary mt-2.5 w-full px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 text-white text-sm font-medium rounded-xl shadow-sm hover:from-red-600 hover:to-rose-600 hover:shadow-md">
                                        Kirim Penolakan
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="mt-6">
        <?= $pager->links('default', 'default_template') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
