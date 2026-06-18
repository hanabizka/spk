<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAW Calculator - Sistem Pendukung Keputusan</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f8f9fa;
            color: #2d3748;
            padding: 20px 0;
        }
        
        .container-main {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* ===== HEADER ===== */
        .header {
            background: white;
            padding: 40px 0;
            margin-bottom: 40px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .header p {
            font-size: 14px;
            color: #718096;
            margin: 0;
            font-weight: 400;
        }
        
        .header-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 12px;
            display: inline-block;
        }
        
        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            margin: 32px 0 16px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i {
            color: #667eea;
            font-size: 20px;
        }
        
        /* ===== CARD ===== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 20px;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 16px 20px;
        }
        
        .card-header h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* ===== FORM ===== */
        .form-control,
        .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 400;
            transition: all 0.2s ease;
            background-color: #fff;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 6px;
        }
        
        /* ===== BUTTON ===== */
        .btn {
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            padding: 10px 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
            border-color: transparent;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            color: white;
            border-color: transparent;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            color: white;
            border-color: transparent;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            color: white;
            border-color: transparent;
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
            color: #374151;
        }
        
        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }
        
        .btn-lg {
            padding: 12px 24px;
            font-size: 14px;
        }
        
        /* ===== INPUT ROW ===== */
        .input-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            align-items: flex-end;
        }
        
        .input-row > div {
            flex: 1;
        }
        
        .input-row .btn {
            margin-bottom: 0;
            height: 38px;
            padding: 0;
            min-width: 38px;
        }
        
        /* ===== TABLE ===== */
        .table {
            margin-bottom: 0;
            font-size: 13px;
        }
        
        .table thead {
            background: #f3f4f6;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .table thead th {
            font-weight: 600;
            color: #374151;
            padding: 12px;
            border: none;
        }
        
        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-color: #f3f4f6;
        }
        
        .table-hover tbody tr:hover {
            background: #f9fafb;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* ===== BADGE ===== */
        .badge {
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }
        
        .badge.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }
        
        .badge.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        }
        
        .badge.bg-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        /* ===== ALERT ===== */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 13px;
        }
        
        .alert-info {
            background: #eff6ff;
            color: #0c4a6e;
        }
        
        /* ===== TEXT HELPER ===== */
        .text-helper {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
            font-weight: 400;
        }
        
        .text-primary-light {
            color: #667eea;
            font-weight: 500;
        }
        
        /* ===== LOADING ===== */
        .spinner-border {
            color: #667eea;
            width: 40px;
            height: 40px;
        }
        
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }
        
        /* ===== DIVIDER ===== */
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 16px 0;
        }
        
        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding: 30px 0;
            color: #9ca3af;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
            margin-top: 40px;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 24px;
            }
            
            .section-title {
                font-size: 16px;
            }
            
            .btn-lg {
                width: 100%;
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container-main">
        <!-- HEADER -->
        <div class="header">
            <div class="header-icon">
                <i class="bi bi-calculator-lg"></i>
            </div>
            <h1>SAW Calculator</h1>
            <p>Simple Additive Weighting - Sistem Pendukung Keputusan</p>
        </div>
        
        <!-- SECTION 1: INPUT DATA -->
        <h2 class="section-title">
            <i class="bi bi-pencil-square"></i> Input Kriteria & Alternatif
        </h2>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-list-check"></i> Kriteria Penilaian</h5>
            </div>
            <div class="card-body">
                <p class="text-helper mb-3">
                    Tambahkan kriteria yang akan digunakan untuk penilaian. Bobot harus berjumlah 100%.
                </p>
                
                <div id="kriteria-container"></div>
                
                <button type="button" class="btn btn-success btn-sm" onclick="tambahKriteria()">
                    <i class="bi bi-plus-circle"></i> Tambah Kriteria
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-collection"></i> Alternatif Pilihan</h5>
            </div>
            <div class="card-body">
                <p class="text-helper mb-3">
                    Tambahkan pilihan/alternatif yang akan dinilai berdasarkan kriteria di atas.
                </p>
                
                <div id="alternatif-container"></div>
                
                <button type="button" class="btn btn-success btn-sm" onclick="tambahAlternatif()">
                    <i class="bi bi-plus-circle"></i> Tambah Alternatif
                </button>
            </div>
        </div>
        
        <!-- Hidden fields -->
        <input type="hidden" id="data-kriteria-hidden" value="[]">
        <input type="hidden" id="data-alternatif-hidden" value="[]">
        
        <!-- ACTION BUTTONS -->
        <div style="display: flex; gap: 12px; margin-bottom: 40px;">
            <button type="button" class="btn btn-primary btn-lg flex-grow-1" onclick="simpanData()">
                <i class="bi bi-arrow-right"></i> Lanjutkan ke Nilai Matriks
            </button>
            <button type="button" class="btn btn-secondary btn-lg" onclick="resetForm()">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </div>
        
        <!-- SECTION 2: INPUT NILAI MATRIKS -->
        <h2 class="section-title" id="section-nilai-matriks">
            <i class="bi bi-grid-3x3-gap"></i> Input Nilai Matriks
        </h2>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-table"></i> Matriks Penilaian</h5>
            </div>
            <div class="card-body">
                <p class="text-helper mb-3">
                    Isikan nilai untuk setiap kombinasi alternatif dan kriteria.
                </p>
                <div id="form-nilai-container"></div>
            </div>
        </div>
        
        <!-- HITUNG BUTTON -->
        <div style="text-align: center; margin-bottom: 40px;">
            <button type="button" class="btn btn-warning btn-lg" onclick="hitungSAW()">
                <i class="bi bi-calculator"></i> Hitung Hasil SAW
            </button>
        </div>
        
        <!-- SECTION 3: HASIL -->
        <h2 class="section-title">
            <i class="bi bi-bar-chart-line"></i> Hasil Perhitungan
        </h2>
        
        <div id="hasil-container"></div>
        
        <!-- FOOTER -->
        <div class="footer">
            <p>
                <i class="bi bi-heart-fill" style="color: #ef4444;"></i>
                Kalkulator SAW untuk Sistem Pendukung Keputusan<br>
                <small>© 2024 - Dibuat untuk pembelajaran</small>
            </p>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>
