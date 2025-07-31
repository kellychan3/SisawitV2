<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemupukan extends CI_Controller
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

        if (!$token || !$organisasi_id) {
            redirect(base_url('Authentication'));
        }

        // ✅ Ambil data pakai helper apiRequest
        list($status, $decoded) = apiRequest("http://103.150.101.10/api/pemupukan");

        // ✅ Cek status response
        if ($status != 200 || !is_array($decoded)) {
            $this->session->set_flashdata('error', 'Gagal mengambil data pemupukan dari server.');
            $decoded = [];
        }

        // ✅ Cek jika token tidak valid / sesi habis
        if (isset($decoded['message']) && strtolower($decoded['message']) === 'unauthenticated') {
            $this->session->set_flashdata('error', 'Sesi login telah habis. Silakan login kembali.');
            redirect(base_url('Authentication'));
        }

        // ✅ Pastikan format data array
        if (is_array($decoded) && isset($decoded[0])) {
            $pemupukan = $decoded;
        } elseif (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            $pemupukan = $decoded['data'];
        } else {
            $pemupukan = [];
        }

        $data['pemupukan'] = $pemupukan;
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('pemupukan/pemupukan', $data);
        $this->load->view('layout/footer', $data);
    }
}
