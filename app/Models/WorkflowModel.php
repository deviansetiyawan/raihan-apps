<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkflowModel extends Model
{
    protected $table            = 'workflows';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'name'
    ];
}
