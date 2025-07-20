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
        // === User & organisasi ===
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama'  => $this->session->userdata('nama'),
            'role'  => $this->session->userdata('role'),
        ];
        $organisasi_id = $this->session->userdata('organisasi_id');

        // === Filter input: tahun, bulan, kebun ===
        $tahun = $this->input->get('tahun') ?? date('Y');
        $bulan_input = $this->input->get('bulan');

        // Get months with data for the selected year
        $bulan_list_db = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);
        $bulan_with_data = array_column($bulan_list_db, 'bulan');

        if ($bulan_input) {
        // If user manually selects months
        $bulan = is_array($bulan_input) ? array_map('intval', $bulan_input) : [(int)$bulan_input];
        } else {
            // If no month selection, get last 2 months with harvest data
            $bulan = $this->get_last_two_months_with_harvest($tahun, $organisasi_id, $bulan_with_data);
        }

        $kebun = $this->input->get('kebun');
        if (!$kebun) {
            $kebun = array_column($this->Dashboard_model->get_kebun_list($organisasi_id), 'sk_kebun');
        } else {
            $kebun = (array) $kebun;
        }

        $data['filter'] = compact('tahun', 'bulan', 'kebun');

        // === Data statis (list dropdown) ===
        $data['tahun_list'] = $this->Dashboard_model->get_tahun_list($organisasi_id);
        $data['bulan_list'] = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);
        $data['kebun_list'] = $this->Dashboard_model->get_kebun_list($organisasi_id);

        // === Data utama dashboard ===
        $data['panen_per_bulan']         = $this->Dashboard_model->get_total_panen_per_bulan($organisasi_id, $tahun, $bulan, $kebun);
        $data['luas_kebun']              = $this->Dashboard_model->get_luas_kebun_persentase($organisasi_id, $kebun);
        $data['summary_kebun']           = $this->Dashboard_model->get_summary_kebun($organisasi_id, $kebun, $tahun, $bulan);
        $data['persediaan_pupuk']        = $this->Dashboard_model->get_persediaan_pupuk($organisasi_id, $kebun);
        $data['persentase_panen_kebun']  = $this->Dashboard_model->get_persen_panen_per_kebun($organisasi_id, $tahun, $bulan, $kebun);
        $data['panen_mingguan_kebun']    = $this->Dashboard_model->get_panen_per_minggu_per_kebun($tahun, $bulan, $organisasi_id, $kebun);

        // === Persiapan label & warna grafik mingguan ===
        $bulan_nama = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
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
            $label = $bulan_nama[(int)$row->bulan] . "\nMinggu " . $row->minggu_ke;
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
                $found = array_filter($panen_data, function($row) use ($label, $kebun_nama, $bulan_nama) {
                    return $row->nama_kebun == $kebun_nama &&
                           $bulan_nama[(int)$row->bulan] . "\nMinggu " . $row->minggu_ke === $label &&
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
    $tahun, 
    $bulan_terpilih, 
    $minggu_ke, 
    $organisasi_id, 
    $kebun
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
        $this->db->select('refreshed_at')->from('dashboard_refresh_log')
            ->where('id_organisasi', $organisasi_id)
            ->order_by('refreshed_at', 'DESC')->limit(1);

        $query = $this->db->get();
        $data['last_updated'] = $query->row()->refreshed_at ?? null;

        // === Render View ===
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/dashboard', $data);
        $this->load->view('layout/footer');
    }

    public function get_bulan_by_tahun()
{
    $tahun = $this->input->post('tahun');
    $organisasi_id = $this->session->userdata('organisasi_id');

    $bulan_list = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);

    $bulan_nama = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
               7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

    foreach ($bulan_list as &$b) {
        $b['nama'] = $bulan_nama[(int)$b['bulan']];
    }


    echo json_encode($bulan_list);
}

private function get_last_two_months_with_harvest($tahun, $organisasi_id, $bulan_with_data)
{
    // Get current month and previous month
    $current_month = (int)date('n');
    $current_year = (int)date('Y');
    
    $months_to_check = [];
    
    if ($tahun == $current_year) {
        // For current year, use actual current and previous month
        $months_to_check[] = $current_month;
        $prev_month = $current_month - 1;
        $months_to_check[] = $prev_month > 0 ? $prev_month : 12;
    } else {
        // For other years, get last 2 months from the available data
        rsort($bulan_with_data);
        $months_to_check = array_slice($bulan_with_data, 0, 2);
    }
    
    // Filter months that actually have harvest data
    $valid_months = [];
    foreach ($months_to_check as $month) {
        if (in_array($month, $bulan_with_data)) {
            $valid_months[] = $month;
        }
    }
    
    // If no valid months, return all months (fallback)
    return empty($valid_months) ? $bulan_with_data : $valid_months;
}

    public function refresh_data()
    {
        $id_user = escapeshellarg($this->input->post('id_user'));
        $command = "/opt/data-integration/kitchen.sh -file=/var/www/html/pentaho/main_job.kjb -param:id_user={$id_user}";
        
        exec($command, $output, $status);

        if ($status === 0) {
            $this->db->insert('dashboard_refresh_log', [
                'id_organisasi' => $this->session->userdata('organisasi_id'),
                'id_user'       => trim($id_user, "'"),
                'refreshed_at'  => date('Y-m-d H:i:s'),
            ]);
            $this->session->set_flashdata('message', 'Refresh berhasil!');
        } else {
            $this->session->set_flashdata('message', 'Refresh gagal. Cek log.');
        }

        redirect('dashboard');
    }
}
