<?php

namespace App\Controllers;

use App\Models\ReviewModel;

/**
 * ReviewController: Menangani ulasan tempat kuliner oleh contributor.
 * Mendukung edit ulasan dalam 24 jam sejak dibuat.
 */
class ReviewController extends BaseController
{
    /**
     * Menambahkan ulasan pada tempat kuliner.
     */
    public function store(int $placeId)
    {
        $rules = [
            'rating'  => 'required|greater_than[0]|less_than[6]',
            'comment' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Ulasan tidak valid. Rating harus 1-5.');
        }

        $reviewModel = new ReviewModel();

        $existing = $reviewModel->where('user_id', session()->get('user_id'))
            ->where('place_id', $placeId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk tempat ini.');
        }

        $reviewModel->insert([
            'user_id'  => session()->get('user_id'),
            'place_id' => $placeId,
            'rating'   => $this->request->getPost('rating'),
            'comment'  => $this->request->getPost('comment'),
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan!');
    }

    /**
     * Form edit ulasan (hanya dalam 24 jam).
     */
    public function edit(int $id): string
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($id);

        if (! $review || $review['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $reviewAge = time() - strtotime($review['created_at']);
        if ($reviewAge > 86400) {
            return redirect()->to('/places/' . $review['place_id'])->with('error', 'Ulasan hanya bisa diedit dalam 24 jam.');
        }

        $data = [
            'title'  => 'Edit Ulasan',
            'review' => $review,
        ];

        return view('reviews/edit', $data);
    }

    /**
     * Update ulasan (hanya dalam 24 jam).
     */
    public function update(int $id)
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($id);

        if (! $review || $review['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $reviewAge = time() - strtotime($review['created_at']);
        if ($reviewAge > 86400) {
            return redirect()->to('/places/' . $review['place_id'])->with('error', 'Ulasan hanya bisa diedit dalam 24 jam.');
        }

        $rules = [
            'rating'  => 'required|greater_than[0]|less_than[6]',
            'comment' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Rating harus 1-5.');
        }

        $reviewModel->update($id, [
            'rating'  => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
        ]);

        return redirect()->to('/places/' . $review['place_id'])->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * Menghapus ulasan.
     */
    public function delete(int $id)
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($id);

        if (! $review || $review['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $reviewModel->delete($id);
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}