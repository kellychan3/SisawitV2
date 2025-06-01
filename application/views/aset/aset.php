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
                        <li class="breadcrumb-item active" aria-current="page">Data Aset</li>
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


        <button type="button" class="btn btn-primary px-5 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addAssetModal">
            <i class='bx bx-plus me-2 align-middle'></i>Tambah Data
        </button>

        <!-- Modal Tambah Aset -->
        <div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-primary">Tambah Aset</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" method="post" action="<?= base_url('Aset/addAset'); ?>">
                            <div class="col-md-6">
                                <label for="namaaset" class="form-label">Nama Aset</label>
                                <select class="form-control" id="namaaset" name="namaaset" required oninvalid="this.setCustomValidity('Nama aset wajib dipilih')" oninput="this.setCustomValidity('')">
                                  <option value="">--Pilih Aset--</option>
                                  <option value="Urea">Urea</option>
                                  <option value="MOP">MOP</option>
                                  <option value="NPK">NPK</option>
                                  <option value="TSP">TSP</option>
                                  <option value="Dolomite">Dolomite</option>
                                  <option value="Traktor">Traktor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="jenisaset" class="form-label">Jenis Aset</label>
                                <select class="form-control" id="kategori_id" name="kategori_id" disabled>
                                    <option value="">--</option>
                                    <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama_kategori']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                          
                            <div class="col-md-6">
                                <label for="lokasiaset" class="form-label">Kebun</label>
                                <select class="form-control" id="kebun_id" name="kebun_id" required oninvalid="this.setCustomValidity('Kebun wajib dipilih')" oninput="this.setCustomValidity('')">
                                    <option value="">--Pilih Kebun--</option>
                                    <?php foreach ($kebun as $k): ?>
                                        <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama_kebun']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                             <div class="col-md-6">
                                <label for="jumlahaset" class="form-label">Jumlah Aset</label>
                                <input type="number" class="form-control" id="jumlahaset" name="jumlahaset" required oninvalid="this.setCustomValidity('Jumlah aset wajib diisi')" oninput="this.setCustomValidity('')">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DATA -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
         <table id="aset" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Aset</th>
                                <th>Jumlah Aset</th>
                                <th>Jenis Aset</th>
                                <th>Kebun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($asset)): ?>
                                <tr><td colspan="5" class="text-center">Tidak ada data aset.</td></tr>
                            <?php else: ?>
                                <?php foreach ($asset as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['nama_aset']); ?></td>
                                        <td><?= htmlspecialchars($a['jumlah_aset']); ?></td>
                                        <td><?= htmlspecialchars($a['kategori']['nama_kategori'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($a['kebun']['nama_kebun'] ?? '-'); ?></td>
                                        <td>
                                            <button class="btn btn-secondary" disabled>Ubah</button>
                                            <button class="btn btn-secondary" disabled>Hapus</button>
                                        </td>
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

<script>
$(document).ready(function() {
    const kategoriMap = {
        'Dolomite': 'Pupuk',
        'MOP': 'Pupuk',
        'NPK': 'Pupuk',
        'RP': 'Pupuk',
        'Traktor': 'Alat Berat',
        'TSP': 'Pupuk',
        'Urea': 'Pupuk'
    };

    // Buat mapping nama_kategori ke id dari data kategori yang ada di PHP
    const kategoriIdMap = {};
    <?php foreach ($kategori as $k): ?>
        kategoriIdMap["<?= addslashes($k['nama_kategori']) ?>"] = "<?= $k['id'] ?>";
    <?php endforeach; ?>

    $('#namaaset').on('change', function() {
        const selectedAset = $(this).val();
        const namaKategori = kategoriMap[selectedAset];

        if (namaKategori && kategoriIdMap[namaKategori]) {
            $('#kategori_id').val(kategoriIdMap[namaKategori]).trigger('change');
        } else {
            $('#kategori_id').val('');
        }
    });
});
</script>

