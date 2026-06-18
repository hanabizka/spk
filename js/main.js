/**
 * ===================================================
 * JAVASCRIPT UTAMA
 * ===================================================
 * File ini menangani interaktivitas frontend:
 * - Tambah/hapus baris input kriteria dan alternatif
 * - Validasi form
 * - AJAX untuk komunikasi dengan backend
 */

// ===== VARIABEL GLOBAL =====
let idKriteriaCounter = 0; // Counter untuk ID unik form kriteria
let idAlternatifCounter = 0; // Counter untuk ID unik form alternatif

/**
 * Fungsi tambahKriteria() - Tambah form input kriteria baru
 * Dipanggil saat user klik tombol "+ Tambah Kriteria"
 */
function tambahKriteria() {
    idKriteriaCounter++;
    
    const html = `
        <div class="row mb-3 kriteria-row" id="kriteria-${idKriteriaCounter}">
            <div class="col-md-5">
                <input type="text" class="form-control kriteria-nama" placeholder="Nama Kriteria (mis: Harga, Performa)" />
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control kriteria-bobot" placeholder="Bobot (%)" step="0.01" min="0" max="100" />
            </div>
            <div class="col-md-3">
                <select class="form-select kriteria-sifat">
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="hapusKriteria(${idKriteriaCounter})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    // Tambah elemen ke container
    document.getElementById('kriteria-container').insertAdjacentHTML('beforeend', html);
}

/**
 * Fungsi hapusKriteria() - Hapus form input kriteria
 * @param id - ID unik form kriteria
 */
function hapusKriteria(id) {
    const element = document.getElementById(`kriteria-${id}`);
    if (element) {
        element.remove();
    }
}

/**
 * Fungsi tambahAlternatif() - Tambah form input alternatif baru
 * Dipanggil saat user klik tombol "+ Tambah Alternatif"
 */
function tambahAlternatif() {
    idAlternatifCounter++;
    
    const html = `
        <div class="row mb-2 alternatif-row" id="alternatif-${idAlternatifCounter}">
            <div class="col-md-10">
                <input type="text" class="form-control alternatif-nama" placeholder="Nama Alternatif (mis: Laptop A, Laptop B)" />
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="hapusAlternatif(${idAlternatifCounter})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('alternatif-container').insertAdjacentHTML('beforeend', html);
}

/**
 * Fungsi hapusAlternatif() - Hapus form input alternatif
 * @param id - ID unik form alternatif
 */
function hapusAlternatif(id) {
    const element = document.getElementById(`alternatif-${id}`);
    if (element) {
        element.remove();
    }
}

/**
 * Fungsi simpanData() - Kumpulkan dan simpan semua data
 * 
 * Proses:
 * 1. Ambil data kriteria dari form
 * 2. Validasi bobot (total harus = 100)
 * 3. Ambil data alternatif dari form
 * 4. Generate form nilai matriks dinamis
 */
function simpanData() {
    // Validasi: minimal ada 1 kriteria dan 1 alternatif
    const kriteriaBaris = document.querySelectorAll('.kriteria-row');
    const alternatifBaris = document.querySelectorAll('.alternatif-row');
    
    if (kriteriaBaris.length === 0 || alternatifBaris.length === 0) {
        alert('⚠️ Minimal ada 1 kriteria dan 1 alternatif!');
        return;
    }
    
    // Ambil data kriteria
    const dataKriteria = [];
    kriteriaBaris.forEach(baris => {
        const nama = baris.querySelector('.kriteria-nama').value.trim();
        const bobot = parseFloat(baris.querySelector('.kriteria-bobot').value) || 0;
        const sifat = baris.querySelector('.kriteria-sifat').value;
        
        if (nama && bobot > 0) {
            dataKriteria.push({ nama, bobot, sifat });
        }
    });
    
    // Ambil data alternatif
    const dataAlternatif = [];
    alternatifBaris.forEach(baris => {
        const nama = baris.querySelector('.alternatif-nama').value.trim();
        if (nama) {
            dataAlternatif.push({ nama });
        }
    });
    
    // Validasi: ada kriteria dan alternatif yg diisi
    if (dataKriteria.length === 0) {
        alert('⚠️ Isikan minimal 1 kriteria dengan bobot > 0!');
        return;
    }
    if (dataAlternatif.length === 0) {
        alert('⚠️ Isikan minimal 1 alternatif!');
        return;
    }
    
    // Validasi bobot: total harus = 100
    const totalBobot = dataKriteria.reduce((sum, k) => sum + k.bobot, 0);
    if (Math.abs(totalBobot - 100) > 0.1) {
        alert('⚠️ Total bobot harus = 100%\nTotal bobot Anda: ' + totalBobot.toFixed(2) + '%');
        return;
    }
    
    // Konversi bobot dari 0-100 ke 0-1 untuk perhitungan
    dataKriteria.forEach(k => {
        k.bobot = k.bobot / 100;
    });
    
    // Generate form nilai matriks
    generateFormNilaiMatriks(dataKriteria, dataAlternatif);
    
    // Scroll ke bagian form nilai matriks
    document.getElementById('section-nilai-matriks').scrollIntoView({ behavior: 'smooth' });
}

