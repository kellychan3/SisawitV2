<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_pengguna extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
        }
    }
	 public function index()
    {
        $token = $this->session->userdata('token');
        $organisasi_id = $this->session->userdata('organisasi_id');
        if (!$token || !$organisasi_id) redirect('authentication');

        // Ambil data dari API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/log",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $decoded = json_decode($response, true);

        // Pastikan hasilnya array of data
        if (is_array($decoded) && isset($decoded[0])) {
            $log_pengguna = $decoded;
        } elseif (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            $log_pengguna = $decoded['data'];
        } else {
            $log_pengguna = [];
        }

        $data['log_pengguna'] = $log_pengguna;
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('log_pengguna/log_pengguna', $data);
        $this->load->view('layout/footer', $data);
    }
}