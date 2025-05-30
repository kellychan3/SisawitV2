<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panen</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
<div class="page-wrapper">
        <div class="page-content">
            <form method="get" action="">
    <div class="filter-box">
        <div class="field">
            <label for="tahun">Tahun</label>
            <select id="tahun" name="tahun" onchange="this.form.submit()">
                <?php foreach($tahun_list as $t): ?>
                    <option value="<?= $t['tahun']; ?>" <?= ($filter['tahun'] == $t['tahun']) ? 'selected' : ''; ?>>
                        <?= $t['tahun']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="bulan">Bulan</label>
            <select id="bulan" name="bulan" onchange="this.form.submit()">
                <option value="">Semua</option>
                <?php foreach($bulan_list as $b): ?>
                    <option value="<?= $b['bulan']; ?>" <?= ($filter['bulan'] == $b['bulan']) ? 'selected' : ''; ?>>
                        <?= $b['bulan']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="kebun">Kebun</label>
        <div class="custom-dropdown">
    <div class="dropdown-label" onclick="toggleDropdown()">Semua ▾</div>
    <div class="dropdown-checkboxes" id="dropdown-kebun">
        <?php foreach($kebun_list as $k): ?>
            <label>
                <input type="checkbox" onchange="this.form.submit()" name="kebun[]" value="<?= $k['nama_kebun']; ?>"
                    <?= (is_array($filter['kebun']) && in_array($k['nama_kebun'], $filter['kebun'])) ? 'checked' : ''; ?>>
                <?= $k['nama_kebun']; ?>
            </label>
        <?php endforeach; ?>
    </div>
    </div>
</div>


    </div>
    
</form>

            <div class="dashboard-container-row">
                <!-- Resume -->
                <div class="resume-box">
                    <div class = "title-box">
                        <div class="resume-title">Resume Laporan</div>
                    </div>
                
                    <div class="info-box-wrap">
                        <div class="info-box-row">
                            <div class="info-box">
                                <div class="label">Jumlah Kebun</div>
                                <div class="value"><?= $summary_kebun->jumlah_kebun ?> Kebun</div>
                            </div>
                            <div class="info-box">
                                <div class="label">Total Luas Kebun</div>
                                <div class="value"><?= number_format($summary_kebun->total_luas, 3) ?> HA</div>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="label">Total Panen Bulan Ini</div>
                            <div class="value"><?= $indikator_panen['nilai']; ?> Kg</div>
                            <div class="label" style="color:<?= $indikator_panen['naik'] ? 'green' : 'red'; ?>">
                                <?= $indikator_panen['naik'] ? '▲' : '▼'; ?> 
                                <?= $indikator_panen['persen']; ?>% dari rata-rata bulanan
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="label">Total Panen Minggu Ini</div>
                                <div class="value"><?= $indikator_panen_mingguan['nilai']; ?> Kg</div>
                                <div class="label" style="color:<?= $indikator_panen_mingguan['naik'] ? 'green' : 'red'; ?>">
                                    <?= $indikator_panen_mingguan['naik'] ? '▲' : '▼'; ?>
                                    <?= $indikator_panen_mingguan['persen']; ?>% dari rata-rata mingguan
                                </div>
                        </div>

                    </div>
                </div>

                <!-- Grafik Panen Bulanan-->
                <div class="dashboard-box chart-box">
                    <h3>Total Panen per Bulan (Kg)</h3>
                    <canvas id="panenChart"></canvas>
                </div>

                <!-- Grafik Luas Kebun -->
                <div class="dashboard-box chart-box">
                    <h3>Luas Kebun</h3>
                    <canvas id="kebunPieChart"></canvas>
                </div>

                <!-- Tabel Persediaan Pupuk -->
                <div class="dashboard-box chart-box">
                    <h3>Data Persediaan Pupuk</h3>
                    <div class="scrollable-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Pupuk</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($persediaan_pupuk as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row->nama_aset); ?></td>
                                        <td><?= number_format($row->jumlah_aset); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="dashboard-container-row">
                
                <!-- Grafik Persentase Panen per Kebun -->
                <div class="dashboard-box chart-box">
                    <h3>Persentase Panen per Kebun</h3>
                 
                </div>

                <!-- Grafik Panen Mingguan-->
                <div class="dashboard-box chart-box">
                    <h3>Total Panen per Minggu per Kebun (Kg)</h3>
                   
                </div>
            </div>
    </div>
</div>
<script>
function toggleDropdown() {
    var dropdown = document.getElementById('dropdown-kebun');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(event) {
    var dropdown = document.getElementById('dropdown-kebun');
    var label = document.querySelector('.dropdown-label');
    if (!label.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});
</script>



<script>
    // Cek dan inisialisasi grafik panen bulanan
    const panenCanvas = document.getElementById('panenChart');
    if (panenCanvas) {
        const labels = <?= json_encode(array_map(function($row) {
            $bulan = ltrim($row->bulan, '0');
            $namaBulan = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
            ][$bulan];
            return $namaBulan . ' ' . $row->tahun;
        }, $panen_per_bulan)); ?>;

        const panenData = <?= json_encode(array_map(fn($row) => (float)$row->total_panen, $panen_per_bulan)); ?>;

        new Chart(panenCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Panen (Kg)',
                    data: panenData,
                    borderColor: 'rgb(31, 4, 154)',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Cek dan inisialisasi pie chart luas kebun
    const kebunCanvas = document.getElementById('kebunPieChart');
    if (kebunCanvas) {
        const pieLabels = <?= json_encode(array_map(fn($row) => $row->nama_kebun, $luas_kebun)); ?>;
        const pieData = <?= json_encode(array_map(fn($row) => (float)$row->total_luas, $luas_kebun)); ?>;

        new Chart(kebunCanvas, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            return ((value / total) * 100).toFixed(1) + '%';
                        },
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
</script>



</body>
</html>
