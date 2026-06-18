<?php
/**
 * ===================================================
 * KONFIGURASI DATABASE
 * ===================================================
 * File ini menangani koneksi ke database MySQL
 * menggunakan PDO (PHP Data Objects)
 * 
 * PDO adalah cara modern dan aman untuk berinteraksi
 * dengan database (mencegah SQL injection)
 */

class Database
{
    // ===== KONFIGURASI DATABASE =====
    private $host = 'localhost';      // Alamat server MySQL
    private $db_name = 'spk_saw';     // Nama database
    private $user = 'root';            // Username MySQL
    private $password = '';            // Password MySQL
    
    // Untuk penggunaan production, ganti nilai di atas
    // atau gunakan environment variables
    
    private $pdo;
    
    /**
     * Fungsi __construct() dijalankan otomatis ketika
     * object Database dibuat
     */
    public function __construct()
    {
        // Buat koneksi ke database
        $this->connect();
    }
    
    /**
     * Fungsi connect() membuat koneksi PDO ke MySQL
     * 
     * @return void
     */
    private function connect()
    {
        // DSN (Data Source Name) - format koneksi database
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
        
        try {
            // PDO options untuk error handling
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Tampilkan error sebagai exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return data sebagai array asosiatif
            ];
            
            // Buat koneksi PDO
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
            
        } catch (PDOException $e) {
            // Jika koneksi gagal, tampilkan pesan error
            die("❌ Koneksi Database Gagal: " . $e->getMessage());
        }
    }
    
    /**
     * Fungsi getPDO() mengembalikan object PDO
     * untuk digunakan dalam query
     * 
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }
    
    /**
     * Fungsi query() untuk menjalankan query SELECT
     * 
     * @param string $sql - Query SQL
     * @param array $params - Parameter binding (opsional)
     * @return array - Hasil query dalam bentuk array
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("❌ Query Error: " . $e->getMessage());
        }
    }
    
    /**
     * Fungsi execute() untuk query INSERT, UPDATE, DELETE
     * 
     * @param string $sql - Query SQL
     * @param array $params - Parameter binding
     * @return bool - True jika berhasil, False jika gagal
     */
    public function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("❌ Execute Error: " . $e->getMessage());
        }
    }
    
    /**
     * Fungsi lastInsertId() untuk mendapatkan ID terakhir
     * yang di-insert (auto increment)
     * 
     * @return int - ID terakhir
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
?>
