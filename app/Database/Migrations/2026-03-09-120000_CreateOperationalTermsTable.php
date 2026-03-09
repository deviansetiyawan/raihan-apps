<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperationalTermsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'term' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'definition' => [
                'type' => 'TEXT',
            ],
            'reference_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'link_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'regulation_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
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
        $this->forge->addKey('term');
        $this->forge->addKey('reference_code');
        $this->forge->addKey('regulation_id');
        $this->forge->addForeignKey('regulation_id', 'regulations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('operational_terms');
    }

    public function down()
    {
        $this->forge->dropTable('operational_terms');
    }
}
