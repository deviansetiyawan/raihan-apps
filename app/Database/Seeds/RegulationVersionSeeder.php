<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegulationVersionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'regulation_id' => 1,
                'version_number' => 1,
                'description' => 'Versi Awal',
                'workflow_id' => 4, // Published
                'file_path' => 'uploads/reg_v1.pdf',
                'is_active' => 1,
            ],
            [
                'regulation_id' => 1,
                'version_number' => 2,
                'description' => 'Revisi Pertama',
                'workflow_id' => 4,
                'file_path' => 'uploads/reg_v2.pdf',
                'is_active' => 0,
            ],
        ];
        
        $this->db->table('regulation_versions')->insertBatch($data);
        
    }
}
