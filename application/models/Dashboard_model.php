<?php
class Dashboard_model extends CI_Model
{
    private $dw;

    public function __construct()
    {
        parent::__construct();
        $this->dw = $this->load->database('dw', TRUE); // db 'dw_sawit'
    }

public function get_total_panen_per_bulan($tahun = null, $bulan = null, $kebun = null)
{
    $sql = "SELECT w.bulan, w.tahun, SUM(f.jumlah_panen) as total_panen
            FROM fact_panen f
            JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
            JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
            WHERE 1=1";

    $params = [];

    if ($tahun) {
        $sql .= " AND w.tahun = ?";
        $params[] = $tahun;
    }
    if ($bulan) {
        $sql .= " AND w.bulan = ?";
        $params[] = $bulan;
    }
    if ($kebun) {
        $sql .= " AND k.nama_kebun = ?";
        $params[] = $kebun;
    }

    $sql .= " GROUP BY w.tahun, w.bulan
              ORDER BY w.tahun, w.bulan";

    return $this->dw->query($sql, $params)->result();
}

public function get_luas_kebun_persentase($kebun = null)
{
    $sql = "SELECT k.nama_kebun, SUM(f.luas_kebun_ha) as total_luas
            FROM fact_luas_kebun f
            JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
            WHERE 1=1";

    $params = [];

    if ($kebun) {
        $sql .= " AND k.nama_kebun = ?";
        $params[] = $kebun;
    }

    $sql .= " GROUP BY k.nama_kebun";

    return $this->dw->query($sql, $params)->result();
}

// Tambahkan fungsi untuk opsi filter
public function get_tahun_list()
{
    $sql = "SELECT DISTINCT tahun FROM dim_waktu ORDER BY tahun";
    return $this->dw->query($sql)->result_array();
}

public function get_bulan_list()
{
    $sql = "SELECT DISTINCT bulan FROM dim_waktu ORDER BY bulan";
    return $this->dw->query($sql)->result_array();
}

public function get_kebun_list()
{
    $sql = "SELECT nama_kebun FROM dim_kebun ORDER BY nama_kebun";
    return $this->dw->query($sql)->result_array();
}

public function get_persediaan_pupuk($kebun = null)
{
    $sql = "SELECT a.nama_aset, a.jumlah_aset, k.nama_kebun
            FROM dim_aset a
            JOIN dim_kategori_aset ka ON a.sk_kategori_aset = ka.sk_kategori_aset
            JOIN dim_kebun k ON a.sk_kebun = k.sk_kebun
            WHERE ka.nama_kategori = 'Pupuk'";

    $params = [];

    if ($kebun) {
        $sql .= " AND k.nama_kebun = ?";
        $params[] = $kebun;
    }

    return $this->dw->query($sql, $params)->result();
}

}
