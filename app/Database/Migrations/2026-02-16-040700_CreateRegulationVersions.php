<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegulationVersions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'regulation_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'version_number' => [
                'type' => 'INT',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'workflow_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('regulation_id', 'regulations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('workflow_id', 'workflows', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('regulation_versions');
    }

    public function down()
    {
        $this->forge->dropTable('regulation_versions');
    }

}
