<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Prediksi_model');
    }

    public function index()
{
    $tahun = $this->input->get('tahun') ?? date('Y');
    $tahun_sekarang = date('Y');
    $bulan_skrg = date('n'); // Bulan sekarang (1-12)

    $data['tahun_list'] = $this->Prediksi_model->get_tahun_list();
    $data['filter'] = ['tahun' => $tahun];

    $data['prediksi'] = $this->Prediksi_model->get_prediksi_by_year($tahun);
    $data['aktual'] = $this->Prediksi_model->get_aktual_by_year($tahun);

    // Tentukan batas bulan untuk total
    if ($tahun == $tahun_sekarang) {
        $bulan_akhir = $bulan_skrg; // sampai bulan sekarang
    } else {
        $bulan_akhir = 12; // total 12 bulan
    }

    $total_prediksi = 0;
    $total_aktual = 0;
    for ($b = 1; $b <= $bulan_akhir; $b++) {
        $total_prediksi += $data['prediksi'][$b] ?? 0;
        $total_aktual += $data['aktual'][$b] ?? 0;
    }
    $data['total_prediksi'] = $total_prediksi;
    $data['total_aktual'] = $total_aktual;

    // Data user dan load view
    $data['user'] = [
        'email' => $this->session->userdata('email'),
        'nama' => $this->session->userdata('nama'),
        'role' => $this->session->userdata('role'),
    ];

    $this->load->view('layout/header', $data);
    $this->load->view('prediksi/prediksi', $data);
    $this->load->view('layout/footer');
}



}
