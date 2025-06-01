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
    $sql = "SELECT DISTINCT w.tahun 
            FROM fact_panen f
            JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
            ORDER BY w.tahun";
    return $this->dw->query($sql)->result_array();
}


    public function get_bulan_list()
    {
    $sql = "SELECT DISTINCT w.bulan 
            FROM fact_panen f
            JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
            ORDER BY w.bulan";
    return [
        ['bulan' => '1', 'nama' => 'Januari'],
        ['bulan' => '2', 'nama' => 'Februari'],
        ['bulan' => '3', 'nama' => 'Maret'],
        ['bulan' => '4', 'nama' => 'April'],
        ['bulan' => '5', 'nama' => 'Mei'],
        ['bulan' => '6', 'nama' => 'Juni'],
        ['bulan' => '7', 'nama' => 'Juli'],
        ['bulan' => '8', 'nama' => 'Agustus'],
        ['bulan' => '9', 'nama' => 'September'],
        ['bulan' => '10', 'nama' => 'Oktober'],
        ['bulan' => '11', 'nama' => 'November'],
        ['bulan' => '12', 'nama' => 'Desember'],
    ];
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
    if (is_array($bulan)) {
        $this->dw->where_in('w.bulan', $bulan);
    } else {
        $this->dw->where('w.bulan', $bulan);
    }
}


        $this->dw->group_by(['w.tahun', 'w.bulan']);
        $this->dw->order_by('w.tahun, w.bulan');

        $query = $this->dw->get();
        return $query->result();
    }

    public function get_total_panen_bulan_ini($tahun, $bulan, $kebun = null)
{
    $this->dw->select_sum('f.jumlah_panen', 'total');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');

    $this->dw->where('w.tahun', $tahun);
    $this->dw->where('w.bulan', $bulan);

    if ($kebun !== null && is_array($kebun)) {
        $this->dw->where_in('f.sk_kebun', $kebun);
    }

    $query = $this->dw->get();
    $result = $query->row();
    return $result ? (float)$result->total : 0;
}

    public function get_rata2_panen_bulanan_tahun_ini($tahun, $kebun = null)
{
    // Ambil bulan terakhir berdasarkan data yang ada
    $this->dw->select_max('w.bulan');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->dw->where('w.tahun', $tahun);

    if ($kebun !== null && is_array($kebun)) {
        $this->dw->where_in('f.sk_kebun', $kebun);
    }

    $bulan_terakhir = $this->dw->get()->row()->bulan;

    // Hitung total panen dari Januari sampai bulan terakhir
    $this->dw->select_sum('f.jumlah_panen', 'total');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->dw->where('w.tahun', $tahun);
    $this->dw->where('w.bulan <=', $bulan_terakhir);

    if ($kebun !== null && is_array($kebun)) {
        $this->dw->where_in('f.sk_kebun', $kebun);
    }

    $total_panen = $this->dw->get()->row()->total;

    if (!$total_panen || !$bulan_terakhir) return 0;

    return $total_panen / $bulan_terakhir;
}

public function get_minggu_terakhir_bulan($tahun, $bulan)
{
    $this->dw->select_max('minggu_ke_dalam_bulan', 'max_minggu');
    $this->dw->from('dim_waktu');
    $this->dw->where('tahun', $tahun);
    $this->dw->where('bulan', $bulan);

    $query = $this->dw->get();
    $row = $query->row();
    return $row ? (int)$row->max_minggu : 1;
}


    public function get_total_panen_minggu_ini($tahun, $bulan, $minggu_ke, $kebun = null)
{
    $this->dw->select_sum('f.jumlah_panen', 'total');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->dw->where('w.tahun', $tahun);
    $this->dw->where('w.bulan', $bulan);
    $this->dw->where('w.minggu_ke_dalam_bulan', $minggu_ke);

    if ($kebun !== null && is_array($kebun)) {
        $this->dw->where_in('f.sk_kebun', $kebun);
    }

    $query = $this->dw->get();
    $result = $query->row();
    return $result ? (float)$result->total : 0;
}

