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
        list($status, $data) = apiRequest("http://160.187.144.173/api/kategori-aset");
        return $data ?: [];
    }

    private function getKebun()
    {
        list($status, $data) = apiRequest("http://160.187.144.173/api/kebun");
        return $data ?: [];
    }

    public function index()
    {
        $token = $this->session->userdata('token');
        $organisasi_id = $this->session->userdata('organisasi_id');
        if (!$token || !$organisasi_id) redirect('authentication');

        list($status, $allAssets) = apiRequest("http://160.187.144.173/api/aset");
        $allAssets = $allAssets ?: [];

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

        // Validasi input kosong
        if (empty($nama_aset) || empty($kategori_aset_id) || empty($kebun_id) || $jumlah_aset <= 0) {
            set_alert('danger', 'Kolom wajib dipilih/diisi.');
            redirect('Aset');
            return;
        }

        list($status, $existingAssets) = apiRequest("http://160.187.144.173/api/aset");
        if ($status !== 200 || !is_array($existingAssets)) {
            log_message('error', 'Gagal mengambil data aset.');
            set_alert('danger', 'Gagal mengambil data aset dari server.');
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
            set_alert('danger', 'Aset dengan nama, jenis, dan lokasi kebun yang sama sudah ada.');
            redirect('Aset');
            return;
        }
    }

    $postData = json_encode([
        'nama_aset' => $nama_aset,
        'kategori_aset_id' => $kategori_aset_id,
        'kebun_id' => $kebun_id,
        'jumlah_aset' => $jumlah_aset
    ]);

    list($status, $result) = apiRequest("http://160.187.144.173/api/aset", 'POST', $postData);

    if ($status == 201 || $status == 200) {
        set_alert('success', 'Aset berhasil ditambahkan.');
    } else {
        set_alert('danger', 'Gagal menambahkan aset.');
    }

    redirect('Aset');
}

public function editAset()
{
    $token = $this->session->userdata('token');
    $id = $this->input->post('id');
    $jumlah_aset = (int) $this->input->post('jumlah_aset');
    $kebun_id = (int) $this->input->post('kebun_id');

    $data = json_encode([
        'jumlah_aset' => $jumlah_aset,
        'kebun_id' => $kebun_id
    ]);

    list($status, $response) = apiRequest("http://160.187.144.173/api/aset/$id", 'PUT', $data);

    if ($status == 200) {
        set_alert('success', 'Aset berhasil diubah.');
    } else {
        set_alert('error', 'Gagal mengubah aset.');
    }

    redirect('Aset');
}

public function deleteAset()
{
    $token = $this->session->userdata('token');
    $id = $this->input->post('id');

    list($status, $response) = apiRequest("http://160.187.144.173/api/aset/$id", 'DELETE');

    if ($status == 200 || $status == 204) {
        set_alert('success', 'Aset berhasil dihapus.');
    } else {
        set_alert('error', 'Gagal menghapus aset.');
    }

    redirect('Aset');
}

public function getAllAssets()
{
    $token = $this->session->userdata('token');
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://160.187.144.173/api/aset",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    
    header('Content-Type: application/json');
    echo $response;
}

}
