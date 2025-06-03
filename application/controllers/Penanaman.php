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

        // Ambil data dari API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/penanaman",
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
            $penanaman = $decoded;
        } elseif (is_array($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            $penanaman = $decoded['data'];
        } else {
            $penanaman = [];
        }

        $data['penanaman'] = $penanaman;
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        $this->load->view('layout/header', $data);
        $this->load->view('penanaman/penanaman', $data);
        $this->load->view('layout/footer', $data);
    }
}
