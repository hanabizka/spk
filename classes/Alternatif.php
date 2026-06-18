<?php
/**
 * ===================================================
 * CLASS ALTERNATIF
 * ===================================================
 * Kelas ini menangani operasi CRUD untuk tabel alternatif
 * 
 * Alternatif adalah pilihan/opsi yang akan dinilai
 * Contoh: Laptop A, Laptop B, Laptop C
 */

class Alternatif
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
     * Fungsi getAll() - Ambil semua alternatif
     * 
     * @return array - Daftar alternatif
     */
    public function getAll()
    {
        $sql = "SELECT * FROM alternatif ORDER BY id_alternatif ASC";
        return $this->db->query($sql);
    }
    
    /**
     * Fungsi getById() - Ambil alternatif berdasarkan ID
     * 
     * @param int $id - ID alternatif
     * @return array - Data alternatif
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM alternatif WHERE id_alternatif = ? LIMIT 1";
        $result = $this->db->query($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Fungsi create() - Tambah alternatif baru
     * 
     * @param string $nama - Nama alternatif
     * @return bool - Sukses atau gagal
     */
    public function create($nama)
    {
        if (empty($nama)) {
            return false;
        }
        
        $sql = "INSERT INTO alternatif (nama_alternatif) VALUES (?)";
        return $this->db->execute($sql, [$nama]);
    }
    
    /**
     * Fungsi update() - Ubah alternatif
     * 
     * @param int $id - ID alternatif
     * @param string $nama - Nama baru
     * @return bool - Sukses atau gagal
     */
    public function update($id, $nama)
    {
        if (empty($nama)) {
            return false;
        }
        
        $sql = "UPDATE alternatif SET nama_alternatif = ? WHERE id_alternatif = ?";
        return $this->db->execute($sql, [$nama, $id]);
    }
    
    /**
     * Fungsi delete() - Hapus alternatif
     * 
     * Cascade delete akan otomatis menghapus nilai matriks
     * yang terkait dengan alternatif ini
     * 
     * @param int $id - ID alternatif
     * @return bool - Sukses atau gagal
     */
    public function delete($id)
    {
        $sql = "DELETE FROM alternatif WHERE id_alternatif = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Fungsi countAll() - Hitung jumlah alternatif
     * 
     * @return int - Jumlah alternatif
     */
    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM alternatif";
        $result = $this->db->query($sql);
        return $result[0]['total'];
    }
}
?>
