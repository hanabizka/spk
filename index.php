<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator SAW - Simple Additive Weighting</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container-main {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            background: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .header h1 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #6c757d;
            font-size: 16px;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px 12px 0 0;
            border: none;
            padding: 20px;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Form Styling */
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 12px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        /* Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        /* Input Group */
        .input-group {
            margin-bottom: 15px;
        }
        
        /* Badge */
        .badge {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }
        
        /* Table */
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: #f8f9fa;
        }
        
        .table-hover tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        /* Spinner */
        .spinner-border {
            color: #667eea;
        }
        
        /* Loading Container */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }
        
        /* Section Title */
        .section-title {
            color: white;
            font-weight: 700;
            margin: 30px 0 20px 0;
            font-size: 24px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        /* Helper Text */
        .form-text {
            font-size: 12px;
            color: #6c757d;
        }
        
        /* Result Container */
        .result-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
    <div class="container-main">
        <!-- HEADER -->
        <div class="header">
            <h1><i class="bi bi-calculator-lg"></i> Kalkulator SAW</h1>
            <p>Simple Additive Weighting - Sistem Pendukung Keputusan</p>
        </div>
        
        <!-- SECTION 1: INPUT KRITERIA & ALTERNATIF -->
        <h2 class="section-title"><i class="bi bi-1-circle"></i> Langkah 1: Input Kriteria & Alternatif</h2>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-list-check"></i> Data Kriteria</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    <strong>Kriteria:</strong> Faktor penilaian (misal: Harga, Performa, Daya Tahan)<br>
                    <strong>Bobot:</strong> Tingkat kepentingan (0-1, total harus = 1.0)<br>
                    <strong>Sifat:</strong> Benefit (semakin besar semakin baik) atau Cost (semakin kecil semakin baik)
                </p>
                
                <div id="kriteria-container"></div>
                
                <button type="button" class="btn btn-success btn-sm" onclick="tambahKriteria()">
                    <i class="bi bi-plus-circle"></i> Tambah Kriteria
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-collection"></i> Data Alternatif</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    <strong>Alternatif:</strong> Pilihan yang akan dinilai (misal: Laptop A, Laptop B, Laptop C)
                </p>
                
                <div id="alternatif-container"></div>
                
                <button type="button" class="btn btn-success btn-sm" onclick="tambahAlternatif()">
                    <i class="bi bi-plus-circle"></i> Tambah Alternatif
                </button>
            </div>
        </div>
        
        <!-- Hidden fields untuk menyimpan data -->
        <input type="hidden" id="data-kriteria-hidden" value="[]">
        <input type="hidden" id="data-alternatif-hidden" value="[]">
        
        <div class="text-center mb-4">
            <button type="button" class="btn btn-primary btn-lg" onclick="simpanData()">
                <i class="bi bi-arrow-right-circle"></i> Lanjut ke Nilai Matriks
            </button>
            <button type="button" class="btn btn-secondary btn-lg" onclick="resetForm()">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
        </div>
        
        <!-- SECTION 2: INPUT NILAI MATRIKS -->
        <h2 class="section-title" id="section-nilai-matriks"><i class="bi bi-2-circle"></i> Langkah 2: Input Nilai Matriks</h2>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-grid-3x3-gap"></i> Matriks Keputusan</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Isikan nilai untuk setiap kombinasi alternatif dan kriteria
                </p>
                <div id="form-nilai-container"></div>
            </div>
        </div>
        
        <div class="text-center mb-4">
            <button type="button" class="btn btn-warning btn-lg" onclick="hitungSAW()">
                <i class="bi bi-calculator"></i> Hitung Hasil SAW
            </button>
        </div>
        
        <!-- SECTION 3: HASIL SAW -->
        <h2 class="section-title"><i class="bi bi-3-circle"></i> Langkah 3: Hasil Perhitungan</h2>
        
        <div id="hasil-container"></div>
        
        <!-- FOOTER -->
        <div style="text-align: center; color: white; padding: 30px 0; border-top: 1px solid rgba(255,255,255,0.2); margin-top: 50px;">
            <p>
                <i class="bi bi-heart-fill" style="color: #ff6b6b;"></i>
                Dibuat untuk pembelajaran Sistem Pendukung Keputusan (SPK)<br>
                <small>© 2024 - Simple Additive Weighting Calculator</small>
            </p>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>
