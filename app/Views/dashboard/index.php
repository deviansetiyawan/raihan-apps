<?= $this->extend('layouts/app_shell') ?>

<?= $this->section('head') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .toolbar { display:flex; justify-content:space-between; gap:10px; margin-bottom:10px; flex-wrap:wrap; }
    .search-form { min-width: 330px; }
    .search-form .input-group-text { background:#1f67c2; color:#fff; border-color:#1f67c2; font-weight:700; }
    .toolbar .btn { border-radius: 8px; font-weight: 700; }

    .kpi-card { border-radius: 10px; overflow: hidden; color: #fff; min-height: 120px; border: 1px solid rgba(255,255,255,.25); box-shadow: inset 0 1px 0 rgba(255,255,255,.18); }
    .kpi-head { text-align:center; padding:10px 12px; font-size:.95rem; font-weight:700; border-bottom:1px solid rgba(255,255,255,.2); text-transform: uppercase; }
    .kpi-body { min-height: 76px; display:flex; align-items:center; justify-content:center; gap:10px; font-size:2.4rem; font-weight:800; line-height:1; }
    .kpi-total { background: linear-gradient(180deg, #1f79d7, #185db1); }
    .kpi-active { background: linear-gradient(180deg, #00aa58, #007740); }
    .kpi-inactive { background: linear-gradient(180deg, #ff1734, #d90a29); }
    .status-icon { width: 36px; height: 36px; border-radius: 999px; display:inline-flex; align-items:center; justify-content:center; background:#fff; color:#0a8f4b; font-size:1.2rem; font-weight:800; }
    .status-icon.red { color: #d92639; }

    .filters { margin: 13px 0 12px; padding: 10px 0; border-top: 1px solid #e7ebf4; border-bottom: 1px solid #e7ebf4; }
    .filters .form-select { border-color:#ccd4e4; color:#3d4d67; font-weight:600; font-size:.9rem; }

    .chart-card { border:1px solid #e2e6f0; border-radius:6px; padding:12px; min-height:290px; background:#fff; }
    .chart-card h3 { margin: 0 0 10px; text-align:center; font-size:1.2rem; font-weight:700; color:#2e3f5e; }

    .table-title { margin-top: 14px; background: linear-gradient(90deg, #0d4a9a, #2370cb); color:#fff; padding: 10px 14px; border-radius: 8px 8px 0 0; font-size: 1.05rem; font-weight: 700; }
    .doc-table { border:1px solid #e3e7f1; border-top: 0; border-radius: 0 0 8px 8px; overflow: hidden; }
    .doc-table thead th { font-size:.86rem; font-weight:700; color:#334362; background:#f7f9fd; white-space: nowrap; }
    .doc-table tbody td { font-size:.84rem; color:#2f3f60; vertical-align: middle; }
    .badge-state { padding:6px 10px; font-size:.74rem; font-weight:700; border-radius:6px; }
    .badge-ok { background:#05a653; }
    .badge-no { background:#e32339; }
    .doc-link { color:#1c5db0; text-decoration:underline; font-weight:600; }`r`n    .actions { white-space: nowrap; min-width: 130px; }`r`n    .actions .btn { padding: 4px 10px; font-size: .78rem; font-weight: 700; }

    .paginate-row { display:flex; justify-content:space-between; align-items:center; padding:12px 4px 2px; color:#4d5b76; font-weight:600; flex-wrap:wrap; gap:10px; }
    .pager { display:flex; gap:6px; align-items:center; }
    .pager a, .pager span { border:1px solid #ccd4e4; padding:6px 10px; border-radius:4px; text-decoration:none; color:#2f4467; font-weight:700; }
    .pager .active { background:#1f67c2; color:#fff; border-color:#1f67c2; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$total = max(1, (int) ($summary['total_documents'] ?? 0));
$active = (int) ($summary['active_documents'] ?? 0);
$inactive = (int) ($summary['inactive_documents'] ?? 0);
$activePct = (int) round(($active / $total) * 100);
$inactivePct = (int) round(($inactive / $total) * 100);
?>

<div class="page-titlebar">
    <h2>Dashboard Peraturan</h2>
    <p>Monitoring peraturan operasional, status berlaku, komposisi jenis dokumen, dan daftar dokumen terkini.</p>
</div>

<div class="toolbar">
    <form method="get" class="search-form">
        <input type="hidden" name="document_type_id" value="<?= esc((string) ($filters['document_type_id'] ?? '')) ?>">
        <input type="hidden" name="workflow_id" value="<?= esc((string) ($filters['workflow_id'] ?? '')) ?>">
        <input type="hidden" name="institution_id" value="<?= esc((string) ($filters['institution_id'] ?? '')) ?>">
        <input type="hidden" name="status" value="<?= esc((string) ($filters['status'] ?? '')) ?>">
        <input type="hidden" name="period" value="<?= esc((string) ($filters['period'] ?? '')) ?>">
        <input type="hidden" name="sort" value="<?= esc((string) ($filters['sort'] ?? 'latest')) ?>">
        <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Search kode/judul peraturan..." value="<?= esc((string) ($filters['q'] ?? '')) ?>">
            <button class="input-group-text" type="submit">&#128269;</button>
        </div>
    </form>

    <a class="btn btn-primary" href="<?= site_url('dashboard/upload') ?>">Upload Dokumen</a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="kpi-card kpi-total">
            <div class="kpi-head">Total Peraturan</div>
            <div class="kpi-body"><?= esc((string) $summary['total_documents']) ?></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="kpi-card kpi-active">
            <div class="kpi-head">Peraturan Berlaku</div>
            <div class="kpi-body"><span class="status-icon">&#10003;</span><?= esc((string) $summary['active_documents']) ?></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="kpi-card kpi-inactive">
            <div class="kpi-head">Peraturan Tidak Berlaku</div>
            <div class="kpi-body"><span class="status-icon red">&#10005;</span><?= esc((string) $summary['inactive_documents']) ?></div>
        </div>
    </div>
</div>

<form method="get" id="filterForm" class="row g-2 filters">
    <input type="hidden" name="q" value="<?= esc((string) ($filters['q'] ?? '')) ?>">
    <div class="col-md-2">
        <select class="form-select" name="document_type_id" onchange="document.getElementById('filterForm').submit()">
            <option value="">Jenis Dokumen: Semua</option>
            <?php foreach ($documentTypes as $type): ?>
                <option value="<?= esc((string) $type['id']) ?>" <?= (string) ($filters['document_type_id'] ?? '') === (string) $type['id'] ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select class="form-select" name="workflow_id" onchange="document.getElementById('filterForm').submit()">
            <option value="">Alur Kerja: Semua</option>
            <?php foreach ($workflows as $workflow): ?>
                <option value="<?= esc((string) $workflow['id']) ?>" <?= (string) ($filters['workflow_id'] ?? '') === (string) $workflow['id'] ? 'selected' : '' ?>><?= esc($workflow['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select class="form-select" name="institution_id" onchange="document.getElementById('filterForm').submit()">
            <option value="">Lembaga: Semua</option>
            <?php foreach ($institutions as $institution): ?>
                <option value="<?= esc((string) $institution['id']) ?>" <?= (string) ($filters['institution_id'] ?? '') === (string) $institution['id'] ? 'selected' : '' ?>><?= esc($institution['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select class="form-select" name="status" onchange="document.getElementById('filterForm').submit()">
            <option value="">Status: Semua</option>
            <option value="Berlaku" <?= (string) ($filters['status'] ?? '') === 'Berlaku' ? 'selected' : '' ?>>Berlaku</option>
            <option value="Tidak Berlaku" <?= (string) ($filters['status'] ?? '') === 'Tidak Berlaku' ? 'selected' : '' ?>>Tidak Berlaku</option>
        </select>
    </div>
    <div class="col-md-2">
        <select class="form-select" name="period" onchange="document.getElementById('filterForm').submit()">
            <option value="">Periode: Semua</option>
            <?php foreach ($periodOptions as $period): ?>
                <option value="<?= esc($period) ?>" <?= (string) ($filters['period'] ?? '') === (string) $period ? 'selected' : '' ?>><?= esc($period) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select class="form-select" name="sort" onchange="document.getElementById('filterForm').submit()">
            <?php foreach ($sortOptions as $key => $label): ?>
                <option value="<?= esc($key) ?>" <?= (string) ($filters['sort'] ?? 'latest') === (string) $key ? 'selected' : '' ?>>Sort: <?= esc($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="row g-3">
    <div class="col-md-4">
        <section class="chart-card">
            <h3>Status Peraturan</h3>
            <canvas id="statusChart" height="220"></canvas>
            <div class="text-center mt-2 small text-secondary fw-semibold">
                Berlaku <?= esc((string) $activePct) ?>% | Tidak Berlaku <?= esc((string) $inactivePct) ?>%
            </div>
        </section>
    </div>
    <div class="col-md-8">
        <section class="chart-card">
            <h3>Jenis Dokumen</h3>
            <canvas id="typeChart" height="220"></canvas>
        </section>
    </div>
</div>

<div id="latestDocsSection">
    <?= view('dashboard/partials/latest_docs_table', ['latestDocs' => $latestDocs, 'latestDocsMeta' => $latestDocsMeta]) ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const statusChartData = <?= json_encode($statusChart, JSON_UNESCAPED_UNICODE) ?>;
    const typeChartData = <?= json_encode($typeChart, JSON_UNESCAPED_UNICODE) ?>;

    const statusColors = {
        'Berlaku': '#06a957',
        'Tidak Berlaku': '#ea1f3b'
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: statusChartData.map(i => i.status),
            datasets: [{
                data: statusChartData.map(i => Number(i.total)),
                backgroundColor: statusChartData.map(i => statusColors[i.status] || '#1f5fb5'),
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        font: { family: 'Montserrat', weight: 700 }
                    }
                }
            }
        }
    });

    const shortLabel = (label) => {
        const words = String(label).trim().split(/\s+/);
        if (words.length === 1) {
            return words[0].length > 10 ? words[0].slice(0, 10) + '.' : words[0];
        }
        if (words.length === 2) {
            return words.map(w => w[0]).join('');
        }
        return words.slice(0, 3).map(w => w[0]).join('');
    };

    new Chart(document.getElementById('typeChart'), {
        type: 'bar',
        data: {
            labels: typeChartData.map(i => shortLabel(i.name)),
            datasets: [{
                data: typeChartData.map(i => Number(i.total)),
                backgroundColor: '#1f67c2',
                borderRadius: 2,
                maxBarThickness: 50
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Montserrat', weight: 700 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { family: 'Montserrat', weight: 600 } }
                }
            }
        }
    });

    document.addEventListener('click', async (event) => {
        const link = event.target.closest('.js-docs-page');
        if (!link) {
            return;
        }

        event.preventDefault();
        const section = document.getElementById('latestDocsSection');
        const url = link.getAttribute('href');
        const ajaxUrl = url + (url.includes('?') ? '&' : '?') + 'ajax_docs=1';

        try {
            const response = await fetch(ajaxUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) {
                window.location.href = url;
                return;
            }

            const payload = await response.json();
            if (!payload || typeof payload.html !== 'string') {
                window.location.href = url;
                return;
            }

            section.innerHTML = payload.html;
            window.history.replaceState({}, '', url);
        } catch (error) {
            window.location.href = url;
        }
    });
</script>
<?= $this->endSection() ?>

