<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_prediksi_by_year($tahun)
    {
        $this->db->select('bulan, SUM(hasil_kg) as total_prediksi');
        $this->db->from('hasil_prediksi');
        $this->db->where('tahun', $tahun);
        $this->db->group_by('bulan');
        $query = $this->db->get();

        $result = [];
        foreach ($query->result() as $row) {
            $result[(int)$row->bulan] = (int)$row->total_prediksi;
        }

        return $result;
    }

    public function get_aktual_by_year($tahun)
    {
        $token = $this->session->userdata('token');
        if (!$token) return [];

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

        $data = json_decode($response, true);
        if (!is_array($data)) return [];

        $result = [];

        foreach ($data as $item) {
            $tanggal = date_create($item['tanggal_panen']);
            if ((int)date_format($tanggal, 'Y') == $tahun) {
                $bulan = (int)date_format($tanggal, 'n');
                if (!isset($result[$bulan])) {
                    $result[$bulan] = 0;
                }
                $result[$bulan] += (int)$item['jumlah_panen'];
            }
        }

        return $result;
    }

    public function get_tahun_list()
    {
        $query = $this->db->query("SELECT DISTINCT tahun FROM hasil_prediksi ORDER BY tahun");
        return $query->result_array();
    }
}
