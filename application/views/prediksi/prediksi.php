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
                </div>
            </form>

            <div class="dashboard-container-row">
                <div class="resume-box">
                    <div class = "title-box">
                        <div class="resume-title">Resume Laporan</div>
                    </div>
                
                    <div class="info-box-wrap">
                            <div class="info-box">
                            <div class="label">Prediksi Total Hasil Panen</div>
                            <!-- <div class="value"><?= $indikator_panen['nilai']; ?> Kg</div> -->
                        </div>
                        <div class="info-box">
                            <div class="label">Realita Total Hasil Panen</div>
                                <!-- <div class="value"><?= $indikator_panen_mingguan['nilai']; ?> Kg</div> -->
                        </div>
                    </div>
                </div>

                <!-- Grafik Prediksi vs Aktual-->
                <div class="dashboard-box chart-box">
                    <h3>Prediksi vs Aktual Hasil Panen (kg)</h3>
                 <canvas id="panenChart"></canvas>

                </div>
            </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('panenChart').getContext('2d');
    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const prediksiData = <?= json_encode(array_map(fn($i) => $prediksi[$i] ?? 0, range(1,12))) ?>;
    const aktualData = <?= json_encode(array_map(fn($i) => $aktual[$i] ?? 0, range(1,12))) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Prediksi',
                    backgroundColor: '#4e73df',
                    data: prediksiData
                },
                {
                    label: 'Aktual',
                    backgroundColor: '#1cc88a',
                    data: aktualData
                }
            ]
        },
        options: {
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: Math.round,
                    font: {
                        weight: 'bold'
                    }
                }
            },
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Hasil Panen (Kg)'
                    }
                }
            }
        }
    });
</script>


</body>
</html>
