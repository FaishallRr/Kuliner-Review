<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * UserController (Admin): Kelola pengguna.
 */
class UserController extends BaseController
{
    /**
     * Daftar semua pengguna.
     */
    public function index(): string
    {
        $userModel = new UserModel();

        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $userModel->orderBy('id', 'ASC')->findAll(),
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Hapus pengguna (kecuali diri sendiri).
     */
    public function delete(int $id)
    {
        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }
}