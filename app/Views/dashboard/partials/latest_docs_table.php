<?php
$docsMeta = $latestDocsMeta ?? ['total' => 0, 'page' => 1, 'perPage' => 8, 'pageCount' => 1];
$docsStart = $docsMeta['total'] > 0 ? (($docsMeta['page'] - 1) * $docsMeta['perPage']) + 1 : 0;
$docsEnd = min($docsMeta['total'], $docsMeta['page'] * $docsMeta['perPage']);

$params = $_GET;
unset($params['docs_page'], $params['ajax_docs']);
$baseQuery = http_build_query($params);
$docsPageUrl = static function (int $page) use ($baseQuery): string {
    $query = $baseQuery !== '' ? $baseQuery . '&docs_page=' . $page : 'docs_page=' . $page;
    return current_url() . '?' . $query;
};

$currentPage = (int) ($docsMeta['page'] ?? 1);
$pageCount = (int) ($docsMeta['pageCount'] ?? 1);
$maxVisible = 7;
$half = (int) floor($maxVisible / 2);
$startPage = max(1, $currentPage - $half);
$endPage = min($pageCount, $startPage + $maxVisible - 1);
$startPage = max(1, $endPage - $maxVisible + 1);
?>
<div class="table-title">DAFTAR PERATURAN TERKINI</div>
<div class="doc-table table-responsive">
    <table class="table table-bordered table-striped align-middle mb-0">
        <thead>
        <tr>
            <th>Kode</th>
            <th>Judul Peraturan</th>
            <th>Versi</th>
            <th>Status</th>
            <th>Link Dokumen</th>
            <th>Aksi</th>
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
                        <a class="doc-link" target="_blank" href="<?= route_to('documents.view', $doc['id']) ?>">Lihat Dokumen</a>
                    <?php elseif (! empty($doc['file_name'])): ?>
                        <?= esc($doc['file_name']) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a class="btn btn-sm btn-outline-primary" href="<?= route_to('documents.edit', $doc['id']) ?>">Edit</a>
                    <form method="post" action="<?= route_to('documents.delete', $doc['id']) ?>" onsubmit="return confirm('Hapus dokumen ini?');" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($latestDocs === []): ?>
            <tr><td colspan="6" class="text-center py-3">Tidak ada dokumen sesuai filter.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="paginate-row">
    <div>Showing <?= esc((string) $docsStart) ?>-<?= esc((string) $docsEnd) ?> of <?= esc((string) $docsMeta['total']) ?> entries</div>
    <?php if ($pageCount > 1): ?>
        <div class="pager">
            <?php if ($currentPage > 1): ?>
                <a class="js-docs-page" href="<?= esc($docsPageUrl($currentPage - 1)) ?>">Previous</a>
            <?php endif; ?>

            <?php if ($startPage > 1): ?>
                <a class="js-docs-page" href="<?= esc($docsPageUrl(1)) ?>">1</a>
                <?php if ($startPage > 2): ?>
                    <span>...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($p = $startPage; $p <= $endPage; $p++): ?>
                <?php if ($p === $currentPage): ?>
                    <span class="active"><?= esc((string) $p) ?></span>
                <?php else: ?>
                    <a class="js-docs-page" href="<?= esc($docsPageUrl($p)) ?>"><?= esc((string) $p) ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($endPage < $pageCount): ?>
                <?php if ($endPage < $pageCount - 1): ?>
                    <span>...</span>
                <?php endif; ?>
                <a class="js-docs-page" href="<?= esc($docsPageUrl($pageCount)) ?>"><?= esc((string) $pageCount) ?></a>
            <?php endif; ?>

            <?php if ($currentPage < $pageCount): ?>
                <a class="js-docs-page" href="<?= esc($docsPageUrl($currentPage + 1)) ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
