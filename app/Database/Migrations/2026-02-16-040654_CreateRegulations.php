<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegulations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'document_type_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'institution_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('document_type_id', 'document_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('institution_id', 'institutions', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('regulations');
    }

    public function down()
    {
        $this->forge->dropTable('regulations');
    }

}
