<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * AuthController: Menangani login, register, dan logout pengguna.
 */
class AuthController extends BaseController
{
    /**
     * Menampilkan form login.
     */
    public function loginForm()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', ['title' => 'Masuk']);
    }

    /**
     * Proses autentikasi login.
     */
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email dan password wajib diisi dengan benar.');
        }

        $userModel = new UserModel();
        $user = $userModel->verifyLogin(
            $this->request->getPost('email'),
            $this->request->getPost('password')
        );

        if (! $user) {
            return redirect()->to('/login')->withInput()->with('error', 'Email atau password salah.');
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'full_name'  => $user['full_name'],
            'role'       => $user['role'],
        ]);

        $intendedUrl = session()->get('intended_url');

        if ($intendedUrl) {
            session()->remove('intended_url');
            return redirect()->to($intendedUrl)->with('success', "Selamat datang, {$user['full_name']}!");
        }

        if ($user['role'] === 'admin') {
            return redirect()->to('/admin')->with('success', "Selamat datang, Admin!");
        }

        return redirect()->to('/dashboard')->with('success', "Selamat datang, {$user['full_name']}!");
    }

    /**
     * Menampilkan form registrasi.
     */
    public function registerForm()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register', ['title' => 'Daftar']);
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register()
    {
        $rules = [
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'full_name' => 'required|min_length[2]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data registrasi tidak valid. Periksa kembali form Anda.');
        }

        $userModel = new UserModel();
        $userModel->insert([
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name'),
            'role'      => 'contributor',
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Proses logout.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah keluar.');
    }
}