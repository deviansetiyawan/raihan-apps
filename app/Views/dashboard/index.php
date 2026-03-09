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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-page: #ececf2;
            --line: #d9dde8;
            --panel: #ffffff;
            --text: #1f2e4a;
            --brand-1: #0c3f86;
            --brand-2: #1f5fb5;
            --brand-3: #3c80d7;
            --green-1: #00aa58;
            --green-2: #00763e;
            --red-1: #ff1734;
            --red-2: #d60925;
            --blue-1: #1e79d6;
            --blue-2: #175eb0;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: linear-gradient(180deg, #f4f5f9 0%, var(--bg-page) 100%);
            color: var(--text);
            font-family: 'Montserrat', sans-serif;
        }

        .dashboard-wrap {
            max-width: 1080px;
            margin: 18px auto;
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(13, 30, 66, 0.12);
        }

        .hero {
            background: linear-gradient(90deg, #123f7f 0%, #1b5cb0 55%, #3f88df 100%);
            color: #fff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hero h1 {
            margin: 0;
            font-size: 2rem;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .logo-pgn {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-mark {
            width: 58px;
            height: 28px;
            position: relative;
        }

        .logo-mark::before,
        .logo-mark::after {
            content: '';
            position: absolute;
            transform: skewX(-32deg);
            border-radius: 2px;
        }

        .logo-mark::before {
            left: 0;
            top: 9px;
            width: 32px;
            height: 10px;
            background: #1f6fd2;
        }

        .logo-mark::after {
            right: 0;
            top: 0;
            width: 24px;
            height: 10px;
            background: #f44336;
            box-shadow: 0 12px 0 0 #11aa4d;
        }

        .logo-name {
            line-height: 1.04;
            font-weight: 800;
            font-size: .95rem;
            text-align: right;
        }

        .logo-name .red { color: #ff4b3e; }

        .tab-strip {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 14px 16px 10px;
            border-bottom: 1px solid #e3e6ee;
            background: #f7f8fc;
        }

        .tab-btn {
            min-width: 260px;
            border: 1px solid #cbd3e6;
            border-bottom-width: 2px;
            color: #334664;
            background: linear-gradient(180deg, #f2f5fb, #e5eaf4);
            border-radius: 8px 8px 0 0;
            padding: 10px 14px;
            text-align: center;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .tab-btn.active {
            background: #fff;
            border-color: #9fb3d6;
            color: #1c3f7a;
        }

        .content { padding: 16px 20px 20px; }

        .quick-action {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .quick-action .btn {
            font-weight: 700;
            border-radius: 8px;
            padding: 8px 16px;
        }

        .kpi-card {
            border-radius: 8px;
            overflow: hidden;
            color: #fff;
            min-height: 136px;
            border: 1px solid rgba(255,255,255,.2);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.2);
        }

        .kpi-head {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
            text-align: center;
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255,255,255,.2);
        }

        .kpi-body {
            min-height: 84px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }

        .kpi-total { background: linear-gradient(180deg, var(--blue-1), var(--blue-2)); }
        .kpi-active { background: linear-gradient(180deg, var(--green-1), var(--green-2)); }
        .kpi-inactive { background: linear-gradient(180deg, var(--red-1), var(--red-2)); }

        .status-icon {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.95);
            color: #0a8f4b;
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }

        .status-icon.red { color: #dc2338; }

        .filters {
            margin: 14px 0 12px;
            padding: 10px 0;
            border-top: 1px solid #e7ebf4;
            border-bottom: 1px solid #e7ebf4;
        }

        .filters .form-select {
            border-color: #ccd4e4;
            color: #3d4d67;
            font-weight: 600;
            border-radius: 6px;
            font-size: .92rem;
        }

        .chart-card {
            border: 1px solid #e2e6f0;
            border-radius: 4px;
            padding: 12px;
            min-height: 320px;
            background: #fff;
        }

        .chart-card h3 {
            text-align: center;
            margin: 2px 0 12px;
            font-size: 1.9rem;
            color: #2e3f5e;
            font-weight: 700;
        }

        .table-title {
            margin-top: 14px;
            background: linear-gradient(90deg, #0d4a9a, #2370cb);
            color: #fff;
            padding: 10px 14px;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .doc-table { border: 1px solid #e3e7f1; border-top: 0; }
        .doc-table table { margin: 0; }
        .doc-table thead th {
            font-size: .9rem;
            font-weight: 700;
            color: #334362;
            background: #f7f9fd;
            white-space: nowrap;
        }

        .doc-table tbody td {
            font-size: .88rem;
            color: #2f3f60;
            vertical-align: middle;
        }

        .badge-state {
            padding: 6px 10px;
            font-size: .78rem;
            font-weight: 700;
            border-radius: 6px;
        }

        .badge-ok { background: #05a653; }
        .badge-no { background: #e32339; }

        .doc-link {
            color: #1c5db0;
            text-decoration: underline;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .hero { padding: 14px 16px; }
            .hero h1 { font-size: 1.2rem; }
            .logo-name { font-size: .72rem; }
            .tab-btn { min-width: 1px; width: 100%; font-size: .88rem; }
            .kpi-head { font-size: .88rem; }
            .kpi-body { font-size: 2.2rem; }
            .chart-card h3 { font-size: 1.2rem; }
            .table-title { font-size: 1rem; }
        }
    </style>
</head>
<body>
<?php
$total = max(1, (int) ($summary['total_documents'] ?? 0));
$active = (int) ($summary['active_documents'] ?? 0);
$inactive = (int) ($summary['inactive_documents'] ?? 0);
$activePct = (int) round(($active / $total) * 100);
$inactivePct = (int) round(($inactive / $total) * 100);
?>
<div class="dashboard-wrap">
    <header class="hero">
        <h1>Dashboard Monitoring Peraturan Operasional</h1>
        <div class="logo-pgn" aria-label="Pertamina Gas Negara">
            <div class="logo-mark"></div>
            <div class="logo-name">PERTAMINA<br><span class="red">GAS NEGARA</span></div>
        </div>
    </header>

    <div class="tab-strip">
        <a class="tab-btn active" href="/dashboard">Dashboard Peraturan</a>
        <a class="tab-btn" href="/kamus-istilah">Kamus Istilah Operasional</a>
    </div>

    <main class="content">
        <div class="quick-action">
            <a class="btn btn-primary" href="/dashboard/upload">Upload Dokumen</a>
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
            <div class="col-md-3">
                <select class="form-select" name="document_type_id" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Jenis Dokumen: Semua</option>
                    <?php foreach ($documentTypes as $type): ?>
                        <option value="<?= esc((string) $type['id']) ?>" <?= (string) $filters['document_type_id'] === (string) $type['id'] ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="workflow_id" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Alur Kerja: Semua</option>
                    <?php foreach ($workflows as $workflow): ?>
                        <option value="<?= esc((string) $workflow['id']) ?>" <?= (string) $filters['workflow_id'] === (string) $workflow['id'] ? 'selected' : '' ?>><?= esc($workflow['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="institution_id" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Lembaga: Semua</option>
                    <?php foreach ($institutions as $institution): ?>
                        <option value="<?= esc((string) $institution['id']) ?>" <?= (string) $filters['institution_id'] === (string) $institution['id'] ? 'selected' : '' ?>><?= esc($institution['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="period" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Periode: Semua</option>
                    <?php foreach ($periodOptions as $period): ?>
                        <option value="<?= esc($period) ?>" <?= (string) $filters['period'] === (string) $period ? 'selected' : '' ?>>Periode: <?= esc($period) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <div class="row g-3">
            <div class="col-md-4">
                <section class="chart-card">
                    <h3>Status Peraturan</h3>
                    <canvas id="statusChart" height="240"></canvas>
                    <div class="text-center mt-2 small text-secondary fw-semibold">
                        Berlaku <?= esc((string) $activePct) ?>% | Tidak Berlaku <?= esc((string) $inactivePct) ?>%
                    </div>
                </section>
            </div>
            <div class="col-md-8">
                <section class="chart-card">
                    <h3>Jenis Dokumen</h3>
                    <canvas id="typeChart" height="240"></canvas>
                </section>
            </div>
        </div>

        <div class="table-title">DAFTAR PERATURAN TERKINI</div>
        <div class="doc-table table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul Peraturan</th>
                    <th>Versi</th>
                    <th>Status</th>
                    <th>Link Dokumen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($latestDocs as $doc): ?>
                    <tr>
                        <td><?= esc($doc['code']) ?></td>
                        <td><?= esc($doc['title']) ?></td>
                        <td><?= esc($doc['revision'] ?? '-') ?></td>
                        <td>
                            <span class="badge badge-state <?= ($doc['status'] ?? '') === 'Berlaku' ? 'badge-ok' : 'badge-no' ?>">
                                <?= esc($doc['status'] ?? '-') ?>
                            </span>
                        </td>
                        <td>
                            <?php if (! empty($doc['file_path']) || ! empty($doc['external_link'])): ?>
                                <a class="doc-link" target="_blank" href="<?= route_to('documents.download', $doc['id']) ?>">Lihat Dokumen</a>
                            <?php elseif (! empty($doc['file_name'])): ?>
                                <?= esc($doc['file_name']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

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
                maxBarThickness: 56
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
</script>
</body>
</html>

