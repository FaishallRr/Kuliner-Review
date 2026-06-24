<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel: Menangani logika data tabel users.
 * Meliputi autentikasi, hash password, dan query berdasarkan role.
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'full_name',
        'role',
        'avatar',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'     => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password'  => 'required|min_length[6]',
        'full_name' => 'required|min_length[2]|max_length[100]',
        'role'      => 'required|in_list[admin,contributor]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username wajib diisi.',
            'is_unique'   => 'Username sudah digunakan.',
            'min_length'  => 'Username minimal 3 karakter.',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email'  => 'Format email tidak valid.',
            'is_unique'    => 'Email sudah terdaftar.',
        ],
        'password' => [
            'required'   => 'Password wajib diisi.',
            'min_length'  => 'Password minimal 6 karakter.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash password otomatis sebelum insert/update.
     */
    protected function hashPassword(array $data): array
    {
        if (! isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash(
            $data['data']['password'],
            PASSWORD_DEFAULT
        );

        return $data;
    }

    /**
     * Verifikasi kredensial login pengguna.
     */
    public function verifyLogin(string $email, string $password): ?array
    {
        $user = $this->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    /**
     * Mengambil pengguna berdasarkan peran (role).
     */
    public function getByRole(string $role): array
    {
        return $this->where('role', $role)->findAll();
    }
}