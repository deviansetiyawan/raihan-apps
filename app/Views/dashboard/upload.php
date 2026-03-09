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
        body {
            margin: 0;
            background: linear-gradient(180deg, #f4f5f9 0%, #ececf2 100%);
            color: #1f2e4a;
            font-family: 'Montserrat', sans-serif;
        }
        .wrap {
            max-width: 1080px;
            margin: 18px auto;
            border: 1px solid #d9dde8;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(13, 30, 66, 0.12);
        }
        .hero {
            background: linear-gradient(90deg, #123f7f 0%, #1b5cb0 55%, #3f88df 100%);
            color: #fff;
            padding: 18px 24px;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: .4px;
            text-transform: uppercase;
        }
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
            color: #334664;
            background: linear-gradient(180deg, #f2f5fb, #e5eaf4);
            border-radius: 8px 8px 0 0;
            padding: 10px 14px;
            text-align: center;
            font-weight: 700;
            text-decoration: none;
        }
        .tab-btn.active {
            background: #fff;
            border-color: #9fb3d6;
            color: #1c3f7a;
        }
        .content { padding: 20px; }
        .card-box {
            border: 1px solid #e2e7f1;
            border-radius: 6px;
            padding: 16px;
            background: #fff;
        }
        .form-label { font-weight: 700; font-size: .88rem; color: #2e3d5a; }
        .form-control, .form-select { border-color: #ccd4e4; }
        @media (max-width: 992px) {
            .hero { font-size: 1rem; padding: 14px 16px; }
            .tab-btn { min-width: 1px; width: 100%; font-size: .88rem; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="hero">Dashboard Monitoring Peraturan Operasional</div>

    <div class="tab-strip">
        <a class="tab-btn active" href="/dashboard">Dashboard Peraturan</a>`r`n        <a class="tab-btn" href="/kamus-istilah">Kamus Istilah Operasional</a>
    </div>

    <div class="content">
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

        <div class="card-box">
            <h5 class="fw-bold mb-3">Form Upload Dokumen</h5>

            <form method="post" action="<?= route_to('documents.store') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

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
                        <input type="text" name="code" value="<?= esc(old('code')) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Judul Peraturan</label>
                        <input type="text" name="title" value="<?= esc(old('title')) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Revisi/Versi</label>
                        <input type="text" name="revision" value="<?= esc(old('revision')) ?>" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="effective_date" value="<?= esc(old('effective_date')) ?>" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
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
                    <a href="/dashboard" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

