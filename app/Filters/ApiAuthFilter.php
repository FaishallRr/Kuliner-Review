<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getServer('HTTP_AUTHORIZATION') ?? $request->getServer('REDIRECT_HTTP_AUTHORIZATION');

        if (! $header) {
            $response = service('response');
            return $response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Authorization header required']);
        }

        $parts = explode(' ', $header);
        $token = end($parts);

        $userModel = new UserModel();
        $user = $userModel->verifyApiToken($token);

        if (! $user) {
            $response = service('response');
            return $response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Invalid or expired token']);
        }

        // set session to allow controllers reuse session-based logic (lightweight)
        session()->set('api_user_id', $user['id']);
        session()->set('api_user', $user);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
        // no-op
    }
}
