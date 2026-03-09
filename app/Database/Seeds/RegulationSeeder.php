<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegulationSeeder extends Seeder
{
    public function run()
    {
        $jsonPath = APPPATH . 'Database/Seeds/data/regulations.json';

        if (! is_file($jsonPath)) {
            throw new \RuntimeException('Dataset not found: ' . $jsonPath);
        }

        $payload = json_decode((string) file_get_contents($jsonPath), true);
        $records = $payload['records'] ?? [];

        if ($records === []) {
            return;
        }

        $now = date('Y-m-d H:i:s');

        $institutions = [];
        $workflows = [];
        $types = [];

        foreach ($records as $record) {
            $source = $record['source'] ?? 'internal';

            $institutionName = $this->cleanLabel((string) ($record['institution'] ?? ''));
            if ($institutionName !== '') {
                $institutions[$source . '|' . $this->normalizeKey($institutionName)] = [
                    'name'       => $institutionName,
                    'source'     => $source,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $workflowName = $this->cleanLabel((string) ($record['workflow'] ?? ''));
            if ($workflowName !== '') {
                $workflows[$source . '|' . $this->normalizeKey($workflowName)] = [
                    'name'       => $workflowName,
                    'source'     => $source,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $typeName = $this->cleanLabel((string) ($record['document_type'] ?? ''));
            if ($typeName !== '') {
                $types[$source . '|' . $this->normalizeKey($typeName)] = [
                    'name'       => $typeName,
                    'source'     => $source,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('regulations')->truncate();
        $this->db->table('document_types')->truncate();
        $this->db->table('workflows')->truncate();
        $this->db->table('institutions')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $this->insertBatch('institutions', array_values($institutions));
        $this->insertBatch('workflows', array_values($workflows));
        $this->insertBatch('document_types', array_values($types));

        $institutionMap = $this->makeMap('institutions');
        $workflowMap = $this->makeMap('workflows');
        $typeMap = $this->makeMap('document_types');

        $rows = [];

        foreach ($records as $record) {
            $source = $record['source'] ?? 'internal';
            $institutionKey = $source . '|' . $this->normalizeKey((string) ($record['institution'] ?? ''));
            $workflowKey = $source . '|' . $this->normalizeKey((string) ($record['workflow'] ?? ''));
            $typeKey = $source . '|' . $this->normalizeKey((string) ($record['document_type'] ?? ''));

            $rows[] = [
                'source'           => $source,
                'institution_id'   => $institutionMap[$institutionKey] ?? null,
                'workflow_id'      => $workflowMap[$workflowKey] ?? null,
                'document_type_id' => $typeMap[$typeKey] ?? null,
                'code'             => trim((string) ($record['code'] ?? '-')),
                'title'            => trim((string) ($record['title'] ?? '-')),
                'revision'         => $record['revision'] !== '' ? ($record['revision'] ?? null) : null,
                'effective_date'   => $record['effective_date'] ?? null,
                'status'           => $record['status'] ?? 'Berlaku',
                'file_name'        => $record['file_name'] ?? null,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        $this->insertBatch('regulations', $rows, 500);
    }

    private function makeMap(string $table): array
    {
        $rows = $this->db->table($table)->select('id, source, name')->get()->getResultArray();
        $map = [];

        foreach ($rows as $row) {
            $map[$row['source'] . '|' . $this->normalizeKey((string) $row['name'])] = (int) $row['id'];
        }

        return $map;
    }

    private function insertBatch(string $table, array $rows): void
    {
        if ($rows === []) {
            return;
        }

        $this->db->table($table)->insertBatch($rows, null, 500);
    }

    private function cleanLabel(string $value): string
    {
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? '';
        return $value;
    }

    private function normalizeKey(string $value): string
    {
        return strtolower($this->cleanLabel($value));
    }
}
