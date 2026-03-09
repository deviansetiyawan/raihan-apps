<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'document_code' => 'DOC-001',
                'document_name' => 'Financial Reporting SOP',
                'organization_id' => 1,
                'document_type_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'document_code' => 'DOC-002',
                'document_name' => 'Education Quality Guideline',
                'organization_id' => 2,
                'document_type_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('documents')->insertBatch($data);
    }
}
