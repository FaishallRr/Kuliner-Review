<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

/**
 * ReviewController (Admin): Moderasi ulasan pengguna.
 */
class ReviewController extends BaseController
{
    /**
     * Daftar semua ulasan.
     */
    public function index(): string
    {
        $reviewModel = new ReviewModel();

        $reviews = $reviewModel->select('reviews.*, users.full_name, places.name AS place_name')
            ->join('users', 'users.id = reviews.user_id')
            ->join('places', 'places.id = reviews.place_id')
            ->orderBy('reviews.created_at', 'DESC')
            ->paginate(20);

        $data = [
            'title'   => 'Kelola Ulasan',
            'reviews' => $reviews,
            'pager'   => $reviewModel->pager,
        ];

        return view('admin/reviews/index', $data);
    }

    /**
     * Menghapus ulasan oleh admin.
     */
    public function delete(int $id)
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($id);

        if (! $review) {
            return redirect()->back()->with('error', 'Ulasan tidak ditemukan.');
        }

        $reviewModel->delete($id);
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}