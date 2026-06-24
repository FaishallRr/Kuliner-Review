<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleFilter: Memproteksi route agar hanya bisa diakses
 * oleh pengguna dengan peran tertentu (misal: admin).
 */
class RoleFilter implements FilterInterface
{
    /**
     * Cek apakah user memiliki role yang diizinkan.
     * Arguments berisi daftar role yang diperbolehkan.
     * Jika tidak sesuai, redirect ke halaman 403 dengan flash message.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');

            return redirect()->to('/login');
        }

        $userRole = $session->get('role');

        if (! empty($arguments) && ! in_array($userRole, $arguments, true)) {
            $session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman tersebut.');

            $response = service('response');
            $response->setStatusCode(403);
            $response->setBody(view('errors/html/error_403'));

            return $response;
        }
    }

    /**
     * Tidak ada aksi setelah request diproses.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void {}
}
