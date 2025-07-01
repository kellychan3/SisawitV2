<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Aset Perkebunan</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <style>
    .dataTables_wrapper .dataTables_length select {
      min-width: 50px;
      padding-right: 1.8em;
    }
  </style>
</head>
<body>
  <!-- Konten dimulai -->
  <div class="page-wrapper">
    <div class="page-content">
      <!-- Breadcrumb -->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Data Perkebunan</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="<?= base_url('Dashboard'); ?>">
                  <i class="bx bx-home-alt"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Aset</li>
            </ol>
          </nav>
        </div>
      </div>

      <!-- Flash message -->
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"> <?= $this->session->flashdata('success'); ?> </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"> <?= $this->session->flashdata('error'); ?> </div>
      <?php endif; ?>

      <!-- Tombol Tambah -->
      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAssetModal">
        <i class='bx bx-plus me-2'></i>Tambah Data
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
                                <select class="form-control" id="kategori_id" name="kategori_id">
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
                                <label for="jumlahaset" class="form-label">Jumlah Aset (Pupuk: Kg)</label>
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

        <!-- Modal Ubah Aset -->
<div class="modal fade" id="editAssetModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title text-primary">Ubah Aset</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="post" action="<?= base_url('Aset/editAset'); ?>">
          <input type="hidden" id="edit_id" name="id">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Aset</label>
              <input type="text" id="edit_namaaset" class="form-control" disabled>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jenis Aset</label>
              <input type="text" id="edit_jenisaset" class="form-control" disabled>
            </div>

            <div class="col-md-6">
              <label class="form-label">Kebun</label>
              <input type="text" id="edit_namakebun" class="form-control" disabled>
              <input type="hidden" id="edit_kebun_id" name="kebun_id">
            </div>

            <div class="col-md-6">
              <label for="edit_jumlahaset" class="form-label">Jumlah Aset</label>
              <input type="number" class="form-control" id="edit_jumlahaset" name="jumlah_aset" required>
            </div>
          </div>

          <div class="modal-footer mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="<?= base_url('Aset/deleteAset'); ?>">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menghapus aset ini?
          <input type="hidden" id="delete_id" name="id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>


      <!-- Tabel -->
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="aset" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Nama Aset</th>
                  <th>Jumlah Aset</th>
                  <th>Jenis Aset</th>
                  <th>Kebun</th>
                  <?php if ($this->session->userdata('role') !== 'mandor'): ?>
                    <th>Aksi</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($asset)): ?>
                  <tr><td colspan="5" class="text-center">Belum ada aset ditambahkan.</td></tr>
                <?php else: ?>
                  <?php foreach ($asset as $a): ?>
                    <tr>
                      <td><?= htmlspecialchars($a['nama_aset']); ?></td>
                      <td><?= htmlspecialchars($a['jumlah_aset']) . ' Kg'; ?></td>
                      <td><?= htmlspecialchars($a['kategori_aset']['nama_kategori'] ?? '-'); ?></td>
                      <td><?= htmlspecialchars($a['kebun']['nama_kebun'] ?? '-'); ?></td>
                      <?php if ($this->session->userdata('role') == 'pemilik'): ?>
                        <td>
                          <button class="btn btn-warning edit-btn"
                            data-id="<?= $a['id']; ?>"
                            data-nama="<?= htmlspecialchars($a['nama_aset']); ?>"
                            data-jenis="<?= htmlspecialchars($a['kategori_aset']['nama_kategori'] ?? '-'); ?>"
                            data-kebun-id="<?= $a['kebun']['id'] ?? ''; ?>"
                            data-kebun-nama="<?= htmlspecialchars($a['kebun']['nama_kebun'] ?? '-'); ?>"
                            data-jumlah="<?= $a['jumlah_aset']; ?>">
                            Ubah
                          </button>
                          <button class="btn btn-danger delete-btn" data-id="<?= $a['id']; ?>">Hapus</button>
                        </td>
                      <?php endif; ?>
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

  <!-- Script dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#aset').DataTable({
        columnDefs: [{ targets: 1, searchable: false }],
        language: {
          lengthMenu: "Menampilkan _MENU_ entri",
          zeroRecords: "Data tidak ditemukan",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
          infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
          infoFiltered: "(disaring dari _MAX_ total entri)",
          search: "Cari:",
          paginate: {
            first: "Pertama", last: "Terakhir", next: "Berikutnya >", previous: "< Sebelumnya"
          }
        }
      });

      const kategoriMap = {
        'Dolomite': 'Pupuk', 'MOP': 'Pupuk', 'NPK': 'Pupuk', 'RP': 'Pupuk',
        'Traktor': 'Alat Berat', 'TSP': 'Pupuk', 'Urea': 'Pupuk'
      };

      const kategoriIdMap = {};
      <?php foreach ($kategori as $k): ?>
        kategoriIdMap["<?= addslashes($k['nama_kategori']) ?>"] = "<?= $k['id'] ?>";
      <?php endforeach; ?>

      $('#namaaset').on('change', function () {
        const kategori = kategoriMap[$(this).val()];
        $('#kategori_id').val(kategoriIdMap[kategori] || '');
      });

      const editModal = new bootstrap.Modal(document.getElementById('editAssetModal'));
      const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

      $(document).on('click', '.edit-btn', function () {
  $('#edit_id').val($(this).data('id'));
  $('#edit_namaaset').val($(this).data('nama'));
  $('#edit_jenisaset').val($(this).data('jenis'));
  $('#edit_namakebun').val($(this).data('kebun-nama'));
  $('#edit_kebun_id').val($(this).data('kebun-id'));
  $('#edit_jumlahaset').val($(this).data('jumlah'));
  editModal.show();
});

$(document).on('click', '.delete-btn', function () {
  $('#delete_id').val($(this).data('id'));
  deleteModal.show();
});


      $('#confirmDeleteBtn').on('click', function () {
        const id = $('#delete_id').val();
        $.ajax({
          url: `http://103.150.101.10/api/aset/${id}`,
          method: 'DELETE',
          headers: {
            'Authorization': 'Bearer <?= $this->session->userdata('token') ?>',
            'Accept': 'application/json'
          },
          success: function () {
  // Tampilkan notifikasi sukses di atas
  $('body').prepend('<div class="alert alert-success">Aset berhasil diubah.</div>');
  setTimeout(() => location.reload(), 1500);
},
          error: function () {
            alert('Gagal menghapus aset.');
          }
        });
      });
    });

    $.fn.dataTable.ext.errMode = 'none';
  </script>
</body>
</html>
