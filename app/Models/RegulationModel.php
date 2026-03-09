<?php

namespace App\Models;

use CodeIgniter\Model;

class RegulationModel extends Model
{
    protected $table = 'regulations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'source',
        'institution_id',
        'workflow_id',
        'document_type_id',
        'code',
        'title',
        'revision',
        'effective_date',
        'status',
        'file_name',
        'file_path',
        'external_link',
        'notes',
    ];

    protected $useTimestamps = true;

    public function getSummary(array $filters = []): array
    {
        $row = $this->baseBuilder($filters)
            ->select('COUNT(regulations.id) AS total_documents')
            ->select("SUM(CASE WHEN regulations.status = 'Berlaku' THEN 1 ELSE 0 END) AS active_documents", false)
            ->select("SUM(CASE WHEN regulations.status = 'Tidak Berlaku' THEN 1 ELSE 0 END) AS inactive_documents", false)
            ->get()
            ->getRowArray();

        return [
            'total_documents'   => (int) ($row['total_documents'] ?? 0),
            'active_documents'  => (int) ($row['active_documents'] ?? 0),
            'inactive_documents' => (int) ($row['inactive_documents'] ?? 0),
        ];
    }

    public function getStatusChart(array $filters = []): array
    {
        return $this->baseBuilder($filters)
            ->select('regulations.status, COUNT(regulations.id) AS total')
            ->groupBy('regulations.status')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getTypeChart(array $filters = []): array
    {
        return $this->baseBuilder($filters)
            ->select('document_types.name, COUNT(regulations.id) AS total')
            ->groupBy('document_types.name')
            ->orderBy('total', 'DESC')
            ->limit(4)
            ->get()
            ->getResultArray();
    }

    public function getLatestDocuments(array $filters = []): array
    {
        return $this->baseBuilder($filters)
            ->select('regulations.id, regulations.code, regulations.title, regulations.revision, regulations.status, regulations.file_name, regulations.file_path, regulations.external_link, regulations.effective_date')
            ->orderBy('regulations.effective_date', 'DESC')
            ->orderBy('regulations.id', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();
    }

    public function getAvailableYears(): array
    {
        $rows = $this->select('YEAR(effective_date) AS year')
            ->where('effective_date IS NOT NULL', null, false)
            ->groupBy('YEAR(effective_date)')
            ->orderBy('YEAR(effective_date)', 'DESC')
            ->findAll();

        return array_values(array_filter(array_map(static fn ($row) => (int) ($row['year'] ?? 0), $rows)));
    }

    private function baseBuilder(array $filters)
    {
        $builder = $this->db->table($this->table)
            ->join('document_types', 'document_types.id = regulations.document_type_id', 'left')
            ->join('workflows', 'workflows.id = regulations.workflow_id', 'left')
            ->join('institutions', 'institutions.id = regulations.institution_id', 'left');

        if (! empty($filters['document_type_id'])) {
            $builder->where('regulations.document_type_id', (int) $filters['document_type_id']);
        }

        if (! empty($filters['workflow_id'])) {
            $builder->where('regulations.workflow_id', (int) $filters['workflow_id']);
        }

        if (! empty($filters['institution_id'])) {
            $builder->where('regulations.institution_id', (int) $filters['institution_id']);
        }

        if (! empty($filters['period_start']) && ! empty($filters['period_end'])) {
            $builder->where('YEAR(regulations.effective_date) >=', (int) $filters['period_start']);
            $builder->where('YEAR(regulations.effective_date) <=', (int) $filters['period_end']);
        }

        return $builder;
    }
}

