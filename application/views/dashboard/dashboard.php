<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panen</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="page-content">
            <div class="dashboard-guide">
    <div class="guide-header">
        <span class="guide-icon">ℹ️</span>
        <h3>Panduan Dashboard</h3>
        <button class="guide-toggle">×</button>
    </div>
    <div class="guide-content" style="font-size:14px; line-height:1.5;">
    <div class="guide-tip">
        📊 <strong>Filter Data:</strong> Data dan grafik akan otomatis menyesuaikan berdasarkan pilihan Anda. Anda dapat memilih filter Tahun, Bulan, dan Kebun.
    </div>
    <div class="guide-tip">
        🔄 <strong>Perbarui Data:</strong> Klik "Perbarui Dashboard" untuk data terbaru.
    </div>
</div>

</div>

            <div class="filter-box">
                <div class="refresh-form">
                    <form method="post" action="<?= base_url('dashboard/refresh_data'); ?>" style="display: flex; align-items: center; gap: 16px; padding: 8px 16px;">
    
                        <?php if ($last_updated): ?>
                            <div style="display: flex; flex-direction: column; justify-content: center; font-family: inherit; font-size: 13px; color: white; line-height: 1.4;">
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
     
                <div class="filter-form">
                    <form method="get" action="">
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            
                            <!-- Tahun -->
                            <div class="field">
                                <label for="tahun">Tahun</label>
                                <select id="tahun" name="tahun">
                                    <?php foreach($tahun_list as $t): ?>
                                        <option value="<?= $t['tahun']; ?>" <?= ($filter['tahun'] == $t['tahun']) ? 'selected' : ''; ?>>
                                            <?= $t['tahun']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Bulan -->
                            <div class="field">
                                <label for="bulan">Bulan</label>
                                 <div class="custom-dropdown">
                                    <?php
                                        $bulan_indonesia = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];

                                        $bulan_valid = array_column($bulan_list, 'bulan');
                                        $bulan_terpilih = isset($filter['bulan']) ? (array)$filter['bulan'] : [];
                                        $bulan_terpilih = array_intersect($bulan_terpilih, $bulan_valid);
                                        
                                        // Hitung apakah semua bulan terpilih
                                        $semua_bulan_terpilih = !empty($bulan_valid) && count($bulan_terpilih) === count($bulan_valid);
                                        $label_bulan = $semua_bulan_terpilih ? 'Semua' : 'Dipilih (' . count($bulan_terpilih) . ')';
                                    ?>
                                    <div class="dropdown-label" onclick="toggleDropdown('bulan')">
                                        <?= $label_bulan ?> ▾
                                    </div>
                                    <div class="dropdown-checkboxes" id="dropdown-bulan">
                                        <label class="select-all-label">
                                            <input type="checkbox" id="select-all-bulan" 
                                                <?= $semua_bulan_terpilih ? 'checked' : '' ?>>
                                            <strong>Semua</strong>
                                        </label>
                                        <?php foreach($bulan_list as $b): 
                                            $bulan_num = (int)$b['bulan'];
                                            $nama_bulan = $bulan_indonesia[$bulan_num];
                                        ?>
                                            <label>
                                                <input type="checkbox" name="bulan[]" value="<?= $bulan_num ?>"
                                                    <?= in_array($bulan_num, $bulan_terpilih) ? 'checked' : '' ?>>
                                                <?= $nama_bulan ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Kebun -->
                            <div class="field">
                                <label for="kebun">Kebun</label>
                                <div class="custom-dropdown">
                                    <?php
                                        $kebun_valid = array_column($kebun_list, 'sk_kebun');
                                        $filter_kebun = isset($filter['kebun']) ? (array)$filter['kebun'] : [];
                                        $kebun_terpilih = array_intersect($filter_kebun, $kebun_valid);
                                        $semua_kebun_terpilih = count($kebun_terpilih) === count($kebun_valid);
                                        $label_kebun = $semua_kebun_terpilih ? 'Semua' : 'Dipilih (' . count($kebun_terpilih) . ')';
                                    ?>
                                    <div class="dropdown-label" onclick="toggleDropdown('kebun')">
                                        <?= $label_kebun ?> ▾
                                    </div>
                                    <div class="dropdown-checkboxes" id="dropdown-kebun">
                                        <label class="select-all-label">
                                            <input type="checkbox" id="select-all-kebun" 
                                                <?= $semua_kebun_terpilih ? 'checked' : '' ?>>
                                            <strong>Semua</strong>
                                        </label>
                                        <?php foreach($kebun_list as $k): ?>
                                            <label>
                                                <input type="checkbox" name="kebun[]" value="<?= $k['sk_kebun']; ?>"
                                                    <?= in_array($k['sk_kebun'], $filter_kebun) ? 'checked' : ''; ?>>
                                                <?= $k['nama_kebun']; ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
            
            <div class="dashboard-container-row  first-row">
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
                                <div class="value"><?= number_format($summary_kebun->total_luas) ?> HA</div>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="label">Total Panen Bulan Ini</div>
                            <div class="value"><?= $indikator_panen['nilai']; ?> Kg</div>
                            <?php if ($indikator_panen['nilai'] == '0,00' && $indikator_panen['persen'] == 0): ?>
                                <div class="label" style="color:gray;"><em>📉 Belum ada data panen.</em></div>
                            <?php elseif ($indikator_panen['persen'] == 0): ?>
                                <div class="label" style="color:gray;">
                                    <em>Sama dengan rata-rata bulanan</em>
                                </div>
                            <?php else: ?>
                                <div class="label" style="color:<?= $indikator_panen['naik'] ? 'green' : 'red'; ?>">
                                    <?= $indikator_panen['naik'] ? '▲' : '▼'; ?> 
                                    <?= $indikator_panen['persen']; ?>% dari rata-rata bulanan
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="info-box">
                            <div class="label">Total Panen Minggu Ini</div>
                                <div class="value"><?= $indikator_panen_mingguan['nilai']; ?> Kg</div>
                                <?php if ($indikator_panen_mingguan['nilai'] == '0,00' && $indikator_panen_mingguan['persen'] == 0): ?>
                                <div class="label" style="color:gray;"><em>📉 Belum ada data panen.</em></div>
                                <?php elseif ($indikator_panen_mingguan['persen'] == 0): ?>
                                    <div class="label" style="color:gray;">
                                <em>Sama dengan rata-rata mingguan</em>
                            </div>
                        <?php else: ?>
                                <div class="label" style="color:<?= $indikator_panen_mingguan['naik'] ? 'green' : 'red'; ?>">
                                    <?= $indikator_panen_mingguan['naik'] ? '▲' : '▼'; ?>
                                    <?= $indikator_panen_mingguan['persen']; ?>% dari rata-rata mingguan
                                </div>
                                <?php endif; ?>
                        </div>

                    </div>
                </div>

                <!-- Grafik Panen Bulanan-->
                <div class="dashboard-box chart-box panen-bulanan">
                    <h3>Total Panen per Bulan (Kg)</h3>
                    <?php if (empty($panen_per_bulan )): ?>
                        <div class="empty-chart">
                            <p>📉 Belum ada data panen. Silahkan inputkan melalui aplikasi mobile</p>
                        </div>
                    <?php else: ?>
                        <canvas id="panenChart"></canvas>
                    <?php endif; ?>
                </div>

                <!-- Grafik Luas Kebun -->
                <div class="dashboard-box chart-box piechart luas-kebun">
                    <h3 style="text-align: center;">Luas Kebun</h3>
                    <?php if (empty($luas_kebun )): ?>
                        <div class="empty-chart">
                            <p>📉 Belum ada data kebun. Silahkan inputkan melalui aplikasi mobile</p>
                        </div>
                    <?php else: ?>
                        <canvas id="kebunPieChart"></canvas>
                    <?php endif; ?>
                </div>

                <!-- Tabel Persediaan Pupuk -->
                <div class="dashboard-box chart-box pupuk">
                    <h3>Data Persediaan Pupuk</h3>
                    <?php if (empty($persediaan_pupuk)): ?>
                        <div class="empty-chart">
                            <p>📦 Belum ada data pupuk. Silakan inputkan melalui menu Data Aset Barang</p>
                        </div>
                    <?php else: ?>
                        <div class="scrollable-table">
    <table style="height: 210px">
        <thead>
            <tr>
                <th onclick="sortTable(0)" style="cursor:pointer;">
                    Nama Pupuk <span id="sort-icon-nama">↕</span>
                </th>
                <th onclick="sortTable(1)" style="cursor:pointer;">
                    Stok (Kg) <span id="sort-icon-stok">↕</span>
                </th>
            </tr>
        </thead>
        <tbody id="pupuk-table-body">
            <?php foreach($persediaan_pupuk as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->nama_aset); ?></td>
                    <td><?= number_format($row->jumlah_aset); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
                    <?php endif; ?>
                </div>
            </div>
                
            <div class="dashboard-container-row second-row">
                <!-- Grafik Persentase Panen per Kebun -->
                <div class="dashboard-box chart-box donut persentase-panen">
                    <h3 style="text-align: center;">Persentase Panen per Kebun</h3>
                    <?php if (empty($persentase_panen_kebun )): ?>
                        <div class="empty-chart">
                            <p>📉 Belum ada data kebun. Silahkan inputkan melalui aplikasi mobile</p>
                        </div>
                    <?php else: ?>
                        <canvas id="persentasePanenKebunChart"></canvas>
                    <?php endif; ?>
                </div>

                <!-- Grafik Panen Mingguan-->
                <div class="dashboard-box chart-box panen-mingguan">
                    <h3>Total Panen per Minggu per Kebun (Kg)</h3>
                    <?php if (empty($panen_mingguan_kebun )): ?>
                        <div class="empty-chart">
                            <p>📉 Belum ada data panen. Silahkan inputkan melalui aplikasi mobile</p>
                        </div>
                    <?php else: ?>
                        <canvas id="panenMingguanKebunChart"></canvas>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</div>

