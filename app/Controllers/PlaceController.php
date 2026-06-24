<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\PlaceTagModel;
use App\Models\ReviewModel;
use App\Models\FavoriteModel;

/**
 * PlaceController: CRUD tempat kuliner oleh contributor.
 * Menangani upload foto, geocoding Nominatim, dan mark tutup permanen.
 */
class PlaceController extends BaseController
{
    /**
     * Daftar semua tempat kuliner yang di-approve (publik).
     * Mendukung filter kategori, tag, rating, dan pencarian.
     */
    public function index(): string
    {
        $placeModel = new PlaceModel();
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();
        $reviewModel = new ReviewModel();

        $keyword = $this->request->getGet('q');
        $categoryId = $this->request->getGet('category');
        $tagId = $this->request->getGet('tag');
        $minRating = $this->request->getGet('min_rating');

        $query = $placeModel->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->where('places.status', 'approved');

        if ($keyword) {
            $query->groupStart()
                ->like('places.name', $keyword)
                ->orLike('places.address', $keyword)
                ->orLike('categories.name', $keyword)
            ->groupEnd();
        }

        if ($categoryId) {
            $query->where('places.category_id', $categoryId);
        }

        if ($tagId) {
            $query->join('place_tags', 'place_tags.place_id = places.id')
                ->where('place_tags.tag_id', $tagId);
        }

        if ($minRating) {
            $query->join('(SELECT place_id, AVG(rating) as avg_rating FROM reviews GROUP BY place_id) AS rating_sub', 'rating_sub.place_id = places.id', 'left')
                ->where('rating_sub.avg_rating >=', (int) $minRating);
        }

        $query->groupBy('places.id')
            ->orderBy('places.created_at', 'DESC');

        $perPage = 12;
        $places = $query->paginate($perPage);
        $pager = $placeModel->pager;

        $data = [
            'title'       => 'Tempat Kuliner',
            'places'      => $places,
            'pager'       => $pager,
            'categories'  => $categoryModel->findAll(),
            'tags'        => $tagModel->getWithPlaceCount(),
            'keyword'     => $keyword,
            'selectedCategory' => $categoryId,
            'selectedTag'      => $tagId,
            'selectedRating'   => $minRating,
        ];

        return view('places/index', $data);
    }

    /**
     * Form tambah tempat kuliner baru.
     */
    public function create(): string
    {
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();

        $data = [
            'title'       => 'Tambah Tempat Kuliner',
            'categories'  => $categoryModel->findAll(),
            'tags'        => $tagModel->findAll(),
        ];

        return view('places/create', $data);
    }

    /**
     * Simpan tempat kuliner baru dengan upload foto.
     */
    public function store()
    {
        $rules = [
            'name'          => 'required|min_length[2]|max_length[150]',
            'category_id'   => 'required|is_natural_no_zero',
            'address'       => 'required|min_length[5]|max_length[255]',
            'description'   => 'permit_empty|max_length[1000]',
            'image'         => 'permit_empty|is_image[image]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali form Anda.');
        }

        $imagePath = $this->uploadImage();

        $placeModel = new PlaceModel();
        $placeId = $placeModel->insert([
            'user_id'      => session()->get('user_id'),
            'category_id'   => $this->request->getPost('category_id'),
            'name'          => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'address'       => $this->request->getPost('address'),
            'latitude'      => $this->request->getPost('latitude') ?: null,
            'longitude'     => $this->request->getPost('longitude') ?: null,
            'image'         => $imagePath,
            'status'        => 'pending',
        ]);

        if ($placeId && $tagIds = $this->request->getPost('tags')) {
            $placeTagModel = new PlaceTagModel();
            $placeTagModel->addTags($placeId, $tagIds);
        }

