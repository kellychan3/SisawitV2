<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(); // Pastikan fungsi ini ada dan bekerja
    }

    public function index()
    {
        $token = $this->session->userdata('token');
        $organisasi_id = $this->session->userdata('organisasi_id');

        if (!$token || !$organisasi_id) {
            redirect('authentication');
        }

        // Ambil data aset dari API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/organisasi/$organisasi_id/aset",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json",
            ],
        ]);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $allAssets = $httpcode == 200 ? json_decode($response, true) : [];

        // Ambil query search dari GET
        $search = $this->input->get('search');

        if ($search) {
            $searchLower = strtolower($search);
            $allAssets = array_filter($allAssets, function ($item) use ($searchLower) {
                $nama_aset = strtolower($item['nama_aset'] ?? '');
                $nama_kategori = strtolower($item['kategori']['nama_kategori'] ?? '');
                $nama_kebun = strtolower($item['kebun']['nama_kebun'] ?? '');

                return strpos($nama_aset, $searchLower) !== false ||
                       strpos($nama_kategori, $searchLower) !== false ||
                       strpos($nama_kebun, $searchLower) !== false;
            });
        }

        // Pagination setup
        $perPage = 20;
        $page = (int) $this->input->get('page');
        if ($page < 1) $page = 1;

        $totalAssets = count($allAssets);
        $start = ($page - 1) * $perPage;
        $assets = array_slice($allAssets, $start, $perPage);

        // Data untuk view
        $data['asset'] = $assets;
        $data['pagination'] = [
            'current' => $page,
            'perPage' => $perPage,
            'total' => $totalAssets,
            'lastPage' => ceil($totalAssets / $perPage),
        ];
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Load views
        $this->load->view('layout/header', $data);
        $this->load->view('aset/aset', $data);
        $this->load->view('layout/footer', $data);
    }
}
