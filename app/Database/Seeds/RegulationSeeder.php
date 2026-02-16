<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegulationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Peraturan Pengadaan Barang',
                'document_type_id' => 1,
                'institution_id' => 1,
            ],
            [
                'title' => 'Peraturan SDM',
                'document_type_id' => 2,
                'institution_id' => 2,
            ],
        ];
        
        $this->db->table('regulations')->insertBatch($data);
        
    }
}
