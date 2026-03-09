<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DocumentVersionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'document_id' => 1,
                'version' => 1,
                'file_url' => '/documents/doc001_v1.pdf',
                'effective_date' => '2026-01-01',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'document_id' => 1,
                'version' => 2,
                'file_url' => '/documents/doc001_v2.pdf',
                'effective_date' => '2026-06-01',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('document_versions')->insertBatch($data);
    }
}
