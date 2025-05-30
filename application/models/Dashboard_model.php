<?php
class Dashboard_model extends CI_Model
{
    private $dw;

    public function __construct()
    {
        parent::__construct();
        $this->dw = $this->load->database('dw', TRUE); // db 'dw_sawit'
    }

    // Helper untuk filter kebun
    private function filter_by_kebun($kebun)
    {
        if ($kebun) {
            $this->dw->join('dim_kebun k', 'f.sk_kebun = k.sk_kebun');
            if (is_array($kebun)) {
                $this->dw->where_in('k.nama_kebun', $kebun);
            } else {
                $this->dw->where('k.nama_kebun', $kebun);
            }
        }
    }

    // Function Filter
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

    public function get_summary_kebun($kebun = null)
    {
        $sql = "SELECT COUNT(DISTINCT f.sk_kebun) as jumlah_kebun, SUM(f.luas_kebun_ha) as total_luas
                FROM fact_luas_kebun f
                JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
                WHERE 1=1";

        $params = [];

        if ($kebun) {
            if (is_array($kebun)) {
                $placeholders = implode(',', array_fill(0, count($kebun), '?'));
                $sql .= " AND k.nama_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND k.nama_kebun = ?";
                $params[] = $kebun;
            }
        }

        return $this->dw->query($sql, $params)->row();
    }

    public function get_total_panen_per_bulan($tahun = null, $bulan = null, $kebun = null)
    {
        $this->dw->select('w.bulan, w.tahun, SUM(f.jumlah_panen) as total_panen');
        $this->dw->from('fact_panen f');
        $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
        $this->filter_by_kebun($kebun);

        if (!empty($tahun)) {
            $this->dw->where('w.tahun', $tahun);
        }
        if (!empty($bulan)) {
            $this->dw->where('w.bulan', $bulan);
        }

        $this->dw->group_by(['w.tahun', 'w.bulan']);
        $this->dw->order_by('w.tahun, w.bulan');

        $query = $this->dw->get();
        return $query->result();
    }

    public function get_total_panen_bulan_ini($tahun, $bulan, $kebun = null)
    {
        $this->dw->select('SUM(f.jumlah_panen) as total');
        $this->dw->from('fact_panen f');
        $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
        $this->filter_by_kebun($kebun);
        $this->dw->where('w.tahun', $tahun);
        $this->dw->where('w.bulan', $bulan);

        return $this->dw->get()->row()->total ?? 0;
    }

    public function get_rata2_panen_bulanan_tahun_ini($tahun, $kebun = null)
    {
        $sql = "SELECT AVG(bulanan.total) as rata2
                FROM (
                    SELECT w.bulan, SUM(f.jumlah_panen) as total
                    FROM fact_panen f
                    JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
                    JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
                    WHERE w.tahun = ?";

        $params = [$tahun];

        if ($kebun) {
            if (is_array($kebun)) {
                $placeholders = implode(',', array_fill(0, count($kebun), '?'));
                $sql .= " AND k.nama_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND k.nama_kebun = ?";
                $params[] = $kebun;
            }
        }

        $sql .= " GROUP BY w.bulan) as bulanan";

        return $this->dw->query($sql, $params)->row()->rata2 ?? 0;
    }

    public function get_total_panen_minggu_ini($tahun, $minggu, $kebun = null)
    {
        $this->dw->select('SUM(f.jumlah_panen) as total');
        $this->dw->from('fact_panen f');
        $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
        $this->filter_by_kebun($kebun);
        $this->dw->where('w.tahun', $tahun);
        $this->dw->where('w.minggu', $minggu);

        return $this->dw->get()->row()->total ?? 0;
    }

    public function get_rata2_panen_mingguan_tahun_ini($tahun, $kebun = null)
    {
        $sql = "SELECT AVG(mingguan.total) as rata2
                FROM (
                    SELECT w.minggu, SUM(f.jumlah_panen) as total
                    FROM fact_panen f
                    JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
                    JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
                    WHERE w.tahun = ?";

        $params = [$tahun];

        if ($kebun) {
            if (is_array($kebun)) {
                $placeholders = implode(',', array_fill(0, count($kebun), '?'));
                $sql .= " AND k.nama_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND k.nama_kebun = ?";
                $params[] = $kebun;
            }
        }

        $sql .= " GROUP BY w.minggu) as mingguan";

        return $this->dw->query($sql, $params)->row()->rata2 ?? 0;
    }

    public function get_luas_kebun_persentase($kebun = null)
    {
        $sql = "SELECT k.nama_kebun, SUM(f.luas_kebun_ha) as total_luas
                FROM fact_luas_kebun f
                JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
                WHERE 1=1";

        $params = [];

        if ($kebun) {
            if (is_array($kebun)) {
                $placeholders = implode(',', array_fill(0, count($kebun), '?'));
                $sql .= " AND k.nama_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND k.nama_kebun = ?";
                $params[] = $kebun;
            }
        }

        $sql .= " GROUP BY k.nama_kebun";

        return $this->dw->query($sql, $params)->result();
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
            if (is_array($kebun)) {
                $placeholders = implode(',', array_fill(0, count($kebun), '?'));
                $sql .= " AND k.nama_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND k.nama_kebun = ?";
                $params[] = $kebun;
            }
        }

        return $this->dw->query($sql, $params)->result();
    }
}
