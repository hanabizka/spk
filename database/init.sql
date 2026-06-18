-- ========================================
-- DATABASE: Sistem Pendukung Keputusan SAW
-- ========================================

-- Buat database
CREATE DATABASE IF NOT EXISTS spk_saw;
USE spk_saw;

-- ========================================
-- TABEL 1: KRITERIA
-- ========================================
CREATE TABLE IF NOT EXISTS kriteria (
  id_kriteria INT AUTO_INCREMENT PRIMARY KEY,
  nama_kriteria VARCHAR(255) NOT NULL,
  bobot DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
  sifat ENUM('benefit', 'cost') NOT NULL DEFAULT 'benefit',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABEL 2: ALTERNATIF
-- ========================================
CREATE TABLE IF NOT EXISTS alternatif (
  id_alternatif INT AUTO_INCREMENT PRIMARY KEY,
  nama_alternatif VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABEL 3: NILAI MATRIKS KEPUTUSAN
-- ========================================
CREATE TABLE IF NOT EXISTS nilai_matriks (
  id_nilai INT AUTO_INCREMENT PRIMARY KEY,
  id_alternatif INT NOT NULL,
  id_kriteria INT NOT NULL,
  nilai DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_alternatif) REFERENCES alternatif(id_alternatif) ON DELETE CASCADE,
  FOREIGN KEY (id_kriteria) REFERENCES kriteria(id_kriteria) ON DELETE CASCADE,
  UNIQUE KEY unique_pair (id_alternatif, id_kriteria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- DATA CONTOH (OPSIONAL)
-- ========================================

-- Contoh Kriteria
INSERT INTO kriteria (nama_kriteria, bobot, sifat) VALUES
('Harga', 0.40, 'cost'),
('Performa', 0.35, 'benefit'),
('Daya Tahan', 0.25, 'benefit');

-- Contoh Alternatif
INSERT INTO alternatif (nama_alternatif) VALUES
('Laptop A'),
('Laptop B'),
('Laptop C');

-- Contoh Nilai Matriks
INSERT INTO nilai_matriks (id_alternatif, id_kriteria, nilai) VALUES
(1, 1, 8000000),  -- Laptop A - Harga
(1, 2, 90),       -- Laptop A - Performa
(1, 3, 85),       -- Laptop A - Daya Tahan
(2, 1, 10000000), -- Laptop B - Harga
(2, 2, 85),       -- Laptop B - Performa
(2, 3, 80),       -- Laptop B - Daya Tahan
(3, 1, 6000000),  -- Laptop C - Harga
(3, 2, 75),       -- Laptop C - Performa
(3, 3, 95);       -- Laptop C - Daya Tahan

-- ========================================
-- VIEWS (OPSIONAL - untuk mempermudah query)
-- ========================================

-- View untuk melihat data lengkap dengan nama
CREATE OR REPLACE VIEW v_nilai_matriks AS
SELECT 
  nm.id_nilai,
  nm.id_alternatif,
  a.nama_alternatif,
  nm.id_kriteria,
  k.nama_kriteria,
  k.bobot,
  k.sifat,
  nm.nilai
FROM nilai_matriks nm
INNER JOIN alternatif a ON nm.id_alternatif = a.id_alternatif
INNER JOIN kriteria k ON nm.id_kriteria = k.id_kriteria
ORDER BY a.id_alternatif, k.id_kriteria;
