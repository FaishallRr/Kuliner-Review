<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Add is_closed column to places table.
 * Kontributor bisa menandai tempat sebagai "tutup permanen"
 * yang akan divalidasi admin.
 */
class AddIsClosedToPlaces extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('places', [
            'is_closed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'       => 'rejection_note',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('places', 'is_closed');
    }
}