/**
 * Fungsi generateFormNilaiMatriks() - Buat form input nilai matriks
 * 
 * Form ini memungkinkan user memasukkan nilai untuk setiap kombinasi
 * alternatif dan kriteria
 * 
 * @param kriteria - Array data kriteria
 * @param alternatif - Array data alternatif
 */
function generateFormNilaiMatriks(kriteria, alternatif) {
    const container = document.getElementById('form-nilai-container');
    container.innerHTML = ''; // Kosongkan container
    
    // Buat tabel untuk input nilai
    let html = `
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Alternatif</th>
    `;
    
    // Header kolom kriteria
    kriteria.forEach(k => {
        const bobotPersen = (k.bobot * 100).toFixed(0);
        html += `<th>${k.nama}<br/><small class="text-muted">(${k.sifat}, ${bobotPersen}%)</small></th>`;
    });
    html += '</tr></thead><tbody>';
    
    // Baris untuk setiap alternatif
    alternatif.forEach((alt, altIdx) => {
        html += `<tr><td><strong>${alt.nama}</strong></td>`;
        
        kriteria.forEach((krit, kritIdx) => {
            const inputId = `nilai_${altIdx}_${kritIdx}`;
            html += `
                <td>
                    <input type="number" class="form-control form-control-sm nilai-input" 
                           id="${inputId}" placeholder="0" step="0.01" min="0" />
                </td>
            `;
        });
        
        html += '</tr>';
    });
    
    html += '</tbody></table></div>';
    
    container.innerHTML = html;
    
    // Simpan data kriteria dan alternatif ke hidden fields (untuk diambil saat hitung)
    document.getElementById('data-kriteria-hidden').value = JSON.stringify(kriteria);
    document.getElementById('data-alternatif-hidden').value = JSON.stringify(alternatif);
}

/**
 * Fungsi hitungSAW() - Hitung hasil SAW
 * 
 * Proses SAW:
 * 1. Normalisasi: Ubah nilai ke skala 0-1
 *    - Benefit: R = Nilai / Max
 *    - Cost: R = Min / Nilai
 * 2. Hitung skor V = Σ(Bobot × Normalisasi)
 * 3. Urutkan dari terbesar ke terkecil
 */
function hitungSAW() {
    const kriteria = JSON.parse(document.getElementById('data-kriteria-hidden').value);
    const alternatif = JSON.parse(document.getElementById('data-alternatif-hidden').value);
    
    // Kumpulkan semua nilai dari form
    const dataNilai = [];
    let allFilled = true;
    
    alternatif.forEach((alt, altIdx) => {
        kriteria.forEach((krit, kritIdx) => {
            const inputId = `nilai_${altIdx}_${kritIdx}`;
            const nilaiInput = document.getElementById(inputId);
            const nilai = parseFloat(nilaiInput.value) || 0;
            
            if (nilai === 0) {
                allFilled = false;
            }
            
            dataNilai.push({
                alternatif_index: altIdx,
                kriteria_index: kritIdx,
                nilai: nilai,
                nama_alternatif: alt.nama,
                nama_kriteria: krit.nama,
                bobot: krit.bobot,
                sifat: krit.sifat
            });
        });
    });
    
    if (!allFilled) {
        alert('⚠️ Harap isi semua nilai terlebih dahulu!');
        return;
    }
    
    // Tampilkan loading spinner
    document.getElementById('hasil-container').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
    
    // Hitung SAW
    const hasilRanking = hitungSAWFrontend(dataNilai, kriteria, alternatif);
    
    // Tampilkan hasil
    tampilkanHasil(dataNilai, kriteria, alternatif, hasilRanking);
}

/**
 * Fungsi hitungSAWFrontend() - Hitung SAW langsung di frontend
 * 
 * RUMUS SAW:
 * 1. Normalisasi: R[i][j] = X[i][j] / max(X[j]) untuk benefit
 *                 R[i][j] = min(X[j]) / X[i][j] untuk cost
 * 2. Ranking: V[i] = Σ(w[j] × R[i][j])
 * 3. Urutkan dari terbesar ke terkecil
 * 
 * @param dataNilai - Array nilai matriks
 * @param kriteria - Array kriteria (bobot sudah dalam 0-1)
 * @param alternatif - Array alternatif
 * @return Array - Hasil ranking
 */
