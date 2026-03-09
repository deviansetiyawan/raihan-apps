<?php

namespace App\Controllers;

use App\Models\DocumentTypeModel;
use App\Models\InstitutionModel;
use App\Models\RegulationModel;
use App\Models\WorkflowModel;

class DocumentController extends BaseController
{
    public function uploadForm()
    {
        $data = [
            'title'         => 'Upload Dokumen Peraturan',
            'documentTypes' => (new DocumentTypeModel())->orderBy('name', 'ASC')->findAll(),
            'workflows'     => (new WorkflowModel())->orderBy('name', 'ASC')->findAll(),
            'institutions'  => (new InstitutionModel())->orderBy('name', 'ASC')->findAll(),
            'validation'    => \Config\Services::validation(),
        ];

        return view('dashboard/upload', $data);
    }

    public function store()
    {
        $rules = [
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

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('document_file');
        $filePath = null;
        $fileName = null;

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $uploadDir = WRITEPATH . 'uploads/regulations';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $storedName = $file->getRandomName();
            $file->move($uploadDir, $storedName);
            $filePath = 'uploads/regulations/' . $storedName;
            $fileName = $file->getClientName();
        }

        $model = new RegulationModel();
        $model->insert([
            'source'           => $this->request->getPost('source'),
            'institution_id'   => (int) $this->request->getPost('institution_id'),
            'workflow_id'      => (int) $this->request->getPost('workflow_id'),
            'document_type_id' => (int) $this->request->getPost('document_type_id'),
            'code'             => trim((string) $this->request->getPost('code')),
            'title'            => trim((string) $this->request->getPost('title')),
            'revision'         => $this->request->getPost('revision') ?: null,
            'effective_date'   => $this->request->getPost('effective_date') ?: null,
            'status'           => $this->request->getPost('status'),
            'file_name'        => $fileName,
            'file_path'        => $filePath,
            'external_link'    => $this->request->getPost('external_link') ?: null,
            'notes'            => $this->request->getPost('notes') ?: null,
        ]);

        return redirect()->to('/dashboard/upload')->with('success', 'Dokumen berhasil diupload.');
    }

    public function download(int $id)
    {
        $model = new RegulationModel();
        $doc = $model->find($id);

        if (! $doc) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
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
}
