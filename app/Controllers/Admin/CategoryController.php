<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

/**
 * CategoryController (Admin): CRUD kategori tempat kuliner.
 */
class CategoryController extends BaseController
{
    /**
     * Daftar semua kategori.
     */
    public function index(): string
    {
        $categoryModel = new CategoryModel();

        $data = [
            'title'       => 'Kelola Kategori',
            'categories'  => $categoryModel->getWithPlaceCount(),
        ];

        return view('admin/categories/index', $data);
    }

    /**
     * Form tambah kategori.
     */
    public function create(): string
    {
        return view('admin/categories/create', ['title' => 'Tambah Kategori']);
    }

    /**
     * Simpan kategori baru.
     */
    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[50]|is_unique[categories.name]',
            'description' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data kategori tidak valid.');
        }

        $categoryModel = new CategoryModel();
        $categoryModel->insert([
            'name'        => $this->request->getPost('name'),
            'slug'        => url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Form edit kategori.
     */
    public function edit(int $id): string
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/categories/edit', [
            'title'     => 'Edit Kategori',
            'category'  => $category,
        ]);
    }

    /**
     * Update kategori.
     */
    public function update(int $id)
    {
        $rules = [
            'name'        => "required|min_length[2]|max_length[50]|is_unique[categories.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data kategori tidak valid.');
        }

        $categoryModel = new CategoryModel();
        $categoryModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'slug'        => url_title($this->request->getPost('name'), '-', true),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function delete(int $id)
    {
        $categoryModel = new CategoryModel();
        $categoryModel->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}