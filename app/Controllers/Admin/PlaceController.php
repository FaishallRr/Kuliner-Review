<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlaceModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\PlaceTagModel;
use App\Models\NotificationModel;

/**
 * PlaceController (Admin): CRUD penuh tempat kuliner + moderasi.
 */
class PlaceController extends BaseController
{
    /**
     * Daftar semua tempat kuliner.
     */
    public function index(): string
    {
        $placeModel = new PlaceModel();

        $places = $placeModel->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->orderBy('places.created_at', 'DESC')
            ->paginate(20);

        $data = [
            'title'   => 'Kelola Tempat Kuliner',
            'places'  => $places,
            'pager'   => $placeModel->pager,
        ];

        return view('admin/places/index', $data);
    }

    /**
     * Form tambah tempat kuliner oleh admin.
     */
    public function create(): string
    {
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();

        $data = [
            'title'      => 'Tambah Tempat Kuliner',
            'categories' => $categoryModel->findAll(),
            'tags'       => $tagModel->findAll(),
        ];

        return view('admin/places/create', $data);
    }

    /**
     * Simpan tempat kuliner baru oleh admin (langsung approved).
     */
    public function store()
    {
        $rules = [
            'name'          => 'required|min_length[2]|max_length[150]',
            'category_id'   => 'required|is_natural_no_zero',
            'address'       => 'required|min_length[5]|max_length[255]',
            'description'   => 'permit_empty|max_length[1000]',
            'status'        => 'permit_empty|in_list[pending,approved,rejected]',
            'image'         => 'permit_empty|is_image[image]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali form Anda.');
        }

        $imagePath = $this->uploadImage();

        $placeModel = new PlaceModel();
        $placeId = $placeModel->insert([
            'user_id'       => session()->get('user_id'),
            'category_id'   => $this->request->getPost('category_id'),
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'address'       => $this->request->getPost('address'),
            'latitude'      => $this->request->getPost('latitude') ?: null,
            'longitude'     => $this->request->getPost('longitude') ?: null,
            'image'         => $imagePath,
            'status'        => $this->request->getPost('status') ?: 'approved',
        ]);

        if ($placeId && $tagIds = $this->request->getPost('tags')) {
            $placeTagModel = new PlaceTagModel();
            $placeTagModel->addTags($placeId, $tagIds);
        }

        return redirect()->to('/admin/places')->with('success', 'Tempat kuliner berhasil ditambahkan.');
    }

    /**
     * Form edit tempat kuliner oleh admin.
     */
    public function edit(int $id): string
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();
        $placeTagModel = new PlaceTagModel();

        $currentTags = array_column($placeTagModel->getTagNamesByPlaceId($id), 'slug');

        $data = [
            'title'       => 'Edit ' . $place['name'],
            'place'       => $place,
            'categories'  => $categoryModel->findAll(),
            'tags'         => $tagModel->findAll(),
            'currentTags'  => $currentTags,
        ];

        return view('admin/places/edit', $data);
    }

    /**
     * Update tempat kuliner oleh admin.
     */
    public function update(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            return redirect()->to('/admin/places')->with('error', 'Tempat kuliner tidak ditemukan.');
        }

        $rules = [
            'name'          => 'required|min_length[2]|max_length[150]',
            'category_id'   => 'required|is_natural_no_zero',
            'address'       => 'required|min_length[5]|max_length[255]',
            'description'   => 'permit_empty|max_length[1000]',
            'status'        => 'permit_empty|in_list[pending,approved,rejected]',
            'image'         => 'permit_empty|is_image[image]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali form Anda.');
        }

        $data = [
            'name'          => $this->request->getPost('name'),
            'category_id'   => $this->request->getPost('category_id'),
            'address'       => $this->request->getPost('address'),
            'description'   => $this->request->getPost('description'),
            'latitude'      => $this->request->getPost('latitude') ?: null,
            'longitude'     => $this->request->getPost('longitude') ?: null,
            'status'        => $this->request->getPost('status') ?: $place['status'],
        ];

        $imagePath = $this->uploadImage();
        if ($imagePath) {
            $data['image'] = $imagePath;
        }

        $placeModel->update($id, $data);

        $tagIds = $this->request->getPost('tags') ?? [];
        $placeTagModel = new PlaceTagModel();
        $placeTagModel->syncTags($id, $tagIds);

        return redirect()->to('/admin/places')->with('success', 'Tempat kuliner berhasil diperbarui.');
    }

    /**
     * Hapus tempat kuliner oleh admin.
     */
    public function delete(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            return redirect()->to('/admin/places')->with('error', 'Tempat kuliner tidak ditemukan.');
        }

        $placeModel->delete($id);

        return redirect()->to('/admin/places')->with('success', 'Tempat kuliner berhasil dihapus.');
    }

    /**
     * Daftar tempat kuliner dengan status pending.
     */
    public function pending(): string
    {
        $placeModel = new PlaceModel();

        $places = $placeModel->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->where('places.status', 'pending')
            ->orderBy('places.created_at', 'DESC')
            ->paginate(20);

        $data = [
            'title'   => 'Moderasi Tempat Kuliner',
            'places'  => $places,
            'pager'   => $placeModel->pager,
        ];

        return view('admin/places/pending', $data);
    }

    /**
     * Approve tempat kuliner.
     */
    public function approve(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            return redirect()->back()->with('error', 'Tempat kuliner tidak ditemukan.');
        }

        $placeModel->update($id, [
            'status'         => 'approved',
            'rejection_note' => null,
        ]);

        $notificationModel = new NotificationModel();
        $notificationModel->createModerationNotification(
            (int) $place['user_id'],
            'approved',
            (string) $place['name']
        );

        return redirect()->back()->with('success', 'Tempat kuliner berhasil disetujui.');
    }

    /**
     * Reject tempat kuliner beserta alasan.
     */
    public function reject(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            return redirect()->back()->with('error', 'Tempat kuliner tidak ditemukan.');
        }

        $rejectionNote = $this->request->getPost('rejection_note');

        if (! $rejectionNote) {
            return redirect()->back()->with('error', 'Alasan penolakan wajib diisi.');
        }

        $placeModel->update($id, [
            'status'         => 'rejected',
            'rejection_note' => $rejectionNote,
        ]);

        $notificationModel = new NotificationModel();
        $notificationModel->createModerationNotification(
            (int) $place['user_id'],
            'rejected',
            (string) $place['name'],
            $rejectionNote
        );

        return redirect()->back()->with('success', 'Tempat kuliner berhasil ditolak. Notifikasi telah dikirim ke contributor.');
    }

    /**
     * Approve status tutup permanen yang diajukan contributor.
     */
    public function approveClosed(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            return redirect()->back()->with('error', 'Tempat kuliner tidak ditemukan.');
        }

        $placeModel->update($id, ['is_closed' => 2]);

        return redirect()->back()->with('success', 'Tempat dikonfirmasi tutup permanen.');
    }

    /**
     * Tolak status tutup permanen (batal mark).
     */
    public function rejectClosed(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (! $place) {
            return redirect()->back()->with('error', 'Tempat kuliner tidak ditemukan.');
        }

        $placeModel->update($id, ['is_closed' => 0]);

        return redirect()->back()->with('success', 'Penandaan tutup permanen dibatalkan.');
    }

    /**
     * Upload dan resize foto tempat kuliner (max 800px).
     */
    private function uploadImage(): ?string
    {
        $file = $this->request->getFile('image');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
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
}