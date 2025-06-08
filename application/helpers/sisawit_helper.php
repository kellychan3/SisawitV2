<?php
function is_logged_in()
{
    $ci = get_instance();

    // Cek apakah user sudah login
    if (!$ci->session->userdata('email')) {
        redirect('Authentication');
    }
}
?>
