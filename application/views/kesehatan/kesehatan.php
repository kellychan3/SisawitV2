<!-- Link dan Script -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@panzoom/panzoom@9.4.0/dist/panzoom.min.js"></script>
<script src="https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js"></script>

<style>
  /* === Upload Area === */
  .upload-area {
    border: 2px dashed #4e73df;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    border-radius: 12px;
    background-color: #f8f9fc;
    transition: all 0.3s ease;
    position: relative;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .upload-area:hover {
    background-color: #e9f0ff;
    border-color: #2e59d9;
  }
  .upload-area.active {
    border-color: #2e59d9;
    background-color: #e9f0ff;
  }
  .upload-icon {
    font-size: 2.8rem;
    color: #4e73df;
    margin-bottom: 10px;
  }
  .upload-text {
    margin-top: 0;
    font-size: 1.1rem;
    color: #555;
    font-weight: 500;
  }
  .filename-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #333;
    font-size: 1rem;
    flex-wrap: wrap;
    background: rgba(78, 115, 223, 0.1);
    padding: 8px 12px;
    border-radius: 8px;
  }
  .remove-btn {
    background: none;
    border: none;
    color: #dc3545;
    font-size: 1.4rem;
    line-height: 1;
    cursor: pointer;
    transition: transform 0.2s;
  }
  .remove-btn:hover {
    transform: scale(1.2);
  }
  #previewImage {
    max-height: 250px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 15px;
  }

  /* === Hasil Deteksi === */
  .result-card {
    border-left: 5px solid #4e73df;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }
  .result-card .card-title {
    color: #2e59d9;
    font-weight: 600;
  }
  .health-status {
    font-size: 1.1rem;
    font-weight: 500;
  }
  .health-status .healthy {
    color: #28a745;
  }
  .health-status .unhealthy {
    color: #dc3545;
  }

  /* === Progress Bar Improved === */
  .progress-container {
    margin: 1.5rem 0 0.5rem;
  }
  
  .progress {
    height: 10px;
    border-radius: 5px;
    background-color: #e9ecef;
    overflow: hidden;
  }
  
  .progress-bar-success {
    background-color: #28a745;
    height: 100%;
    float: left;
    transition: width 0.6s ease;
  }
  
  .progress-bar-danger {
    background-color: #dc3545;
    height: 100%;
    float: left;
    transition: width 0.6s ease;
  }
  
  .progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.25rem;
  }

  /* === Zoom Controls === */
  .zoom-controls {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    padding: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 100;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .zoom-btn {
    background: white;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4e73df;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  .zoom-btn:hover {
    background: #4e73df;
    color: white;
    transform: scale(1.1);
  }

  /* === Image Container === */
  #panzoom-wrapper {
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
    background: #f9f9f9;
    min-height: 450px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative; 
  }

  #panzoom-target {
    max-height: 450px;
    max-width: 100%; 
    object-fit: contain;
    cursor: grab;
    display: block; 
    margin: 0 auto; 
    transform-origin: center center;
  }

  #panzoom-target:active {
    cursor: grabbing;
  }

  /* === Style untuk tidak ada deteksi === */
  .no-detection-message {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    z-index: 10;
  }
  
  .alert-warning {
    background-color: #fff3cd;
    border-left: 5px solid #ffc107;
    color: #856404;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
  }

  /* === Responsive Adjustments === */
  @media (max-width: 767.98px) {
    .zoom-controls {
      top: 10px;
      right: 10px;
    }
    #panzoom-wrapper {
      min-height: 300px;
    }
  }

  /* Additional simple style for the note */
  .area-note {
    background-color: #f8f9fa;
    border-left: 4px solid #4e73df;
    padding: 12px;
    border-radius: 4px;
    margin-top: 15px;
    font-size: 0.9rem;
  }
</style>

