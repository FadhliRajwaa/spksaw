<?php
session_start();

// Security check
if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
    exit;
}

// Include database connection
include "../../../configurasi/koneksi.php";

$module = $_GET['act'];
$aksi = "../../media_admin.php?module=laporan&act=analisa";

/**
 * ============================================
 * MFEP (Multi Factor Evaluation Process)
 * ============================================
 * Formula:
 * 1. Normalisasi Factor (E): E = X / X_max
 * 2. Weight Evaluation (WE): WE = W × E
 * 3. Total WE: ∑WE = WE1 + WE2 + ... + WEn
 * 4. Weight Problem (WP): WP = ∑((1-W) × (1-E))
 * 5. MFEP Score: MFEP = ∑WE - WP
 * 6. Ranking: Sort descending by MFEP
 * ============================================
 */

switch($module){
    case "hitung_mfep":
        // Process MFEP calculation
        if ($_SESSION['leveluser']=='admin') {
            
            // Hapus hasil MFEP sebelumnya
            mysqli_query($koneksi, "TRUNCATE TABLE tbl_hasil_mfep");
            
            // Get kriteria dan bobot
            $kriteria_query = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE nilai > 0 ORDER BY kode_kriteria");
            $bobot = array();
            $kriteria_names = array();
            
            while($k = mysqli_fetch_array($kriteria_query)) {
                $bobot[$k['kode_kriteria']] = $k['nilai'];
                $kriteria_names[$k['kode_kriteria']] = $k['keterangan'];
            }
            
            // Validasi total bobot
            $total_bobot = array_sum($bobot);
            if (abs($total_bobot - 1.0) > 0.01) {
                echo "<script>
                        alert('Error: Total bobot kriteria = " . number_format($total_bobot, 2) . "\\nTotal bobot harus sama dengan 1.0 (100%)!\\n\\nSilakan sesuaikan bobot di menu Data Kriteria.');
                        window.location.href='../../media_admin.php?module=pembobotan';
                      </script>";
                exit;
            }
            
            // Get data warga dengan kriteria
            $warga_query = mysqli_query($koneksi, "
                SELECT 
                    id_warga,
                    nama_lengkap,
                    jumlah_lansia as C1,
                    jumlah_disabilitas_berat as C2,
                    jumlah_anak_sd as C3,
                    jumlah_anak_smp as C4,
                    jumlah_anak_sma as C5,
                    jumlah_balita as C6,
                    jumlah_ibu_hamil as C7,
                    0 as C8
                FROM data_warga 
                ORDER BY id_warga
            ");
            
            if (mysqli_num_rows($warga_query) == 0) {
                echo "<script>
                        alert('Tidak ada data warga!\\nSilakan tambahkan data warga terlebih dahulu.');
                        window.location.href='../../media_admin.php?module=warga';
                      </script>";
                exit;
            }
            
            // Collect data matrix dan hitung max values
            $data_matrix = array();
            $max_values = array();
            
            while($row = mysqli_fetch_array($warga_query)) {
                $data_matrix[] = $row;
                
                // Hitung max untuk setiap kriteria (benefit semua)
                for($i = 1; $i <= 8; $i++) {
                    $col = 'C' . $i;
                    $nilai = $row[$col];
                    
                    if (!isset($max_values[$col]) || $nilai > $max_values[$col]) {
                        $max_values[$col] = $nilai;
                    }
                }
            }
            
            // ============================================
            // HITUNG MFEP UNTUK SETIAP WARGA
            // ============================================
            $hasil_mfep = array();
            
            foreach($data_matrix as $row) {
                $total_we = 0;
                $total_wp = 0;
                
                $E = array(); // Factor evaluation
                $WE = array(); // Weight evaluation
                
                // Hitung untuk setiap kriteria
                for($i = 1; $i <= 8; $i++) {
                    $col = 'C' . $i;
                    $nilai = $row[$col];
                    $weight = isset($bobot[$col]) ? $bobot[$col] : 0;
                    
                    // Skip jika bobot 0 atau max = 0
                    if ($weight == 0 || $max_values[$col] == 0) {
                        $E[$i] = 0;
                        $WE[$i] = 0;
                        continue;
                    }
                    
                    // 1. Normalisasi Factor (E) - semua benefit untuk PKH
                    $E[$i] = $nilai / $max_values[$col];
                    
                    // 2. Weight Evaluation (WE = W × E)
                    $WE[$i] = $weight * $E[$i];
                    
                    // 3. Akumulasi Total WE
                    $total_we += $WE[$i];
                    
                    // 4. Weight Problem (WP = (1-W) × (1-E))
                    $wp_component = (1 - $weight) * (1 - $E[$i]);
                    $total_wp += $wp_component;
                }
                
                // 5. MFEP Score = Total WE - Total WP
                $nilai_mfep = $total_we - $total_wp;
                
                // Store hasil
                $hasil_mfep[] = array(
                    'id_warga' => $row['id_warga'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'C1' => $row['C1'], 'C2' => $row['C2'], 'C3' => $row['C3'], 'C4' => $row['C4'],
                    'C5' => $row['C5'], 'C6' => $row['C6'], 'C7' => $row['C7'], 'C8' => $row['C8'],
                    'E1' => $E[1], 'E2' => $E[2], 'E3' => $E[3], 'E4' => $E[4],
                    'E5' => $E[5], 'E6' => $E[6], 'E7' => $E[7], 'E8' => $E[8],
                    'WE1' => $WE[1], 'WE2' => $WE[2], 'WE3' => $WE[3], 'WE4' => $WE[4],
                    'WE5' => $WE[5], 'WE6' => $WE[6], 'WE7' => $WE[7], 'WE8' => $WE[8],
                    'total_we' => $total_we,
                    'nilai_mfep' => $nilai_mfep
                );
            }
            
            // Sort by nilai_mfep descending
            usort($hasil_mfep, function($a, $b) {
                return $b['nilai_mfep'] <=> $a['nilai_mfep'];
            });
            
            // Insert hasil ke database dengan ranking
            $success_count = 0;
            $ranking = 1;
            
            foreach($hasil_mfep as $hasil) {
                // Tentukan rekomendasi (top 30% = Ya)
                $total_data = count($hasil_mfep);
                $rekomendasi = ($ranking <= ceil($total_data * 0.3)) ? 'Ya' : 'Tidak';
                
                $insert = mysqli_query($koneksi, "
                    INSERT INTO tbl_hasil_mfep 
                    (id_warga, nama_warga, 
                     C1, C2, C3, C4, C5, C6, C7, C8,
                     E1, E2, E3, E4, E5, E6, E7, E8,
                     WE1, WE2, WE3, WE4, WE5, WE6, WE7, WE8,
                     total_we, nilai_mfep, ranking, rekomendasi, created_at) 
                    VALUES 
                    ({$hasil['id_warga']}, '{$hasil['nama_lengkap']}',
                     {$hasil['C1']}, {$hasil['C2']}, {$hasil['C3']}, {$hasil['C4']}, 
                     {$hasil['C5']}, {$hasil['C6']}, {$hasil['C7']}, {$hasil['C8']},
                     {$hasil['E1']}, {$hasil['E2']}, {$hasil['E3']}, {$hasil['E4']}, 
                     {$hasil['E5']}, {$hasil['E6']}, {$hasil['E7']}, {$hasil['E8']},
                     {$hasil['WE1']}, {$hasil['WE2']}, {$hasil['WE3']}, {$hasil['WE4']}, 
                     {$hasil['WE5']}, {$hasil['WE6']}, {$hasil['WE7']}, {$hasil['WE8']},
                     {$hasil['total_we']}, {$hasil['nilai_mfep']}, $ranking, '$rekomendasi', NOW())
                ");
                
                if ($insert) {
                    $success_count++;
                }
                $ranking++;
            }
            
            if ($success_count > 0) {
                $top_score = number_format($hasil_mfep[0]['nilai_mfep'], 4);
                $top_name = $hasil_mfep[0]['nama_lengkap'];
                
                echo "<script>
                        alert('✅ Perhitungan MFEP Berhasil!\\n\\n📊 Total Data: $success_count warga\\n🏆 Ranking #1: $top_name\\n📈 Nilai MFEP: $top_score\\n\\n✨ Hasil perhitungan sudah tersedia di Perankingan');
                        window.location.href='$aksi';
                      </script>";
            } else {
                echo "<script>
                        alert('❌ Perhitungan MFEP gagal!\\nTidak ada data yang berhasil disimpan.');
                        window.location.href='$aksi';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('❌ Akses ditolak!\\nAnda tidak memiliki akses untuk melakukan perhitungan MFEP.');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    default:
        echo "<script>
                alert('Aksi tidak dikenal!');
                window.location.href='$aksi';
              </script>";
        break;
}
?>
