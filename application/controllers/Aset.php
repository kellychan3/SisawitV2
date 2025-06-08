<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    private function getKategori()
    {
        $token = $this->session->userdata('token');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/kategori-aset",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true) ?: [];
    }

    private function getKebun()
    {
        $token = $this->session->userdata('token');
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/kebun",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true) ?: [];
    }

    public function index()
    {
        $token = $this->session->userdata('token');
        $organisasi_id = $this->session->userdata('organisasi_id');
        if (!$token || !$organisasi_id) redirect('authentication');

        // Ambil data aset
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/aset",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $allAssets = json_decode($response, true) ?: [];

        $data['asset'] = $allAssets;
        $data['kategori'] = $this->getKategori();
        $data['kebun'] = $this->getKebun();
        
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('aset/aset', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addAset()
{
    $token = $this->session->userdata('token');
    $organisasi_id = $this->session->userdata('organisasi_id');
    if (!$token || !$organisasi_id) redirect('authentication');

    $nama_aset = $this->input->post('namaaset');
    $kategori_aset_id = $this->input->post('kategori_id');
    $kebun_id = $this->input->post('kebun_id');
    $jumlah_aset = (int) $this->input->post('jumlahaset');

    // Ambil semua aset dulu
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.150.101.10/api/aset",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    $existingAssets = json_decode($response, true) ?: [];

if (!is_array($existingAssets)) {
    log_message('error', 'Format response tidak sesuai: ' . $response);
    $this->session->set_flashdata('error', 'Gagal mengambil data aset dari server.');
    redirect('Aset');
    return;
}

foreach ($existingAssets as $aset) {
    if (
        is_array($aset) &&
        isset($aset['nama_aset'], $aset['kategori_aset']['id'], $aset['kebun']['id']) &&
        strtolower($aset['nama_aset']) === strtolower($nama_aset) &&
        $aset['kategori_aset']['id'] == $kategori_aset_id &&
        $aset['kebun']['id'] == $kebun_id
    ) {
        $this->session->set_flashdata('error', 'Aset dengan nama, jenis, dan lokasi kebun yang sama sudah ada.');
        redirect('Aset');
        return;
    }
}

    // Lanjut tambah aset jika tidak duplikat
    $postData = json_encode([
        'nama_aset' => $nama_aset,
        'kategori_aset_id' => $kategori_aset_id,
        'kebun_id' => $kebun_id,
        'jumlah_aset' => $jumlah_aset
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.150.101.10/api/aset",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json",
            "Content-Type: application/json"
        ],
    ]);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($status == 201 || $status == 200) {
        $this->session->set_flashdata('success', 'Aset berhasil ditambahkan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menambahkan aset.');
    }

    redirect('Aset');
}

}
