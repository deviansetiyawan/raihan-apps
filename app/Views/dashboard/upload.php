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
    <h2>Upload Dokumen Peraturan</h2>
    <p>Tambahkan dokumen baru beserta metadata peraturan dan tautan dokumen.</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger mb-3">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="box">
    <form id="uploadForm" method="post" action="<?= route_to('documents.store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="confirm_duplicate" id="confirmDuplicate" value="0">

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Sumber</label>
                <select name="source" class="form-select" required>
                    <option value="internal" <?= old('source') === 'internal' ? 'selected' : '' ?>>Internal</option>
                    <option value="external" <?= old('source') === 'external' ? 'selected' : '' ?>>Eksternal</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Lembaga</label>
                <select name="institution_id" class="form-select" required>
                    <option value="">Pilih Lembaga</option>
                    <?php foreach ($institutions as $institution): ?>
                        <option value="<?= esc((string) $institution['id']) ?>" <?= old('institution_id') == $institution['id'] ? 'selected' : '' ?>>
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
                        <option value="<?= esc((string) $workflow['id']) ?>" <?= old('workflow_id') == $workflow['id'] ? 'selected' : '' ?>>
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
                        <option value="<?= esc((string) $type['id']) ?>" <?= old('document_type_id') == $type['id'] ? 'selected' : '' ?>>
                            <?= esc($type['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kode Dokumen</label>
                <input type="text" name="code" id="codeInput" value="<?= esc(old('code')) ?>" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Judul Peraturan</label>
                <input type="text" name="title" value="<?= esc(old('title')) ?>" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Revisi/Versi</label>
                <input type="text" name="revision" id="revisionInput" value="<?= esc(old('revision')) ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal Berlaku</label>
                <input type="date" name="effective_date" value="<?= esc(old('effective_date')) ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" id="statusInput" class="form-select" required>
                    <option value="Berlaku" <?= old('status') === 'Berlaku' ? 'selected' : '' ?>>Berlaku</option>
                    <option value="Tidak Berlaku" <?= old('status') === 'Tidak Berlaku' ? 'selected' : '' ?>>Tidak Berlaku</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">File Dokumen</label>
                <input type="file" name="document_file" class="form-control">
                <div class="form-text">Format: pdf/doc/docx/xls/xlsx/ppt/pptx/jpg/png (maks 10MB)</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">External Link</label>
                <input type="url" name="external_link" value="<?= esc(old('external_link')) ?>" class="form-control" placeholder="https://...">
            </div>

            <div class="col-md-12">
                <label class="form-label">Catatan</label>
                <textarea name="notes" rows="3" class="form-control"><?= esc(old('notes')) ?></textarea>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    const form = document.getElementById('uploadForm');
    if (!form) return;

    const codeInput = document.getElementById('codeInput');
    const revisionInput = document.getElementById('revisionInput');
    const statusInput = document.getElementById('statusInput');
    const confirmDuplicate = document.getElementById('confirmDuplicate');
    const checkCodeUrl = <?= json_encode($checkCodeUrl ?? site_url('/dashboard/documents/check-code')) ?>;

    form.addEventListener('submit', async function (event) {
        if (confirmDuplicate.value === '1') {
            return;
        }

        event.preventDefault();

        const code = (codeInput.value || '').trim();
        if (!code) {
            form.submit();
            return;
        }

        try {
            const res = await fetch(checkCodeUrl + '?code=' + encodeURIComponent(code), {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!res.ok) {
                form.submit();
                return;
            }

            const data = await res.json();
            if (!data || !data.exists) {
                form.submit();
                return;
            }

            const msg = [
                'Kode peraturan "' + code + '" sudah ada.',
                'Versi terbaru saat ini: ' + (data.latest_revision ?? '-'),
                'Jika lanjut, versi dokumen baru akan menjadi: ' + data.next_revision,
                'Status dokumen lama akan otomatis menjadi: ' + data.old_status_change,
                '',
                'Lanjutkan simpan dokumen versi baru?'
            ].join('\n');

            if (!window.confirm(msg)) {
                return;
            }

            revisionInput.value = String(data.next_revision);
            statusInput.value = 'Berlaku';
            confirmDuplicate.value = '1';
            form.submit();
        } catch (e) {
            form.submit();
        }
    });
})();
</script>
<?= $this->endSection() ?>
