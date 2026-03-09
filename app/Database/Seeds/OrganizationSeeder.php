<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Ministry of Finance',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ministry of Education',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'National Statistics Agency',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('organizations')->insertBatch($data);
    }
}