<script>
function toggleDropdown(type) {
    const dropdown = document.getElementById('dropdown-' + type);
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    event.stopPropagation(); // tambahkan ini
}

document.addEventListener('click', function(event) {
    ['kebun', 'bulan'].forEach(type => {
        const dropdown = document.getElementById('dropdown-' + type);
        const label = document.querySelector(`.dropdown-label[onclick*="${type}"]`);

        if (dropdown && !dropdown.contains(event.target) && !label.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
});

</script>

<script>
// Fungsi untuk handle "Pilih Semua" pada checkbox
function setupSelectAll(selectAllId, checkboxName) {
    const selectAll = document.getElementById(selectAllId);
    const checkboxes = document.querySelectorAll(`input[name="${checkboxName}[]"]`);
    const dropdownLabel = document.querySelector(`.dropdown-label[onclick*="${checkboxName}"]`);

    // Handle ketika "Pilih Semua" dicentang
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateLabel(checkboxName);
        submitForm();
    });

    // Handle ketika checkbox individual diubah
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            selectAll.checked = allChecked;
            updateLabel(checkboxName);
            submitForm();
        });
    });

    // Fungsi untuk update label dropdown
    function updateLabel(type) {
        const checkboxes = document.querySelectorAll(`input[name="${type}[]"]:checked`);
        const totalCheckboxes = document.querySelectorAll(`input[name="${type}[]"]`).length;
        const label = document.querySelector(`.dropdown-label[onclick*="${type}"]`);
        
        if (checkboxes.length === totalCheckboxes || checkboxes.length === 0) {
            label.textContent = 'Semua ▾';
        } else {
            label.textContent = `Dipilih (${checkboxes.length}) ▾`;
        }
    }

    // Fungsi untuk submit form
    function submitForm() {
        setTimeout(() => {
            document.querySelector('.filter-form form').submit();
        }, 300);
    }
}

