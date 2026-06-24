<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlaceModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\ReviewModel;

/**
 * DashboardController: Dashboard admin dengan statistik.
 */
class DashboardController extends BaseController
{
    /**
     * Menampilkan dashboard admin dengan statistik keseluruhan.
     */
    public function index(): string
    {
        $placeModel = new PlaceModel();
        $userModel = new UserModel();
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();
        $reviewModel = new ReviewModel();

        $statusCount = $placeModel->getStatusCount();

        $data = [
            'title'             => 'Admin Dashboard',
            'totalPlaces'       => $placeModel->countAllResults(),
            'pendingCount'      => $statusCount['pending'],
            'approvedCount'     => $statusCount['approved'],
            'rejectedCount'     => $statusCount['rejected'],
            'totalUsers'        => $userModel->countAllResults(),
            'totalCategories'  => $categoryModel->countAllResults(),
            'totalTags'         => $tagModel->countAllResults(),
            'totalReviews'      => $reviewModel->countAllResults(),
            'recentPlaces'      => $placeModel->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
                ->join('categories', 'categories.id = places.category_id')
                ->join('users', 'users.id = places.user_id')
                ->orderBy('places.created_at', 'DESC')
                ->limit(5)
                ->findAll(),
        ];

        return view('admin/dashboard/index', $data);
    }
}