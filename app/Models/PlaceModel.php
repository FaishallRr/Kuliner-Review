<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PlaceModel: Menangani logika data tabel places.
 * Termasuk pencarian, filter status, dan relasi dengan kategori/user.
 */
class PlaceModel extends Model
{
    protected $table            = 'places';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'address',
        'latitude',
        'longitude',
        'image',
        'status',
        'rejection_note',
        'is_closed',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id'       => 'required|is_natural_no_zero',
        'category_id'   => 'required|is_natural_no_zero',
        'name'          => 'required|min_length[2]|max_length[150]',
        'slug'          => 'permit_empty|max_length[160]|is_unique[places.slug,id,{id}]',
        'description'   => 'permit_empty|max_length[1000]',
        'address'       => 'required|min_length[5]|max_length[255]',
        'latitude'      => 'permit_empty|decimal|greater_than[-90]|less_than[90]',
        'longitude'     => 'permit_empty|decimal|greater_than[-180]|less_than[180]',
        'image'         => 'permit_empty|max_length[255]',
        'status'        => 'permit_empty|in_list[pending,approved,rejected]',
        'rejection_note' => 'permit_empty|max_length[500]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Nama tempat wajib diisi.',
            'min_length'  => 'Nama tempat minimal 2 karakter.',
        ],
        'address' => [
            'required'   => 'Alamat wajib diisi.',
        ],
        'status' => [
            'in_list' => 'Status harus pending, approved, atau rejected.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['createSlug'];
    protected $beforeUpdate   = ['createSlug'];

    /**
     * Generate slug dari nama tempat secara otomatis.
     * Menangani duplikasi slug dengan menambahkan angka increment.
     */
    protected function createSlug(array $data): array
    {
        if (isset($data['data']['name']) && ! isset($data['data']['slug'])) {
            $slug = url_title($data['data']['name'], '-', true);
            $baseSlug = $slug;
            $counter = 1;
            $this->where('slug', $slug);
            if (isset($data['id'])) {
                $this->where('id !=', $data['id']);
            }
            while ($this->first()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
                $this->resetQuery();
                $this->where('slug', $slug);
                if (isset($data['id'])) {
                    $this->where('id !=', $data['id']);
                }
            }
            $this->resetQuery();
            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    /**
     * Mengambil tempat kuliner yang sudah di-approve beserta relasi dan rating.
     */
    public function getApprovedWithRelations(): array
    {
        return $this->select('places.*, categories.name AS category_name, users.full_name AS contributor_name, COALESCE(avg_ratings.avg_rating, 0) as avg_rating')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->join('(SELECT place_id, AVG(rating) as avg_rating FROM reviews GROUP BY place_id) AS avg_ratings', 'avg_ratings.place_id = places.id', 'left')
            ->where('places.status', 'approved')
            ->orderBy('places.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil tempat kuliner berdasarkan status moderasi.
     */
    public function getByStatus(string $status): array
    {
        return $this->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->where('places.status', $status)
            ->orderBy('places.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil detail tempat kuliner dengan relasi lengkap.
     */
    public function getDetail(int $id): ?array
    {
        return $this->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->find($id);
    }

    /**
     * Pencarian tempat kuliner berdasarkan kata kunci.
     */
    public function search(string $keyword): array
    {
        return $this->select('places.*, categories.name AS category_name')
            ->join('categories', 'categories.id = places.category_id')
            ->groupStart()
                ->like('places.name', $keyword)
                ->orLike('places.address', $keyword)
                ->orLike('categories.name', $keyword)
            ->groupEnd()
            ->where('places.status', 'approved')
            ->orderBy('places.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Filter tempat yang sudah di-approve berdasarkan tag dan rating minimal.
     */
    public function getFilteredApproved(?int $tagId = null, ?int $minRating = null, ?int $categoryId = null, ?string $keyword = null): array
    {
        $this->select('places.*, categories.name AS category_name, users.full_name AS contributor_name, COALESCE(avg_ratings.avg_rating, 0) as avg_rating')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->join('(SELECT place_id, AVG(rating) as avg_rating FROM reviews GROUP BY place_id) AS avg_ratings', 'avg_ratings.place_id = places.id', 'left')
            ->where('places.status', 'approved');

        if ($keyword) {
            $this->groupStart()
                ->like('places.name', $keyword)
                ->orLike('places.address', $keyword)
                ->orLike('categories.name', $keyword)
            ->groupEnd();
        }

        if ($categoryId) {
            $this->where('places.category_id', $categoryId);
        }

        if ($tagId) {
            $this->join('place_tags', 'place_tags.place_id = places.id')
                ->where('place_tags.tag_id', $tagId);
        }

        if ($minRating) {
            $this->where('avg_ratings.avg_rating >=', $minRating);
        }

        $this->groupBy('places.id')
            ->orderBy('places.created_at', 'DESC');

        return $this->findAll();
    }

    /**
     * Statistik jumlah tempat per status.
     */
    public function getStatusCount(): array
    {
        $result = $this->select("status, COUNT(*) AS total")
            ->groupBy('status')
            ->findAll();

        $stats = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
        foreach ($result as $row) {
            $stats[$row['status']] = (int) $row['total'];
        }

        return $stats;
    }

    /**
     * Ambil daftar tag untuk sebuah place.
     */
    public function getTags(int $placeId): array
    {
        return $this->db->table('place_tags')
            ->select('tags.id, tags.name, tags.slug')
            ->join('tags', 'tags.id = place_tags.tag_id')
            ->where('place_tags.place_id', $placeId)
            ->get()
            ->getResultArray();
    }
}