<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperationalTermSeeder extends Seeder
{
    public function run()
    {
        $jsonPath = APPPATH . 'Database/Seeds/data/operational_terms.json';

        if (! is_file($jsonPath)) {
            throw new \RuntimeException('Dataset not found: ' . $jsonPath);
        }

        $payload = json_decode((string) file_get_contents($jsonPath), true);
        $records = $payload['records'] ?? [];

        if ($records === []) {
            return;
        }

        $regulations = $this->db->table('regulations')->select('id, code, file_name')->get()->getResultArray();

        $exactCodeMap = [];
        $normCodeMap = [];
        $exactFileMap = [];
        $normFileMap = [];
        $regList = [];

        foreach ($regulations as $row) {
            $id = (int) ($row['id'] ?? 0);
            $code = trim((string) ($row['code'] ?? ''));
            $file = trim((string) ($row['file_name'] ?? ''));

            $normCode = $this->normalizeText($code);
            $normFile = $this->normalizeText(pathinfo($file, PATHINFO_FILENAME));

            if ($code !== '' && ! isset($exactCodeMap[$code])) {
                $exactCodeMap[$code] = $id;
            }
            if ($normCode !== '' && ! isset($normCodeMap[$normCode])) {
                $normCodeMap[$normCode] = $id;
            }
            if ($file !== '' && ! isset($exactFileMap[$file])) {
                $exactFileMap[$file] = $id;
            }
            if ($normFile !== '' && ! isset($normFileMap[$normFile])) {
                $normFileMap[$normFile] = $id;
            }

            $regList[] = [
                'id'        => $id,
                'norm_code' => $normCode,
                'norm_file' => $normFile,
            ];
        }

        $now = date('Y-m-d H:i:s');
        $rows = [];

        foreach ($records as $record) {
            $referenceCode = trim((string) ($record['reference_code'] ?? ''));
            $linkLabel = trim((string) ($record['link_label'] ?? ''));

            $regulationId = $this->resolveRegulationId(
                $referenceCode,
                $linkLabel,
                $exactCodeMap,
                $normCodeMap,
                $exactFileMap,
                $normFileMap,
                $regList
            );

            $rows[] = [
                'term'           => trim((string) ($record['term'] ?? '')),
                'definition'     => trim((string) ($record['definition'] ?? '')),
                'reference_code' => $referenceCode !== '' ? $referenceCode : null,
                'link_label'     => $linkLabel !== '' ? $linkLabel : null,
                'regulation_id'  => $regulationId,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('operational_terms')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $this->db->table('operational_terms')->insertBatch($rows, null, 200);
    }

    private function resolveRegulationId(
        string $referenceCode,
        string $linkLabel,
        array $exactCodeMap,
        array $normCodeMap,
        array $exactFileMap,
        array $normFileMap,
        array $regList
    ): ?int {
        if ($referenceCode !== '' && isset($exactCodeMap[$referenceCode])) {
            return $exactCodeMap[$referenceCode];
        }

        $normRef = $this->normalizeText($referenceCode);
        if ($normRef !== '' && isset($normCodeMap[$normRef])) {
            return $normCodeMap[$normRef];
        }

        if ($normRef !== '') {
            foreach ($regList as $reg) {
                if ($reg['norm_code'] === '') {
                    continue;
                }
                if (str_contains($normRef, $reg['norm_code']) || str_contains($reg['norm_code'], $normRef)) {
                    return (int) $reg['id'];
                }
            }
        }

        if ($linkLabel !== '' && isset($exactFileMap[$linkLabel])) {
            return $exactFileMap[$linkLabel];
        }

        $normLink = $this->normalizeText(pathinfo($linkLabel, PATHINFO_FILENAME));
        if ($normLink !== '' && isset($normFileMap[$normLink])) {
            return $normFileMap[$normLink];
        }

        if ($normLink !== '') {
            foreach ($regList as $reg) {
                if ($reg['norm_file'] === '') {
                    continue;
                }
                if (str_contains($normLink, $reg['norm_file']) || str_contains($reg['norm_file'], $normLink)) {
                    return (int) $reg['id'];
                }
            }
        }

        return null;
    }

    private function normalizeText(string $value): string
    {
        return strtoupper((string) preg_replace('/[^A-Z0-9]/i', '', $value));
    }
}