public function get_rata2_panen_mingguan_bulan_ini($tahun, $bulan, $kebun = null)
{
    $this->dw->select('w.minggu_ke_dalam_bulan, SUM(f.jumlah_panen) as total');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->dw->where('w.tahun', $tahun);
    $this->dw->where('w.bulan', $bulan);

    if ($kebun !== null && is_array($kebun)) {
        $this->dw->where_in('f.sk_kebun', $kebun);
    }

    $this->dw->group_by('w.minggu_ke_dalam_bulan');
    $query = $this->dw->get();
    $rows = $query->result();

    if (count($rows) === 0) return 0;

    $total = array_sum(array_column($rows, 'total'));
    return $total / count($rows);
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

   public function get_persen_panen_per_kebun($tahun, $bulan_arr, $kebun = null)
{
    $tahun = (int)$tahun;
    $bulan_in = implode(',', array_map('intval', $bulan_arr));

    // Subquery total panen, dengan filter kebun jika ada
    $subquery = "
        SELECT SUM(fp2.jumlah_panen) AS total_panen
        FROM fact_panen fp2
        JOIN dim_waktu dw2 ON fp2.sk_waktu = dw2.sk_waktu
        JOIN dim_kebun dk2 ON fp2.sk_kebun = dk2.sk_kebun
        WHERE dw2.tahun = {$tahun}
          AND dw2.bulan IN ({$bulan_in})
    ";

    // Tambahkan filter kebun ke subquery jika ada
    if ($kebun) {
        if (is_array($kebun)) {
            // Escape & wrap nama kebun untuk SQL injection (harus hati-hati)
            $escaped_kebun = array_map(function($k){ return "'" . $this->dw->escape_str($k) . "'"; }, $kebun);
            $kebun_list = implode(',', $escaped_kebun);
            $subquery .= " AND dk2.nama_kebun IN ({$kebun_list})";
        } else {
            $escaped_kebun = $this->dw->escape_str($kebun);
            $subquery .= " AND dk2.nama_kebun = '{$escaped_kebun}'";
        }
    }

    $this->dw->select('
        dk.nama_kebun,
        SUM(fp.jumlah_panen) AS total_panen_kebun,
        (SUM(fp.jumlah_panen) / NULLIF(total.total_panen, 0)) * 100 AS persentase
    ', false);
    $this->dw->from('fact_panen fp');
    $this->dw->join('dim_kebun dk', 'fp.sk_kebun = dk.sk_kebun');
    $this->dw->join('dim_waktu dw', 'fp.sk_waktu = dw.sk_waktu');
    $this->dw->join("($subquery) total", '1=1', 'inner', false);

    $this->dw->where('dw.tahun', $tahun);
    $this->dw->where_in('dw.bulan', $bulan_arr);

    // Filter kebun di outer query juga supaya konsisten
    if ($kebun) {
        if (is_array($kebun)) {
            $this->dw->where_in('dk.nama_kebun', $kebun);
        } else {
            $this->dw->where('dk.nama_kebun', $kebun);
        }
    }

    $this->dw->group_by('dk.nama_kebun');
    $this->dw->order_by('total_panen_kebun', 'DESC');

    return $this->dw->get()->result();
}
public function get_panen_per_minggu_per_kebun($tahun, $bulan = null, $kebun = null)
{
    $this->dw->select('w.bulan, w.minggu_ke_dalam_bulan AS minggu_ke, k.nama_kebun, SUM(f.jumlah_panen) as total_panen');
    $this->dw->from('fact_panen f');
    $this->dw->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->dw->join('dim_kebun k', 'f.sk_kebun = k.sk_kebun');
    $this->dw->where('w.tahun', $tahun);

    if (!empty($bulan)) {
        if (is_array($bulan)) {
            $this->dw->where_in('w.bulan', $bulan);
        } else {
            $this->dw->where('w.bulan', $bulan);
        }
    }

    if (!empty($kebun)) {
        if (is_array($kebun)) {
            $this->dw->where_in('k.nama_kebun', $kebun);
        } else {
            $this->dw->where('k.nama_kebun', $kebun);
        }
    }

    $this->dw->group_by(['w.bulan', 'w.minggu_ke_dalam_bulan', 'k.nama_kebun']);
    $this->dw->order_by('w.bulan ASC, w.minggu_ke_dalam_bulan ASC, k.nama_kebun ASC');

    return $this->dw->get()->result();
}



}
