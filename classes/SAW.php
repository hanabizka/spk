<?php
/**
 * ===================================================
 * CLASS SAW (SIMPLE ADDITIVE WEIGHTING)
 * ===================================================
 * 
 * Kelas ini menangani seluruh proses perhitungan
 * metode Simple Additive Weighting (SAW)
 * 
 * PENJELASAN RUMUS SAW:
 * 
 * SAW adalah metode penjumlahan bobot untuk mencari alternatif terbaik.
 * Prosesnya ada 3 tahap:
 * 
 * 1. NORMALISASI MATRIKS (Membuat skala 0-1)
 *    Untuk kriteria BENEFIT (semakin besar semakin baik):
 *    R[i][j] = X[i][j] / max(X[j])
 *    
 *    Untuk kriteria COST (semakin kecil semakin baik):
 *    R[i][j] = min(X[j]) / X[i][j]
 * 
 * 2. HITUNG RANKING (Bobot × Normalisasi)
 *    V[i] = Σ(w[j] × R[i][j])
 *    
 *    Dimana:
 *    - V[i] = Skor preferensi alternatif i
 *    - w[j] = Bobot kriteria j
 *    - R[i][j] = Nilai normalisasi alternatif i untuk kriteria j
 * 
 * 3. URUTKAN hasil V[i] dari terbesar ke terkecil
 */

class SAW
{
    private $nilaiMatriks;
    private $kriteria;
    private $alternatif;
    
    /**
     * Constructor - menerima object yang diperlukan
     */
    public function __construct($nilaiMatriks, $kriteria, $alternatif)
    {
        $this->nilaiMatriks = $nilaiMatriks;
        $this->kriteria = $kriteria;
        $this->alternatif = $alternatif;
    }
    
    /**
     * Fungsi getMatriksBruto() - Ambil matriks keputusan asli
     * 
     * Hasil:
     * [
     *   ['id_alternatif' => 1, 'id_kriteria' => 1, 'nilai' => 100, ...],
     *   ...
     * ]
     * 
     * @return array - Matriks asli
     */
    public function getMatriksBruto()
    {
        return $this->nilaiMatriks->getMatriksForCalculation();
    }
    
    /**
     * Fungsi normalisasi() - TAHAP 1: Normalisasi matriks
     * 
     * Mengubah nilai matriks menjadi skala 0-1 dengan mempertimbangkan
     * jenis kriteria (benefit atau cost)
     * 
     * Formula:
     * - Benefit: R[i][j] = X[i][j] / max(X[j])
     * - Cost: R[i][j] = min(X[j]) / X[i][j]
     * 
     * @return array - Matriks yang sudah dinormalisasi
     */
    public function normalisasi()
    {
        // Ambil matriks asli
        $matriks = $this->getMatriksBruto();
        
        if (empty($matriks)) {
            return [];
        }
        
        // Ambil semua kriteria
        $kriteria = $this->kriteria->getAll();
        
        // Siapkan array untuk menyimpan nilai max/min per kriteria
        $maxMinValues = [];
        
        // Hitung max/min untuk setiap kriteria
        foreach ($kriteria as $k) {
            $id_krit = $k['id_kriteria'];
            $sifat = $k['sifat'];
            
            if ($sifat === 'benefit') {
                // Untuk benefit, cari nilai terbesar
                $maxMinValues[$id_krit] = $this->nilaiMatriks->getMaxValue($id_krit);
            } else {
                // Untuk cost, cari nilai terkecil
                $maxMinValues[$id_krit] = $this->nilaiMatriks->getMinValue($id_krit);
            }
        }
        
        // Normalisasi setiap elemen matriks
        $matriksNormalisasi = [];
        
        foreach ($matriks as $data) {
            $id_alt = $data['id_alternatif'];
            $id_krit = $data['id_kriteria'];
            $nilai = $data['nilai'];
            $sifat = $data['sifat'];
            
            // Hitung nilai normalisasi
            if ($sifat === 'benefit') {
                // Benefit: bagi dengan nilai max
                $r = $maxMinValues[$id_krit] > 0 
                    ? $nilai / $maxMinValues[$id_krit] 
                    : 0;
            } else {
                // Cost: bagi min dengan nilai
                $r = $nilai > 0 
                    ? $maxMinValues[$id_krit] / $nilai 
                    : 0;
            }
            
            $matriksNormalisasi[] = [
                'id_alternatif' => $id_alt,
                'id_kriteria' => $id_krit,
                'nama_alternatif' => $data['nama_alternatif'],
                'nama_kriteria' => $data['nama_kriteria'],
                'nilai_asli' => $nilai,
                'sifat' => $sifat,
                'nilai_normalisasi' => round($r, 4), // Bulatkan 4 desimal
                'bobot' => $data['bobot'],
            ];
        }
        
        return $matriksNormalisasi;
    }
    
