<?= $this->extend('layouts/app_shell') ?>

<?= $this->section('head') ?>
<style>
    .box { border:1px solid #e2e7f1; border-radius:8px; padding:16px; background:#fff; }
    .form-label { font-weight:700; font-size:.9rem; color:#2e3d5a; }
    .form-control, .form-select { border-color:#ccd4e4; }
    .hint { font-size:.86rem; color:#5c6b87; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-titlebar">
    <h2>Edit Istilah Operasional</h2>
    <p>Perbarui istilah, definisi, dan dokumen referensi terkait.</p>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger mb-3">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="box">
    <form method="post" action="<?= route_to('terms.update', $term['id']) ?>">
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Istilah</label>
                <input type="text" name="term" value="<?= esc(old('term', $term['term'])) ?>" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Dokumen Referensi (LOV)</label>
                <select name="regulation_id" id="regulation_id" class="form-select" required>
                    <option value="">Pilih Dokumen Referensi</option>
                    <?php foreach ($regulations as $regulation): ?>
                        <option
                            value="<?= esc((string) $regulation['id']) ?>"
                            data-code="<?= esc($regulation['code']) ?>"
                            data-url="<?= esc($regulation['download_url']) ?>"
                            <?= (string) old('regulation_id', $term['regulation_id']) === (string) $regulation['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($regulation['code']) ?> | <?= esc($regulation['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Definisi</label>
                <textarea name="definition" class="form-control" rows="5" required><?= esc(old('definition', $term['definition'])) ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Referensi Otomatis</label>
                <input type="text" id="reference_preview" class="form-control" readonly placeholder="Akan terisi dari dokumen referensi">
                <div class="hint">Nilai ini disimpan otomatis dari kode dokumen referensi.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Link Dokumen Otomatis</label>
                <div class="form-control d-flex align-items-center" style="min-height: 38px;">
                    <a href="#" id="doc_link_preview" target="_blank" style="display:none;">Lihat Dokumen Referensi</a>
                    <span id="doc_link_empty" class="text-muted">Pilih dokumen terlebih dulu</span>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= route_to('terms.index') ?>" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const regulationSelect = document.getElementById('regulation_id');
    const referencePreview = document.getElementById('reference_preview');
    const docLink = document.getElementById('doc_link_preview');
    const docLinkEmpty = document.getElementById('doc_link_empty');

    const refreshPreview = () => {
        const selected = regulationSelect.options[regulationSelect.selectedIndex];
        if (!selected || !selected.value) {
            referencePreview.value = '';
            docLink.style.display = 'none';
            docLink.href = '#';
            docLinkEmpty.style.display = 'inline';
            return;
        }

        referencePreview.value = selected.dataset.code || '';
        docLink.href = selected.dataset.url || '#';
        docLink.style.display = 'inline';
        docLinkEmpty.style.display = 'none';
    };

    regulationSelect.addEventListener('change', refreshPreview);
    refreshPreview();
</script>
<?= $this->endSection() ?>
