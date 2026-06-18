<?php
/**
 * ===================================================
 * API: Hapus Alternatif
 * ===================================================
 * File ini menangani request untuk menghapus alternatif
 * dari database melalui AJAX
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Alternatif.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metode request hanya POST');
    }
    
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    
    if (!$id) {
        throw new Exception('ID alternatif tidak ditemukan');
    }
    
    $db = new Database();
    $alternatif = new Alternatif($db->getPDO());
    
    if ($alternatif->delete($id)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Alternatif berhasil dihapus'
        ]);
    } else {
        throw new Exception('Gagal menghapus alternatif');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
