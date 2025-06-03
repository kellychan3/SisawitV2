<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
  .dataTables_wrapper .dataTables_length select {
    min-width: 50px;
    padding-right: 1.8em; 
}

</style>
<div class="page-wrapper">
    <div class="page-content">
        <!-- breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Perkebunan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Penanaman</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end breadcrumb -->

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <!-- TABEL DATA -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
         <table id="aset" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kebun</th>
                                <th>Jenis Bibit</th>
                                <th>Jumlat Bibit</th>
                                <th>Tanggal Tanam</th>
                                <th>Ditambahkan Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($penanaman)): ?>
                                <tr><td colspan="5" class="text-center">Data penanaman belum pernah ditambahkan, silahkan tambahkan melalui aplikasi mobile.</td></tr>
                            <?php else: ?>
                                <?php foreach ($penanaman as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['nama_kebun']); ?></td>
                                        <td><?= htmlspecialchars($a['nama_varietas_bibit']); ?></td>
                                        <td><?= htmlspecialchars($a['jumlah_bibit']); ?></td>
                                        <td><?= htmlspecialchars($a['tanggal_penanaman']); ?></td>
                                        <td><?= htmlspecialchars($a['nama_user']); ?></td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#aset').DataTable({
        order: [[3, 'desc']],
        columnDefs: [
            { targets: 1, searchable: false }
        ],
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


</script>

