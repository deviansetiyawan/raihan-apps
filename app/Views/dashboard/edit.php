<?= $this->extend('layouts/app_shell') ?>

<?= $this->section('head') ?>
<style>
    .box { border:1px solid #e2e7f1; border-radius:8px; padding:16px; background:#fff; }
    .form-label { font-weight:700; font-size:.88rem; color:#2e3d5a; }
    .form-control, .form-select { border-color:#ccd4e4; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-titlebar">
    <h2>Edit Dokumen Peraturan</h2>
    <p>Perbarui metadata dokumen dan tautan file peraturan.</p>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger mb-3">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="box">
    <form method="post" action="<?= route_to('documents.update', $document['id']) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Sumber</label>
                <select name="source" class="form-select" required>
                    <option value="internal" <?= old('source', $document['source']) === 'internal' ? 'selected' : '' ?>>Internal</option>
                    <option value="external" <?= old('source', $document['source']) === 'external' ? 'selected' : '' ?>>Eksternal</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Lembaga</label>
                <select name="institution_id" class="form-select" required>
                    <option value="">Pilih Lembaga</option>
                    <?php foreach ($institutions as $institution): ?>
                        <option value="<?= esc((string) $institution['id']) ?>" <?= (string) old('institution_id', $document['institution_id']) === (string) $institution['id'] ? 'selected' : '' ?>>
                            <?= esc($institution['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Alur Kerja</label>
                <select name="workflow_id" class="form-select" required>
                    <option value="">Pilih Alur Kerja</option>
                    <?php foreach ($workflows as $workflow): ?>
                        <option value="<?= esc((string) $workflow['id']) ?>" <?= (string) old('workflow_id', $document['workflow_id']) === (string) $workflow['id'] ? 'selected' : '' ?>>
                            <?= esc($workflow['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Jenis Dokumen</label>
                <select name="document_type_id" class="form-select" required>
                    <option value="">Pilih Jenis Dokumen</option>
                    <?php foreach ($documentTypes as $type): ?>
                        <option value="<?= esc((string) $type['id']) ?>" <?= (string) old('document_type_id', $document['document_type_id']) === (string) $type['id'] ? 'selected' : '' ?>>
                            <?= esc($type['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kode Dokumen</label>
                <input type="text" name="code" value="<?= esc(old('code', $document['code'])) ?>" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Judul Peraturan</label>
                <input type="text" name="title" value="<?= esc(old('title', $document['title'])) ?>" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Revisi/Versi</label>
                <input type="text" name="revision" value="<?= esc(old('revision', $document['revision'])) ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal Berlaku</label>
                <input type="date" name="effective_date" value="<?= esc(old('effective_date', $document['effective_date'])) ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Berlaku" <?= old('status', $document['status']) === 'Berlaku' ? 'selected' : '' ?>>Berlaku</option>
                    <option value="Tidak Berlaku" <?= old('status', $document['status']) === 'Tidak Berlaku' ? 'selected' : '' ?>>Tidak Berlaku</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">File Dokumen</label>
                <input type="file" name="document_file" class="form-control">
                <?php if (! empty($document['file_name'])): ?>
                    <div class="form-text">File saat ini: <?= esc($document['file_name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">External Link</label>
                <input type="url" name="external_link" value="<?= esc(old('external_link', $document['external_link'])) ?>" class="form-control" placeholder="https://...">
            </div>

            <div class="col-md-12">
                <label class="form-label">Catatan</label>
                <textarea name="notes" rows="3" class="form-control"><?= esc(old('notes', $document['notes'])) ?></textarea>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
