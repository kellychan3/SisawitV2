<!DOCTYPE html>
<html>
<head>
    <title>Data Kebun</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Laporan Data Kebun SisawitV2</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kebun</th>
                <th>Luas (Ha)</th>
                <th>Jenis Tanah</th>
                <th>Desa</th>
                <th>Kecamatan</th>
                <th>Kabupaten</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($kebun as $k) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $k['nama_kebun']; ?></td>
                <td><?= $k['luas_kebun_ha']; ?></td>
                <td><?= $k['jenis_tanah']; ?></td>
                <td><?= $k['desa']; ?></td>
                <td><?= $k['kecamatan']; ?></td>
                <td><?= $k['kabupaten_kota']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
