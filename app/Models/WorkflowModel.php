<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkflowModel extends Model
{
    protected $table = 'workflows';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'source',
    ];

    protected $useTimestamps = true;
}
