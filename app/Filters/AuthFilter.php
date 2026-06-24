<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter: Memproteksi route agar hanya bisa diakses
 * oleh pengguna yang sudah login (memiliki session user).
 */
class AuthFilter implements FilterInterface
{
    /**
     * Cek apakah user sudah login sebelum request diproses.
     * Jika belum, redirect ke halaman login dengan flash message.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
            $session->set('intended_url', current_url());

            return redirect()->to('/login');
        }
    }

    /**
     * Tidak ada aksi setelah request diproses.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void {}
}
