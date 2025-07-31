<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->helper('sisawit');
    }

    public function index()
    {
        $this->load->view('layout/auth_header');
        $this->load->view('auth/login');
        $this->load->view('layout/auth_footer');
    }

    public function ceklogin()
{
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    if (empty($email) || empty($password)) {
        set_alert('danger', 'Email/No.HP dan Password wajib diisi!');
        redirect('Authentication');
        return;
    }

    $result = $this->Login_model->login_api($email, $password);

    if (isset($result['error']) && $result['error']) {
        set_alert('danger', $result['message']);
        redirect('Authentication');
        return;
    }

    if (isset($result['message']) && $result['message'] == "Success") {
        $user = $result['user'];
        $token = $result['token'];

        $this->session->set_userdata([
            'id_user' => $user['id'],
            'email' => $user['email'],
            'nama' => $user['nama'],
            'role' => $user['role'],
            'token' => $token,
            'organisasi_id' => $user['organisasi_id'],
        ]);

        $this->db->insert('api_tokens', [
            'id_user' => $user['id'],
            'id_organisasi' => $user['organisasi_id'],
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s') 
        ]);

        redirect('Dashboard');
    } else {
        set_alert('danger', 'Email/Nomor HP atau Kata Sandi salah!');
        redirect('Authentication');
    }
}

    public function logout()
    {
        $this->session->sess_destroy();
        set_alert('success', 'Logout berhasil');
        redirect('Authentication');
    }

    public function lupa_sandi()
    {
        $this->load->view('layout/auth_header');
        $this->load->view('auth/lupa_sandi');
        $this->load->view('layout/auth_footer');
    }
}
