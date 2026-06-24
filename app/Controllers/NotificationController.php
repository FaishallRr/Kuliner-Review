<?php

namespace App\Controllers;

use App\Models\NotificationModel;

/**
 * NotificationController: Menangani notifikasi pengguna.
 */
class NotificationController extends BaseController
{
    /**
     * Menampilkan daftar notifikasi pengguna.
     */
    public function index(): string
    {
        $notificationModel = new NotificationModel();
        $userId = session()->get('user_id');

        $data = [
            'title'           => 'Notifikasi',
            'notifications'   => $notificationModel->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->findAll(),
            'unreadCount'     => $notificationModel->countUnread($userId),
        ];

        return view('notifications/index', $data);
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->markAllAsRead(session()->get('user_id'));

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}