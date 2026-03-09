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
        body { margin:0; background:linear-gradient(180deg,#f4f5f9 0%,#ececf2 100%); font-family:'Montserrat',sans-serif; color:#1f2e4a; }
        .wrap { max-width:980px; margin:18px auto; border:1px solid #d9dde8; background:#fff; border-radius:6px; overflow:hidden; box-shadow:0 12px 30px rgba(13,30,66,.12); }
        .hero { background:linear-gradient(90deg,#123f7f 0%,#1b5cb0 55%,#3f88df 100%); color:#fff; padding:18px 24px; font-size:1.35rem; font-weight:800; text-transform:uppercase; }
        .tabs { display:flex; justify-content:center; gap:10px; padding:14px 16px 10px; border-bottom:1px solid #e3e6ee; background:#f7f8fc; }
        .tab { min-width:260px; border:1px solid #cbd3e6; border-radius:8px 8px 0 0; padding:10px 14px; text-align:center; font-weight:700; text-decoration:none; color:#334664; background:linear-gradient(180deg,#f2f5fb,#e5eaf4); }
        .tab.active { background:#fff; border-color:#9fb3d6; color:#1c3f7a; }
        .content { padding:20px; }
        .box { border:1px solid #e2e7f1; border-radius:6px; padding:16px; background:#fff; }
        .form-label { font-weight:700; font-size:.9rem; color:#2e3d5a; }
        .form-control, .form-select { border-color:#ccd4e4; }
        .hint { font-size:.86rem; color:#5c6b87; }
        @media (max-width:992px){ .hero{font-size:1rem;padding:14px 16px;} .tab{min-width:1px;width:100%;font-size:.88rem;} }
    </style>
</head>
<body>
<div class="wrap">
    <div class="hero">Dashboard Monitoring Peraturan Operasional</div>

    <div class="tabs">
        <a class="tab" href="/dashboard">Dashboard Peraturan</a>
        <a class="tab active" href="/kamus-istilah">Kamus Istilah Operasional</a>
    </div>

    <div class="content">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-3">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="box">
            <h5 class="fw-bold mb-3">Tambah Istilah Baru</h5>

            <form method="post" action="<?= route_to('terms.store') ?>">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Istilah</label>
                        <input type="text" name="term" value="<?= esc(old('term')) ?>" class="form-control" required>
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
                                    <?= old('regulation_id') == $regulation['id'] ? 'selected' : '' ?>
                                >
                                    <?= esc($regulation['code']) ?> | <?= esc($regulation['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Definisi</label>
                        <textarea name="definition" class="form-control" rows="5" required><?= esc(old('definition')) ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Referensi Otomatis</label>
                        <input type="text" id="reference_preview" class="form-control" readonly placeholder="Akan terisi dari dokumen referensi">
                        <div class="hint">Nilai ini akan disimpan otomatis dari kode dokumen referensi.</div>
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
                    <button type="submit" class="btn btn-primary">Simpan Istilah</button>
                    <a href="<?= route_to('terms.index') ?>" class="btn btn-outline-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

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
</body>
</html>
