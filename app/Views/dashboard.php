<!DOCTYPE html>
<html>
<head>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-light">

<div class="container mt-4">

<h2 class="mb-4">Dashboard Monitoring Documents</h2>

<div class="row mb-4">

<div class="col-md-4">
<div class="card bg-primary text-white text-center">
<div class="card-body">
<h5>Total Documents</h5>
<h2><?= $summary->total_documents ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card bg-success text-white text-center">
<div class="card-body">
<h5>Active</h5>
<h2><?= $summary->active_documents ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card bg-danger text-white text-center">
<div class="card-body">
<h5>Inactive</h5>
<h2><?= $summary->inactive_documents ?></h2>
</div>
</div>
</div>

</div>

<div class="row">

<div class="col-md-6">
<canvas id="statusChart"></canvas>
</div>

<div class="col-md-6">
<canvas id="typeChart"></canvas>
</div>

</div>

<hr>

<h4>Latest Documents</h4>

<table class="table table-bordered">

<thead>
<tr>
<th>Code</th>
<th>Name</th>
<th>Version</th>
<th>Status</th>
<th>Document</th>
</tr>
</thead>

<tbody>

<?php foreach($latestDocs as $doc): ?>

<tr>

<td><?= $doc->document_code ?></td>

<td><?= $doc->document_name ?></td>

<td><?= $doc->version ?></td>

<td>

<?php if($doc->is_active): ?>

<span class="badge bg-success">Active</span>

<?php else: ?>

<span class="badge bg-danger">Inactive</span>

<?php endif; ?>

</td>

<td>
<a href="<?= $doc->file_url ?>" target="_blank">View</a>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<script>

const statusData = <?= json_encode($statusChart) ?>;
const typeData = <?= json_encode($typeChart) ?>;

new Chart(document.getElementById('statusChart'), {

type: 'pie',

data: {
labels: statusData.map(e => e.status),
datasets: [{
data: statusData.map(e => e.total)
}]
}

});

new Chart(document.getElementById('typeChart'), {

type: 'bar',

data: {
labels: typeData.map(e => e.name),
datasets: [{
data: typeData.map(e => e.total)
}]
}

});

</script>

</body>
</html>