<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin:0; background:linear-gradient(180deg,#f4f5f9 0%,#ececf2 100%); color:#1f2e4a; font-family:'Montserrat',sans-serif; }
        .wrap { max-width:1080px; margin:18px auto; border:1px solid #d9dde8; background:#fff; border-radius:6px; overflow:hidden; box-shadow:0 12px 30px rgba(13,30,66,.12); }
        .hero { background:linear-gradient(90deg,#123f7f 0%,#1b5cb0 55%,#3f88df 100%); color:#fff; padding:18px 24px; display:flex; justify-content:space-between; align-items:center; }
        .hero h1 { margin:0; font-size:2rem; font-weight:800; text-transform:uppercase; letter-spacing:.5px; }
        .logo { font-weight:800; font-size:.95rem; text-align:right; line-height:1.06; }
        .logo span { color:#ff4b3e; }
        .tabs { display:flex; justify-content:center; gap:10px; padding:14px 16px 10px; border-bottom:1px solid #e3e6ee; background:#f7f8fc; }
        .tab { min-width:260px; border:1px solid #cbd3e6; border-radius:8px 8px 0 0; padding:10px 14px; text-align:center; font-weight:700; text-decoration:none; color:#334664; background:linear-gradient(180deg,#f2f5fb,#e5eaf4); }
        .tab.active { background:#fff; border-color:#9fb3d6; color:#1c3f7a; }
        .content { padding:16px 20px 20px; }
        .head-row { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; }
        .title { margin:0; font-size:2.2rem; font-weight:700; }
        .desc { margin:.4rem 0 0; color:#5a6883; font-size:.95rem; font-weight:600; }
        .search-box { width:340px; }
        .input-group-text { background:#1f67c2; color:#fff; border-color:#1f67c2; font-weight:700; }
        .toolbar { margin:14px 0 10px; display:flex; justify-content:flex-end; }
        .filters { margin:10px 0 14px; padding-bottom:10px; border-bottom:1px solid #e7ebf4; }
        .filters .form-select { border-color:#ccd4e4; color:#3d4d67; font-weight:600; }
        .table-wrap { border:1px solid #e2e6f0; }
        table { margin:0 !important; }
        thead th { background:#f3f6fc !important; color:#334362; font-size:.95rem; font-weight:700; white-space:nowrap; }
        tbody td { font-size:.9rem; color:#2f3f60; vertical-align:top; }
        .doc-link { color:#1b5fb5; text-decoration:underline; font-weight:600; }
        .paginate-row { display:flex; justify-content:space-between; align-items:center; padding:12px 4px 2px; color:#4d5b76; font-weight:600; flex-wrap:wrap; gap:10px; }
        .pager { display:flex; gap:6px; align-items:center; }
        .pager a, .pager span { border:1px solid #ccd4e4; padding:6px 10px; border-radius:4px; text-decoration:none; color:#2f4467; font-weight:700; }
        .pager .active { background:#1f67c2; color:#fff; border-color:#1f67c2; }
        @media (max-width:992px){ .hero h1{font-size:1.2rem;} .title{font-size:1.3rem;} .search-box{width:100%;} .tab{min-width:1px;width:100%;font-size:.88rem;} }
    </style>
</head>
<body>
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
<div class="wrap">
    <header class="hero">
        <h1>Dashboard Monitoring Peraturan Operasional</h1>
        <div class="logo">PERTAMINA<br><span>GAS NEGARA</span></div>
    </header>

    <div class="tabs">
        <a class="tab" href="/dashboard">Dashboard Peraturan</a>
        <a class="tab active" href="/kamus-istilah">Kamus Istilah Operasional</a>
    </div>

    <main class="content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="head-row">
            <div>
                <h2 class="title">KAMUS ISTILAH OPERASIONAL</h2>
                <p class="desc">Daftar istilah penting terkait operasional perusahaan, beserta definisi dan referensi peraturan terkait.</p>
            </div>

            <form class="search-box" method="get">
                <input type="hidden" name="workflow_id" value="<?= esc((string) ($filters['workflow_id'] ?? '')) ?>">
                <input type="hidden" name="institution_id" value="<?= esc((string) ($filters['institution_id'] ?? '')) ?>">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search Istilah..." name="q" value="<?= esc((string) ($filters['q'] ?? '')) ?>">
                    <button class="input-group-text" type="submit">&#128269;</button>
                </div>
            </form>
        </div>

        <div class="toolbar">
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
                    </tr>
                <?php endforeach; ?>
                <?php if ($terms === []): ?>
                    <tr><td colspan="4" class="text-center py-4">Tidak ada data istilah.</td></tr>
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
    </main>
</div>
</body>
</html>
