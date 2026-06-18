<?php
/**
 * ===================================================
 * API: Simpan Nilai Matriks
 * ===================================================
 * File ini menangani request untuk menyimpan nilai matriks
 * ke database melalui AJAX
 * 
 * Data diterima dalam format:
 * {
 *   "data": [
 *     {"id_alternatif": 1, "id_kriteria": 1, "nilai": 100},
 *     ...
 *   ]
 * }
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/NilaiMatriks.php';

header('Content-Type: application/json');

try {
    // Cek metode request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metode request hanya POST');
    }
    
    // Ambil data JSON dari request body
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['data']) || !is_array($input['data'])) {
        throw new Exception('Format data tidak valid');
    }
    
    // Inisialisasi database dan class
    $db = new Database();
    $nilaiMatriks = new NilaiMatriks($db->getPDO());
    
    $berhasil = 0;
    $gagal = 0;
    
    // Simpan setiap nilai matriks
    foreach ($input['data'] as $item) {
        $id_alt = isset($item['id_alternatif']) ? (int)$item['id_alternatif'] : null;
        $id_krit = isset($item['id_kriteria']) ? (int)$item['id_kriteria'] : null;
        $nilai = isset($item['nilai']) ? (float)$item['nilai'] : 0;
        
        if ($id_alt && $id_krit) {
            if ($nilaiMatriks->create($id_alt, $id_krit, $nilai)) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => "Tersimpan: $berhasil, Gagal: $gagal",
        'berhasil' => $berhasil,
        'gagal' => $gagal
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
