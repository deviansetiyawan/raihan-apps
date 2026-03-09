<?php

namespace App\Controllers;

use App\Models\InstitutionModel;
use App\Models\OperationalTermModel;
use App\Models\RegulationModel;
use App\Models\WorkflowModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class OperationalTermController extends BaseController
{
    public function index()
    {
        $termModel = new OperationalTermModel();

        $filters = [
            'q'              => trim((string) $this->request->getGet('q')),
            'workflow_id'    => $this->request->getGet('workflow_id'),
            'institution_id' => $this->request->getGet('institution_id'),
        ];

        $terms = $termModel->getPaginatedTerms($filters, 9);
        $pager = $termModel->pager;

        $data = [
            'title'        => 'Kamus Istilah Operasional',
            'terms'        => $terms,
            'pager'        => $pager,
            'total'        => $pager->getTotal(),
            'currentPage'  => $pager->getCurrentPage(),
            'perPage'      => $pager->getPerPage(),
            'filters'      => $filters,
            'workflows'    => (new WorkflowModel())->orderBy('name', 'ASC')->findAll(),
            'institutions' => (new InstitutionModel())->orderBy('name', 'ASC')->findAll(),
        ];

        return view('terms/index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'Tambah Istilah Operasional',
            'regulations' => $this->regulationLov(),
        ];

        return view('terms/create', $data);
    }

    public function store()
    {
        $validated = $this->validateInput();
        if ($validated === null) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new OperationalTermModel())->insert($validated);

        return redirect()->to('/kamus-istilah')->with('success', 'Istilah baru berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $term = (new OperationalTermModel())->find($id);

        if (! $term) {
            throw PageNotFoundException::forPageNotFound('Istilah tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Istilah Operasional',
            'term'        => $term,
            'regulations' => $this->regulationLov(),
        ];

        return view('terms/edit', $data);
    }

    public function update(int $id)
    {
        $model = new OperationalTermModel();
        $term = $model->find($id);

        if (! $term) {
            throw PageNotFoundException::forPageNotFound('Istilah tidak ditemukan.');
        }

        $validated = $this->validateInput();
        if ($validated === null) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, $validated);

        return redirect()->to('/kamus-istilah')->with('success', 'Istilah berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new OperationalTermModel();
        $term = $model->find($id);

        if (! $term) {
            throw PageNotFoundException::forPageNotFound('Istilah tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/kamus-istilah')->with('success', 'Istilah berhasil dihapus.');
    }

    private function validateInput(): ?array
    {
        $rules = [
            'term'          => 'required|max_length[255]',
            'definition'    => 'required',
            'regulation_id' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return null;
        }

        $regulationId = (int) $this->request->getPost('regulation_id');
        $regulation = (new RegulationModel())->find($regulationId);

        if (! $regulation) {
            $this->validator->setError('regulation_id', 'Dokumen referensi tidak ditemukan.');
            return null;
        }

        return [
            'term'           => trim((string) $this->request->getPost('term')),
            'definition'     => trim((string) $this->request->getPost('definition')),
            'reference_code' => $regulation['code'] ?? null,
            'link_label'     => $regulation['file_name'] ?: ($regulation['title'] ?? null),
            'regulation_id'  => $regulationId,
        ];
    }

    private function regulationLov(): array
    {
        $regulations = (new RegulationModel())
            ->select('id, code, title, file_path, external_link')
            ->orderBy('code', 'ASC')
            ->findAll();

        foreach ($regulations as &$regulation) {
            $regulation['download_url'] = route_to('documents.download', $regulation['id']);
        }

        return $regulations;
    }
}