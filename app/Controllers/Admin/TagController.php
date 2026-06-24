<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TagModel;

/**
 * TagController (Admin): CRUD tag tempat kuliner.
 */
class TagController extends BaseController
{
    /**
     * Daftar semua tag.
     */
    public function index(): string
    {
        $tagModel = new TagModel();

        $data = [
            'title' => 'Kelola Tag',
            'tags'  => $tagModel->getWithPlaceCount(),
        ];

        return view('admin/tags/index', $data);
    }

    /**
     * Form tambah tag.
     */
    public function create(): string
    {
        return view('admin/tags/create', ['title' => 'Tambah Tag']);
    }

    /**
     * Simpan tag baru.
     */
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[50]|is_unique[tags.name]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tag tidak valid.');
        }

        $tagModel = new TagModel();
        $tagModel->insert([
            'name' => $this->request->getPost('name'),
            'slug' => url_title($this->request->getPost('name'), '-', true),
        ]);

        return redirect()->to('/admin/tags')->with('success', 'Tag berhasil ditambahkan.');
    }

    /**
     * Form edit tag.
     */
    public function edit(int $id): string
    {
        $tagModel = new TagModel();
        $tag = $tagModel->find($id);

        if (! $tag) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/tags/edit', [
            'title' => 'Edit Tag',
            'tag'   => $tag,
        ]);
    }

    /**
     * Update tag.
     */
    public function update(int $id)
    {
        $rules = [
            'name' => "required|min_length[2]|max_length[50]|is_unique[tags.name,id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tag tidak valid.');
        }

        $tagModel = new TagModel();
        $tagModel->update($id, [
            'name' => $this->request->getPost('name'),
            'slug' => url_title($this->request->getPost('name'), '-', true),
        ]);

        return redirect()->to('/admin/tags')->with('success', 'Tag berhasil diperbarui.');
    }

    /**
     * Hapus tag.
     */
    public function delete(int $id)
    {
        $tagModel = new TagModel();
        $tagModel->delete($id);

        return redirect()->to('/admin/tags')->with('success', 'Tag berhasil dihapus.');
    }
}