<?php
/**
 * ===================================================
 * CLASS KRITERIA
 * ===================================================
 * Kelas ini menangani operasi CRUD untuk tabel kriteria
 * (Create, Read, Update, Delete)
 */

class Kriteria
{
    private $db;
    
    /**
     * Constructor - menerima object Database
     */
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Fungsi getAll() - Ambil semua kriteria
     * 
     * @return array - Daftar kriteria
     */
    public function getAll()
    {
        $sql = "SELECT * FROM kriteria ORDER BY id_kriteria ASC";
        return $this->db->query($sql);
    }
    
    /**
     * Fungsi getById() - Ambil kriteria berdasarkan ID
     * 
     * @param int $id - ID kriteria
     * @return array - Data kriteria
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM kriteria WHERE id_kriteria = ? LIMIT 1";
        $result = $this->db->query($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Fungsi create() - Tambah kriteria baru
     * 
     * Untuk rumus SAW:
     * - Kriteria terdiri dari "benefit" (semakin besar semakin baik)
     *   dan "cost" (semakin kecil semakin baik)
     * - Bobot harus dijumlahkan = 1 (100%) atau divalidasi
     * 
     * @param string $nama - Nama kriteria
     * @param float $bobot - Bobot kriteria (0-1)
     * @param string $sifat - Jenis kriteria ('benefit' atau 'cost')
     * @return bool - Sukses atau gagal
     */
    public function create($nama, $bobot, $sifat)
    {
        // Validasi input
        if (empty($nama) || $bobot < 0) {
            return false;
        }
        
        $sql = "INSERT INTO kriteria (nama_kriteria, bobot, sifat) 
                VALUES (?, ?, ?)";
        
        return $this->db->execute($sql, [$nama, $bobot, $sifat]);
    }
    
    /**
     * Fungsi update() - Ubah kriteria
     * 
     * @param int $id - ID kriteria
     * @param string $nama - Nama kriteria baru
     * @param float $bobot - Bobot baru
     * @param string $sifat - Sifat baru
     * @return bool - Sukses atau gagal
     */
    public function update($id, $nama, $bobot, $sifat)
    {
        if (empty($nama) || $bobot < 0) {
            return false;
        }
        
        $sql = "UPDATE kriteria 
                SET nama_kriteria = ?, bobot = ?, sifat = ? 
                WHERE id_kriteria = ?";
        
        return $this->db->execute($sql, [$nama, $bobot, $sifat, $id]);
    }
    
    /**
     * Fungsi delete() - Hapus kriteria
     * 
     * Cascade delete akan otomatis menghapus nilai matriks
     * yang terkait dengan kriteria ini
     * 
     * @param int $id - ID kriteria
     * @return bool - Sukses atau gagal
     */
    public function delete($id)
    {
        $sql = "DELETE FROM kriteria WHERE id_kriteria = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Fungsi countAll() - Hitung jumlah kriteria
     * 
     * @return int - Jumlah kriteria
     */
    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM kriteria";
        $result = $this->db->query($sql);
        return $result[0]['total'];
    }
    
    /**
     * Fungsi getTotalBobot() - Hitung total bobot kriteria
     * 
     * Digunakan untuk validasi: total bobot seharusnya = 1.0 (100%)
     * 
     * @return float - Total bobot
     */
    public function getTotalBobot()
    {
        $sql = "SELECT SUM(bobot) as total FROM kriteria";
        $result = $this->db->query($sql);
        return $result[0]['total'] ?? 0;
    }
}
?>
