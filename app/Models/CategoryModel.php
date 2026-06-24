<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CategoryModel: Menangani logika data tabel categories.
 */
class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'description',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'        => 'required|min_length[2]|max_length[50]|is_unique[categories.name,id,{id}]',
        'slug'        => 'required|min_length[2]|max_length[60]|is_unique[categories.slug,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Nama kategori wajib diisi.',
            'is_unique'   => 'Nama kategori sudah ada.',
        ],
        'slug' => [
            'required' => 'Slug kategori wajib diisi.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['createSlug'];
    protected $beforeUpdate   = ['createSlug'];

    /**
     * Generate slug dari nama kategori secara otomatis.
     */
    protected function createSlug(array $data): array
    {
        if (isset($data['data']['name']) && ! isset($data['data']['slug'])) {
            $data['data']['slug'] = url_title(
                $data['data']['name'],
                '-',
                true
            );
        }

        return $data;
    }

    /**
     * Mengambil semua kategori dengan jumlah tempat kuliner.
     */
    public function getWithPlaceCount(): array
    {
        return $this->select('categories.*, COUNT(places.id) AS place_count')
            ->join('places', 'places.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->findAll();
    }
}