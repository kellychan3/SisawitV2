<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Dashboard_model');
    }

    public function index()
    {
        // Ambil data user login
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // Ambil filter dari URL, gunakan nilai default jika kosong
        $tahun = $this->input->get('tahun') ?? date('Y');
        $bulan = $this->input->get('bulan');
        $bulan = is_null($bulan) ? date('n') : $bulan;

        $minggu = date('W');

        $kebun = $this->input->get('kebun'); // bisa array jika multiple select

        // Pastikan $kebun selalu null atau array
        if (empty($kebun)) {
            $kebun = null;
        } elseif (!is_array($kebun)) {
            $kebun = [$kebun];
        }

        // Simpan filter untuk dikirim ke view
        $data['filter'] = [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'kebun' => $kebun,
        ];

        // List dropdown untuk filter
        $data['tahun_list'] = $this->Dashboard_model->get_tahun_list();
        $data['bulan_list'] = $this->Dashboard_model->get_bulan_list();
        $data['kebun_list'] = $this->Dashboard_model->get_kebun_list();

        // Data utama dashboard
        $data['panen_per_bulan'] = $this->Dashboard_model->get_total_panen_per_bulan($tahun, $bulan, $kebun);
        $data['luas_kebun'] = $this->Dashboard_model->get_luas_kebun_persentase($kebun);
        $data['summary_kebun'] = $this->Dashboard_model->get_summary_kebun($kebun);
        $data['persediaan_pupuk'] = $this->Dashboard_model->get_persediaan_pupuk($kebun);

        // Total panen bulan dan minggu ini
        $total_bulan_ini = $this->Dashboard_model->get_total_panen_bulan_ini($tahun, $bulan, $kebun);
        $total_minggu_ini = $this->Dashboard_model->get_total_panen_minggu_ini($tahun, $minggu, $kebun);

        // Rata-rata panen
        $rata_bulanan = $this->Dashboard_model->get_rata2_panen_bulanan_tahun_ini($tahun, $kebun);
        $rata_mingguan = $this->Dashboard_model->get_rata2_panen_mingguan_tahun_ini($tahun, $kebun);

        // Indikator panen bulanan (% selisih terhadap rata-rata)
        $selisih_persen_bulan = $rata_bulanan ? (($total_bulan_ini - $rata_bulanan) / $rata_bulanan * 100) : 0;
        $data['indikator_panen'] = [
            'nilai' => number_format($total_bulan_ini, 2, ',', '.'),
            'persen' => round(abs($selisih_persen_bulan), 1),
            'naik' => $selisih_persen_bulan >= 0
        ];

        // Indikator panen mingguan (% selisih terhadap rata-rata)
        $selisih_persen_minggu = $rata_mingguan ? (($total_minggu_ini - $rata_mingguan) / $rata_mingguan * 100) : 0;
        $data['indikator_panen_mingguan'] = [
            'nilai' => number_format($total_minggu_ini, 2, ',', '.'),
            'persen' => round(abs($selisih_persen_minggu), 1),
            'naik' => $selisih_persen_minggu >= 0
        ];

        // Kirim data ke view
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('layout/footer');
    }
}
