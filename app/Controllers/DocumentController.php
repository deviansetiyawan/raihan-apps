<?php

namespace App\Controllers;

use App\Models\DocumentTypeModel;
use App\Models\InstitutionModel;
use App\Models\RegulationModel;
use App\Models\WorkflowModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class DocumentController extends BaseController
{
    public function uploadForm()
    {
        return view('dashboard/upload', $this->formData('Upload Dokumen Peraturan'));
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegulationModel();
        $code = trim((string) $this->request->getPost('code'));
        $confirmedDuplicate = $this->request->getPost('confirm_duplicate') === '1';
        $duplicateMeta = $this->getDuplicateMeta($code);

        if ($duplicateMeta['exists'] && ! $confirmedDuplicate) {
            return redirect()->back()->withInput()->with('errors', ['Kode peraturan sudah ada. Silakan konfirmasi pembaruan versi.']);
        }

        $fileData = $this->handleUpload(null);

        $insertData = [
            'source'           => $this->request->getPost('source'),
            'institution_id'   => (int) $this->request->getPost('institution_id'),
            'workflow_id'      => (int) $this->request->getPost('workflow_id'),
            'document_type_id' => (int) $this->request->getPost('document_type_id'),
            'code'             => $code,
            'title'            => trim((string) $this->request->getPost('title')),
            'revision'         => $this->request->getPost('revision') ?: null,
            'effective_date'   => $this->request->getPost('effective_date') ?: null,
            'status'           => $this->request->getPost('status'),
            'file_name'        => $fileData['file_name'],
            'file_path'        => $fileData['file_path'],
            'external_link'    => $this->request->getPost('external_link') ?: null,
            'notes'            => $this->request->getPost('notes') ?: null,
        ];

        if ($duplicateMeta['exists']) {
            $insertData['revision'] = (string) $duplicateMeta['next_revision'];
            $insertData['status'] = 'Berlaku';

            $model->db->transStart();
            $model->where('code', $code)->set(['status' => 'Tidak Berlaku'])->update();
            $model->insert($insertData);
            $model->db->transComplete();

            if (! $model->db->transStatus()) {
                return redirect()->back()->withInput()->with('errors', ['Gagal menyimpan dokumen versi baru.']);
            }
        } else {
            $model->insert($insertData);
        }

        return redirect()->to('/dashboard/upload')->with('success', 'Dokumen berhasil diupload.');
    }

    public function checkCode()
    {
        $code = trim((string) $this->request->getGet('code'));
        $meta = $this->getDuplicateMeta($code);

        return $this->response->setJSON($meta);
    }

    public function edit(int $id)
    {
        $doc = (new RegulationModel())->find($id);

        if (! $doc) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        $data = $this->formData('Edit Dokumen Peraturan');
        $data['document'] = $doc;

        return view('dashboard/edit', $data);
    }

    public function update(int $id)
    {
        $model = new RegulationModel();
        $doc = $model->find($id);

        if (! $doc) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fileData = $this->handleUpload($doc);

        $model->update($id, [
            'source'           => $this->request->getPost('source'),
            'institution_id'   => (int) $this->request->getPost('institution_id'),
            'workflow_id'      => (int) $this->request->getPost('workflow_id'),
            'document_type_id' => (int) $this->request->getPost('document_type_id'),
            'code'             => trim((string) $this->request->getPost('code')),
            'title'            => trim((string) $this->request->getPost('title')),
            'revision'         => $this->request->getPost('revision') ?: null,
            'effective_date'   => $this->request->getPost('effective_date') ?: null,
            'status'           => $this->request->getPost('status'),
            'file_name'        => $fileData['file_name'],
            'file_path'        => $fileData['file_path'],
            'external_link'    => $this->request->getPost('external_link') ?: null,
            'notes'            => $this->request->getPost('notes') ?: null,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new RegulationModel();
        $doc = $model->find($id);

        if (! $doc) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        if (! empty($doc['file_path'])) {
            $oldPath = WRITEPATH . $doc['file_path'];
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $model->delete($id);

        return redirect()->to('/dashboard')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function view(int $id)
    {
        $doc = (new RegulationModel())->find($id);

        if (! $doc) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        $ext = strtolower(pathinfo((string) ($doc['file_name'] ?? ''), PATHINFO_EXTENSION));
        $previewable = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp'], true);

        $externalLink = trim((string) ($doc['external_link'] ?? ''));
        $externalEmbedUrl = null;

        if ($externalLink !== '') {
            $externalEmbedUrl = $this->toEmbeddableUrl($externalLink);
        }

        return view('dashboard/document_view', [
            'title'            => 'Viewer Dokumen',
            'document'         => $doc,
            'previewable'      => $previewable,
            'externalEmbedUrl' => $externalEmbedUrl,
        ]);
    }

    public function preview(int $id)
    {
        $doc = (new RegulationModel())->find($id);

        if (! $doc) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        if (! empty($doc['external_link'])) {
            return redirect()->to($doc['external_link']);
        }

        if (! empty($doc['file_path'])) {
            $absolutePath = WRITEPATH . $doc['file_path'];
            if (is_file($absolutePath)) {
                $mimeType = mime_content_type($absolutePath) ?: 'application/octet-stream';
                $filename = $doc['file_name'] ?: basename($absolutePath);

                return $this->response
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Disposition', 'inline; filename="' . addslashes($filename) . '"')
                    ->setBody((string) file_get_contents($absolutePath));
            }
        }

        throw PageNotFoundException::forPageNotFound('Konten dokumen tidak tersedia.');
    }

    public function download(int $id)
    {
        $model = new RegulationModel();
        $doc = $model->find($id);

        if (! $doc) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        if (! empty($doc['file_path'])) {
            $absolutePath = WRITEPATH . $doc['file_path'];

            if (is_file($absolutePath)) {
                return $this->response->download($absolutePath, null)->setFileName($doc['file_name'] ?: basename($absolutePath));
            }
        }

        if (! empty($doc['external_link'])) {
            return redirect()->to($doc['external_link']);
        }

        return redirect()->back()->with('errors', ['Dokumen fisik belum tersedia.']);
    }

    private function toEmbeddableUrl(string $url): ?string
    {
        // Google Drive: /file/d/{id}/view -> /file/d/{id}/preview
        if (preg_match('~https?://drive\.google\.com/file/d/([^/]+)/~i', $url, $m) === 1) {
            return 'https://drive.google.com/file/d/' . $m[1] . '/preview';
        }

        // Google Docs direct links generally embeddable as-is
        if (preg_match('~https?://docs\.google\.com/~i', $url) === 1) {
            return $url;
        }

        // Fallback: unknown provider might block iframe
        return null;
    }

    private function formData(string $title): array
    {
        return [
            'title'         => $title,
            'documentTypes' => (new DocumentTypeModel())->orderBy('name', 'ASC')->findAll(),
            'workflows'     => (new WorkflowModel())->orderBy('name', 'ASC')->findAll(),
            'institutions'  => (new InstitutionModel())->orderBy('name', 'ASC')->findAll(),
            'validation'    => \Config\Services::validation(),
            'checkCodeUrl'  => site_url('/dashboard/documents/check-code'),
        ];
    }

    private function rules(): array
    {
        return [
            'source'           => 'required|in_list[internal,external]',
            'institution_id'   => 'required|integer',
            'workflow_id'      => 'required|integer',
            'document_type_id' => 'required|integer',
            'code'             => 'required|max_length[255]',
            'title'            => 'required|max_length[1000]',
            'revision'         => 'permit_empty|max_length[50]',
            'effective_date'   => 'permit_empty|valid_date[Y-m-d]',
            'status'           => 'required|in_list[Berlaku,Tidak Berlaku]',
            'external_link'    => 'permit_empty|valid_url',
            'document_file'    => 'permit_empty|max_size[document_file,10240]|ext_in[document_file,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png]',
            'notes'            => 'permit_empty|max_length[2000]',
        ];
    }

    private function handleUpload(?array $existing): array
    {
        $file = $this->request->getFile('document_file');
        $filePath = $existing['file_path'] ?? null;
        $fileName = $existing['file_name'] ?? null;

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploadDir = WRITEPATH . 'uploads/regulations';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            if (! empty($existing['file_path'])) {
                $oldPath = WRITEPATH . $existing['file_path'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $storedName = $file->getRandomName();
            $file->move($uploadDir, $storedName);
            $filePath = 'uploads/regulations/' . $storedName;
            $fileName = $file->getClientName();
        }

        return [
            'file_path' => $filePath,
            'file_name' => $fileName,
        ];
    }

    private function getDuplicateMeta(string $code): array
    {
        if ($code === '') {
            return [
                'exists' => false,
            ];
        }

        $rows = (new RegulationModel())
            ->select('id, revision, status')
            ->where('code', $code)
            ->findAll();

        if (empty($rows)) {
            return [
                'exists' => false,
            ];
        }

        $maxRevision = 0;
        foreach ($rows as $row) {
            $maxRevision = max($maxRevision, $this->parseRevisionNumber((string) ($row['revision'] ?? '')));
        }

        return [
            'exists'            => true,
            'code'              => $code,
            'existing_count'    => count($rows),
            'active_count'      => count(array_filter($rows, static fn ($row): bool => ($row['status'] ?? '') === 'Berlaku')),
            'latest_revision'   => $maxRevision > 0 ? $maxRevision : null,
            'next_revision'     => $maxRevision + 1,
            'new_status'        => 'Berlaku',
            'old_status_change' => 'Tidak Berlaku',
        ];
    }

    private function parseRevisionNumber(string $revision): int
    {
        if ($revision === '') {
            return 0;
        }

        if (preg_match('/(\d+)/', $revision, $matches) === 1) {
            return (int) $matches[1];
        }

        return 0;
    }
}
