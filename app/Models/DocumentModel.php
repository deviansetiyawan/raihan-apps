<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'document_code',
        'document_name',
        'organization_id',
        'document_type_id',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getDashboardSummary()
    {
        return $this->db->query("
            SELECT 
                COUNT(DISTINCT d.id) as total_documents,
                SUM(CASE WHEN dv.is_active = 1 THEN 1 ELSE 0 END) as active_documents,
                SUM(CASE WHEN dv.is_active = 0 THEN 1 ELSE 0 END) as inactive_documents
            FROM documents d
            LEFT JOIN document_versions dv 
            ON dv.document_id = d.id
        ")->getRow();
    }

    public function getStatusChart()
    {
        return $this->db->query("
            SELECT 
                CASE 
                    WHEN is_active = 1 THEN 'Active'
                    ELSE 'Inactive'
                END as status,
                COUNT(*) as total
            FROM document_versions
            GROUP BY is_active
        ")->getResult();
    }

    public function getDocumentTypeChart()
    {
        return $this->db->query("
            SELECT 
                dt.name,
                COUNT(d.id) as total
            FROM documents d
            JOIN document_types dt
            ON dt.id = d.document_type_id
            GROUP BY dt.name
        ")->getResult();
    }

    public function getLatestDocuments()
    {
        return $this->db->query("
            SELECT 
                d.document_code,
                d.document_name,
                dv.version,
                dv.is_active,
                dv.file_url
            FROM documents d
            JOIN document_versions dv
            ON dv.document_id = d.id
            WHERE dv.is_active = 1
            ORDER BY dv.effective_date DESC
            LIMIT 5
        ")->getResult();
    }
}
