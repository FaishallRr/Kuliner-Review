<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\CategoryModel;
use App\Models\TagModel;

/**
 * HomeController: Halaman utama situs.
 */
class Home extends BaseController
{
    /**
     * Menampilkan halaman beranda dengan daftar tempat kuliner yang di-approve.
     */
    public function index(): string
    {
        $placeModel = new PlaceModel();
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();

        $data = [
            'title'       => 'Beranda',
            'places'      => $placeModel->getApprovedWithRelations(),
            'categories'  => $categoryModel->findAll(),
            'tags'        => $tagModel->getWithPlaceCount(),
        ];

        return view('home/index', $data);
    }

    /**
     * Menyajikan file upload dari writable/uploads.
     */
    public function serveUpload(string $filename)
    {
        // sanitize filename to prevent path traversal
        $safe = basename($filename);
        $allowedExt = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];
        $ext = strtolower(pathinfo($safe, PATHINFO_EXTENSION));

        if (! in_array($ext, $allowedExt, true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . 'uploads/' . $safe;

        if (! file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mimeType = mime_content_type($path) ?: 'application/octet-stream';
        return $this->response->setHeader('Content-Type', $mimeType)->setBody(file_get_contents($path));
    }
}