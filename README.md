# Kalkulator Simple Additive Weighting (SAW)

Sebuah website berbasis kalkulator SAW yang dinamis dan fleksibel untuk Sistem Pendukung Keputusan.

## 📋 Daftar Isi
- [Teknologi](#teknologi)
- [Struktur Database](#struktur-database)
- [Cara Penggunaan](#cara-penggunaan)
- [Fitur Utama](#fitur-utama)

## 🛠 Teknologi

- **Backend:** PHP Native (terstruktur, mudah dipahami)
- **Database:** MySQL (PDO/MySQLi)
- **Frontend:** HTML5 + Bootstrap 5
- **Interaktivitas:** JavaScript vanilla (dynamic form)

## 📊 Struktur Database

### Tabel `kriteria`
- `id_kriteria` (INT, PRIMARY KEY, AUTO INCREMENT)
- `nama_kriteria` (VARCHAR 255)
- `bobot` (DECIMAL 5,2)
- `sifat` (ENUM: 'benefit', 'cost')

### Tabel `alternatif`
- `id_alternatif` (INT, PRIMARY KEY, AUTO INCREMENT)
- `nama_alternatif` (VARCHAR 255)

### Tabel `nilai_matriks`
- `id_nilai` (INT, PRIMARY KEY, AUTO INCREMENT)
- `id_alternatif` (INT, FOREIGN KEY)
- `id_kriteria` (INT, FOREIGN KEY)
- `nilai` (DECIMAL 10,2)

## 🚀 Cara Penggunaan

1. Buat database MySQL dan jalankan script SQL di file `database/init.sql`
2. Konfigurasi koneksi database di `config/Database.php`
3. Buka `index.php` di browser
4. Masukkan kriteria dan alternatif secara dinamis
5. Klik "Hitung" untuk melihat hasil SAW

## ✨ Fitur Utama

- ✅ Input kriteria & alternatif dinamis (tanpa reload)
- ✅ Hitung SAW otomatis dengan normalisasi
- ✅ Tampil hasil dalam 3 tabel rapi (Matriks, Normalisasi, Ranking)
- ✅ UI modern dengan Bootstrap 5
- ✅ Kode terstruktur dengan komentar pemula

## 📝 Rumus SAW

1. **Normalisasi:** R[i][j] = X[i][j] / max(X[j]) untuk benefit, min(X[j]) / X[i][j] untuk cost
2. **Ranking:** V[i] = Σ(w[j] × R[i][j])

---

Selamat belajar! 🎓
