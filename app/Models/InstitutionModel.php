<?php

namespace App\Models;

use CodeIgniter\Model;

class InstitutionModel extends Model
{
    protected $table = 'institutions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'source',
    ];

    protected $useTimestamps = true;
}
