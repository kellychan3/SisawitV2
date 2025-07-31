<?php
function run_pentaho_job($id_user, $job_file = 'main_job.kjb')
{
    $safe_id = escapeshellarg($id_user);
    $job_path = "/var/www/html/pentaho/{$job_file}";
    $command  = "/opt/data-integration/kitchen.sh -file={$job_path} -param:id_user={$safe_id}";

    exec($command, $output, $status);
    log_message('debug', 'Pentaho Output: ' . print_r($output, true));
    log_message('debug', 'Pentaho Status: ' . $status);

    return $status === 0;
}
