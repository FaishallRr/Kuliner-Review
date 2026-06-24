<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Create Place_Tags Table (Pivot)
 * Relasi many-to-many antara places dan tags.
 */
class CreatePlaceTagsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'place_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tag_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addKey(['place_id', 'tag_id'], true);
        $this->forge->addKey('place_id');
        $this->forge->addKey('tag_id');

        $this->forge->addForeignKey('place_id', 'places', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('place_tags');
    }

    public function down(): void
    {
        $this->forge->dropTable('place_tags');
    }
}