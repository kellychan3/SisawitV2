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

public function has_prediksi_data($organisasi_id)
{
    // Ambil semua sk_organisasi milik organisasi ini
    $this->db->select('sk_organisasi');
    $this->db->from('dim_organisasi');
    $this->db->where('id_organisasi', $organisasi_id);
    $subquery = $this->db->get_compiled_select();

    // Cek apakah ada data prediksi panen untuk sk_organisasi tersebut
    $this->db->from('fact_prediksi_panen');
    $this->db->where("sk_organisasi IN ($subquery)", null, false);
    $this->db->where('jumlah_prediksi_panen >', 0);
    return $this->db->count_all_results() > 0;
}



public function get_available_years()
{
    $this->db->select('DISTINCT(LEFT(sk_waktu, 4)) AS tahun');
    $this->db->from('fact_prediksi_panen');
    $this->db->order_by('tahun', 'ASC');
    $query = $this->db->get();

    return $query->result_array();
}

public function get_total_prediksi_by_year($tahun, $kebun = [], $organisasi_id = null)
{
    $this->db->select_sum('fp.jumlah_prediksi_panen', 'total');
    $this->db->from('fact_prediksi_panen fp');
    $this->db->join('dim_organisasi do', 'fp.sk_organisasi = do.sk_organisasi');
    $this->db->where('LEFT(fp.sk_waktu, 4) =', $tahun, false);

    if (!empty($kebun)) {
        $this->db->where_in('fp.sk_kebun', $kebun);
    }

    if ($organisasi_id !== null) {
        $this->db->where('do.id_organisasi', $organisasi_id);
    }

    return $this->db->get()->row()->total ?? 0;
}

public function get_total_aktual_by_year($tahun, $kebun = [], $organisasi_id = null)
{
    $this->db->select_sum('fp.jumlah_panen', 'total');
    $this->db->from('fact_panen fp');
    $this->db->join('dim_user du', 'fp.sk_user = du.sk_user');
    $this->db->join('dim_organisasi do', 'du.sk_organisasi = do.sk_organisasi');
    $this->db->where('LEFT(fp.sk_waktu, 4) =', $tahun, false);

    if (!empty($kebun)) {
        $this->db->where_in('fp.sk_kebun', $kebun);
    }

    if ($organisasi_id !== null) {
        $this->db->where('do.id_organisasi', $organisasi_id);
    }

    $query = $this->db->get();
    return $query->row()->total ?? 0;
}

public function get_prediksi_bulanan($tahun, $kebun = [], $organisasi_id = null)
{
    $this->db->select("MONTH(fp.sk_waktu) as bulan, SUM(fp.jumlah_prediksi_panen) as total");
    $this->db->from('fact_prediksi_panen fp');
    $this->db->join('dim_organisasi do', 'fp.sk_organisasi = do.sk_organisasi');
    $this->db->where('LEFT(fp.sk_waktu, 4) =', $tahun, false);

    if (!empty($kebun)) {
        $this->db->where_in('fp.sk_kebun', $kebun);
    }

    if ($organisasi_id !== null) {
        $this->db->where('do.id_organisasi', $organisasi_id);
    }

    $this->db->group_by('bulan');
    $result = $this->db->get()->result();

    $data = [];
    foreach ($result as $row) {
        $data[(int)$row->bulan] = (int)$row->total;
    }

    return $data;
}

public function get_aktual_bulanan($tahun, $kebun = [], $organisasi_id = null)
{
    $this->db->select("MONTH(fp.sk_waktu) as bulan, SUM(fp.jumlah_panen) as total");
    $this->db->from('fact_panen fp');
    $this->db->join('dim_user du', 'fp.sk_user = du.sk_user');
    $this->db->join('dim_organisasi do', 'du.sk_organisasi = do.sk_organisasi');
    $this->db->where('LEFT(fp.sk_waktu, 4) =', $tahun, false);

    if (!empty($kebun)) {
        $this->db->where_in('fp.sk_kebun', $kebun);
    }

    if ($organisasi_id !== null) {
        $this->db->where('do.id_organisasi', $organisasi_id);
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
    $this->db->select('dk.sk_kebun as kebun, dk.nama_kebun');
    $this->db->from('dim_kebun dk');
    
    // Join with fact_prediksi_panen to ensure we only get kebuns with predictions
    $this->db->join('fact_prediksi_panen fp', 'dk.sk_kebun = fp.sk_kebun', 'inner');
    
    // Join with dim_organisasi for organization filtering
    $this->db->join('dim_organisasi do', 'fp.sk_organisasi = do.sk_organisasi');
    
    if ($organisasi_id !== null) {
        $this->db->where('do.id_organisasi', $organisasi_id);
    }
    
    // Group by the primary key of dim_kebun to ensure uniqueness
    $this->db->group_by('dk.sk_kebun');
    
    $this->db->order_by('dk.nama_kebun', 'ASC');
    
    return $this->db->get()->result_array();
}


}