    /**
     * Fungsi hitung() - TAHAP 2 & 3: Hitung perankingan
     * 
     * Menghitung skor V[i] untuk setiap alternatif:
     * V[i] = Σ(w[j] × R[i][j])
     * 
     * Kemudian mengurutkan dari nilai terbesar ke terkecil
     * 
     * @return array - Hasil ranking dengan urutan terbaik di atas
     */
    public function hitung()
    {
        // Ambil matriks normalisasi
        $matriksNormalisasi = $this->normalisasi();
        
        if (empty($matriksNormalisasi)) {
            return [];
        }
        
        // Siapkan array untuk menyimpan hasil ranking
        $hasilRanking = [];
        
        // Kelompokkan data berdasarkan alternatif
        $dataByAlternatif = [];
        foreach ($matriksNormalisasi as $data) {
            $id_alt = $data['id_alternatif'];
            if (!isset($dataByAlternatif[$id_alt])) {
                $dataByAlternatif[$id_alt] = [
                    'id_alternatif' => $id_alt,
                    'nama_alternatif' => $data['nama_alternatif'],
                    'data' => []
                ];
            }
            $dataByAlternatif[$id_alt]['data'][] = $data;
        }
        
        // Hitung skor V[i] untuk setiap alternatif
        foreach ($dataByAlternatif as $id_alt => $alternatifData) {
            $nilaiV = 0;
            
            // Hitung: V[i] = Σ(w[j] × R[i][j])
            // Looping setiap kriteria untuk alternatif ini
            foreach ($alternatifData['data'] as $item) {
                $bobot = $item['bobot'];
                $nilaiNormalisasi = $item['nilai_normalisasi'];
                
                // Tambahkan ke skor: bobot × nilai_normalisasi
                $nilaiV += $bobot * $nilaiNormalisasi;
            }
            
            $hasilRanking[] = [
                'id_alternatif' => $id_alt,
                'nama_alternatif' => $alternatifData['nama_alternatif'],
                'nilai_v' => round($nilaiV, 4), // Bulatkan 4 desimal
                'detail' => $alternatifData['data']
            ];
        }
        
        // URUTKAN dari nilai terbesar ke terkecil (ranking)
        usort($hasilRanking, function($a, $b) {
            return $b['nilai_v'] <=> $a['nilai_v'];
        });
        
        // Tambahkan urutan ranking
        foreach ($hasilRanking as $key => &$item) {
            $item['ranking'] = $key + 1; // Ranking mulai dari 1
        }
        
        return $hasilRanking;
    }
    
    /**
     * Fungsi getDetailMatriksRapih() - Format matriks untuk ditampilkan
     * dalam bentuk tabel HTML
     * 
     * Mengubah struktur data menjadi format yang mudah untuk ditampilkan
     * dalam HTML table
     * 
     * @return array - Data terstruktur untuk tabel
     */
    public function getDetailMatriksRapih()
    {
        $matriks = $this->getMatriksBruto();
        $result = [];
        
        foreach ($matriks as $data) {
            $id_alt = $data['id_alternatif'];
            $nama_alt = $data['nama_alternatif'];
            
            if (!isset($result[$id_alt])) {
                $result[$id_alt] = [
                    'nama_alternatif' => $nama_alt,
                    'nilai' => []
                ];
            }
            
            $result[$id_alt]['nilai'][$data['id_kriteria']] = $data['nilai'];
        }
        
        return $result;
    }
}
?>
