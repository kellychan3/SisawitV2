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

        // Ambil data dari API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/pemupukan",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err || $http_status != 200) {
            $this->session->set_flashdata('error', 'Gagal mengambil data pemupukan dari server.');
            $decoded = [];
        } else {
            $decoded = json_decode($response, true);

            // Cek jika token tidak valid atau sesi kadaluarsa
            if (isset($decoded['message']) && strtolower($decoded['message']) === 'unauthenticated') {
                $this->session->set_flashdata('error', 'Sesi login telah habis. Silakan login kembali.');
                redirect(base_url('Authentication'));
            }
        }

        // Tentukan struktur data
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