function hitungSAWFrontend(dataNilai, kriteria, alternatif) {
    // LANGKAH 1: Organisir data dalam bentuk matriks 2D
    const matriks = {};
    alternatif.forEach((alt, altIdx) => {
        matriks[altIdx] = {};
        kriteria.forEach((krit, kritIdx) => {
            const nilai = dataNilai.find(d => d.alternatif_index === altIdx && d.kriteria_index === kritIdx);
            matriks[altIdx][kritIdx] = nilai ? nilai.nilai : 0;
        });
    });
    
    // LANGKAH 2: Cari nilai max dan min untuk setiap kriteria
    const maxMinKriteria = {};
    kriteria.forEach((krit, kritIdx) => {
        const nilaiKriteria = alternatif.map((alt, altIdx) => matriks[altIdx][kritIdx]);
        const nilaiYgAda = nilaiKriteria.filter(n => n > 0); // Filter hanya nilai > 0
        
        maxMinKriteria[kritIdx] = {
            max: nilaiYgAda.length > 0 ? Math.max(...nilaiYgAda) : 0,
            min: nilaiYgAda.length > 0 ? Math.min(...nilaiYgAda) : 0,
            sifat: krit.sifat
        };
    });
    
    // LANGKAH 3: NORMALISASI - Ubah semua nilai ke skala 0-1
    const matriksNormalisasi = {};
    alternatif.forEach((alt, altIdx) => {
        matriksNormalisasi[altIdx] = {};
        kriteria.forEach((krit, kritIdx) => {
            const nilai = matriks[altIdx][kritIdx];
            const maxVal = maxMinKriteria[kritIdx].max;
            const minVal = maxMinKriteria[kritIdx].min;
            const sifat = maxMinKriteria[kritIdx].sifat;
            
            let rNormalisasi = 0;
            
            if (sifat === 'benefit') {
                // BENEFIT: semakin besar semakin baik
                // Rumus: R = Nilai / Max
                rNormalisasi = maxVal > 0 ? nilai / maxVal : 0;
            } else {
                // COST: semakin kecil semakin baik
                // Rumus: R = Min / Nilai
                rNormalisasi = nilai > 0 ? minVal / nilai : 0;
            }
            
            matriksNormalisasi[altIdx][kritIdx] = rNormalisasi;
        });
    });
    
    // LANGKAH 4: HITUNG SKOR V[i] = Σ(w[j] × R[i][j])
    // Untuk setiap alternatif, hitung total bobot × normalisasi
    const hasilRanking = alternatif.map((alt, altIdx) => {
        let nilaiV = 0;
        
        // Jumlahkan: (bobot kriteria × nilai normalisasi) untuk semua kriteria
        kriteria.forEach((krit, kritIdx) => {
            const bobot = krit.bobot; // Sudah dalam bentuk 0-1
            const normalisasi = matriksNormalisasi[altIdx][kritIdx];
            nilaiV += bobot * normalisasi;
        });
        
        return {
            ranking: 0, // Akan diisi nanti
            nama_alternatif: alt.nama,
            nilaiV: nilaiV
        };
    });
    
    // LANGKAH 5: URUTKAN dari skor terbesar ke terkecil
    hasilRanking.sort((a, b) => b.nilaiV - a.nilaiV);
    
    // Tambahkan urutan ranking
    hasilRanking.forEach((item, idx) => {
        item.ranking = idx + 1;
    });
    
    return hasilRanking;
}

/**
 * Fungsi tampilkanHasil() - Tampilkan hasil SAW dalam 3 tabel
 * 
 * Tabel 1: Matriks Keputusan Awal (nilai asli)
 * Tabel 2: Matriks Normalisasi (nilai 0-1)
 * Tabel 3: Ranking Akhir (skor V urut dari terbaik)
 */
