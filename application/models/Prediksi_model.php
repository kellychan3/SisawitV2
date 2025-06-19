<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi_model extends CI_Model
{
    private $db;

    public function __construct()
{
    parent::__construct();
    $this->db = $this->load->database('default', TRUE);
}

public function get_available_years()
{
    $this->db->select('DISTINCT(LEFT(sk_waktu, 4)) AS tahun');
    $this->db->from('fact_prediksi_panen');
    $this->db->order_by('tahun', 'ASC');
    $query = $this->db->get();

    return $query->result_array();
}

public function get_total_prediksi_by_year($tahun, $kebun = [])
{
    $this->db->select_sum('jumlah_prediksi_panen', 'total');
    $this->db->from('fact_prediksi_panen');
    $this->db->where('LEFT(sk_waktu, 4) =', $tahun, false);
    if (!empty($kebun)) {
        $this->db->where_in('sk_kebun', $kebun);
    }
    return $this->db->get()->row()->total ?? 0;
}

public function get_total_aktual_by_year($tahun, $kebun = [])
{
    $this->db->select_sum('jumlah_panen', 'total');
    $this->db->from('fact_panen');
    $this->db->where('LEFT(sk_waktu, 4) =', $tahun, false);
    if (!empty($kebun)) {
        $this->db->where_in('sk_kebun', $kebun);
    }
    $query = $this->db->get();
    return $query->row()->total ?? 0;
}


public function get_prediksi_bulanan($tahun, $kebun = [])
{
    $this->db->select("MONTH(sk_waktu) as bulan, SUM(jumlah_prediksi_panen) as total");
    $this->db->from('fact_prediksi_panen');
    $this->db->where('LEFT(sk_waktu, 4) =', $tahun, false);
    if (!empty($kebun)) {
        $this->db->where_in('sk_kebun', $kebun);
    }
    $this->db->group_by('bulan');
    $result = $this->db->get()->result();

    $data = [];
    foreach ($result as $row) {
        $data[(int)$row->bulan] = (int)$row->total;
    }

    return $data;
}

public function get_aktual_bulanan($tahun, $kebun = [])
{
    $this->db->select("MONTH(sk_waktu) as bulan, SUM(jumlah_panen) as total");
    $this->db->from('fact_panen');
    $this->db->where('LEFT(sk_waktu, 4) =', $tahun, false);
    if (!empty($kebun)) {
        $this->db->where_in('sk_kebun', $kebun);
    }
    $this->db->group_by('bulan');
    $result = $this->db->get()->result();

    $data = [];
    foreach ($result as $row) {
        $data[(int)$row->bulan] = (int)$row->total;
    }

    return $data;
}

public function get_all_kebun($organisasi_id = null)
{
    $this->db->select('fp.sk_kebun as kebun, dk.nama_kebun');
    $this->db->from('fact_prediksi_panen fp');
    $this->db->join('dim_kebun dk', 'fp.sk_kebun = dk.sk_kebun');
    $this->db->join('dim_organisasi do', 'fp.sk_organisasi = do.sk_organisasi');

    if ($organisasi_id !== null) {
        $this->db->where('do.id_organisasi', $organisasi_id);
    }

    $this->db->group_by(['fp.sk_kebun', 'dk.nama_kebun']);
    $this->db->order_by('dk.nama_kebun', 'ASC');

    return $this->db->get()->result_array();
}

}
