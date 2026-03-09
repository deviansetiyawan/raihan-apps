<?= $this->extend('layouts/app_shell') ?>

<?= $this->section('head') ?>
<style>
    .toolbar-line { margin:14px 0 10px; display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap; }
    .search-box { width:340px; max-width: 100%; }
    .input-group-text { background:#1f67c2; color:#fff; border-color:#1f67c2; font-weight:700; }
    .filters { margin:10px 0 14px; padding-bottom:10px; border-bottom:1px solid #e7ebf4; }
    .filters .form-select { border-color:#ccd4e4; color:#3d4d67; font-weight:600; }
    .table-wrap { border:1px solid #e2e6f0; border-radius: 8px; overflow: hidden; }
    .table-wrap table { margin:0 !important; }
    .table-wrap thead th { background:#f3f6fc !important; color:#334362; font-size:.95rem; font-weight:700; white-space:nowrap; }
    .table-wrap tbody td { font-size:.9rem; color:#2f3f60; vertical-align:top; }
    .doc-link { color:#1b5fb5; text-decoration:underline; font-weight:600; }
    .actions { white-space: nowrap; min-width: 130px; }
    .actions .btn { padding: 4px 10px; font-size: .78rem; font-weight: 700; }
    .actions form { display:inline; }
    .paginate-row { display:flex; justify-content:space-between; align-items:center; padding:12px 4px 2px; color:#4d5b76; font-weight:600; flex-wrap:wrap; gap:10px; }
    .pager { display:flex; gap:6px; align-items:center; }
    .pager a, .pager span { border:1px solid #ccd4e4; padding:6px 10px; border-radius:4px; text-decoration:none; color:#2f4467; font-weight:700; }
    .pager .active { background:#1f67c2; color:#fff; border-color:#1f67c2; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$start = $total > 0 ? (($currentPage - 1) * $perPage) + 1 : 0;
$end = min($total, $currentPage * $perPage);
$pageCount = $pager->getPageCount();
$params = $_GET;
unset($params['page']);
$baseQuery = http_build_query($params);
$pageUrl = static function (int $page) use ($baseQuery): string {
    $query = $baseQuery !== '' ? $baseQuery . '&page=' . $page : 'page=' . $page;
    return current_url() . '?' . $query;
};
?>

<div class="page-titlebar">
    <h2>Kamus Istilah Operasional</h2>
    <p>Daftar istilah penting terkait operasional perusahaan, beserta definisi dan referensi peraturan terkait.</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success py-2 mb-2"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<div class="toolbar-line">
    <form class="search-box" method="get">
        <input type="hidden" name="workflow_id" value="<?= esc((string) ($filters['workflow_id'] ?? '')) ?>">
        <input type="hidden" name="institution_id" value="<?= esc((string) ($filters['institution_id'] ?? '')) ?>">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search Istilah..." name="q" value="<?= esc((string) ($filters['q'] ?? '')) ?>">
            <button class="input-group-text" type="submit">&#128269;</button>
        </div>
    </form>

    <a href="<?= route_to('terms.create') ?>" class="btn btn-primary">Tambah Istilah</a>
</div>

<form method="get" id="filterForm" class="row g-2 filters">
    <input type="hidden" name="q" value="<?= esc((string) ($filters['q'] ?? '')) ?>">
    <div class="col-md-3">
        <select name="workflow_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Alur Kerja: Semua</option>
            <?php foreach ($workflows as $workflow): ?>
                <option value="<?= esc((string) $workflow['id']) ?>" <?= (string) ($filters['workflow_id'] ?? '') === (string) $workflow['id'] ? 'selected' : '' ?>><?= esc($workflow['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="institution_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Lembaga: Semua</option>
            <?php foreach ($institutions as $institution): ?>
                <option value="<?= esc((string) $institution['id']) ?>" <?= (string) ($filters['institution_id'] ?? '') === (string) $institution['id'] ? 'selected' : '' ?>><?= esc($institution['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="table-wrap table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th>Istilah</th>
            <th>Definisi</th>
            <th>Referensi</th>
            <th>Link Dokumen</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($terms as $term): ?>
            <tr>
                <td><strong><?= esc($term['term']) ?></strong></td>
                <td><?= esc($term['definition']) ?></td>
                <td>
                    <?php if (! empty($term['regulation_code'])): ?>
                        <?= esc($term['regulation_code']) ?><?= ! empty($term['regulation_title']) ? ': ' . esc($term['regulation_title']) : '' ?>
                    <?php else: ?>
                        <?= esc($term['reference_code'] ?? '-') ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (! empty($term['regulation_id'])): ?>
                        <a class="doc-link" href="<?= route_to('documents.download', $term['regulation_id']) ?>" target="_blank">Lihat Dokumen</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="<?= route_to('terms.edit', $term['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="<?= route_to('terms.delete', $term['id']) ?>" onsubmit="return confirm('Hapus istilah ini?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($terms === []): ?>
            <tr><td colspan="5" class="text-center py-4">Tidak ada data istilah.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="paginate-row">
    <div>Showing <?= esc((string) $start) ?>-<?= esc((string) $end) ?> of <?= esc((string) $total) ?> entries</div>
    <?php if ($pageCount > 1): ?>
        <div class="pager">
            <?php if ($currentPage > 1): ?>
                <a href="<?= esc($pageUrl($currentPage - 1)) ?>">Previous</a>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $pageCount; $p++): ?>
                <?php if ($p === $currentPage): ?>
                    <span class="active"><?= esc((string) $p) ?></span>
                <?php else: ?>
                    <a href="<?= esc($pageUrl($p)) ?>"><?= esc((string) $p) ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $pageCount): ?>
                <a href="<?= esc($pageUrl($currentPage + 1)) ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
