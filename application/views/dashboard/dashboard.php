<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Panen</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .dashboard-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        align-items: flex-start;
    }

    .chart-box {
        flex: 1;
        min-width: 400px;
    }

    .chart-box canvas {
        height: 400px !important;
    }

    .chart-box table {
    width: 100%;
    border-collapse: collapse;
}
.chart-box th, .chart-box td {
    padding: 8px;
    text-align: left;
}

</style>

</head>
<body>
    <div class="page-wrapper">
        <div class="page-content">
			<form method="get" action="">
    <label for="tahun">Tahun:</label>
    <select id="tahun" name="tahun">
        <option value="">-- Semua Tahun --</option>
        <?php foreach($tahun_list as $t): ?>
            <option value="<?= $t['tahun']; ?>" <?= ($filter['tahun'] == $t['tahun']) ? 'selected' : ''; ?>>
                <?= $t['tahun']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="bulan">Bulan:</label>
    <select id="bulan" name="bulan">
        <option value="">-- Semua Bulan --</option>
        <?php foreach($bulan_list as $b): ?>
            <option value="<?= $b['bulan']; ?>" <?= ($filter['bulan'] == $b['bulan']) ? 'selected' : ''; ?>>
                <?= $b['bulan']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="kebun">Kebun:</label>
    <select id="kebun" name="kebun">
        <option value="">-- Semua Kebun --</option>
        <?php foreach($kebun_list as $k): ?>
            <option value="<?= $k['nama_kebun']; ?>" <?= ($filter['kebun'] == $k['nama_kebun']) ? 'selected' : ''; ?>>
                <?= $k['nama_kebun']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filter</button>
</form>
            <div class="dashboard-container">
				

                <div class="chart-box">
                    <h2>Total Panen per Bulan (Kg)</h2>
                    <canvas id="panenChart"></canvas>
                </div>
                <div class="chart-box">
                    <h2>Luas Kebun</h2>
                    <canvas id="kebunPieChart"></canvas>
                </div>

                <div class="chart-box">
    <h2>Data Persediaan Pupuk</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Aset</th>
                <th>Jumlah</th>
                <th>Nama Kebun</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($persediaan_pupuk as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->nama_aset); ?></td>
                    <td><?= number_format($row->jumlah_aset); ?></td>
                    <td><?= htmlspecialchars($row->nama_kebun); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


            </div>
        </div>
    </div>

    <script>
        const labels = <?= json_encode(array_map(function($row) {
            return $row->bulan . ' ' . $row->tahun;
        }, $panen_per_bulan)); ?>;

        const data = {
            labels: labels,
            datasets: [{
                label: 'Total Panen (Kg)',
                data: <?= json_encode(array_map(function($row) {
                    return (float)$row->total_panen;
                }, $panen_per_bulan)); ?>,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        };

        const config = {
            type: 'line',
            data: data,
        };

        new Chart(
            document.getElementById('panenChart'),
            config
        );
    </script>

    <script>
        const pieLabels = <?= json_encode(array_map(function($row) {
            return $row->nama_kebun;
        }, $luas_kebun)); ?>;

        const pieData = <?= json_encode(array_map(function($row) {
            return (float)$row->total_luas;
        }, $luas_kebun)); ?>;

        const pieConfig = {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    label: 'Luas Kebun (HA)',
                    data: pieData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ]
                }]
            }
        };

        new Chart(
            document.getElementById('kebunPieChart'),
            pieConfig
        );
    </script>
</body>
</html>
