<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panen</title>
    <!-- Pindahkan ke paling atas -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Baru setelah itu: -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
<div class="page-wrapper">
        <div class="page-content">
            <div class="filter-box">
                <div class="refresh-form">
                    <?php if ($this->session->flashdata('message')): ?>
    <div style="color: yellow; margin-bottom: 10px;">
        <?= $this->session->flashdata('message') ?>
    </div>
<?php endif; ?>
                <form method="post" action="<?= base_url('prediksi/refresh_data'); ?>" style="display: flex; align-items: center; gap: 16px; padding: 8px 16px;">
    
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
            </div>
    </div>
</div>

</body>
</html>
