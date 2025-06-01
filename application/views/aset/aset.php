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
                                <input type="text" class="form-control" id="namaaset" name="namaaset" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jenisaset" class="form-label">Jenis Aset</label>
                                <input type="text" class="form-control" id="jenisaset" name="jenisaset" required>
                            </div>
                            <div class="col-md-6">
                                <label for="jumlahaset" class="form-label">Jumlah Aset</label>
                                <input type="number" class="form-control" id="jumlahaset" name="jumlahaset" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lokasiaset" class="form-label">Kebun</label>
                                <input type="text" class="form-control" id="lokasiaset" name="lokasiaset" required>
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
                    <form method="get" action="<?= base_url('Aset'); ?>" class="mb-3 d-flex justify-content-end align-items-center" id="searchForm" style="gap: 0.5rem;">
  <label for="searchInput" class="mb-0">Cari:</label>
  <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari aset..." value="<?= htmlspecialchars($this->input->get('search')); ?>" style="width: 200px;">
</form>



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

                    <!-- PAGINATION NAVIGATION -->
                    <?php
                    $current = (int) $pagination['current'];
                    $last = (int) $pagination['lastPage'];
                    $prev = $current > 1 ? $current - 1 : 1;
                    $next = $current < $last ? $current + 1 : $last;
                    $searchQuery = $this->input->get('search') ? '&search=' . urlencode($this->input->get('search')) : '';
                    ?>
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= $current == 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?= base_url('Aset?page=' . $prev . $searchQuery); ?>"> < Sebelumnya</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#"><?= $current; ?></a>
                                </li>
                                <li class="page-item <?= $current == $last ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?= base_url('Aset?page=' . $next . $searchQuery); ?>">Berikutnya ></a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');

    let timeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const query = searchInput.value.trim();
            const baseUrl = '<?= base_url('Aset'); ?>';
            const url = query ? `${baseUrl}?search=${encodeURIComponent(query)}&page=1` : `${baseUrl}`;
            window.location.href = url;
        }, 500); // delay 500ms setelah user berhenti ketik
    });
});
</script>
