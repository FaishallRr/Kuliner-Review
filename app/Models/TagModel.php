<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TagModel: Menangani logika data tabel tags.
 */
class TagModel extends Model
{
    protected $table            = 'tags';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[50]|is_unique[tags.name,id,{id}]',
        'slug' => 'required|min_length[2]|max_length[60]|is_unique[tags.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'  => 'Nama tag wajib diisi.',
            'is_unique'  => 'Nama tag sudah ada.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['createSlug'];
    protected $beforeUpdate   = ['createSlug'];

    /**
     * Generate slug dari nama tag secara otomatis.
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
     * Mengambil semua tag dengan jumlah tempat kuliner yang menggunakannya.
     */
    public function getWithPlaceCount(): array
    {
        return $this->select('tags.*, COUNT(place_tags.place_id) AS place_count')
            ->join('place_tags', 'place_tags.tag_id = tags.id', 'left')
            ->groupBy('tags.id')
            ->findAll();
    }
}