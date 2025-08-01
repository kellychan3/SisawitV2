<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panen</title>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2/dist/chartjs-plugin-annotation.min.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/prediksi.css">
</head>
<body>
    
    <div class="page-wrapper">
        <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Monitoring</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Prediksi Hasil Panen</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="filter-box">
                <!-- Refresh Button -->
                <div class="refresh-form">
                    <form method="post" action="<?= base_url('prediksi/refresh_data'); ?>" style="display: flex; align-items: center; gap: 16px; padding: 8px 16px;">
                        <?php if ($last_updated): ?>
                            <div style="display: flex; flex-direction: column; font-size: 13px; color: white;">
                                <label>Terakhir Diperbarui</label>
                                <label><?= date('d/m/Y H:i:s', strtotime($last_updated)) ?></label>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="id_user" value="<?= $this->session->userdata('id_user'); ?>">
                        <button type="submit" style="padding: 6px 12px; background-color: white; color: black; font-weight: 600; border: none; cursor: pointer;">
                            🔄 Perbarui Dashboard
                        </button>
                    </form>
                </div>

                <!-- Filter Tahun -->
                <div class="filter-form">
                    <form method="get" action="">
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
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
                </div>
            </div>
            
            <?php if ($data_prediksi_tersedia): ?>
            <div class="dashboard-container-row">
                <div class="left-column">
                    <div class="resume-box">
                        <div class="title-box">
                            <div class="resume-title">Resume Laporan</div>
                        </div>
                        <div class="info-box-wrap">
                            <div class="info-box">
                                <div class="label">Prediksi Total Hasil Panen</div>
                                <div class="value"><?= number_format($total_prediksi, 0, ',', '.') ?> Kg</div>
                            </div>
                            <div class="info-box">
                                <div class="label">Realita Total Hasil Panen</div>
                                <?php $color = ($total_aktual >= $total_prediksi) ? 'green' : 'red'; ?>
                                <div class="value" style="color: <?= $color ?>;">
                                    <?= number_format($total_aktual, 0, ',', '.') ?> Kg
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kebun-filter-box">
                        <form method="get" action="">
                            <input type="hidden" name="tahun" value="<?= $filter['tahun'] ?>">

                            <strong style="display: block; margin-bottom: 8px;">FILTER KEBUN</strong>
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <?php foreach ($kebun_list as $k): ?>
                                    <?php $id = $k['kebun']; ?>
                                    <label style="display: flex; justify-content: space-between; align-items: center; font-weight: normal;">
                                        <span><?= $k['nama_kebun'] ?></span>
                                        <input type="checkbox" name="kebun[]" value="<?= $id ?>" <?= in_array($id, $filter['kebun']) ? 'checked' : '' ?> onchange="this.form.submit()" <?= $id === '' ? 'disabled' : '' ?>>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dashboard-box chart-box">
                    <h3>Prediksi vs Aktual Hasil Panen (kg)</h3>
                    <canvas id="panenChart"></canvas>
                    <div id="customLegend" style=" text-align: left;"></div>
                </div>
            </div>

            <div class="dashboard-box chart-box" style="margin-top:15px;">
                    <h3>Tren Panen Aktual 2024–2025 & Prediksi Tahun 2025</h3>
                    <canvas id="lineChart"></canvas>
            </div>

            <?php else: ?>
            <div style="display: flex; justify-content: center; align-items: center; height: 60vh;">
                <div style="max-width: 700px; padding: 40px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 20px;">🌾</div>
                    <h2 style="margin-bottom: 12px; color: #333;">Data Prediksi Belum Tersedia</h2>
                    <p style="color: #666; font-size: 15px;">Silakan hubungi admin untuk melakukan pelatihan model terlebih dahulu.</p>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>
    
<script>
const ctx = document.getElementById('panenChart').getContext('2d');
const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

const prediksiData = <?= json_encode(array_map(fn($i) => $prediksi[$i] ?? 0, range(1,12))) ?>;
const aktualData = <?= json_encode(array_map(fn($i) => $aktual[$i] ?? 0, range(1,12))) ?>;

