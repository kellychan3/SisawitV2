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
            <div class="breadcrumb-title pe-3">Data Pengguna</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Pengguna</li>
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
                                <th>Nama</th>
                                <th>Nomor HP</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr><td colspan="5" class="text-center">Tidak ada data aset.</td></tr>
                            <?php else: ?>
                                <?php foreach ($users as $u): ?>
    <?php if ($u['is_active'] == 1): ?>
        <tr>
            <td><?= htmlspecialchars($u['nama']); ?></td>
            <td><?= htmlspecialchars($u['no_hp']); ?></td>
            <td><?= htmlspecialchars($u['email']); ?></td>
            <td><?= htmlspecialchars($u['role']); ?></td>
        </tr>
    <?php endif; ?>
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


</script>

