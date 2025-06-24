<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
.dataTables_wrapper .dataTables_length select {
    min-width: 50px;
    padding-right: 1.8em;
}
</style>

<div class="page-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Perkebunan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Pemupukan</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tabel Data -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pemupukan" class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Kebun</th>
                                <th>Jenis Pupuk</th>
                                <th>Jumlah Pupuk</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Ditambahkan Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if (empty($pemupukan)): ?>
        <tr>
            <td colspan="7" class="text-center text-muted">
                Data pemupukan belum tersedia. Silakan tambahkan melalui aplikasi mobile.
            </td>
        </tr>
    <?php else: ?>
        <?php foreach ($pemupukan as $a): ?>
            <tr>
                <td><?= isset($a['kebun']['nama_kebun']) ? htmlspecialchars($a['kebun']['nama_kebun']) : '-'; ?></td>
                <td><?= isset($a['aset']['nama_aset']) ? htmlspecialchars($a['aset']['nama_aset']) : '-'; ?></td>
                <td><?= isset($a['jumlah_pupuk']) ? htmlspecialchars($a['jumlah_pupuk']) : '-'; ?></td>
                <td><?= isset($a['tanggal_mulai']) ? htmlspecialchars($a['tanggal_mulai']) : '-'; ?></td>
                <td><?= isset($a['tanggal_selesai']) ? htmlspecialchars($a['tanggal_selesai']) : '-'; ?></td>
                <td><?= isset($a['status']) ? htmlspecialchars($a['status']) : '-'; ?></td>
                <td><?= isset($a['added_by']['nama']) ? htmlspecialchars($a['added_by']['nama']) : '-'; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JS DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#pemupukan').DataTable({
        order: [[3, 'desc']],
        columnDefs: [
            { targets: 2, searchable: false } 
        ],
        search: {
            smart: false,
            regex: false,
            caseInsensitive: true
        },
        language: {
            lengthMenu: "Menampilkan _MENU_ entri",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            infoFiltered: "(disaring dari _MAX_ total entri)",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya >",
                previous: "< Sebelumnya"
            }
        }
    });
});

$.fn.dataTable.ext.errMode = 'none';

</script>
