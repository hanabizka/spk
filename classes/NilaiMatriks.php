<?php
/**
 * ===================================================
 * CLASS NILAI MATRIKS
 * ===================================================
 * Kelas ini menangani operasi untuk tabel nilai_matriks
 * 
 * Nilai matriks adalah nilai keputusan untuk setiap
 * kombinasi alternatif dan kriteria
 */

class NilaiMatriks
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
     * Fungsi getAll() - Ambil semua nilai matriks
     * 
     * @return array - Daftar nilai matriks
     */
    public function getAll()
    {
        $sql = "SELECT * FROM nilai_matriks ORDER BY id_alternatif, id_kriteria";
        return $this->db->query($sql);
    }
    
    /**
     * Fungsi getByAlternatifDanKriteria() - Ambil nilai untuk
     * kombinasi alternatif dan kriteria spesifik
     * 
     * @param int $id_alt - ID alternatif
     * @param int $id_krit - ID kriteria
     * @return array - Data nilai
     */
    public function getByAlternatifDanKriteria($id_alt, $id_krit)
    {
        $sql = "SELECT * FROM nilai_matriks 
                WHERE id_alternatif = ? AND id_kriteria = ? LIMIT 1";
        $result = $this->db->query($sql, [$id_alt, $id_krit]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Fungsi getMatriksByAlternatif() - Ambil semua nilai
     * untuk satu alternatif
     * 
     * @param int $id_alt - ID alternatif
     * @return array - Daftar nilai untuk alternatif tersebut
     */
    public function getMatriksByAlternatif($id_alt)
    {
        $sql = "SELECT nm.*, a.nama_alternatif, k.nama_kriteria, k.bobot, k.sifat
                FROM nilai_matriks nm
                INNER JOIN alternatif a ON nm.id_alternatif = a.id_alternatif
                INNER JOIN kriteria k ON nm.id_kriteria = k.id_kriteria
                WHERE nm.id_alternatif = ?
                ORDER BY nm.id_kriteria";
        return $this->db->query($sql, [$id_alt]);
    }
    
    /**
     * Fungsi getMatriksForCalculation() - Ambil matriks lengkap
     * untuk keperluan perhitungan SAW
     * 
     * Mengembalikan data dengan struktur yang rapi:
     * [
     *   ['id_alternatif' => 1, 'id_kriteria' => 1, 'nilai' => 100, ...],
     *   ...
     * ]
     * 
     * @return array - Matriks keputusan lengkap
     */
    public function getMatriksForCalculation()
    {
        $sql = "SELECT nm.id_alternatif, nm.id_kriteria, nm.nilai,
                       a.nama_alternatif, k.nama_kriteria, k.bobot, k.sifat
                FROM nilai_matriks nm
                INNER JOIN alternatif a ON nm.id_alternatif = a.id_alternatif
                INNER JOIN kriteria k ON nm.id_kriteria = k.id_kriteria
                ORDER BY nm.id_alternatif, nm.id_kriteria";
        return $this->db->query($sql);
    }
    
    /**
     * Fungsi create() - Tambah nilai matriks baru
     * 
     * @param int $id_alt - ID alternatif
     * @param int $id_krit - ID kriteria
     * @param float $nilai - Nilai matriks
     * @return bool - Sukses atau gagal
     */
    public function create($id_alt, $id_krit, $nilai)
    {
        // Validasi input
        if ($nilai < 0) {
            return false;
        }
        
        $sql = "INSERT INTO nilai_matriks (id_alternatif, id_kriteria, nilai)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE nilai = ?";
        
        return $this->db->execute($sql, [$id_alt, $id_krit, $nilai, $nilai]);
    }
    
    /**
     * Fungsi update() - Ubah nilai matriks
     * 
     * @param int $id - ID nilai
     * @param float $nilai - Nilai baru
     * @return bool - Sukses atau gagal
     */
    public function update($id, $nilai)
    {
        if ($nilai < 0) {
            return false;
        }
        
        $sql = "UPDATE nilai_matriks SET nilai = ? WHERE id_nilai = ?";
        return $this->db->execute($sql, [$nilai, $id]);
    }
    
    /**
     * Fungsi delete() - Hapus nilai matriks
     * 
     * @param int $id - ID nilai
     * @return bool - Sukses atau gagal
     */
    public function delete($id)
    {
        $sql = "DELETE FROM nilai_matriks WHERE id_nilai = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Fungsi deleteByAlternatif() - Hapus semua nilai
     * untuk satu alternatif
     * 
     * @param int $id_alt - ID alternatif
     * @return bool - Sukses atau gagal
     */
    public function deleteByAlternatif($id_alt)
    {
        $sql = "DELETE FROM nilai_matriks WHERE id_alternatif = ?";
        return $this->db->execute($sql, [$id_alt]);
    }
    
    /**
     * Fungsi getMaxValue() - Ambil nilai maksimum untuk
     * satu kriteria
     * 
     * Digunakan untuk normalisasi benefit:
     * R[i][j] = X[i][j] / max(X[j])
     * 
     * @param int $id_krit - ID kriteria
     * @return float - Nilai maksimum
     */
    public function getMaxValue($id_krit)
    {
        $sql = "SELECT MAX(nilai) as max_nilai FROM nilai_matriks 
                WHERE id_kriteria = ?";
        $result = $this->db->query($sql, [$id_krit]);
        return $result[0]['max_nilai'] ?? 0;
    }
    
    /**
     * Fungsi getMinValue() - Ambil nilai minimum untuk
     * satu kriteria
     * 
     * Digunakan untuk normalisasi cost:
     * R[i][j] = min(X[j]) / X[i][j]
     * 
     * @param int $id_krit - ID kriteria
     * @return float - Nilai minimum
     */
    public function getMinValue($id_krit)
    {
        $sql = "SELECT MIN(nilai) as min_nilai FROM nilai_matriks 
                WHERE id_kriteria = ?";
        $result = $this->db->query($sql, [$id_krit]);
        return $result[0]['min_nilai'] ?? 0;
    }
    
    /**
     * Fungsi countByAlternatif() - Hitung jumlah nilai
     * untuk satu alternatif
     * 
     * @param int $id_alt - ID alternatif
     * @return int - Jumlah nilai
     */
    public function countByAlternatif($id_alt)
    {
        $sql = "SELECT COUNT(*) as total FROM nilai_matriks 
                WHERE id_alternatif = ?";
        $result = $this->db->query($sql, [$id_alt]);
        return $result[0]['total'];
    }
}
?>
