<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentTypes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    
        $this->forge->addKey('id', true);
        $this->forge->createTable('document_types');
    
    }

    public function down()
    {
        $this->forge->dropTable('document_types');
    }
}
