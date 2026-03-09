<?php

namespace App\Controllers;

use App\Models\DocumentTypeModel;
use App\Models\InstitutionModel;
use App\Models\RegulationModel;
use App\Models\WorkflowModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $regulationModel = new RegulationModel();
        $documentTypeModel = new DocumentTypeModel();
        $workflowModel = new WorkflowModel();
        $institutionModel = new InstitutionModel();

        $filters = [
            'document_type_id' => $this->request->getGet('document_type_id'),
            'workflow_id'      => $this->request->getGet('workflow_id'),
            'institution_id'   => $this->request->getGet('institution_id'),
            'status'           => $this->request->getGet('status'),
            'q'                => trim((string) $this->request->getGet('q')),
            'sort'             => $this->request->getGet('sort') ?: 'latest',
        ];

        $periodRange = $this->parsePeriod((string) $this->request->getGet('period'));
        $filters['period_start'] = $periodRange['start'];
        $filters['period_end'] = $periodRange['end'];

        $docsPage = max(1, (int) $this->request->getGet('docs_page'));
        $docsPerPage = 8;
        $latestDocsData = $regulationModel->getLatestDocumentsPaginated($filters, $docsPerPage, $docsPage);
        $latestDocsTotal = (int) ($latestDocsData['total'] ?? 0);
        $docsPageCount = max(1, (int) ceil($latestDocsTotal / $docsPerPage));

        if ($docsPage > $docsPageCount) {
            $docsPage = $docsPageCount;
            $latestDocsData = $regulationModel->getLatestDocumentsPaginated($filters, $docsPerPage, $docsPage);
        }

        $latestDocsMeta = [
            'total'     => $latestDocsTotal,
            'page'      => $docsPage,
            'perPage'   => $docsPerPage,
            'pageCount' => $docsPageCount,
        ];

        if ($this->request->getGet('ajax_docs') === '1') {
            $html = view('dashboard/partials/latest_docs_table', [
                'latestDocs'     => $latestDocsData['rows'] ?? [],
                'latestDocsMeta' => $latestDocsMeta,
            ]);

            return $this->response->setJSON([
                'html' => $html,
            ]);
        }

        $data = [
            'title'          => 'Dashboard Monitoring Peraturan Operasional',
            'summary'        => $regulationModel->getSummary($filters),
            'statusChart'    => $regulationModel->getStatusChart($filters),
            'typeChart'      => $regulationModel->getTypeChart($filters),
            'latestDocs'     => $latestDocsData['rows'] ?? [],
            'latestDocsMeta' => $latestDocsMeta,
            'filters'        => [
                'document_type_id' => $filters['document_type_id'],
                'workflow_id'      => $filters['workflow_id'],
                'institution_id'   => $filters['institution_id'],
                'status'           => $filters['status'],
                'q'                => $filters['q'],
                'sort'             => $filters['sort'],
                'period'           => $this->request->getGet('period') ?: '',
            ],
            'sortOptions' => [
                'latest'       => 'Terbaru',
                'version_desc' => 'Versi Tertinggi',
                'version_asc'  => 'Versi Terendah',
                'status_asc'   => 'Status A-Z',
                'status_desc'  => 'Status Z-A',
            ],
            'documentTypes' => $documentTypeModel->orderBy('name', 'ASC')->findAll(),
            'workflows'     => $workflowModel->orderBy('name', 'ASC')->findAll(),
            'institutions'  => $institutionModel->orderBy('name', 'ASC')->findAll(),
            'periodOptions' => $this->buildPeriodOptions($regulationModel->getAvailableYears()),
        ];

        return view('dashboard/index', $data);
    }

    private function parsePeriod(string $period): array
    {
        if ($period === '') {
            return ['start' => null, 'end' => null];
        }

        $period = trim($period);

        if (preg_match('/^(\d{4})\s*-\s*(\d{4})$/', $period, $matches) === 1) {
            $start = min((int) $matches[1], (int) $matches[2]);
            $end = max((int) $matches[1], (int) $matches[2]);

            return ['start' => $start, 'end' => $end];
        }

        if (preg_match('/^\d{4}$/', $period) === 1) {
            $year = (int) $period;
            return ['start' => $year, 'end' => $year];
        }

        return ['start' => null, 'end' => null];
    }

    private function buildPeriodOptions(array $years): array
    {
        if ($years === []) {
            return [];
        }

        sort($years);
        $minYear = $years[0];
        $maxYear = $years[count($years) - 1];

        $options = [
            $minYear . ' - ' . $maxYear,
        ];

        if ($maxYear - 2 >= $minYear) {
            $options[] = ($maxYear - 2) . ' - ' . $maxYear;
        }

        foreach (array_reverse($years) as $year) {
            $options[] = (string) $year;
        }

        return array_values(array_unique($options));
    }
}
