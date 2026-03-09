<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentVersionModel extends Model
{
    protected $table = 'document_versions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'document_id',
        'version',
        'file_url',
        'effective_date',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
