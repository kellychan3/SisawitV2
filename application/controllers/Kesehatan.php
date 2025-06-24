<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kesehatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $token = $this->session->userdata('token');
        if (!$token) {
            redirect('authentication');
        }

        $data = [
            'kebun' => $this->getKebunData(),
            'hasil' => $this->session->flashdata('hasil'),
            'old_input' => $this->session->flashdata('old_input'),
            'error' => $this->session->flashdata('error')
        ];

        $this->load->view('layout/header');
        $this->load->view('kesehatan/kesehatan', $data);
        $this->load->view('layout/footer');
    }

    private function getKebunData()
    {
        $token = $this->session->userdata('token');
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://103.150.101.10/api/kebun',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            $this->session->set_flashdata('error', 'Gagal mengambil data kebun: ' . $error);
            return [];
        }

        return json_decode($response, true) ?? [];
    }

    public function cek()
    {
        $token = $this->session->userdata('token');
        if (!$token) {
            redirect('authentication');
        }

        // Basic validation
        if (empty($_FILES['gambar_kebun']['name'])) {
            $this->session->set_flashdata('error', 'Harap upload gambar');
            $this->session->set_flashdata('old_input', $this->input->post());
            redirect('kesehatan');
        }

        if (!$this->input->post('nama_kebun') || !$this->input->post('blok_kebun') || !$this->input->post('tanggal_foto')) {
            $this->session->set_flashdata('error', 'Harap lengkapi semua field');
            $this->session->set_flashdata('old_input', $this->input->post());
            redirect('kesehatan');
        }

        // Tambahkan validasi ini sebelum CURLFile
    if (
        !isset($_FILES['gambar_kebun']) ||
        $_FILES['gambar_kebun']['error'] !== UPLOAD_ERR_OK ||
        empty($_FILES['gambar_kebun']['tmp_name'])
    ) {
        $this->session->set_flashdata('error', 'File upload gagal atau tidak valid');
        $this->session->set_flashdata('old_input', $this->input->post());
        redirect('kesehatan');
    }
    
        // Prepare API data
        $postData = [
            'kebun_id' => $this->input->post('nama_kebun'),
            'blok_id' => $this->input->post('blok_kebun'),
            'tanggal_foto' => $this->input->post('tanggal_foto'),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/kesehatan",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
            CURLOPT_POSTFIELDS => array_merge($postData, [
                'gambar_kebun' => new CURLFile(
                    $_FILES['gambar_kebun']['tmp_name'],
                    $_FILES['gambar_kebun']['type'],
                    $_FILES['gambar_kebun']['name']
                ),
            ])
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            $this->session->set_flashdata('error', 'Error API: ' . $error);
            redirect('kesehatan');
        }

        $result = json_decode($response, true);

        if (isset($result['data'])) {
            $this->session->set_flashdata('hasil', $result['data']);
            $this->session->set_flashdata('old_input', $this->input->post());
            redirect('kesehatan');
        } else {
            $this->session->set_flashdata('error', 'Gagal memproses gambar');
            redirect('kesehatan');
        }
    }
}