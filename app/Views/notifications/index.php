<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<style>*{font-family:'Inter',system-ui,-apple-system,sans-serif}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}@keyframes fadeIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}.animate-fade-in{animation:fadeIn .3s ease-out}</style>
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8 animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
            <?php if ($unreadCount > 0): ?>
            <span class="bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-xl"><?= $unreadCount ?></span>
            <?php endif; ?>
        </div>
        <?php if ($unreadCount > 0): ?>
        <form action="/notifications/read" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-orange-600 hover:text-orange-700 bg-orange-50 hover:bg-orange-100 px-3.5 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Tandai semua dibaca
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php if (empty($notifications)): ?>
    <div class="text-center py-20 animate-fade-in">
        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
        </div>
        <p class="text-gray-400 text-lg font-medium">Tidak ada notifikasi.</p>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php foreach ($notifications as $notif): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow animate-fade-in <?= $notif['is_read'] ? '' : 'border-l-4 border-l-gradient-to-r from-orange-500 to-amber-500' ?>" style="<?= $notif['is_read'] ? '' : 'border-left-color: #f97316' ?>">
            <?php if (!$notif['is_read']): ?>
            <div class="absolute w-1.5 h-1.5 rounded-full bg-orange-500 -ml-6 mt-2"></div>
            <?php endif; ?>
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl <?= $notif['is_read'] ? 'bg-gray-100' : 'bg-gradient-to-br from-orange-100 to-amber-100' ?> flex items-center justify-center shrink-0 mt-0.5">
                        <?php if ($notif['is_read']): ?>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <?php else: ?>
                        <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 14a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-gray-800 text-sm"><?= esc($notif['title']) ?></h3>
                        <p class="text-gray-500 text-sm mt-0.5 line-clamp-2"><?= esc($notif['message']) ?></p>
                    </div>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap shrink-0 mt-1"><?= date('d M Y H:i', strtotime($notif['created_at'])) ?></span>
            </div>
            <?php if (!$notif['is_read']): ?>
            <div class="ml-12 mt-2">
                <span class="inline-block w-2 h-2 rounded-full bg-orange-500 mr-1"></span>
                <span class="text-xs text-orange-600 font-medium">Baru</span>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>