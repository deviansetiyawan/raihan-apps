<?php

namespace App\Models;

use CodeIgniter\Model;

class RegulationVersionModel extends Model
{
    protected $table            = 'regulation_versions';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields    = [
        'regulation_id',
        'version_number',
        'description',
        'workflow_id',
        'file_path',
        'is_active'
    ];

    public function deactivateOldVersions($regulationId)
    {
        return $this->where('regulation_id', $regulationId)
                    ->set(['is_active' => 0])
                    ->update();
    }

    public function getNextVersionNumber($regulationId)
    {
        $lastVersion = $this->where('regulation_id', $regulationId)
                            ->orderBy('version_number', 'DESC')
                            ->first();

        if (!$lastVersion) {
            return 1;
        }

        return $lastVersion['version_number'] + 1;
    }

}