        return redirect()->to('/places')->with('success', 'Tempat kuliner berhasil ditambahkan! Menunggu persetujuan admin.');
    }

    /**
     * Detail tempat kuliner dengan peta Leaflet.
     */
    public function show(int $id): string
    {
        $placeModel = new PlaceModel();
        $reviewModel = new ReviewModel();
        $favoriteModel = new FavoriteModel();
        $placeTagModel = new PlaceTagModel();

        $place = $placeModel->getDetail($id);

        if (! $place || $place['status'] !== 'approved') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $userId = session()->get('isLoggedIn') ? session()->get('user_id') : null;

        $data = [
            'title'         => $place['name'],
            'place'         => $place,
            'tags'          => $placeTagModel->getTagNamesByPlaceId($id),
            'reviews'       => $reviewModel->getReviewsByPlace($id),
            'avgRating'     => $reviewModel->getAverageRating($id),
            'reviewCount'   => $reviewModel->getReviewCount($id),
            'isFavorited'   => $userId ? $favoriteModel->isFavorited($userId, $id) : false,
        ];

        return view('places/show', $data);
    }

    /**
     * Form edit tempat kuliner.
     */
    public function edit(int $id): string
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place || $place['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();
        $placeTagModel = new PlaceTagModel();

        $currentTags = array_column($placeTagModel->getTagNamesByPlaceId($id), 'slug');

        $data = [
            'title'        => 'Edit ' . $place['name'],
            'place'        => $place,
            'categories'   => $categoryModel->findAll(),
            'tags'         => $tagModel->findAll(),
            'currentTags'  => $currentTags,
        ];

        return view('places/edit', $data);
    }

    /**
     * Update tempat kuliner dengan upload foto opsional.
     */
    public function update(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place || $place['user_id'] != session()->get('user_id')) {
            return redirect()->to('/places')->with('error', 'Anda tidak memiliki akses.');
        }

        $data = [
            'name'          => $this->request->getPost('name'),
            'category_id'   => $this->request->getPost('category_id'),
            'address'        => $this->request->getPost('address'),
            'description'   => $this->request->getPost('description'),
            'latitude'      => $this->request->getPost('latitude') ?: null,
            'longitude'     => $this->request->getPost('longitude') ?: null,
            'status'        => 'pending',
        ];

        $imagePath = $this->uploadImage();
        if ($imagePath) {
            $data['image'] = $imagePath;
        }

        $placeModel->update($id, $data);

        $tagIds = $this->request->getPost('tags') ?? [];
        $placeTagModel = new PlaceTagModel();
        $placeTagModel->syncTags($id, $tagIds);

        return redirect()->to('/places')->with('success', 'Tempat kuliner berhasil diperbarui! Menunggu persetujuan ulang.');
    }

    /**
     * Hapus tempat kuliner.
     */
    public function delete(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place || $place['user_id'] != session()->get('user_id')) {
            return redirect()->to('/places')->with('error', 'Anda tidak memiliki akses.');
        }

        $placeModel->delete($id);
        return redirect()->to('/places')->with('success', 'Tempat kuliner berhasil dihapus.');
    }

    /**
     * Upload dan resize foto tempat kuliner (max 800px).
     * Mengembalikan path file relatif atau null.
     */
    private function uploadImage(): ?string
    {
        $file = $this->request->getFile('image');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        if (! $file->isValid()) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowedTypes, true)) {
            return null;
        }

        $newName = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads';

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $newName);

        if (extension_loaded('gd') || extension_loaded('imagick')) {
            try {
                $imageService = \Config\Services::image();
                $sourcePath = $uploadPath . '/' . $newName;

                $imageService->withFile($sourcePath)
                    ->resize(800, 800, true, 'height')
                    ->save($sourcePath);
            } catch (\Exception $e) {
                log_message('warning', 'Failed to resize image: ' . $e->getMessage());
            }
        }

        return $newName;
    }

    /**
     * Endpoint AJAX untuk geocoding Nominatim.
     * Menerima alamat, mengembalikan koordinat lat/lng (dengan cache 30 hari).
     */
    public function geocode()
    {
        $address = $this->request->getGet('q');

        if (empty($address)) {
            return $this->response->setJSON(['error' => 'Alamat tidak boleh kosong'])->setStatusCode(400);
        }

        // Caching key berdasarkan MD5 dari alamat
        $cacheKey = 'geocode_' . md5(strtolower(trim($address)));
        if ($cachedResult = cache($cacheKey)) {
            return $this->response->setJSON($cachedResult);
        }

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get('https://nominatim.openstreetmap.org/search', [
                'query' => [
                    'q'            => $address,
                    'format'       => 'json',
                    'limit'        => 1,
                    'countrycodes' => 'id',
                    'viewbox'      => '110.30,-6.90,110.50,-7.08', // Prioritaskan area Semarang
                ],
                'headers' => [
                    'User-Agent' => 'KulinerReview/1.0 (kuliner-review-app)',
                ],
                'timeout' => 5, // batasi waktu tunggu agar tidak hang
            ]);

            $data = json_decode($response->getBody(), true);

            if (empty($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Koordinat tidak ditemukan untuk alamat tersebut.',
                ]);
            }

            $result = [
                'success'      => true,
                'latitude'     => $data[0]['lat'],
                'longitude'    => $data[0]['lon'],
                'display_name' => $data[0]['display_name'],
            ];

            // Simpan ke cache selama 30 hari
            cache()->save($cacheKey, $result, 30 * DAY);

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghubungi layanan geocoding: ' . $e->getMessage(),
            ])->setStatusCode(502);
        }
    }

    /**
     * Tandai tempat sebagai tutup permanen oleh contributor.
     */
    public function markClosed(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place || $place['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $placeModel->update($id, ['is_closed' => 1]);

        return redirect()->back()->with('success', 'Tempat ditandai sebagai tutup permanen. Akan divalidasi oleh admin.');
    }
}