<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #eceff6;
            --card: #ffffff;
            --line: #dce2ee;
            --text: #263a59;
            --muted: #6c7c96;
            --primary: #2b65bf;
            --primary-2: #1a4f9f;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: linear-gradient(180deg, #f4f6fb 0%, var(--bg) 100%);
            color: var(--text);
            font-family: 'Montserrat', sans-serif;
        }
        .app-shell {
            max-width: 1420px;
            margin: 14px auto;
            border: 1px solid var(--line);
            border-radius: 10px;
            overflow: hidden;
            background: var(--card);
            box-shadow: 0 14px 30px rgba(13, 28, 61, 0.1);
            display: grid;
            grid-template-columns: 255px minmax(0, 1fr);
            min-height: calc(100vh - 28px);
        }
        .sidebar {
            background: linear-gradient(180deg, #f8faff 0%, #f2f5fb 100%);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
        }
        .brand {
            padding: 18px 16px;
            border-bottom: 1px solid var(--line);
        }
        .brand img {
            width: 190px;
            max-width: 100%;
            height: auto;
            display: block;
        }
        .side-menu {
            padding: 12px 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .side-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #2e4366;
            font-weight: 600;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 12px 12px;
            transition: .18s ease;
        }
        .side-link:hover {
            background: #edf3ff;
            border-color: #d4def2;
            color: #1e3f7c;
        }
        .side-link.active {
            background: linear-gradient(90deg, var(--primary), var(--primary-2));
            color: #fff;
            border-color: #3d74c9;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.18);
        }
        .side-icon {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(52, 101, 184, 0.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        .side-link.active .side-icon {
            background: rgba(255,255,255,.22);
        }
        .main {
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #f7f9fd;
        }
        .content-area {
            padding: 14px;
            min-width: 0;
            flex: 1;
        }
        .content-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 16px;
            min-height: calc(100vh - 60px);
        }
        .page-titlebar {
            margin: -16px -16px 14px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(180deg, #fbfcff 0%, #f4f7fc 100%);
            border-radius: 10px 10px 0 0;
        }
        .page-titlebar h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #253957;
        }
        .page-titlebar p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: .92rem;
            font-weight: 600;
        }
        @media (max-width: 1180px) {
            .app-shell { grid-template-columns: 1fr; }
            .sidebar { border-right: 0; border-bottom: 1px solid var(--line); }
            .brand { display:flex; justify-content:center; }
            .side-menu { flex-direction: row; }
            .side-link { flex:1; justify-content:center; }
            .content-card { min-height: auto; }
        }
    </style>
    <?= $this->renderSection('head') ?>
</head>
<?php
$path = service('uri')->getPath();
$dashboardActive = $path === '' || $path === '/' || str_starts_with($path, 'dashboard');
$termsActive = str_starts_with($path, 'kamus-istilah');
?>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <img src="<?= base_url('assets/pgn-logo.svg') ?>" alt="PGN Pertamina Gas Negara">
        </div>

        <nav class="side-menu">
            <a class="side-link <?= $dashboardActive ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">
                <span class="side-icon">&#128202;</span>
                <span>Dashboard Peraturan</span>
            </a>
            <a class="side-link <?= $termsActive ? 'active' : '' ?>" href="<?= site_url('kamus-istilah') ?>">
                <span class="side-icon">&#128214;</span>
                <span>Kamus Istilah Operasional</span>
            </a>
        </nav>
    </aside>

    <section class="main">
        <div class="content-area">
            <div class="content-card">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </section>
</div>
<?= $this->renderSection('scripts') ?>
</body>
</html>
