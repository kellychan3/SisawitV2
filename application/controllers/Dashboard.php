<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $bulan_nama = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    private $bulan_nama_singkat = [
        1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun',
        7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'
    ];

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Dashboard_model');
        $this->load->helper('pentaho');
    }

    private function getUserData()
    {
        return [
            'organisasi_id' => $this->session->userdata('organisasi_id'),
            'email'         => $this->session->userdata('email'),
            'nama'          => $this->session->userdata('nama'),
            'role'          => $this->session->userdata('role'),
            'id_user'       => $this->session->userdata('id_user')
        ];
    }

    public function index()
    {
        $user = $this->getUserData();
        $data['user'] = [
            'email' => $user['email'],
            'nama'  => $user['nama'],
            'role'  => $user['role'],
        ];
        $organisasi_id = $user['organisasi_id'];

        // === Filter input: tahun, bulan, kebun ===
        $tahun = $this->input->get('tahun') ?? date('Y');
        $bulan_input = $this->input->get('bulan');

        $bulan_list_db = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);
        $bulan_with_data = array_column($bulan_list_db, 'bulan');

        // Handle bulan filter
        if ($bulan_input === 'all') {
            $bulan = $bulan_with_data; // Ambil semua bulan yang ada data
        } elseif (empty($bulan_input)) {
            // Default: 2 bulan terakhir yang ada data
            $bulan = $this->get_last_two_months_with_harvest($tahun, $organisasi_id, $bulan_with_data);
        } elseif (is_array($bulan_input)) {
            $bulan = array_map('intval', $bulan_input);
        } else {
            $bulan = [(int)$bulan_input];
        }

        // Handle kebun filter
        $kebun_input = $this->input->get('kebun');
        if ($kebun_input === 'all' || empty($kebun_input)) {
            $kebun = array_column($this->Dashboard_model->get_kebun_list($organisasi_id), 'sk_kebun');
        } elseif (is_array($kebun_input)) {
            $kebun = $kebun_input;
        } else {
            $kebun = [$kebun_input];
        }

        $data['filter'] = compact('tahun', 'bulan', 'kebun');

        // === Data statis (list dropdown) ===
        $data['tahun_list'] = $this->Dashboard_model->get_tahun_list($organisasi_id);
        $data['bulan_list'] = $bulan_list_db;
        $data['kebun_list'] = $this->Dashboard_model->get_kebun_list($organisasi_id);

        // === Data utama dashboard ===
        $data['panen_per_bulan']         = $this->Dashboard_model->get_total_panen_per_bulan($organisasi_id, $tahun, $bulan, $kebun);
        $data['luas_kebun']              = $this->Dashboard_model->get_luas_kebun_persentase($organisasi_id, $kebun);
        $data['summary_kebun']           = $this->Dashboard_model->get_summary_kebun($organisasi_id, $kebun, $tahun, $bulan);
        $data['persediaan_pupuk']        = $this->Dashboard_model->get_persediaan_pupuk($organisasi_id, $kebun);
        $data['persentase_panen_kebun']  = $this->Dashboard_model->get_persen_panen_per_kebun($organisasi_id, $tahun, $bulan, $kebun);
        $data['panen_mingguan_kebun']    = $this->Dashboard_model->get_panen_per_minggu_per_kebun($tahun, $bulan, $organisasi_id, $kebun);

        // === Persiapan label & warna grafik mingguan ===
        $warna_preset = [
            'rgb(31, 4, 154)','rgb(0, 149, 255)','rgb(105, 118, 235)','rgb(66, 148, 196)',
            'rgb(73, 144, 226)','rgb(88, 106, 204)','rgb(54, 89, 255)','rgb(90, 130, 255)',
            'rgb(125, 160, 255)','rgb(160, 190, 255)'
        ];

        $labels = [];
        $kebun_list = [];
        $warna_kebun = [];
        $panen_data = $data['panen_mingguan_kebun'];

        foreach ($panen_data as $row) {
            $label = $this->bulan_nama_singkat[(int)$row->bulan] . "\nMinggu " . $row->minggu_ke;
            if (!in_array($label, $labels)) $labels[] = $label;
            if (!in_array($row->nama_kebun, $kebun_list)) $kebun_list[] = $row->nama_kebun;
        }

        foreach ($kebun_list as $i => $nama_kebun) {
            $warna_kebun[$nama_kebun] = $warna_preset[$i % count($warna_preset)];
        }

        // === Dataset grafik mingguan ===
        $datasets = [];
        foreach ($kebun_list as $kebun_nama) {
            $data_kebun = [];
            foreach ($labels as $label) {
                $found = array_filter($panen_data, function($row) use ($label, $kebun_nama) {
                    $current_label = $this->bulan_nama_singkat[(int)$row->bulan] . "\nMinggu " . $row->minggu_ke;
                    return $row->nama_kebun == $kebun_nama &&
                           $current_label === $label &&
                           $row->total_panen > 0;
                });
                $data_kebun[] = $found ? reset($found)->total_panen : null;
            }
            $datasets[] = [
                'label' => $kebun_nama,
                'data' => $data_kebun,
                'backgroundColor' => $warna_kebun[$kebun_nama],
            ];
        }

        $data['labels'] = $labels;
        $data['datasets'] = $datasets;
        $data['warna_kebun'] = $warna_kebun;

        // === Indikator bulanan ===
        $bulan_terakhir = max($bulan);
        $total_bulan_ini = $this->Dashboard_model->get_total_panen_bulan_ini($tahun, $bulan_terakhir, $organisasi_id, $kebun) ?? 0;
        $rata_bulanan = $this->Dashboard_model->get_rata2_panen_bulanan_tahun_ini($tahun, $organisasi_id, $kebun) ?? 0;

        $selisih_persen_bulan = $rata_bulanan ? (($total_bulan_ini - $rata_bulanan) / $rata_bulanan * 100) : 0;
        $data['rata_panen_bulanan'] = $rata_bulanan;
        $data['indikator_panen'] = [
            'nilai'  => number_format($total_bulan_ini, 0, ',', '.'),
            'persen' => round(abs($selisih_persen_bulan), 1),
            'naik'   => $selisih_persen_bulan >= 0,
        ];

        // === Indikator mingguan ===
        $is_current_month = (int)$tahun === (int)date('Y') && in_array((int)date('n'), $bulan);
        $bulan_terpilih = $is_current_month ? (int)date('n') : $bulan_terakhir;
        
        $minggu_ke = $is_current_month
            ? $this->Dashboard_model->get_current_week_in_month()
            : $this->Dashboard_model->get_minggu_terakhir_ada_panen($tahun, $bulan_terpilih, $organisasi_id, $kebun);

        $total_minggu_ini = $this->Dashboard_model->get_total_panen_minggu_ini(
            $tahun, $bulan_terpilih, $minggu_ke, $organisasi_id, $kebun
        );

        $rata_mingguan = $this->Dashboard_model->get_rata2_panen_mingguan_bulan($tahun, $organisasi_id, $kebun) ?? 0;
        $selisih_persen_minggu = $rata_mingguan ? (($total_minggu_ini - $rata_mingguan) / $rata_mingguan * 100) : 0;

        $data['rata_panen_mingguan'] = $rata_mingguan;
        $data['indikator_panen_mingguan'] = [
            'nilai'  => number_format($total_minggu_ini, 0, ',', '.'),
            'persen' => round(abs($selisih_persen_minggu), 1),
            'naik'   => $selisih_persen_minggu >= 0,
        ];

        // === Refresh time terakhir ===
        $data['last_updated'] = $this->Dashboard_model->get_last_refresh($organisasi_id);

        // === Render View ===
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('layout/footer');
    }

    public function get_bulan_by_tahun()
    {
        $user = $this->getUserData();
        $organisasi_id = $user['organisasi_id'];
        $tahun = $this->input->post('tahun');

        $bulan_list = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);
        foreach ($bulan_list as &$b) {
            $b['nama'] = $this->bulan_nama[(int)$b['bulan']];
        }

        echo json_encode($bulan_list);
    }

    private function get_last_two_months_with_harvest($tahun, $organisasi_id, $bulan_with_data)
    {
        $current_month = (int)date('n');
        $current_year = (int)date('Y');
        
        if ($tahun == $current_year) {
            // Jika tahun saat ini, ambil bulan ini dan bulan sebelumnya
            $months_to_check = [
                $current_month,
                $current_month - 1 > 0 ? $current_month - 1 : 12
            ];
        } else {
            // Jika tahun lain, ambil 2 bulan terakhir yang ada data
            rsort($bulan_with_data);
            $months_to_check = array_slice($bulan_with_data, 0, 2);
        }

        // Pastikan hanya bulan yang ada data yang diambil
        $valid_months = array_values(array_intersect($months_to_check, $bulan_with_data));
        
        // Jika tidak ada bulan valid, kembalikan array kosong atau bulan terakhir yang ada
        return empty($valid_months) ? (empty($bulan_with_data) ? [] : [max($bulan_with_data)]) : $valid_months;
    }

    public function refresh_data()
    {
        $user    = $this->getUserData();
        $id_user = $this->input->post('id_user');

        // Jalankan Pentaho job utama (default main_job.kjb)
        if (run_pentaho_job($id_user, 'main_job.kjb')) {
            $this->db->insert('dashboard_refresh_log', [
                'id_organisasi' => $user['organisasi_id'],
                'id_user'       => $id_user,
                'refreshed_at'  => date('Y-m-d H:i:s'),
            ]);
            set_alert('success', 'Refresh berhasil!');
        } else {
            set_alert('danger', 'Refresh gagal. Cek log.');
        }

        redirect('dashboard');
    }

}
