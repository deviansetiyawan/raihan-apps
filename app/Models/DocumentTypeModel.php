<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentTypeModel extends Model
{
    protected $table = 'document_types';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'source',
    ];

    protected $useTimestamps = true;
}
