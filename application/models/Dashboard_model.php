<?php
class Dashboard_model extends CI_Model
{
    private $db;

    public function __construct()
{
    parent::__construct();
    $this->db = $this->load->database('default', TRUE);
}

    public function get_tahun_list($organisasi_id)
    {
        $sql = "SELECT DISTINCT w.tahun 
                FROM fact_panen f
                JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
                JOIN dim_user u ON f.sk_user = u.sk_user
                JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE o.id_organisasi = ?
                ORDER BY w.tahun";

        return $this->db->query($sql, [$organisasi_id])->result_array();
    }

    public function get_bulan_list($organisasi_id, $tahun)
    {
        $sql = "SELECT DISTINCT w.bulan 
                FROM fact_panen f
                JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
                JOIN dim_user u ON f.sk_user = u.sk_user
                JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE o.id_organisasi = ? AND w.tahun = ?
                ORDER BY CAST(w.bulan AS UNSIGNED)";

        $result = $this->db->query($sql, [$organisasi_id, $tahun])->result_array();

        $bulan_map = [
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        $bulan_list = [];
        foreach ($result as $row) {
            $bulan_num = (int)$row['bulan'];
            $bulan_list[] = [
                'bulan' => (string)$bulan_num,
                'nama' => $bulan_map[$bulan_num] ?? 'Bulan tidak dikenal',
            ];
        }

        return $bulan_list;
    }


    public function get_kebun_list($organisasi_id)
    {
        $sql = "SELECT DISTINCT k.nama_kebun 
                FROM fact_panen f
                LEFT JOIN dim_kebun k ON k.sk_kebun = f.sk_kebun
                JOIN dim_user u ON f.sk_user = u.sk_user
                JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE o.id_organisasi = ?
                ORDER BY k.nama_kebun";

        $result = $this->db->query($sql, [$organisasi_id])->result_array();

        // Filter null kebun jika tidak ingin menampilkan baris tanpa nama kebun
        return array_filter($result, function($row) {
            return !is_null($row['nama_kebun']);
        });
    }


    public function get_summary_kebun($organisasi_id = null, $kebun = null)
    {
        $sql = "SELECT COUNT(DISTINCT f.sk_kebun) as jumlah_kebun, 
                    SUM(f.luas_kebun_ha) as total_luas
                FROM fact_luas_kebun f
                LEFT JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
                LEFT JOIN dim_user u ON f.sk_user = u.sk_user
                LEFT JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE 1=1";

        $params = [];

        if ($organisasi_id !== null) {
            $sql .= " AND o.id_organisasi = ?";
            $params[] = $organisasi_id;
        }

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

        $result = $this->db->query($sql, $params)->row();

        // Pastikan hasilnya tetap memiliki nilai default jika null
        return (object)[
            'jumlah_kebun' => $result->jumlah_kebun ?? 0,
            'total_luas'   => $result->total_luas ?? 0,
        ];
    }

    public function get_total_panen_per_bulan($organisasi_id = null, $tahun = null, $bulan = null, $kebun = null)
    {
        $this->db->select('w.bulan, w.tahun, SUM(f.jumlah_panen) as total_panen');
        $this->db->from('fact_panen f');
        $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
        $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
        $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');
        $this->db->join('dim_kebun k', 'f.sk_kebun = k.sk_kebun');

        if (!empty($organisasi_id)) {
            $this->db->where('o.id_organisasi', $organisasi_id);
        }

        if (!empty($kebun)) {
            if (is_array($kebun)) {
                $this->db->where_in('k.nama_kebun', $kebun);
            } else {
                $this->db->where('k.nama_kebun', $kebun);
            }
        }

        if (!empty($tahun)) {
            $this->db->where('w.tahun', $tahun);
        }

        if (!empty($bulan)) {
            if (is_array($bulan)) {
                $this->db->where_in('w.bulan', $bulan);
            } else {
                $this->db->where('w.bulan', $bulan);
            }
        }

        $this->db->group_by(['w.tahun', 'w.bulan']);
        $this->db->order_by('w.tahun, w.bulan');

        $result = $this->db->get()->result();
        return $result ?: []; 

    }

    public function get_total_panen_bulan_ini($tahun, $bulan, $organisasi_id = null, $kebun = null)
    {
        $this->db->select_sum('f.jumlah_panen', 'total');
        $this->db->from('fact_panen f');
        $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
        $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
        $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');

        $this->db->where('w.tahun', $tahun);
        $this->db->where('w.bulan', $bulan);

        if (!empty($organisasi_id)) {
            $this->db->where('o.id_organisasi', $organisasi_id);
        }

        if ($kebun !== null && is_array($kebun) && !empty($kebun)) {
        $this->db->where_in('f.sk_kebun', $kebun);
    }

        $query = $this->db->get();
        $result = $query->row();
        return $result ? (float)$result->total : 0;
    }

    public function get_rata2_panen_bulanan_tahun_ini($tahun, $organisasi_id = null, $kebun = null)
{
    // Ambil bulan terakhir
    $this->db->select_max('w.bulan');
    $this->db->from('fact_panen f');
    $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
    $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');
    $this->db->where('w.tahun', $tahun);

    if (!empty($organisasi_id)) {
        $this->db->where('o.id_organisasi', $organisasi_id);
    }

    if (!empty($kebun) && is_array($kebun)) {
        $this->db->where_in('f.sk_kebun', $kebun);
    }

    $bulan_terakhir = $this->db->get()->row()->bulan;

    if (empty($bulan_terakhir)) {
        return 0; // Tidak ada data, langsung return 0
    }

    // Hitung total panen
    $this->db->select_sum('f.jumlah_panen', 'total');
    $this->db->from('fact_panen f');
    $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
    $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');
    $this->db->where('w.tahun', $tahun);
    $this->db->where('w.bulan <=', $bulan_terakhir);

    if (!empty($organisasi_id)) {
        $this->db->where('o.id_organisasi', $organisasi_id);
    }

    if (!empty($kebun) && is_array($kebun)) {
        $this->db->where_in('f.sk_kebun', $kebun);
    }

    $total_panen = $this->db->get()->row()->total;

    return (!$total_panen) ? 0 : $total_panen / $bulan_terakhir;
}

    public function get_minggu_terakhir_bulan($tahun, $bulan)
    {
        $this->db->select_max('minggu_ke_dalam_bulan', 'max_minggu');
        $this->db->from('dim_waktu');
        $this->db->where('tahun', $tahun);
        $this->db->where('bulan', $bulan);

        $query = $this->db->get();
        $row = $query->row();
        return $row ? (int)$row->max_minggu : 1;
    }

    public function get_total_panen_minggu_ini($tahun, $bulan, $minggu_ke, $organisasi_id = null, $kebun = null)
{
    $this->db->select_sum('f.jumlah_panen', 'total');
    $this->db->from('fact_panen f');
    $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
    $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');

    $this->db->where('w.tahun', $tahun);
    $this->db->where('w.bulan', $bulan);
    $this->db->where('w.minggu_ke_dalam_bulan', $minggu_ke);

    if (!empty($organisasi_id)) {
        $this->db->where('o.id_organisasi', $organisasi_id);
    }

    if ($kebun !== null && is_array($kebun) && !empty($kebun)) {
    $this->db->where_in('f.sk_kebun', $kebun);
}

    $query = $this->db->get();
    $result = $query->row();
    return $result ? (float)$result->total : 0;
}


public function get_rata2_panen_mingguan_bulan_ini($tahun, $bulan, $organisasi_id = null, $kebun = null)
{
    $this->db->select('w.minggu_ke_dalam_bulan, SUM(f.jumlah_panen) as total');
    $this->db->from('fact_panen f');
    $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
    $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
    $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');

    $this->db->where('w.tahun', $tahun);
    $this->db->where('w.bulan', $bulan);

    if (!empty($organisasi_id)) {
        $this->db->where('o.id_organisasi', $organisasi_id);
    }

    if ($kebun !== null && is_array($kebun) && !empty($kebun)) {
    $this->db->where_in('f.sk_kebun', $kebun);
}

    $this->db->group_by('w.minggu_ke_dalam_bulan');
    $query = $this->db->get();
    $rows = $query->result();

    if (count($rows) === 0) return 0;

    $total = array_sum(array_column($rows, 'total'));
    return $total / count($rows);
}


    public function get_luas_kebun_persentase($organisasi_id = null, $kebun = null)
    {
        $sql = "SELECT k.nama_kebun, SUM(f.luas_kebun_ha) as total_luas
                FROM fact_luas_kebun f
                JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
                JOIN dim_user u ON f.sk_user = u.sk_user
                JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE 1=1";

        $params = [];

        if ($organisasi_id !== null) {
                $sql .= " AND o.id_organisasi = ?";
                $params[] = $organisasi_id;
            }

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

        return $this->db->query($sql, $params)->result();
    }

    public function get_persediaan_pupuk($organisasi_id = null, $kebun = null)
    {
        $sql = "SELECT a.nama_aset, a.jumlah_aset, k.nama_kebun
                FROM dim_aset a
                JOIN dim_kategori_aset ka ON a.sk_kategori_aset = ka.sk_kategori_aset
                JOIN dim_kebun k ON a.sk_kebun = k.sk_kebun
                WHERE ka.nama_kategori = 'Pupuk'";
        
        $params = [];

        // Filter berdasarkan organisasi (jika ada)
        if ($organisasi_id) {
            $sql .= " AND k.sk_organisasi = ?";
            $params[] = $organisasi_id;
        }

        // Filter berdasarkan kebun (jika ada)
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

        return $this->db->query($sql, $params)->result();
    }

    public function get_persen_panen_per_kebun($organisasi_id, $tahun, $bulan_arr, $kebun = null)
    {
        $tahun = (int)$tahun;
        $bulan_in = implode(',', array_map('intval', $bulan_arr));

        // Subquery total panen untuk organisasi tertentu
        $sql = "
            SELECT SUM(fp2.jumlah_panen) AS total_panen
            FROM fact_panen fp2
            JOIN dim_waktu dw2 ON fp2.sk_waktu = dw2.sk_waktu
            JOIN dim_kebun dk2 ON fp2.sk_kebun = dk2.sk_kebun
            JOIN dim_user du2 ON fp2.sk_user = du2.sk_user
            JOIN dim_organisasi o2 ON du2.sk_organisasi = o2.sk_organisasi
            WHERE dw2.tahun = {$tahun}
            AND dw2.bulan IN ({$bulan_in})
            AND o2.id_organisasi = {$this->db->escape($organisasi_id)}
        ";

        // Filter kebun di subquery jika ada
        if ($kebun) {
            if (is_array($kebun)) {
                $escaped_kebun = array_map(function($k){ return "'" . $this->db->escape_str($k) . "'"; }, $kebun);
                $kebun_list = implode(',', $escaped_kebun);
                $sql .= " AND dk2.nama_kebun IN ({$kebun_list})";
            } else {
                $escaped_kebun = $this->db->escape_str($kebun);
                $sql .= " AND dk2.nama_kebun = '{$escaped_kebun}'";
            }
        }

        // Main query
        $this->db->select('
            dk.nama_kebun,
            SUM(fp.jumlah_panen) AS total_panen_kebun,
            (SUM(fp.jumlah_panen) / NULLIF(total.total_panen, 0)) * 100 AS persentase
        ', false);
        $this->db->from('fact_panen fp');
        $this->db->join('dim_kebun dk', 'fp.sk_kebun = dk.sk_kebun');
        $this->db->join('dim_waktu dw', 'fp.sk_waktu = dw.sk_waktu');
        $this->db->join('dim_user du', 'fp.sk_user = du.sk_user');
        $this->db->join('dim_organisasi o', 'du.sk_organisasi = o.sk_organisasi');
        $this->db->join("($sql) total", '1=1', 'inner', false);

        $this->db->where('dw.tahun', $tahun);
        $this->db->where_in('dw.bulan', $bulan_arr);
        $this->db->where('o.id_organisasi', $organisasi_id);

        if ($kebun) {
            if (is_array($kebun)) {
                $this->db->where_in('dk.nama_kebun', $kebun);
            } else {
                $this->db->where('dk.nama_kebun', $kebun);
            }
        }

        $this->db->group_by('dk.nama_kebun');
        $this->db->order_by('total_panen_kebun', 'DESC');

        return $this->db->get()->result();
    }


    public function get_panen_per_minggu_per_kebun($tahun, $bulan = null, $organisasi_id = null, $kebun = null)
    {
        $this->db->select('w.bulan, w.minggu_ke_dalam_bulan AS minggu_ke, k.nama_kebun, SUM(f.jumlah_panen) as total_panen');
        $this->db->from('fact_panen f');
        $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');
        $this->db->join('dim_kebun k', 'f.sk_kebun = k.sk_kebun');
        $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
        $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');
        
        $this->db->where('w.tahun', $tahun);

        if (!empty($organisasi_id)) {
            $this->db->where('o.id_organisasi', $organisasi_id);
        }

        if (!empty($bulan)) {
            if (is_array($bulan)) {
                $this->db->where_in('w.bulan', $bulan);
            } else {
                $this->db->where('w.bulan', $bulan);
            }
        }

        if (!empty($kebun)) {
            if (is_array($kebun)) {
                $this->db->where_in('k.nama_kebun', $kebun);
            } else {
                $this->db->where('k.nama_kebun', $kebun);
            }
        }

        $this->db->group_by(['w.bulan', 'w.minggu_ke_dalam_bulan', 'k.nama_kebun']);
        $this->db->order_by('w.bulan ASC, w.minggu_ke_dalam_bulan ASC, k.nama_kebun ASC');

        return $this->db->get()->result();
    }


}
