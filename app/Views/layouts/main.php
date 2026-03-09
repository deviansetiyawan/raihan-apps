<!DOCTYPE html>
<html>
<head>

    <title><?= $title ?? 'Dashboard' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?= $this->include('layouts/navbar') ?>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2">
            <?= $this->include('layouts/sidebar') ?>
        </div>

        <div class="col-md-10">

            <?= $this->renderSection('content') ?>

        </div>

    </div>
</div>

</body>
</html>