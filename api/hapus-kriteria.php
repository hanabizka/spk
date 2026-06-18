<?php
/**
 * ===================================================
 * API: Hapus Kriteria
 * ===================================================
 * File ini menangani request untuk menghapus kriteria
 * dari database melalui AJAX
 */

// Sertakan file konfigurasi dan class
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Kriteria.php';

header('Content-Type: application/json');

try {
    // Cek metode request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metode request hanya POST');
    }
    
    // Ambil ID dari POST
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    
    if (!$id) {
        throw new Exception('ID kriteria tidak ditemukan');
    }
    
    // Inisialisasi database dan class Kriteria
    $db = new Database();
    $kriteria = new Kriteria($db->getPDO());
    
    // Hapus kriteria
    if ($kriteria->delete($id)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Kriteria berhasil dihapus'
        ]);
    } else {
        throw new Exception('Gagal menghapus kriteria');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
