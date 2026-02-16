<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'PT Contoh Indonesia'],
            ['name' => 'Divisi Legal'],
            ['name' => 'Divisi Kepatuhan'],
        ];
        
        $this->db->table('institutions')->insertBatch($data);
        
    }
}