// Inisialisasi untuk bulan dan kebun
document.addEventListener('DOMContentLoaded', function() {
    setupSelectAll('select-all-bulan', 'bulan');
    setupSelectAll('select-all-kebun', 'kebun');
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
            return $namaBulan;
        }, $panen_per_bulan)); ?>;

        const panenData = <?= json_encode(array_map(fn($row) => (float)$row->total_panen, $panen_per_bulan)); ?>;

        new Chart(panenCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                {
                    label: 'Total Panen (Kg)',
                    data: panenData,
                    borderColor: 'rgb(31, 4, 154)', // garis biru
                    fill: false,
                    tension: 0.1,
                    pointBackgroundColor: panenData.map(p => p >= <?= (float)$rata_panen_bulanan ?> ? 'green' : 'red'), // warna titik
                    pointBorderWidth: 0, // tanpa border
                    pointRadius: 5
                }
                ]

            },
            options: {
            responsive: true,
            maintainAspectRatio: false,
            
            plugins: {
                legend: { display: false },
                annotation: {
                    annotations: {
                        garisRataRata: {
                            type: 'line',
                            yMin: <?= (float)$rata_panen_bulanan ?>,
                            yMax: <?= (float)$rata_panen_bulanan ?>,
                            borderColor: 'rgb(31, 4, 154)',
                            borderWidth: 1,
                            borderDash: [5, 5]
                        }
                    }
                }
            },
            scales: {
                x: {
                    offset: true,
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { display: true }
                }
            }
        }


        });
    }

    // Cek dan inisialisasi pie chart luas kebun
    const kebunCanvas = document.getElementById('kebunPieChart');
    if (kebunCanvas) {
        const pieLabels = <?= json_encode(array_map(fn($row) => $row->nama_kebun, $luas_kebun)); ?>;
        const pieData = <?= json_encode(array_map(fn($row) => (float)$row->total_luas, $luas_kebun)); ?>;

        const warnaKebun = <?= json_encode($warna_kebun); ?>;
        const backgroundColors = pieLabels.map(label => warnaKebun[label] || 'gray');

        new Chart(kebunCanvas, {
            type: 'pie',
            data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: backgroundColors
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
                            pointStyle: 'circle',
                            font: {
                                size: 11
            }
                        }
                    },
                    tooltip: {
        callbacks: {
            label: function(context) {
                let label = context.label || '';
                let value = context.parsed;
                return value.toLocaleString('id-ID') + ' HA';
            }
        }
    },
                    datalabels: {
                        formatter: (value, ctx) => {
            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            return ((value / total) * 100).toFixed(1) + '%';
        },
                        color: 'white',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
</script>

<script>
    const persentaseCanvas = document.getElementById('persentasePanenKebunChart');
    if (persentaseCanvas) {
        const donutLabels = <?= json_encode(array_map(fn($row) => $row->nama_kebun, $persentase_panen_kebun)); ?>;
        const kebunTotalPanen = <?= json_encode(array_map(fn($row) => (float)$row->total_panen_kebun, $persentase_panen_kebun)); ?>;
        const donutData = <?= json_encode(array_map(fn($row) => (float)$row->persentase, $persentase_panen_kebun)); ?>;

        const warnaKebun = <?= json_encode($warna_kebun); ?>;

    const donutColors = donutLabels.map(label => warnaKebun[label] || 'gray');

        new Chart(persentaseCanvas, {
    type: 'doughnut',
    data: {
            labels: donutLabels,
            datasets: [{
                data: donutData,
                backgroundColor: donutColors
            }]
        },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: { size: 11 }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const index = context.dataIndex;
                        const label = context.label || '';
                        const totalPanen = kebunTotalPanen?.[index] ?? 0;
                        return `${totalPanen.toLocaleString('id-ID')} Kg`;
                    }
                }
            },
            datalabels: {
                formatter: (value, ctx) => {
                    const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    const percentage = (value / total) * 100;
                    return percentage.toFixed(1) + '%';
                },
                color: 'white',
                font: { size: 12 }
            }
        }
    },
    plugins: [ChartDataLabels]
});

    }
