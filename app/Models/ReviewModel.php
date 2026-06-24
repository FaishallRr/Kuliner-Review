<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ReviewModel: Menangani logika data tabel reviews.
 * Meliputi rating rata-rata dan ulasan per tempat.
 */
class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'place_id',
        'rating',
        'comment',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id'  => 'required|is_natural_no_zero',
        'place_id' => 'required|is_natural_no_zero',
        'rating'   => 'required|greater_than[0]|less_than[6]',
        'comment'  => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'rating' => [
            'required'      => 'Rating wajib dipilih.',
            'greater_than'   => 'Rating minimal 1.',
            'less_than'      => 'Rating maksimal 5.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['preventDuplicateReview'];

    /**
     * Mencegah pengguna memberikan ulasan ganda pada tempat yang sama.
     */
    protected function preventDuplicateReview(array $data): array
    {
        if (isset($data['data']['user_id']) && isset($data['data']['place_id'])) {
            $exists = $this->where('user_id', $data['data']['user_id'])
                ->where('place_id', $data['data']['place_id'])
                ->first();

            if ($exists) {
                throw new \RuntimeException('Anda sudah memberikan ulasan untuk tempat ini.');
            }
        }

        return $data;
    }

    /**
     * Mengambil ulasan suatu tempat dengan nama pengguna.
     */
    public function getReviewsByPlace(int $placeId): array
    {
        return $this->select('reviews.*, users.full_name, users.avatar')
            ->join('users', 'users.id = reviews.user_id')
            ->where('reviews.place_id', $placeId)
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Menghitung rata-rata rating suatu tempat.
     */
    public function getAverageRating(int $placeId): float
    {
        $result = $this->selectAvg('rating')
            ->where('place_id', $placeId)
            ->first();

        if (! $result || $result['rating'] === null) {
            return 0.0;
        }

        return (float) round($result['rating'], 1);
    }

    /**
     * Menghitung jumlah ulasan suatu tempat.
     */
    public function getReviewCount(int $placeId): int
    {
        return $this->where('place_id', $placeId)->countAllResults();
    }
}