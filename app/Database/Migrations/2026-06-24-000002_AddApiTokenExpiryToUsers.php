<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApiTokenExpiryToUsers extends Migration
{
    public function up(): void
    {
        $fields = [
            'api_token_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'api_token_expires_at');
    }
}
