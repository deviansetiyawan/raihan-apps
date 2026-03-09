<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationalTermModel extends Model
{
    protected $table = 'operational_terms';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'term',
        'definition',
        'reference_code',
        'link_label',
        'regulation_id',
    ];

    protected $useTimestamps = true;

    public function getPaginatedTerms(array $filters = [], int $perPage = 10): array
    {
        $builder = $this->select('operational_terms.*, regulations.code AS regulation_code, regulations.title AS regulation_title, regulations.file_path, regulations.external_link, workflows.name AS workflow_name, institutions.name AS institution_name')
            ->join('regulations', 'regulations.id = operational_terms.regulation_id', 'left')
            ->join('workflows', 'workflows.id = regulations.workflow_id', 'left')
            ->join('institutions', 'institutions.id = regulations.institution_id', 'left');

        if (! empty($filters['workflow_id'])) {
            $builder->where('regulations.workflow_id', (int) $filters['workflow_id']);
        }

        if (! empty($filters['institution_id'])) {
            $builder->where('regulations.institution_id', (int) $filters['institution_id']);
        }

        if (! empty($filters['q'])) {
            $q = trim((string) $filters['q']);
            $builder->groupStart()
                ->like('operational_terms.term', $q)
                ->orLike('operational_terms.definition', $q)
                ->orLike('operational_terms.reference_code', $q)
                ->orLike('regulations.code', $q)
                ->orLike('regulations.title', $q)
                ->groupEnd();
        }

        return $builder->orderBy('operational_terms.term', 'ASC')->paginate($perPage);
    }
}