</script>

<script>
    let currentSortColumn = -1;
    let currentSortDirection = 0; // 0 = unsorted, 1 = ascending, 2 = descending

    function sortTable(columnIndex) {
        const tableBody = document.getElementById('pupuk-table-body');
        const rows = Array.from(tableBody.querySelectorAll('tr'));
        const sortIconNama = document.getElementById('sort-icon-nama');
        const sortIconStok = document.getElementById('sort-icon-stok');
        
        // Reset all sort icons
        sortIconNama.textContent = '↕';
        sortIconStok.textContent = '↕';
        
        // If clicking same column, toggle direction. Else start with ascending.
        if (currentSortColumn === columnIndex) {
            currentSortDirection = (currentSortDirection + 1) % 3;
        } else {
            currentSortColumn = columnIndex;
            currentSortDirection = 1;
        }
        
        // Get the appropriate sort icon for the current column
        const currentSortIcon = columnIndex === 0 ? sortIconNama : sortIconStok;
        
        // Update sort icon based on direction
        if (currentSortDirection === 1) {
            currentSortIcon.textContent = '↑';
        } else if (currentSortDirection === 2) {
            currentSortIcon.textContent = '↓';
        } else {
            currentSortIcon.textContent = '↕';
        }
        
        // If unsorted, return to original order
        if (currentSortDirection === 0) {
            tableBody.innerHTML = '';
            rows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            return;
        }
        
        const isAscending = currentSortDirection === 1;
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            if (columnIndex === 1) {
                // Numeric sorting for Stok column
                const aNum = parseFloat(aValue.replace(/,/g, ''));
                const bNum = parseFloat(bValue.replace(/,/g, ''));
                return isAscending ? aNum - bNum : bNum - aNum;
            } else {
                // Alphabetical sorting for Nama Pupuk column
                return isAscending 
                    ? aValue.localeCompare(bValue, 'id') 
                    : bValue.localeCompare(aValue, 'id');
            }
        });
        
        // Clear and re-append sorted rows
        tableBody.innerHTML = '';
        rows.forEach(row => tableBody.appendChild(row));
    }
