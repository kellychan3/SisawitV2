<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); {
            is_logged_in();
            $this->load->model('Dashboard_model');
        }
    }

    public function index()
{
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

    // Tangkap filter dari input (GET)
    $tahun = $this->input->get('tahun') ?? null;
    $bulan = $this->input->get('bulan') ?? null;
    $kebun = $this->input->get('kebun') ?? null;

    // Kirim ke model
    $data['panen_per_bulan'] = $this->Dashboard_model->get_total_panen_per_bulan($tahun, $bulan, $kebun);
    $data['luas_kebun'] = $this->Dashboard_model->get_luas_kebun_persentase($kebun);

    // Kirim pilihan filter ke view supaya tetap terpilih
    $data['filter'] = [
        'tahun' => $tahun,
        'bulan' => $bulan,
        'kebun' => $kebun,
    ];

    // Bisa juga siapkan data opsi dropdown filter (tahun, bulan, kebun)
    $data['tahun_list'] = $this->Dashboard_model->get_tahun_list();
    $data['bulan_list'] = $this->Dashboard_model->get_bulan_list();
    $data['kebun_list'] = $this->Dashboard_model->get_kebun_list();
    $data['persediaan_pupuk'] = $this->Dashboard_model->get_persediaan_pupuk($kebun);


    $this->load->view('layout/header', $data);
    $this->load->view('dashboard/dashboard', $data);
    $this->load->view('layout/footer', $data);
}

}

