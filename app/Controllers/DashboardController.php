<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\NotificationModel;

/**
 * DashboardController: Dashboard untuk contributor.
 */
class DashboardController extends BaseController
{
    /**
     * Menampilkan dashboard contributor dengan statistik.
     */
    public function index(): string
    {
        $placeModel = new PlaceModel();
        $notificationModel = new NotificationModel();
        $userId = session()->get('user_id');

        $data = [
            'title'          => 'Dashboard',
            'myPlacesCount'  => $placeModel->where('user_id', $userId)->countAllResults(),
            'approvedCount'  => $placeModel->where('user_id', $userId)->where('status', 'approved')->countAllResults(),
            'pendingCount'   => $placeModel->where('user_id', $userId)->where('status', 'pending')->countAllResults(),
            'rejectedCount'  => $placeModel->where('user_id', $userId)->where('status', 'rejected')->countAllResults(),
            'notifications'  => $notificationModel->getUnread($userId),
            'recentPlaces'   => $placeModel->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll(),
        ];

        return view('dashboard/index', $data);
    }
}