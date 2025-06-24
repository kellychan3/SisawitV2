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
        if ($this->session->userdata('email')) {
            $data['user'] = [
                'email' => $this->session->userdata('email'),
                'nama' => $this->session->userdata('nama'),
                'role' => $this->session->userdata('role'),
            ];
        }

        $organisasi_id = $this->session->userdata('organisasi_id'); 

        $tahun = $this->input->get('tahun') ?? date('Y');
        $bulan = range(1, date('n'));

        if (empty($bulan)) {
            $bulanIni = (int)date('n');
            $bulan = [
                $bulanIni,
                $bulanIni - 1 > 0 ? $bulanIni - 1 : 12,
                $bulanIni - 2 > 0 ? $bulanIni - 2 : 11,
            ];
        } else {
            if (!is_array($bulan)) {
                $bulan = [$bulan];
            }
            $bulan = array_map('intval', $bulan);
        }

        $kebun = $this->input->get('kebun');

        if (empty($kebun)) {
            $semua_kebun = $this->Dashboard_model->get_kebun_list($organisasi_id);
            $kebun = array_column($semua_kebun ?? [], 'sk_kebun');
        } elseif (!is_array($kebun)) {
            $kebun = [$kebun];
        }

        $data['filter'] = [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'kebun' => $kebun,
        ];

        $data['tahun_list'] = $this->Dashboard_model->get_tahun_list($organisasi_id);
        $data['bulan_list'] = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);
        $data['kebun_list'] = $this->Dashboard_model->get_kebun_list($organisasi_id);
        
        $data['panen_per_bulan'] = $this->Dashboard_model->get_total_panen_per_bulan($organisasi_id, $tahun, $bulan, $kebun);
        $data['luas_kebun'] = $this->Dashboard_model->get_luas_kebun_persentase($organisasi_id, $kebun);
        $data['summary_kebun'] = $this->Dashboard_model->get_summary_kebun($organisasi_id, $kebun, $tahun, $bulan);

        $data['persediaan_pupuk'] = $this->Dashboard_model->get_persediaan_pupuk($organisasi_id, $kebun);
        $data['persentase_panen_kebun'] = $this->Dashboard_model->get_persen_panen_per_kebun($organisasi_id, $tahun, $bulan, $kebun);
        $data['panen_mingguan_kebun'] = $this->Dashboard_model->get_panen_per_minggu_per_kebun($tahun, $bulan, $organisasi_id, $kebun);

        $panen_data = $data['panen_mingguan_kebun'];

        $bulan_nama = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $minggu_labels = [];
        $kebun_list = [];

        foreach ($panen_data as $row) {

        if ((float)$row->total_panen > 0) {
            $label = $bulan_nama[(int)$row->bulan] . ' Minggu ' . $row->minggu_ke;
            if (!in_array($label, $minggu_labels)) {
                $minggu_labels[] = $label;
            }
        }

        if (!in_array($row->nama_kebun, $kebun_list)) {
            $kebun_list[] = $row->nama_kebun;
        }
        }

        $datasets = [];
        
        $warna_preset = [
        'rgb(31, 4, 154)',    
        'rgb(0, 149, 255)',   
        'rgb(105, 118, 235)',
        'rgb(66, 148, 196)',
        'rgb(73, 144, 226)',
        'rgb(88, 106, 204)',
        'rgb(54, 89, 255)',
        'rgb(90, 130, 255)',
        'rgb(125, 160, 255)',
        'rgb(160, 190, 255)', 
        ];


        $warna_kebun = [];
        foreach ($kebun_list as $i => $nama_kebun) {
            $warna_kebun[$nama_kebun] = $warna_preset[$i % count($warna_preset)];
        }

        foreach ($kebun_list as $index => $kebun) {
        $data_per_kebun = [];

        foreach ($minggu_labels as $label) {
            $panen = null;
            foreach ($panen_data as $row) {
                $row_label = $bulan_nama[(int)$row->bulan] . ' Minggu ' . $row->minggu_ke;
                if ($row_label == $label && $row->nama_kebun == $kebun && (float)$row->total_panen > 0) {
                    $panen = (float)$row->total_panen;
                    break;
                }
            }
            $data_per_kebun[] = $panen;
        }

        $datasets[] = [
            'label' => $kebun,
            'data' => $data_per_kebun,
            'backgroundColor' => $warna_kebun[$kebun],
        ];
    }

    $data['labels'] = $minggu_labels;
    $data['datasets'] = $datasets;

    // total bulan ini & rata-rata
    $bulan_terakhir = max($bulan);
    $total_bulan_ini = $this->Dashboard_model->get_total_panen_bulan_ini($tahun, $bulan_terakhir,$organisasi_id, $kebun);
    $total_bulan_ini = $total_bulan_ini ?? 0;

    $rata_bulanan = $this->Dashboard_model->get_rata2_panen_bulanan_tahun_ini( $tahun, $organisasi_id, $kebun);
    $rata_bulanan = $rata_bulanan ?? 0;

    $selisih_persen_bulan = $rata_bulanan ? (($total_bulan_ini - $rata_bulanan) / $rata_bulanan * 100) : 0;

    $data['indikator_panen'] = [
        'nilai' => number_format($total_bulan_ini, 2, ',', '.'),
        'persen' => round(abs($selisih_persen_bulan), 1),
        'naik' => $selisih_persen_bulan >= 0
    ];

    // Ambil tanggal hari ini
