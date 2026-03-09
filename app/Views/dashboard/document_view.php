<?= $this->extend('layouts/app_shell') ?>

<?= $this->section('head') ?>
<style>
    .viewer-wrap { border:1px solid #e2e7f1; border-radius:8px; overflow:hidden; background:#fff; }
    .viewer-toolbar { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:12px 14px; border-bottom:1px solid #e2e7f1; background:#f8fafd; flex-wrap:wrap; }
    .viewer-title { font-weight:700; color:#2d3d5a; }
    .viewer-body { min-height: 72vh; }
    .viewer-frame { width:100%; height:72vh; border:0; }
    .empty-state { padding:24px; color:#5b6b87; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-titlebar">
    <h2>Viewer Dokumen</h2>
    <p><?= esc($document['code']) ?> - <?= esc($document['title']) ?></p>
</div>

<div class="viewer-wrap">
    <div class="viewer-toolbar">
        <div class="viewer-title"><?= esc($document['file_name'] ?: 'Dokumen Referensi') ?></div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary btn-sm" href="<?= route_to('documents.download', $document['id']) ?>" target="_blank">Download</a>
            <?php if (! empty($document['external_link'])): ?>
                <a class="btn btn-outline-success btn-sm" href="<?= esc($document['external_link']) ?>" target="_blank">Buka Link Asli</a>
            <?php endif; ?>
            <a class="btn btn-outline-secondary btn-sm" href="<?= site_url('dashboard') ?>">Kembali</a>
        </div>
    </div>

    <div class="viewer-body">
        <?php if (! empty($externalEmbedUrl)): ?>
            <iframe class="viewer-frame" src="<?= esc($externalEmbedUrl) ?>"></iframe>
        <?php elseif ($previewable): ?>
            <iframe class="viewer-frame" src="<?= route_to('documents.preview', $document['id']) ?>"></iframe>
        <?php else: ?>
            <div class="empty-state">
                Dokumen ini tidak bisa ditampilkan inline di browser. Klik <strong>Download</strong> atau <strong>Buka Link Asli</strong>.
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
