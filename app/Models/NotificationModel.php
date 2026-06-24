<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * NotificationModel: Menangani logika data tabel notifications.
 * Mengelola notifikasi status moderasi untuk contributor.
 */
class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey      = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'title',
        'message',
        'is_read',
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps   = true;
    protected $dateFormat      = 'datetime';
    protected $createdField    = 'created_at';
    protected $updatedField    = '';

    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'title'   => 'required|min_length[2]|max_length[150]',
        'message' => 'required',
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul notifikasi wajib diisi.',
        ],
        'message' => [
            'required' => 'Pesan notifikasi wajib diisi.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;

    /**
     * Mengambil notifikasi yang belum dibaca oleh pengguna.
     */
    public function getUnread(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Menghitung jumlah notifikasi yang belum dibaca.
     */
    public function countUnread(int $userId): int
    {
        return $this->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /**
     * Menandai semua notifikasi pengguna sebagai sudah dibaca.
     */
    public function markAllAsRead(int $userId): bool
    {
        return $this->where('user_id', $userId)
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update() !== false;
    }

    /**
     * Membuat notifikasi status moderasi tempat.
     */
    public function createModerationNotification(int $userId, string $status, string $placeName, ?string $rejectionNote = null): bool
    {
        if ($status === 'approved') {
            $title   = 'Tempat Disetujui';
            $message = "Tempat kuliner \"{$placeName}\" telah disetujui admin dan sekarang tampil publik.";
        } else {
            $title   = 'Tempat Ditolak';
            $message = "Tempat kuliner \"{$placeName}\" ditolak admin.";
            if ($rejectionNote) {
                $message .= " Alasan: {$rejectionNote}";
            }
        }

        return $this->insert([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'is_read' => 0,
        ]) !== false;
    }
}