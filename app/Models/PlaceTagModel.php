<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PlaceTagModel: Menangani logika data tabel place_tags (pivot).
 * Relasi many-to-many antara places dan tags.
 */
class PlaceTagModel extends Model
{
    protected $table            = 'place_tags';
    protected $primaryKey       = '';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'place_id',
        'tag_id',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps   = false;
    protected $skipValidation   = true;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;

    /**
     * Menambahkan tag ke tempat kuliner (batch insert).
     */
    public function addTags(int $placeId, array $tagIds): bool
    {
        $data = [];
        foreach ($tagIds as $tagId) {
            $data[] = [
                'place_id' => $placeId,
                'tag_id'   => $tagId,
            ];
        }

        return $this->insertBatch($data) !== false;
    }

    /**
     * Menghapus semua tag dari tempat kuliner tertentu.
     */
    public function removeTags(int $placeId): bool
    {
        return $this->where('place_id', $placeId)->delete() !== false;
    }

    /**
     * Mengambil nama tag berdasarkan place_id.
     */
    public function getTagNamesByPlaceId(int $placeId): array
    {
        return $this->select('tags.name, tags.slug')
            ->join('tags', 'tags.id = place_tags.tag_id')
            ->where('place_tags.place_id', $placeId)
            ->findAll();
    }

    /**
     * Sinkronisasi tag: hapus lama, tambah baru.
     */
    public function syncTags(int $placeId, array $tagIds): bool
    {
        $this->removeTags($placeId);

        if (! empty($tagIds)) {
            return $this->addTags($placeId, $tagIds);
        }

        return true;
    }
}