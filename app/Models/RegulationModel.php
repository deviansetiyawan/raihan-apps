<?php

namespace App\Models;

use CodeIgniter\Model;

class RegulationModel extends Model
{
    protected $table            = 'regulations';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'title',
        'document_type_id',
        'intitution_id'
    ];

    public function getWithActiveVersion()
    {
        return $this-> select('
            regulations.*,
            regulation_versions.version_number,
            regulation_versions.file_path,
            workflows.name as workflow_name
        ')
        ->join('regulation_versions', 'regulation_versions.regulation_id = regulations.id')
        ->join('workflows', 'workflows.id = regulation_versions.workflow_id')
        ->where('regulation_versions.is_active', 1)
        ->findAll();
    }
}
