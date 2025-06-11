<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi_model extends CI_Model
{
    private $dw;

    public function __construct()
    {
        parent::__construct();
        $this->dw = $this->load->database('dw', TRUE); 
    }

    public function get_prediksi_by_year($tahun)
    {
        $this->dw->select('bulan, SUM(hasil_kg) as total_prediksi');
        $this->dw->from('fact_prediksi_panen');
        $this->dw->where('tahun', $tahun);
        $this->dw->group_by('bulan');
        $query = $this->dw->get();

        $result = [];
        foreach ($query->result() as $row) {
            $result[(int)$row->bulan] = (int)$row->total_prediksi;
        }

        return $result;
    }

    public function get_aktual_by_year($tahun)
{
    $this->dw->select('w.bulan, SUM(f.jumlah_panen) as total_panen');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->dw->where('w.tahun', $tahun);
    $this->dw->group_by('w.bulan');

    $query = $this->dw->get();
    $result = [];
    foreach ($query->result() as $row) {
        $result[(int)$row->bulan] = (int)$row->total_panen;
    }

    return $result;
}

    public function get_tahun_list()
{
    $query = $this->dw->query("SELECT DISTINCT tahun FROM fact_prediksi_panen ORDER BY tahun");
    return $query->result_array();
}

}
