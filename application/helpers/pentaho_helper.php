<?php
function run_pentaho_job($id_user, $job_file = 'main_job.kjb')
{
    $safe_id = escapeshellarg($id_user);

    // Lokasi file Pentaho job di dalam container
    $job_path = "/var/www/html/pentaho/{$job_file}";

    // Lokasi kitchen.sh di dalam container
    $command = "/opt/data-integration/kitchen.sh -file={$job_path} -param:id_user={$safe_id}";

    // Jalankan di background agar tidak blocking request
    $background_command = $command . " > /var/www/html/application/logs/pentaho_output.log 2>&1 &";

    exec($background_command, $output, $status);

    log_message('debug', 'Pentaho background job started: ' . $background_command);

    // Tidak perlu menunggu hasil — anggap sukses memulai
    return true;
}
