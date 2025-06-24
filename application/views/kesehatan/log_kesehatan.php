<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
  .dataTables_wrapper .dataTables_length select {
    min-width: 50px;
    padding-right: 1.8em; 
  }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Monitoring</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Log Kesehatan</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

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
                <th>No</th>
                <th>Nama Kebun</th>
                <th>Blok Kebun</th>
                <th>Tanggal Foto</th>
                <th>Pohon Sehat</th>
                <th>Pohon Tidak Sehat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($kesehatan)): ?>
                <tr>
                  <td colspan="7" class="text-center">
                    Belum ada data kesehatan pohon.<br>
                    Silakan lakukan pengecekan terlebih dahulu di halaman 
                    <a href="<?= base_url('Kesehatan'); ?>">Cek Kesehatan</a>.
                  </td>
                </tr>
              <?php else: ?>
                <?php $no = 1; foreach ($kesehatan as $p): ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($p['nama_kebun']); ?></td>
                    <td><?= htmlspecialchars($p['blok_kebun']); ?></td>
                    <td><?= htmlspecialchars($p['tanggal_foto']); ?></td>
                    <td><?= htmlspecialchars($p['sehat']); ?></td>
                    <td><?= htmlspecialchars($p['tidak_sehat']); ?></td>
                    <td>
                      <?php if (!empty($p['gambar_url'])): ?>
                        <a href="#" class="btn btn-sm btn-primary tampilGambar" data-bs-toggle="modal" data-bs-target="#modalGambar" data-src="<?= htmlspecialchars($p['gambar_url']); ?>">
                          Lihat Gambar
                        </a>
                      <?php else: ?>
                        Tidak ada gambar
                      <?php endif; ?>
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

<!-- Modal Gambar -->
<div class="modal fade" id="modalGambar" tabindex="-1" aria-labelledby="modalGambarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalGambarLabel">Gambar Hasil Pemeriksaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body text-center">
        <img id="previewGambar" src="" class="img-fluid" alt="Gambar kesehatan pohon">
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
  $('#aset').DataTable({
    order: [[0, 'asc']],
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

  // Event ketika tombol lihat gambar diklik
  $('.tampilGambar').on('click', function () {
    const url = $(this).data('src');
    $('#previewGambar').attr('src', url);
  });
});

$.fn.dataTable.ext.errMode = 'none';
</script>