$tanggal_hari_ini = new DateTime(); // default ke hari ini
$tahun_sekarang = (int)$tanggal_hari_ini->format('Y');
$bulan_sekarang = (int)$tanggal_hari_ini->format('n');
$tanggal_ke = (int)$tanggal_hari_ini->format('j');

// Hitung minggu ke dalam bulan
$minggu_ke = (int)ceil($tanggal_ke / 7);

// Pakai bulan sekarang (bukan bulan_terakhir)
$total_minggu_ini = $this->Dashboard_model->get_total_panen_minggu_ini($tahun_sekarang, $bulan_sekarang, $minggu_ke, $organisasi_id, $kebun);

    $total_minggu_ini = $total_minggu_ini ?? 0;
    
    $rata_mingguan = $this->Dashboard_model->get_rata2_panen_mingguan_bulan($tahun, $bulan, $organisasi_id, $kebun);

    $rata_mingguan = $rata_mingguan ?? 0;
    
    $selisih_persen_minggu = $rata_mingguan ? (($total_minggu_ini - $rata_mingguan) / $rata_mingguan * 100) : 0;

    $data['indikator_panen_mingguan'] = [
        'nilai' => number_format($total_minggu_ini, 2, ',', '.'),
        'persen' => round(abs($selisih_persen_minggu), 1),
        'naik' => $selisih_persen_minggu >= 0
    ];

    $data['warna_kebun'] = $warna_kebun;

    $this->db->select('refreshed_at');
$this->db->from('dashboard_refresh_log');
$this->db->where('id_organisasi', $organisasi_id);
$this->db->order_by('refreshed_at', 'DESC');
$this->db->limit(1);
$query = $this->db->get();

$data['last_updated'] = $query->row() ? $query->row()->refreshed_at : null;


    $this->load->view('layout/header', $data);
    $this->load->view('dashboard/dashboard', $data);
    $this->load->view('layout/footer');
    }

    public function get_bulan_by_tahun()
    {
        $organisasi_id = $this->session->userdata('organisasi_id');
        $tahun = $this->input->post('tahun');
        $bulan_list = $this->Dashboard_model->get_bulan_list($organisasi_id, $tahun);
        echo json_encode($bulan_list);
    }

    public function refresh_data()
    {
        $id_user = $this->input->post('id_user');

        // Pastikan aman dari injection jika Anda pakai shell
        $escaped_id_user = escapeshellarg($id_user);

        $command = "/opt/data-integration/kitchen.sh -file=/var/www/html/pentaho/main_job.kjb -param:id_user={$escaped_id_user}";

        exec($command, $output, $status);

        if ($status === 0) {
    // Catat waktu refresh
    $this->db->insert('dashboard_refresh_log', [
        'id_organisasi' => $this->session->userdata('organisasi_id'),
        'id_user' => $id_user,
        'refreshed_at' => date('Y-m-d H:i:s'),
    ]);

    $this->session->set_flashdata('message', 'Refresh berhasil!');
}
 else {
            $this->session->set_flashdata('message', 'Refresh gagal. Cek log.');
        }

        redirect('dashboard');
    }



}
