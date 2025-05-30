<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(); // Pastikan fungsi helper ini tersedia untuk cek login user
        $this->load->model('Dashboard_model');
    }

    public function index()
    {
        // Ambil data user login
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // Ambil filter dari URL, gunakan default tahun sekarang
        $tahun = $this->input->get('tahun') ?? date('Y');
        $bulan = $this->input->get('bulan');

    // Jika $bulan kosong/null, set default 3 bulan: bulan ini, bulan lalu, dan 2 bulan lalu
    if (empty($bulan)) {
        $bulanIni = (int)date('n'); // bulan ini (1-12)
        $bulan = [
            $bulanIni,
            $bulanIni - 1 > 0 ? $bulanIni - 1 : 12,
            $bulanIni - 2 > 0 ? $bulanIni - 2 : 11,
        ];
    } else {
        // Jika bukan array, ubah jadi array agar konsisten
        if (!is_array($bulan)) {
            $bulan = [$bulan];
        }
        // Pastikan semua bulan dalam integer
        $bulan = array_map('intval', $bulan);
    }
      

        $minggu = date('W');

        $kebun = $this->input->get('kebun'); // bisa array jika multiple select

        if (empty($kebun)) {
            $kebun = null;
        } elseif (!is_array($kebun)) {
            $kebun = [$kebun];
        }

        // Simpan filter ke data untuk view
        $data['filter'] = [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'kebun' => $kebun,
        ];

        // Data dropdown filter
        $data['tahun_list'] = $this->Dashboard_model->get_tahun_list();
        $data['bulan_list'] = $this->Dashboard_model->get_bulan_list();
        $data['kebun_list'] = $this->Dashboard_model->get_kebun_list();

     

        // Data dashboard
        $data['panen_per_bulan'] = $this->Dashboard_model->get_total_panen_per_bulan($tahun, $bulan, $kebun);
        $data['luas_kebun'] = $this->Dashboard_model->get_luas_kebun_persentase($kebun);
        $data['summary_kebun'] = $this->Dashboard_model->get_summary_kebun($kebun);
        $data['persediaan_pupuk'] = $this->Dashboard_model->get_persediaan_pupuk($kebun);

        $total_bulan_ini = $this->Dashboard_model->get_total_panen_bulan_ini($tahun, $bulan, $kebun);
        $total_minggu_ini = $this->Dashboard_model->get_total_panen_minggu_ini($tahun, $minggu, $kebun);

        $rata_bulanan = $this->Dashboard_model->get_rata2_panen_bulanan_tahun_ini($tahun, $kebun);
        $rata_mingguan = $this->Dashboard_model->get_rata2_panen_mingguan_tahun_ini($tahun, $kebun);

        $selisih_persen_bulan = $rata_bulanan ? (($total_bulan_ini - $rata_bulanan) / $rata_bulanan * 100) : 0;
        $data['indikator_panen'] = [
            'nilai' => number_format($total_bulan_ini, 2, ',', '.'),
            'persen' => round(abs($selisih_persen_bulan), 1),
            'naik' => $selisih_persen_bulan >= 0
        ];

        $selisih_persen_minggu = $rata_mingguan ? (($total_minggu_ini - $rata_mingguan) / $rata_mingguan * 100) : 0;
        $data['indikator_panen_mingguan'] = [
            'nilai' => number_format($total_minggu_ini, 2, ',', '.'),
            'persen' => round(abs($selisih_persen_minggu), 1),
            'naik' => $selisih_persen_minggu >= 0
        ];

        $data['persentase_panen_kebun'] = $this->Dashboard_model->get_persen_panen_per_kebun($tahun, $bulan);

        // Load view
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('layout/footer');
    }
}
