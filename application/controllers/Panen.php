<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
        }
    }
	
    public function index()
{
    $token = $this->session->userdata('token');
    $organisasi_id = $this->session->userdata('organisasi_id');
    if (!$token || !$organisasi_id) redirect('authentication');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.150.101.10/api/pemanenan",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    $allPemanenan = json_decode($response, true) ?: [];

    $dailyData = [];
    $kebunList = [];

    foreach ($allPemanenan as $panen) {
        if (!$panen['kebun'] || !$panen['tanggal_panen']) continue;

        $tanggal = date('d M Y', strtotime($panen['tanggal_panen']));
        $nama_kebun = $panen['kebun']['nama_kebun'];
        $jumlah = (int) $panen['jumlah_panen'];

        $kebunList[$nama_kebun] = true;

        if (!isset($dailyData[$tanggal])) {
            $dailyData[$tanggal] = [];
        }

        if (!isset($dailyData[$tanggal][$nama_kebun])) {
            $dailyData[$tanggal][$nama_kebun] = 0;
        }

        $dailyData[$tanggal][$nama_kebun] += $jumlah;
    }

    // Susun struktur final untuk view
    $kebunNames = array_keys($kebunList);
    sort($kebunNames); // untuk urutan kolom tetap

    $tableData = [];
    foreach ($dailyData as $tanggal => $kebunPanen) {
        $row = ['Tanggal Panen' => $tanggal];
        foreach ($kebunNames as $kebun) {
            $row[$kebun] = isset($kebunPanen[$kebun]) ? $kebunPanen[$kebun] : null;
        }
        $tableData[] = $row;
    }

    usort($tableData, function ($a, $b) {
    return strtotime($b['Tanggal Panen']) - strtotime($a['Tanggal Panen']);
});


    $data['panenPerTanggal'] = [
        'title' => array_merge(['Tanggal Panen'], $kebunNames),
        'data' => $tableData
    ];

    // Data bulanan dan tahunan
    $monthlyData = [];
    $yearlyData = [];

    foreach ($allPemanenan as $panen) {
        if (!$panen['kebun'] || !$panen['tanggal_panen']) continue;

        $bulan = date('Y-m', strtotime($panen['tanggal_panen'])); // format YYYY-MM untuk bulan
        $tahun = date('Y', strtotime($panen['tanggal_panen']));
        $nama_kebun = $panen['kebun']['nama_kebun'];
        $jumlah = (int) $panen['jumlah_panen'];

        // Bulanan
        if (!isset($monthlyData[$bulan])) $monthlyData[$bulan] = [];
        if (!isset($monthlyData[$bulan][$nama_kebun])) $monthlyData[$bulan][$nama_kebun] = 0;
        $monthlyData[$bulan][$nama_kebun] += $jumlah;

        // Tahunan
        if (!isset($yearlyData[$tahun])) $yearlyData[$tahun] = [];
        if (!isset($yearlyData[$tahun][$nama_kebun])) $yearlyData[$tahun][$nama_kebun] = 0;
        $yearlyData[$tahun][$nama_kebun] += $jumlah;
    }

    // Susun data untuk view Monthly
    $tableDataBulan = [];
    foreach ($monthlyData as $bulan => $kebunPanen) {
        $row = ['Bulan Panen' => $bulan];
        foreach ($kebunNames as $kebun) {
            $row[$kebun] = isset($kebunPanen[$kebun]) ? $kebunPanen[$kebun] : null;
        }
        $tableDataBulan[] = $row;
    }
    usort($tableDataBulan, function ($a, $b) {
        return strtotime($b['Bulan Panen'] . '-01') - strtotime($a['Bulan Panen'] . '-01');
    });

    // Susun data untuk view Yearly
    $tableDataTahun = [];
    foreach ($yearlyData as $tahun => $kebunPanen) {
        $row = ['Tahun Panen' => $tahun];
        foreach ($kebunNames as $kebun) {
            $row[$kebun] = isset($kebunPanen[$kebun]) ? $kebunPanen[$kebun] : null;
        }
        $tableDataTahun[] = $row;
    }
    usort($tableDataTahun, function ($a, $b) {
        return $b['Tahun Panen'] - $a['Tahun Panen'];
    });

    // Assign ke data untuk view
    $data['panenPerBulan'] = [
        'title' => array_merge(['Bulan Panen'], $kebunNames),
        'data' => $tableDataBulan
    ];
    $data['panenPerTahun'] = [
        'title' => array_merge(['Tahun Panen'], $kebunNames),
        'data' => $tableDataTahun
    ];


    $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];

    $this->load->view('layout/header', $data);
    $this->load->view('panen/panen', $data);
    $this->load->view('layout/footer', $data);
}



}
