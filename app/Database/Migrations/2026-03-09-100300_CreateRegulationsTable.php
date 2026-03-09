<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegulationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'internal',
            ],
            'institution_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'workflow_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'document_type_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'title' => [
                'type' => 'TEXT',
            ],
            'revision' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'effective_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Berlaku',
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'external_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('source');
        $this->forge->addKey('status');
        $this->forge->addKey('effective_date');
        $this->forge->addKey('institution_id');
        $this->forge->addKey('workflow_id');
        $this->forge->addKey('document_type_id');

        $this->forge->addForeignKey('institution_id', 'institutions', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('workflow_id', 'workflows', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('document_type_id', 'document_types', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('regulations');
    }

    public function down()
    {
        $this->forge->dropTable('regulations');
    }
}
