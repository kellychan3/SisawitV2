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

    // Ambil filter dari URL, gunakan default tahun sekarang
    $tahun = $this->input->get('tahun') ?? date('Y');
    $bulan = $this->input->get('bulan');

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

    $minggu = date('W');

    $kebun = $this->input->get('kebun');

if (empty($kebun)) {
    $semua_kebun = $this->Dashboard_model->get_kebun_list();
    $kebun = array_column($semua_kebun, 'sk_kebun'); // default: semua kebun terpilih
} elseif (!is_array($kebun)) {
    $kebun = [$kebun];
}

    $data['filter'] = [
        'tahun' => $tahun,
        'bulan' => $bulan,
        'kebun' => $kebun,
    ];

    $data['tahun_list'] = $this->Dashboard_model->get_tahun_list();
    $data['bulan_list'] = $this->Dashboard_model->get_bulan_list();
    $data['kebun_list'] = $this->Dashboard_model->get_kebun_list();

    $data['panen_per_bulan'] = $this->Dashboard_model->get_total_panen_per_bulan($tahun, $bulan, $kebun);
    $data['luas_kebun'] = $this->Dashboard_model->get_luas_kebun_persentase($kebun);
    $data['summary_kebun'] = $this->Dashboard_model->get_summary_kebun();
    $data['persediaan_pupuk'] = $this->Dashboard_model->get_persediaan_pupuk($kebun);
    $data['persentase_panen_kebun'] = $this->Dashboard_model->get_persen_panen_per_kebun($tahun, $bulan, $kebun);
    $data['panen_mingguan_kebun'] = $this->Dashboard_model->get_panen_per_minggu_per_kebun($tahun, $bulan,  $kebun);

    $panen_data = $data['panen_mingguan_kebun'];

    $bulan_nama = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    $minggu_labels = [];
    $kebun_list = [];

    foreach ($panen_data as $row) {
    // Tambah label hanya jika ada panen
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
    $warna = [
        'rgb(31, 4, 154)',
        'rgb(0, 149, 255)',
        'rgb(105, 118, 235)',
        'rgb(66, 148, 196)',
        'rgb(73, 144, 226)',
        'rgb(88, 106, 204)'
    ];

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
        'backgroundColor' => $warna[$index % count($warna)],
    ];
}

    $data['labels'] = $minggu_labels;
    $data['datasets'] = $datasets;

    // total bulan ini & rata-rata
    $bulan_terakhir = max($bulan);
    $total_bulan_ini = $this->Dashboard_model->get_total_panen_bulan_ini($tahun, $bulan_terakhir, $kebun);

    $rata_bulanan = $this->Dashboard_model->get_rata2_panen_bulanan_tahun_ini($tahun, $kebun);
    $selisih_persen_bulan = $rata_bulanan ? (($total_bulan_ini - $rata_bulanan) / $rata_bulanan * 100) : 0;

    $data['indikator_panen'] = [
        'nilai' => number_format($total_bulan_ini, 2, ',', '.'),
        'persen' => round(abs($selisih_persen_bulan), 1),
        'naik' => $selisih_persen_bulan >= 0
    ];

    // Tentukan minggu terakhir dari bulan terakhir yang difilter
$minggu_terakhir = $this->Dashboard_model->get_minggu_terakhir_bulan($tahun, $bulan_terakhir);

$total_minggu_ini = $this->Dashboard_model->get_total_panen_minggu_ini($tahun, $bulan_terakhir, $minggu_terakhir, $kebun);
$rata_mingguan = $this->Dashboard_model->get_rata2_panen_mingguan_bulan_ini($tahun, $bulan_terakhir, $kebun);
$selisih_persen_minggu = $rata_mingguan ? (($total_minggu_ini - $rata_mingguan) / $rata_mingguan * 100) : 0;

$data['indikator_panen_mingguan'] = [
    'nilai' => number_format($total_minggu_ini, 2, ',', '.'),
    'persen' => round(abs($selisih_persen_minggu), 1),
    'naik' => $selisih_persen_minggu >= 0
];


    $this->load->view('layout/header', $data);
    $this->load->view('dashboard/dashboard', $data);
    $this->load->view('layout/footer');
}

}
