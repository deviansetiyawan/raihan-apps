<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Peraturan Direksi', 'code' => 'PERDIR'],
            ['name' => 'Surat Keputusan', 'code' => 'SK'],
            ['name' => 'Peraturan Komisaris', 'code' => 'PERKOM'],
        ];
        
        $this->db->table('document_types')->insertBatch($data);
        
    }
}
