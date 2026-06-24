<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Ulasan</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola ulasan pengguna</p>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <?php if (empty($reviews)): ?>
            <div class="p-8 text-center text-slate-400">Belum ada ulasan.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50/80">
                            <th class="px-6 py-3.5">Pengguna</th>
                            <th class="px-6 py-3.5">Tempat</th>
                            <th class="px-6 py-3.5">Rating</th>
                            <th class="px-6 py-3.5">Komentar</th>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($reviews as $r): ?>
                        <tr class="hover:bg-orange-50/20 transition-colors duration-150">
                            <td class="px-6 py-3.5 text-sm font-medium text-slate-800"><?= esc($r['full_name']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-600"><?= esc($r['place_name']) ?></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-0.5">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $r['rating']): ?>
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.063 8.468c-.785-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <?php else: ?>
                                            <svg class="w-4 h-4 text-slate-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.063 8.468c-.785-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-500 line-clamp-2 max-w-xs"><?= esc($r['comment']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-400"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                            <td class="px-6 py-3.5 text-right">
                                <form action="/admin/reviews/<?= $r['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Hapus ulasan ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition ring-1 ring-red-200/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
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