<div class="page-wrapper">
  <div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Monitoring</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Cek Kesehatan</li>
          </ol>
        </nav>
      </div>
    </div>
  <!-- Simple note before upload -->
    <div class="alert alert-info mb-3">
      <i class="bi bi-info-circle me-2"></i> Untuk hasil terbaik, pastikan:
      <ul class="mb-0 mt-2">
        <li>Pastikan gambar jelas dan pohon terlihat</li>
        <li>Untuk area >1 hektar, disarankan mengambil gambar per blok</li>
      </ul>
    </div>

    <!-- Form dan Hasil -->
    <div class="card mb-4 shadow-sm border-0 rounded-lg">
      <div class="card-body">
        <div class="row g-4">
          <!-- Kiri: Form -->
          <div class="col-md-6">
            <form method="post" enctype="multipart/form-data" action="<?= base_url('kesehatan/cek') ?>">
              <div class="mb-4">
                <div class="upload-area" onclick="triggerFileInput()" id="uploadArea">
                  <div id="dropContent">
                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                    <div class="upload-text">Drop Gambar atau Klik untuk Upload</div>
                    <small class="text-muted">Format: JPG, PNG (Maks. 5MB)</small>
                  </div>
                  <div id="fileNameBox" class="filename-box d-none">
                    <span id="fileNameText"></span>
                    <button type="button" class="remove-btn" onclick="resetFileInput(event)" title="Hapus gambar">&times;</button>
                  </div>
                  <img id="previewImage" src="#" alt="Preview" class="d-none">
                  <input type="file" name="gambar_kebun" id="fileInput" class="form-control d-none" accept="image/*" required>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="nama_kebun" class="form-label fw-bold">Nama Kebun</label>
                <select name="nama_kebun" id="nama_kebun" class="form-select" required>
                  <option value="">Pilih Kebun</option>
                  <?php foreach ($kebun as $k) : ?>
                    <option value="<?= $k['id']; ?>" <?= set_select('nama_kebun', $k['id'], ($old_input['nama_kebun'] ?? '') == $k['id']) ?>>
                      <?= $k['nama_kebun']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="blok_kebun" class="form-label fw-bold">Blok/Area Kebun</label>
                <select name="blok_kebun" id="blok_kebun" class="form-select" required>
                  <option value="">Pilih Blok</option>
                  <?php if (!empty($old_input['blok_kebun'])) : ?>
                    <option value="<?= $old_input['blok_kebun'] ?>" selected><?= $old_input['blok_kebun'] ?></option>
                  <?php endif; ?>
                </select>
                <div id="loadingSpinner" class="text-muted small mt-2 d-none">
                  <i class="bi bi-arrow-repeat bi-spin me-1"></i> Memuat blok kebun...
                </div>
              </div>

              <div class="mb-4">
                <label for="tanggal_foto" class="form-label fw-bold">Tanggal Foto Kebun</label>
                <input type="date" name="tanggal_foto" id="tanggal_foto" class="form-control" 
                      value="<?= set_value('tanggal_foto', $old_input['tanggal_foto'] ?? '') ?>" required>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-search me-2"></i> Cek Kesehatan Pohon
              </button>
              <!-- Simple note after submit button -->
              <div class="area-note mt-3">
                <i class="bi bi-lightbulb text-warning"></i> Jika tidak ada pohon terdeteksi, 
                mungkin karena gambar kurang jelas atau area terlalu luas.
              </div>
            </form>
            <?php if (!empty($error)) : ?>
              <div class="alert alert-danger mt-3">
                <?= $error ?>
              </div>
            <?php endif; ?>

            <!-- Informasi Deteksi -->
            <?php if (isset($hasil)) : ?>
              <div class="mt-5">
                <div class="card result-card border-0">
                  <div class="card-body">
                    <h5 class="card-title mb-4">
                      <i class="bi bi-clipboard2-pulse me-1"></i> <strong>Hasil Prediksi Kesehatan Pohon</strong>
                    </h5>
                    
                    <?php if ($hasil['total_pohon'] == 0) : ?>
                      <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Tidak ada pohon sawit yang terdeteksi pada gambar.
                      </div>
                    <?php endif; ?>
                    
                    <p class="mb-2">Nama Kebun:<br><span class="fs-5 fw-bold"><?= $hasil['nama_kebun']; ?></span></p>
                    <p class="mb-2">Blok/Area Kebun: <strong><?= $hasil['blok_kebun']; ?></strong></p>
                    <p class="mb-3">Tanggal Foto Kebun: <strong><?= $hasil['tanggal_foto']; ?></strong></p>
                    
                    <div class="health-status mb-3">
                      <p class="mb-2 d-flex align-items-center gap-2">
                        <img src="<?= base_url('assets/icons/tree.png'); ?>" width="24">
                        <span>Total Pohon Sawit: <strong><?= $hasil['total_pohon']; ?></strong></span>
                      </p>
                      
                      <?php if ($hasil['total_pohon'] > 0) : ?>
                        <p class="mb-2 d-flex align-items-center gap-2 healthy">
                          <img src="<?= base_url('assets/icons/tree-healthy.png'); ?>" width="24">
                          <span>Pohon Sehat: <strong><?= $hasil['sehat']; ?></strong></span>
                        </p>
                        <p class="mb-3 d-flex align-items-center gap-2 unhealthy">
                          <img src="<?= base_url('assets/icons/tree-unhealthy.png'); ?>" width="22">
                          <span>Pohon Tidak Sehat: <strong><?= $hasil['tidak_sehat']; ?></strong></span>
                        </p>
                      <?php endif; ?>
                    </div>

                    <?php if ($hasil['total_pohon'] > 0) : ?>
                      <div class="progress-container">
                        <div class="progress">
                          <div class="progress-bar-success" style="width: <?= ($hasil['sehat']/$hasil['total_pohon'])*100 ?>%"></div>
                          <div class="progress-bar-danger" style="width: <?= ($hasil['tidak_sehat']/$hasil['total_pohon'])*100 ?>%"></div>
                        </div>
                        <div class="progress-labels">
                          <span><?= round(($hasil['sehat']/$hasil['total_pohon'])*100, 1) ?>% Sehat</span>
                          <span><?= round(($hasil['tidak_sehat']/$hasil['total_pohon'])*100, 1) ?>% Tidak Sehat</span>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Kanan: Hasil Gambar -->
          <div class="col-md-6">
            <?php if (!empty($hasil['gambar_url'])): ?>
              <h5 class="mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-image-fill text-primary"></i><strong>Hasil Pemeriksaan</strong>
              </h5>
              <div class="position-relative">
                <div id="panzoom-wrapper">
                  <img id="panzoom-target" 
                        src="<?= $hasil['gambar_url']; ?>" 
                        alt="Hasil Deteksi" 
                        class="w-100 h-auto">
                  <?php if ($hasil['total_pohon'] == 0) : ?>
                    <div class="no-detection-message">
                      Tidak ada pohon sawit terdeteksi
                    </div>
                  <?php endif; ?>
                </div>

                <!-- Tombol Zoom -->
                <div class="zoom-controls">
                  <button id="zoom-in-btn" class="zoom-btn" title="Zoom In"><i class="bi bi-plus-lg"></i></button>
                  <button id="zoom-out-btn" class="zoom-btn" title="Zoom Out"><i class="bi bi-dash-lg"></i></button>
                  <button id="zoom-reset-btn" class="zoom-btn" title="Reset Zoom"><i class="bi bi-arrow-counterclockwise"></i></button>
                </div>
              </div>
                <?php if ($hasil['total_pohon'] == 0) : ?>
                <div class="alert alert-warning mt-3">
                  <i class="bi bi-exclamation-triangle me-2"></i>
                  <strong>Mengapa tidak terdeteksi?</strong>
                  <ul class="mt-2 mb-1">
                    <li>Area kebun terlalu luas dalam 1 gambar (>1 hektar)</li>
                    <li>Pohon terlalu kecil/tidak jelas dalam gambar</li>
                    <li>Gambar buram atau pencahayaan kurang</li>
                  </ul>
                  <strong>Solusi:</strong> Ambil gambar dari ketinggian lebih rendah atau per blok area.
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    <?php if (!empty($old_input['nama_kebun'])) : ?>
      $('#nama_kebun').val('<?= $old_input['nama_kebun'] ?>').trigger('change');
    <?php endif; ?>
  });
  
  // === File Upload Handling ===
  const fileInput = document.getElementById('fileInput');
  const uploadArea = document.getElementById('uploadArea');
  const dropContent = document.getElementById('dropContent');
  const fileNameBox = document.getElementById('fileNameBox');
  const fileNameText = document.getElementById('fileNameText');
  const previewImage = document.getElementById('previewImage');

  function triggerFileInput() {
    fileInput.click();
  }

  fileInput.addEventListener('change', function() {
    if (this.files && this.files.length > 0) {
      const file = this.files[0];
      uploadArea.classList.add('active');
      dropContent.classList.add('d-none');
      fileNameText.textContent = file.name;
      fileNameBox.classList.remove('d-none');

      const reader = new FileReader();
      reader.onload = function(e) {
        previewImage.src = e.target.result;
        previewImage.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }
  });

  function resetFileInput(e) {
    e.stopPropagation();
    fileInput.value = '';
    uploadArea.classList.remove('active');
    fileNameBox.classList.add('d-none');
    dropContent.classList.remove('d-none');
    previewImage.classList.add('d-none');
  }

  // Drag and Drop
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('active');
  });

  uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('active');
  });

  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('active');
    fileInput.files = e.dataTransfer.files;
    const event = new Event('change');
    fileInput.dispatchEvent(event);
  });

  // === Dynamic Dropdown (Blok Kebun) ===
  $('#nama_kebun').on('change', function() {
    const kebunId = $(this).val();
    const $blokSelect = $('#blok_kebun');
    const $loadingSpinner = $('#loadingSpinner');
    
    $blokSelect.prop('disabled', true);
    $loadingSpinner.removeClass('d-none');
    
    if (kebunId) {
      $.ajax({
        url: 'http://160.187.144.173/api/blok-kebun?kebun=' + kebunId,
        method: 'GET',
        headers: {
          "Authorization": "Bearer <?= $this->session->userdata('token') ?>",
          "Accept": "application/json"
        },
        success: function(response) {
          $blokSelect.empty().append('<option value="">Pilih Blok</option>');
          response.forEach(function(blok) {
            $blokSelect.append(
              $('<option>', {
                value: blok.id,
                text: blok.nama_blok
              })
            );
          });
          
          <?php if (!empty($old_input['blok_kebun'])) : ?>
            $blokSelect.val('<?= $old_input['blok_kebun'] ?>');
          <?php endif; ?>
        },
        error: function(xhr) {
          console.error('Error:', xhr.responseText);
          alert('Gagal mengambil data blok kebun');
        },
        complete: function() {
          $loadingSpinner.addClass('d-none');
          $blokSelect.prop('disabled', false);
        }
      });
    } else {
      $blokSelect.empty().append('<option value="">Pilih Blok</option>');
      $loadingSpinner.addClass('d-none');
      $blokSelect.prop('disabled', false);
    }
  });

  // === Image Zoom/Pan ===
  document.addEventListener("DOMContentLoaded", function () {
  const elem = document.getElementById('panzoom-target');

  if (!elem) return;

  const initPanzoom = () => {
    const panzoom = Panzoom(elem, {
      maxScale: 5,
      minScale: 0.5,
      contain: 'outside',
      animate: true,
      duration: 200
    });

    const wrapper = document.getElementById('panzoom-wrapper');
    wrapper.addEventListener('wheel', panzoom.zoomWithWheel);

    document.getElementById('zoom-in-btn').addEventListener('click', () => {
      panzoom.zoomIn({ animate: true });
    });

    document.getElementById('zoom-out-btn').addEventListener('click', () => {
      panzoom.zoomOut({ animate: true });
    });

    document.getElementById('zoom-reset-btn').addEventListener('click', () => {
      panzoom.reset({ animate: true });
    });

    // Fit gambar ke awal tampilan
    panzoom.reset({ animate: false });
  };

  // Jika gambar sudah load
  if (elem.complete && elem.naturalWidth !== 0) {
    initPanzoom();
  } else {
    elem.addEventListener('load', initPanzoom);
  }
});

</script>