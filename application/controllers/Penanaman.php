<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penanaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $token = $this->session->userdata('token');
        $organisasi_id = $this->session->userdata('organisasi_id');
        if (!$token || !$organisasi_id) redirect('authentication');

        list($status, $decoded) = apiRequest("http://103.150.101.10/api/penanaman");
    
        // Pastikan hasilnya array of data
        if (is_array($decoded) && isset($decoded[0])) {
            $penanaman = $decoded;
        } elseif (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            $penanaman = $decoded['data'];
        } else {
            $penanaman = [];
        }

        $data['penanaman'] = $penanaman;
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('penanaman/penanaman', $data);
        $this->load->view('layout/footer', $data);
    }
}
