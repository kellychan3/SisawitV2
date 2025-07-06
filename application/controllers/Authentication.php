<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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

    // Setup CURL untuk call API login eksternal
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.150.101.10/api/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            "no_hp" => $email,
            "password" => $password,
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Koneksi ke server gagal!</div>');
        redirect('Authentication');
    } else {
        $result = json_decode($response, true);

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
        'created_at' => date('Y-m-d H:i:s') // jika DB tidak auto timestamp
    ]);

    redirect('Dashboard');
}
else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email/Nomor HP atau Kata Sandi salah!</div>');
            redirect('Authentication');
        }
    }
}

    public function logout()
{
    $this->session->sess_destroy();
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Logout berhasil</div>');
    redirect('Authentication');
}


    public function lupa_sandi()
    {
        $this->load->view('layout/auth_header');
        $this->load->view('auth/lupa_sandi');
        $this->load->view('layout/auth_footer');
    }
}
