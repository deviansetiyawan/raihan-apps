<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Draft'],
            ['name' => 'Review'],
            ['name' => 'Approved'],
            ['name' => 'Published'],
        ];
        
        $this->db->table('workflows')->insertBatch($data);
        
    }
}
