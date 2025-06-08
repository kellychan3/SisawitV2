<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengguna</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Log Aktivitas Pengguna</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="aset" class="table table-striped table-bordered">
                        <?php if ($SystemLog) : ?>
                            <thead>
                                <th>Tanggal Log</th>
                                <th>Nama Log</th>
                            </thead>
                            <?php foreach ($SystemLog as $sl) : ?>
                                <tr>
                                    <td><?= $sl['date']; ?></td>
                                    <td><?= $sl['value']; ?></td>
                                <?php endforeach; ?>
                                </tr>
                            <?php else : ?>
                                <tr style="text-align:center">
                                    <th>Data Log Tidak Tersedia</th>
                                </tr>
                            <?php endif ?>
                    </table>
                </div>
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
        order: [[0, 'desc']],
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
