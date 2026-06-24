<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * FavoriteModel: Menangani logika data tabel favorites.
 * Pencatatan tempat favorit pengguna.
 */
class FavoriteModel extends Model
{
    protected $table            = 'favorites';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'place_id',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps   = true;
    protected $dateFormat      = 'datetime';
    protected $createdField    = 'created_at';
    protected $updatedField    = '';

    protected $validationRules = [
        'user_id'  => 'required|is_natural_no_zero',
        'place_id' => 'required|is_natural_no_zero',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;

    /**
     * Toggle favorit: tambah jika belum ada, hapus jika sudah ada.
     * Mengembalikan true jika difavoritkan, false jika dihapus.
     */
    public function toggleFavorite(int $userId, int $placeId): bool
    {
        $existing = $this->where('user_id', $userId)
            ->where('place_id', $placeId)
            ->first();

        if ($existing) {
            $this->where('user_id', $userId)
                ->where('place_id', $placeId)
                ->delete();

            return false;
        }

        $this->insert([
            'user_id'  => $userId,
            'place_id' => $placeId,
        ]);

        return true;
    }

    /**
     * Cek apakah tempat sudah difavoritkan pengguna.
     */
    public function isFavorited(int $userId, int $placeId): bool
    {
        return $this->where('user_id', $userId)
            ->where('place_id', $placeId)
            ->first() !== null;
    }

    /**
     * Mengambil daftar favorit pengguna dengan detail tempat.
     */
    public function getUserFavorites(int $userId): array
    {
        return $this->select('favorites.*, places.name, places.slug, places.address, places.image, categories.name AS category_name')
            ->join('places', 'places.id = favorites.place_id')
            ->join('categories', 'categories.id = places.category_id', 'left')
            ->where('favorites.user_id', $userId)
            ->orderBy('favorites.created_at', 'DESC')
            ->findAll();
    }
}