<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $this->call('DocumentTypeSeeder');
        $this->call('WorkflowSeeder');
        $this->call('InstitutionSeeder');
        $this->call('RegulationSeeder');
        $this->call('RegulationVersionSeeder');
    }
}
