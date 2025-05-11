<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title>Sisawit Kebun</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
</head>
<body>
<h1 class="text-center bg-info">Sisawit Kebun</h1>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Kebun</th>
            <th>QR Code</th>
        </tr>
    </thead>
    <tbody>
		<?php $i = 1; foreach ($kebun as $k): ?>
		<tr>
            <td><?= $i; ?></td>
            <td><?= $k['nama']; ?></td>
            <td>
			<img 
					src="<?= 'qr/kebun/'. $k['id_kebun'] . '.jpg' ?>" 
					width="100" 
					height="100">
			</td>
        </tr>
		<?php $i++; ?>
		<?php endforeach; ?>
        <tbody>
</table>
</body>
</html>
