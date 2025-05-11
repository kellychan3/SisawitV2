<?php
function is_logged_in() // batasi akses ke halaman admin
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('Authentication');
    } else {
        $role = $ci->session->userdata('role');
        if ($role == "Owner") {
            redirect('Dashboard');
        }else if ($role == "Supervisor") {
            redirect('Dashboard');
        }
    }
}


?>