// Buat chart
const panenChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [
            {
                label: 'Aktual',
                data: aktualData,
                backgroundColor: aktualData.map((val, i) => 
                    val >= prediksiData[i] ? '#1cc88a' : '#e74a3b'
                ),
                barThickness: 20,
                categoryPercentage: 0.6,
                barPercentage: 0.9
            },
            {
                label: 'Prediksi',
                data: prediksiData,
                backgroundColor: '#3c36aeff',
                barThickness: 20,
                categoryPercentage: 0.6,
                barPercentage: 0.9
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false // Nonaktifkan legend default
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y.toLocaleString() + ' kg';
                            
                            if (context.datasetIndex === 0) {
                                const prediksiVal = prediksiData[context.dataIndex];
                                const aktualVal = aktualData[context.dataIndex];
                                const selisih = aktualVal - prediksiVal;
                                if (selisih > 0) {
                                    label += ' (↑ ' + Math.abs(selisih).toLocaleString() + ' kg)';
                                } else if (selisih < 0) {
                                    label += ' (↓ ' + Math.abs(selisih).toLocaleString() + ' kg)';
                                } else {
                                    label += ' (Tepat)';
                                }
                            }
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                stacked: false,
                grid: { display: false }
            },
            y: {
                beginAtZero: true,
                grid: { display: true },
                title: {
                    display: false,
                    text: 'Hasil Panen (Kg)'
                },
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' kg';
                    }
                }
            }
        }
    }
});

// Fungsi untuk membuat custom legend
function createCustomLegend() {
    const legendContainer = document.getElementById('customLegend');
    legendContainer.innerHTML = '';
    
    const legendItems = [
        {
            label: 'Aktual',
            color: 'linear-gradient(90deg, #1cc88a 50%, #e74a3b 50%)',
            border: '1px solid #ddd'
        },
        {
            label: 'Prediksi',
            color: '#3c36aeff',
            border: '1px solid #3c36aeff'
        }
    ];
    
    const legendWrapper = document.createElement('div');
    legendWrapper.style.display = 'flex';
    legendWrapper.style.justifyContent = 'center';
    legendWrapper.style.gap = '20px';
    legendWrapper.style.flexWrap = 'wrap';
    
    legendItems.forEach(item => {
        const legendItem = document.createElement('div');
        legendItem.style.display = 'flex';
        legendItem.style.alignItems = 'center';
        legendItem.style.margin = '0 10px';
        
        const colorBox = document.createElement('div');
        colorBox.style.width = '40px';
        colorBox.style.height = '20px';
        colorBox.style.marginRight = '8px';
        colorBox.style.background = item.color;
        colorBox.style.border = item.border;
        
        const labelText = document.createElement('span');
        labelText.textContent = item.label;
        labelText.style.fontSize = '12px';
        labelText.style.color = '#333';
        
        legendItem.appendChild(colorBox);
        legendItem.appendChild(labelText);
        legendWrapper.appendChild(legendItem);
    });
    
    legendContainer.appendChild(legendWrapper);
}

// Panggil fungsi setelah chart selesai render
setTimeout(createCustomLegend, 500);
</script>

<script>
const timelineCtx = document.getElementById('lineChart').getContext('2d');

new Chart(timelineCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($timeline['labels']) ?>,
        datasets: [
            {
                label: 'Aktual (2024-2025)',
                data: <?= json_encode($timeline['aktual']) ?>,
                borderColor: '#1cc88a',
                backgroundColor: 'transparent',
                tension: 0.3,
                borderWidth: 2,
                fill: false,
                pointBackgroundColor: <?= json_encode($timeline['pointColors']) ?>,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1,
                pointRadius: 5,
                pointHoverRadius: 7
            },
            {
                label: 'Prediksi (2025)',
                data: <?= json_encode($timeline['prediksi']) ?>,
                borderColor: '#3c36aeff',
                backgroundColor: 'transparent',
                tension: 0.3,
                borderWidth: 2,
                borderDash: [5, 3],
                fill: true,
                pointRadius: 3 
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                align: 'start'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y.toLocaleString() + ' kg';
                            
                            // Tambahkan indikator lebih tinggi/rendah untuk aktual 2025
                            if (context.datasetIndex === 0 && context.dataIndex >= 12) {
                                const prediksi = context.chart.data.datasets[1].data[context.dataIndex];
                                const selisih = context.parsed.y - prediksi;
                                if (selisih > 0) {
                                    label += ' (↑ ' + Math.abs(selisih).toLocaleString() + ' kg)';
                                } else if (selisih < 0) {
                                    label += ' (↓ ' + Math.abs(selisih).toLocaleString() + ' kg)';
                                } else {
                                    label += ' (Tepat)';
                                }
                            }
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { 
                    display: true,
                    color: function(context) {
                        return context.index === 11 ? 'rgba(0,0,0,0.3)' : 'rgba(0,0,0,0.1)';
                    }
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' kg';
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>