<?php

namespace App\Controllers;

use App\Models\FavoriteModel;

/**
 * FavoriteController: Menangani favorit tempat kuliner.
 */
class FavoriteController extends BaseController
{
    /**
     * Toggle favorit tempat kuliner.
     */
    public function toggle(int $placeId)
    {
        $favoriteModel = new FavoriteModel();
        $isFavorited = $favoriteModel->toggleFavorite(session()->get('user_id'), $placeId);

        if ($isFavorited) {
            return redirect()->back()->with('success', 'Tempat ditambahkan ke favorit!');
        }

        return redirect()->back()->with('success', 'Tempat dihapus dari favorit.');
    }

    /**
     * Daftar favorit pengguna.
     */
    public function index(): string
    {
        $favoriteModel = new FavoriteModel();

        $data = [
            'title'      => 'Favorit Saya',
            'favorites'  => $favoriteModel->getUserFavorites(session()->get('user_id')),
        ];

        return view('favorites/index', $data);
    }
}