</script>

<script>
    const ctx = document.getElementById('panenMingguanKebunChart').getContext('2d');

    const labels = <?= json_encode($labels); ?>;
    const datasets = <?= json_encode($datasets); ?>;
    const rataMingguan = <?= json_encode($rata_panen_mingguan); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            indexAxis: 'x',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: { display: false },
                    ticks: {
                        callback: function (val, index) {
                            return this.getLabelForValue(val).split('\n');
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    title: { display: false }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                },
                annotation: {
                    annotations: {
                        garisRataRata: {
                            type: 'line',
                            yMin: rataMingguan,
                            yMax: rataMingguan,
                            borderColor: 'rgb(31, 4, 154)',
                            borderWidth: 1.5,
                            borderDash: [5, 5]
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.filter-form form');
    const tahunSelect = document.getElementById('tahun');
    const bulanContainer = document.getElementById('dropdown-bulan');
    const bulanLabel = document.querySelector('.dropdown-label[onclick*="bulan"]');

    function updateLabelBulan() {
        const checked = bulanContainer.querySelectorAll('input[type="checkbox"]:checked').length;
        bulanLabel.textContent = checked === 0 ? 'Semua ▾' : `Dipilih (${checked}) ▾`;
    }

    function attachCheckboxListeners() {
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                form.submit();
            });
        });
    }

    attachCheckboxListeners();

    tahunSelect.addEventListener('change', function () {
        const selectedTahun = this.value;

        fetch("<?= base_url('dashboard/get_bulan_by_tahun') ?>", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `tahun=${encodeURIComponent(selectedTahun)}`
        })
        .then(response => response.json())
        .then(data => {
            bulanContainer.innerHTML = '';

            if (data.length === 0) {
                bulanContainer.innerHTML = '<p>Tidak ada bulan tersedia</p>';
                return;
            }

            // Sort months in descending order (newest first)
            data.sort((a, b) => parseInt(b.bulan) - parseInt(a.bulan));
            
            // Only select the first 2 months by default
            const monthsToSelect = data.slice(0, 2).map(item => item.bulan);

            data.forEach(b => {
                const namaBulan = b.nama; 
                const label = document.createElement('label');
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'bulan[]';
                input.value = b.bulan;
                input.checked = monthsToSelect.includes(b.bulan.toString());
                input.addEventListener('click', function(event) {
                    event.stopPropagation();
                });

                label.appendChild(input);
                label.appendChild(document.createTextNode(' ' + namaBulan));
                bulanContainer.appendChild(label);
            });

            updateLabelBulan();
            attachCheckboxListeners();

            // Submit automatically after updating months
            setTimeout(() => {
                form.submit();
            }, 200);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script>

<script>
// Toggle guide visibility
document.addEventListener('DOMContentLoaded', function() {
    const guide = document.querySelector('.dashboard-guide');
    const toggleBtn = document.querySelector('.guide-toggle');
    
    if (guide && toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            guide.classList.toggle('collapsed');
        });
        
        // Simpan state di localStorage
        const guideState = localStorage.getItem('dashboardGuideCollapsed');
        if (guideState === 'true') {
            guide.classList.add('collapsed');
        }
        
        guide.addEventListener('transitionend', function() {
            if (guide.classList.contains('collapsed')) {
                localStorage.setItem('dashboardGuideCollapsed', 'true');
            } else {
                localStorage.setItem('dashboardGuideCollapsed', 'false');
            }
        });
    }
});
</script>
</body>