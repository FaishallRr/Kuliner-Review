<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengguna</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola pengguna platform</p>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <?php if (empty($users)): ?>
            <div class="p-8 text-center text-slate-400">Belum ada pengguna.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50/80">
                            <th class="px-6 py-3.5">Nama</th>
                            <th class="px-6 py-3.5">Username</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Peran</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-orange-50/20 transition-colors duration-150">
                            <td class="px-6 py-3.5 text-sm font-medium text-slate-800"><?= esc($u['full_name']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($u['username']) ?></td>
                            <td class="px-6 py-3.5 text-sm text-slate-500"><?= esc($u['email']) ?></td>
                            <td class="px-6 py-3.5">
                                <?php
                                    $roleColors = [
                                        'admin'      => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200',
                                        'contributor' => 'bg-sky-100 text-sky-700 ring-1 ring-sky-200',
                                    ];
                                    $colorClass = $roleColors[$u['role']] ?? 'bg-slate-100 text-slate-700 ring-1 ring-slate-200';
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <?php if (session()->get('user_id') != $u['id']): ?>
                                <form action="/admin/users/<?= $u['id'] ?>" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition ring-1 ring-red-200/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="text-xs text-slate-400 font-medium">Akun Anda</span>
                                <?php endif; ?>
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
