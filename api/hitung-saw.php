<?php
/**
 * ===================================================
 * API: Hitung SAW
 * ===================================================
 * File ini menangani request untuk menghitung SAW
 * melalui AJAX dan mengembalikan hasil dalam JSON
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Kriteria.php';
require_once __DIR__ . '/../classes/Alternatif.php';
require_once __DIR__ . '/../classes/NilaiMatriks.php';
require_once __DIR__ . '/../classes/SAW.php';

header('Content-Type: application/json');

try {
    // Inisialisasi database dan class
    $db = new Database();
    $pdo = $db->getPDO();
    
    $kriteria = new Kriteria($pdo);
    $alternatif = new Alternatif($pdo);
    $nilaiMatriks = new NilaiMatriks($pdo);
    $saw = new SAW($nilaiMatriks, $kriteria, $alternatif);
    
    // Ambil semua data yang diperlukan
    $matriksBruto = $saw->getMatriksBruto();
    $matriksNormalisasi = $saw->normalisasi();
    $hasilRanking = $saw->hitung();
    
    // Validasi data
    if (empty($matriksBruto)) {
        throw new Exception('Data matriks kosong. Silakan isi kriteria dan alternatif terlebih dahulu.');
    }
    
    if (empty($hasilRanking)) {
        throw new Exception('Gagal menghitung hasil SAW.');
    }
    
    // Kembalikan hasil dalam format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Perhitungan SAW berhasil',
        'data' => [
            'matriks_bruto' => $matriksBruto,
            'matriks_normalisasi' => $matriksNormalisasi,
            'hasil_ranking' => $hasilRanking
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
