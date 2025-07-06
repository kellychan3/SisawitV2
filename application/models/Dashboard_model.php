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
        $sql = "
            SELECT DISTINCT tahun FROM (
                SELECT w.tahun 
                FROM fact_panen f
                JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
                JOIN dim_user u ON f.sk_user = u.sk_user
                JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE o.id_organisasi = ?
                UNION
                SELECT w.tahun 
                FROM fact_luas_kebun f
                JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
                JOIN dim_user u ON f.sk_user = u.sk_user
                JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
                WHERE o.id_organisasi = ?
            ) t
        ";

        $result = $this->db->query($sql, [$organisasi_id, $organisasi_id])->result_array();
        $tahun_tersedia = array_column($result, 'tahun');
        $tahun_saat_ini = (int)date('Y');

        if (!in_array($tahun_saat_ini, $tahun_tersedia)) {
            $tahun_tersedia[] = $tahun_saat_ini;
        }

        sort($tahun_tersedia);
        return array_map(fn($t) => ['tahun' => $t], $tahun_tersedia);
    }

    public function get_bulan_list($organisasi_id, $tahun = null)
{
    $params = [$organisasi_id];
    $params_duplikat = [$organisasi_id]; // untuk UNION
    $sql = "
        SELECT DISTINCT w.bulan
        FROM fact_panen f
        JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
        JOIN dim_user u ON f.sk_user = u.sk_user
        JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
        WHERE o.id_organisasi = ?
    ";

    if ($tahun !== null) {
        $sql .= " AND w.tahun = ?";
        $params[] = $tahun;
    }

    $sql .= "
        UNION
        SELECT DISTINCT w.bulan
        FROM fact_luas_kebun f
        JOIN dim_waktu w ON f.sk_waktu = w.sk_waktu
        JOIN dim_user u ON f.sk_user = u.sk_user
        JOIN dim_organisasi o ON u.sk_organisasi = o.sk_organisasi
        WHERE o.id_organisasi = ?
    ";

    if ($tahun !== null) {
        $sql .= " AND w.tahun = ?";
        $params_duplikat[] = $tahun;
    }

    $sql .= " ORDER BY bulan";

    $result = $this->db->query($sql, array_merge($params, $params_duplikat))->result_array();
    $bulan_tersedia = array_column($result, 'bulan');

    $bulan_saat_ini = (int)date('n');
    $tahun_saat_ini = (int)date('Y');

    if ($tahun == $tahun_saat_ini && !in_array($bulan_saat_ini, $bulan_tersedia)) {
        $bulan_tersedia[] = $bulan_saat_ini;
    }

    sort($bulan_tersedia);
    return array_map(fn($b) => ['bulan' => $b], $bulan_tersedia);
}


    public function get_kebun_list($organisasi_id)
    {
        $sql = "
            SELECT k.sk_kebun, k.nama_kebun
            FROM dim_kebun k
            JOIN dim_organisasi o ON k.sk_organisasi = o.sk_organisasi
            WHERE o.id_organisasi = ?
            ORDER BY k.nama_kebun
        ";

        return $this->db->query($sql, [$organisasi_id])->result_array();
    }

     public function get_summary_kebun($organisasi_id = null, $kebun = null, $tahun = null, $bulan = null)
    {
        $max_bulan = max($bulan ?? [12]);
        $max_tanggal = sprintf('%04d%02d31', $tahun, $max_bulan);

        $sql = "
            SELECT 
                COUNT(DISTINCT f.sk_kebun) AS jumlah_kebun,
                SUM(f.luas_kebun) AS total_luas
            FROM (
                SELECT f1.*
                FROM fact_luas_kebun f1
                INNER JOIN (
                    SELECT sk_kebun, MAX(COALESCE(sk_waktu, 0)) AS max_waktu
                    FROM fact_luas_kebun
                    WHERE COALESCE(sk_waktu, 0) <= ?
                    GROUP BY sk_kebun
                ) latest ON f1.sk_kebun = latest.sk_kebun AND COALESCE(f1.sk_waktu, 0) = latest.max_waktu
            ) f
            LEFT JOIN dim_kebun k ON f.sk_kebun = k.sk_kebun
            LEFT JOIN dim_organisasi o ON k.sk_organisasi = o.sk_organisasi
            WHERE 1=1
        ";

        $params = [$max_tanggal];

        if ($organisasi_id !== null) {
            $sql .= " AND o.id_organisasi = ?";
            $params[] = $organisasi_id;
        }

        if ($kebun) {
            if (is_array($kebun)) {
                $placeholders = implode(',', array_fill(0, count($kebun), '?'));
                $sql .= " AND f.sk_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND f.sk_kebun = ?";
                $params[] = $kebun;
            }
        }

        $result = $this->db->query($sql, $params)->row();

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
            $this->db->where_in('f.sk_kebun', $kebun); // Perbaikan di sini
        } else {
            $this->db->where('f.sk_kebun', $kebun); // Perbaikan di sini
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
        $this->db->join('dim_user u', 'f.sk_user = u.sk_user');
        $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi');
        $this->db->join('dim_kebun k', 'f.sk_kebun = k.sk_kebun');
        $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu');

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
    // Hitung total panen dan bulan yang memiliki panen
    $this->db->select('w.bulan, SUM(f.jumlah_panen) as total_bulanan');
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

    $this->db->group_by('w.bulan');

    $query = $this->db->get()->result();

    $total_panen = 0;
    $jumlah_bulan_ada_panen = 0;

    foreach ($query as $row) {
        if ($row->total_bulanan > 0) {
            $total_panen += $row->total_bulanan;
            $jumlah_bulan_ada_panen++;
        }
    }

    return ($jumlah_bulan_ada_panen > 0) ? ($total_panen / $jumlah_bulan_ada_panen) : 0;
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

public function get_minggu_terakhir_ada_panen($tahun, $bulan, $organisasi_id = null, $kebun = null)
{
    $this->db->select('w.minggu_ke_dalam_bulan');
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

    $this->db->order_by('w.minggu_ke_dalam_bulan', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get();
    $row = $query->row();

    return $row ? (int)$row->minggu_ke_dalam_bulan : null;
}

public function get_rata2_panen_mingguan_bulan($tahun, $organisasi_id = null, $kebun = null)
{
    // 1. Total panen
    $this->db->select('SUM(f.jumlah_panen) as total');
    $this->db->from('fact_panen f');
    $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu', 'left');
    $this->db->join('dim_user u', 'f.sk_user = u.sk_user', 'left');
    $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi', 'left');

    $this->db->where('w.tahun', $tahun);

    if (!empty($organisasi_id)) {
        $this->db->where('o.id_organisasi', $organisasi_id);
    }

    if (!empty($kebun) && is_array($kebun)) {
        $this->db->where_in('f.sk_kebun', $kebun);
    }

    $result = $this->db->get()->row();
    $total_panen = (float) ($result->total ?? 0);

    // 2. Hitung jumlah minggu aktif panen
    $this->db->select('DISTINCT CONCAT(w.tahun, "-", w.minggu) as minggu');
    $this->db->from('fact_panen f');
    $this->db->join('dim_waktu w', 'f.sk_waktu = w.sk_waktu', 'left');
    $this->db->join('dim_user u', 'f.sk_user = u.sk_user', 'left');
    $this->db->join('dim_organisasi o', 'u.sk_organisasi = o.sk_organisasi', 'left');

    $this->db->where('w.tahun', $tahun);

    if (!empty($organisasi_id)) {
        $this->db->where('o.id_organisasi', $organisasi_id);
    }

    if (!empty($kebun) && is_array($kebun)) {
        $this->db->where_in('f.sk_kebun', $kebun);
    }

    $minggu_rows = $this->db->get()->result();
    $jumlah_minggu = count($minggu_rows);

    if ($jumlah_minggu === 0) return 0;

    return $total_panen / $jumlah_minggu;
}

    public function get_luas_kebun_persentase($organisasi_id = null, $kebun = null)
    {
        $sql = "SELECT k.nama_kebun, SUM(f.luas_kebun) as total_luas
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
                $sql .= " AND k.sk_kebun IN ($placeholders)";
                $params = array_merge($params, $kebun);
            } else {
                $sql .= " AND k.sk_kebun = ?";
                $params[] = $kebun;
            }
        }

        $sql .= " GROUP BY k.sk_kebun";

        return $this->db->query($sql, $params)->result();
    }

    public function get_persediaan_pupuk($organisasi_id = null, $kebun = null)
{
    $sql = "SELECT a.nama_aset, a.jumlah_aset, a.sk_kebun
            FROM dim_aset a
            JOIN dim_kategori_aset ka ON a.sk_kategori_aset = ka.sk_kategori_aset
            JOIN dim_kebun k ON a.sk_kebun = k.sk_kebun
            JOIN dim_organisasi o ON k.sk_organisasi = o.sk_organisasi
            WHERE ka.nama_kategori = 'Pupuk'";

    $params = [];

    if ($organisasi_id !== null) {
        $sql .= " AND o.id_organisasi = ?";
        $params[] = $organisasi_id;
    }

    if ($kebun) {
        if (is_array($kebun)) {
            $placeholders = implode(',', array_fill(0, count($kebun), '?'));
            $sql .= " AND a.sk_kebun IN ($placeholders)";
            $params = array_merge($params, $kebun);
        } else {
            $sql .= " AND a.sk_kebun = ?";
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
                $sql .= " AND dk2.sk_kebun IN ({$kebun_list})";
            } else {
                $escaped_kebun = $this->db->escape_str($kebun);
                $sql .= " AND dk2.sk_kebun = '{$escaped_kebun}'";
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
                $this->db->where_in('dk.sk_kebun', $kebun);
            } else {
                $this->db->where('dk.sk_kebun', $kebun);
            }
        }

        $this->db->group_by('dk.sk_kebun');
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
                $this->db->where_in('k.sk_kebun', $kebun);
            } else {
                $this->db->where('k.sk_kebun', $kebun);
            }
        }

        $this->db->group_by(['w.bulan', 'w.minggu_ke_dalam_bulan', 'k.nama_kebun']);
        $this->db->order_by('w.bulan ASC, w.minggu_ke_dalam_bulan ASC, k.nama_kebun ASC');

        return $this->db->get()->result();
    }


}