function tampilkanHasil(dataNilai, kriteria, alternatif, hasilRanking) {
    let html = '';
    
    // ===== TABEL 1: MATRIKS KEPUTUSAN AWAL =====
    html += `
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Tabel 1: Matriks Keputusan Awal (X)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Tabel ini menunjukkan nilai asli untuk setiap alternatif dan kriteria sebelum dinormalisasi
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
    `;
    
    kriteria.forEach(k => {
        html += `<th>${k.nama}</th>`;
    });
    html += '</tr></thead><tbody>';
    
    alternatif.forEach((alt, altIdx) => {
        html += `<tr><td><strong>${alt.nama}</strong></td>`;
        
        kriteria.forEach((krit, kritIdx) => {
            const nilai = dataNilai.find(d => d.alternatif_index === altIdx && d.kriteria_index === kritIdx);
            html += `<td class="text-center">${nilai ? nilai.nilai.toFixed(2) : '0'}</td>`;
        });
        
        html += '</tr>';
    });
    
    html += `</tbody></table>
            </div>
        </div>
    `;
    
    // ===== TABEL 2: MATRIKS NORMALISASI =====
    html += `
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calculator"></i> Tabel 2: Matriks Normalisasi (R)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    <strong>Rumus Normalisasi:</strong><br/>
                    <strong class="text-success">• Benefit (↑):</strong> R[i][j] = Nilai / Max<br/>
                    <strong class="text-danger">• Cost (↓):</strong> R[i][j] = Min / Nilai
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
    `;
    
    kriteria.forEach(k => {
        const icon = k.sifat === 'benefit' ? '↑' : '↓';
        html += `<th>${k.nama} <small>${icon}</small></th>`;
    });
    html += '</tr></thead><tbody>';
    
    // Hitung normalisasi dan tampilkan
    const matriks = {};
    alternatif.forEach((alt, altIdx) => {
        matriks[altIdx] = {};
        kriteria.forEach((krit, kritIdx) => {
            const nilai = dataNilai.find(d => d.alternatif_index === altIdx && d.kriteria_index === kritIdx);
            matriks[altIdx][kritIdx] = nilai ? nilai.nilai : 0;
        });
    });
    
    const maxMinKriteria = {};
    kriteria.forEach((krit, kritIdx) => {
        const nilaiKriteria = alternatif.map((alt, altIdx) => matriks[altIdx][kritIdx]);
        const nilaiYgAda = nilaiKriteria.filter(n => n > 0);
        maxMinKriteria[kritIdx] = {
            max: nilaiYgAda.length > 0 ? Math.max(...nilaiYgAda) : 0,
            min: nilaiYgAda.length > 0 ? Math.min(...nilaiYgAda) : 0,
            sifat: krit.sifat
        };
    });
    
    alternatif.forEach((alt, altIdx) => {
        html += `<tr><td><strong>${alt.nama}</strong></td>`;
        
        kriteria.forEach((krit, kritIdx) => {
            const nilai = matriks[altIdx][kritIdx];
            const maxVal = maxMinKriteria[kritIdx].max;
            const minVal = maxMinKriteria[kritIdx].min;
            const sifat = maxMinKriteria[kritIdx].sifat;
            
            let rNormalisasi = 0;
            if (sifat === 'benefit') {
                rNormalisasi = maxVal > 0 ? nilai / maxVal : 0;
            } else {
                rNormalisasi = nilai > 0 ? minVal / nilai : 0;
            }
            
            html += `<td class="text-center">${rNormalisasi.toFixed(4)}</td>`;
        });
        
        html += '</tr>';
    });
    
    html += `</tbody></table>
            </div>
        </div>
    `;
    
    // ===== TABEL 3: RANKING AKHIR =====
    html += `
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Tabel 3: Ranking Akhir (V)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    <strong>Rumus Perankingan:</strong> V[i] = Σ(w[j] × R[i][j])<br/>
                    Urutan dari skor tertinggi ke terendah (TERBAIK DI ATAS)
                </p>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Alternatif</th>
                                <th class="text-center">Skor Akhir (V)</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    hasilRanking.forEach((item, idx) => {
        let badge = '';
        if (item.ranking === 1) {
            badge = '<span class="badge bg-success">🏆 TERBAIK</span>';
        } else if (item.ranking === hasilRanking.length) {
            badge = '<span class="badge bg-danger">TERENDAH</span>';
        } else {
            badge = '<span class="badge bg-info">Ranking</span>';
        }
        
        html += `
            <tr>
                <td class="text-center"><strong style="font-size: 18px;">${item.ranking}</strong></td>
                <td><strong>${item.nama_alternatif}</strong></td>
                <td class="text-center"><strong style="font-size: 16px; color: #0d6efd;">${item.nilaiV.toFixed(4)}</strong></td>
                <td class="text-center">${badge}</td>
            </tr>
        `;
    });
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    // Tampilkan hasil
    document.getElementById('hasil-container').innerHTML = html;
    document.getElementById('hasil-container').scrollIntoView({ behavior: 'smooth' });
}

/**
 * Fungsi resetForm() - Reset semua form ke awal
 */
function resetForm() {
    document.getElementById('kriteria-container').innerHTML = '';
    document.getElementById('alternatif-container').innerHTML = '';
    document.getElementById('form-nilai-container').innerHTML = '';
    document.getElementById('hasil-container').innerHTML = '';
    document.getElementById('data-kriteria-hidden').value = '[]';
    document.getElementById('data-alternatif-hidden').value = '[]';
    
    idKriteriaCounter = 0;
    idAlternatifCounter = 0;
    
    // Scroll ke atas
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
