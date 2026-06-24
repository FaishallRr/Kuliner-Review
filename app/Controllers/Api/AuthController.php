<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return $this->fail('Email atau password salah', 401);
        }

        // generate token and store hashed
        $rawToken = bin2hex(random_bytes(40));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
        $userModel->setApiToken((int)$user['id'], $rawToken, $expiresAt);

        return $this->respond([
            'success' => true,
            'token' => $rawToken,
            'expires_at' => $expiresAt,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
            ],
        ]);
    }

    public function logout()
    {
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (! $header) {
            return $this->fail('Authorization header required', 401);
        }

        $parts = explode(' ', $header);
        $token = end($parts);

        $userModel = new UserModel();
        $user = $userModel->verifyApiToken($token);

        if (! $user) {
            return $this->fail('Invalid token', 401);
        }

        $userModel->update($user['id'], ['api_token' => null, 'api_token_expires_at' => null]);

        return $this->respond(['success' => true, 'message' => 'Logged out']);
    }
}
