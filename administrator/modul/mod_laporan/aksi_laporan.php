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
$aksi = "index.php?module=laporan&act=analisa";

switch($module){
    case "hitung_saw":
        // Process SAW calculation
        if ($_SESSION['leveluser']=='admin') {
            
            // Hapus hasil SAW sebelumnya
            mysqli_query($koneksi, "DELETE FROM tbl_hasil_saw");
            
            // Get kriteria dan bobot
            $kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
            $bobot = array();
            $jenis_kriteria = array();
            
            while($k = mysqli_fetch_array($kriteria)) {
                $bobot[$k['kode_kriteria']] = $k['nilai'];
                $jenis_kriteria[$k['kode_kriteria']] = $k['jenis'];
            }
            
            // Validasi total bobot
            $total_bobot = array_sum($bobot);
            if (abs($total_bobot - 1.0) > 0.01) {
                echo "<script>
                        alert('Error: Total bobot kriteria = $total_bobot\\nTotal bobot harus sama dengan 1.0!\\nSilakan sesuaikan bobot kriteria terlebih dahulu.');
                        window.location.href='index.php?module=kriteria';
                      </script>";
                exit;
            }
            
            // Get data klasifikasi
            $klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY k.id_warga
            ");
            
            if (mysqli_num_rows($klasifikasi) == 0) {
                echo "<script>
                        alert('Tidak ada data klasifikasi!\\nSilakan tambahkan data warga terlebih dahulu.');
                        window.location.href='index.php?module=warga';
                      </script>";
                exit;
            }
            
            // Collect all data untuk normalisasi
            $data_matrix = array();
            $max_values = array();
            $min_values = array();
            
            // Reset pointer dan collect data
            mysqli_data_seek($klasifikasi, 0);
            while($row = mysqli_fetch_array($klasifikasi)) {
                $data_matrix[] = $row;
                
                // Hitung max dan min untuk setiap kriteria
                for($i = 1; $i <= 8; $i++) {
                    $kriteria_col = 'C' . $i;
                    $nilai = $row[$kriteria_col];
                    
                    if (!isset($max_values[$kriteria_col]) || $nilai > $max_values[$kriteria_col]) {
                        $max_values[$kriteria_col] = $nilai;
                    }
                    if (!isset($min_values[$kriteria_col]) || $nilai < $min_values[$kriteria_col]) {
                        $min_values[$kriteria_col] = $nilai;
                    }
                }
            }
            
            // Hitung nilai SAW untuk setiap warga
            $hasil_saw = array();
            
            foreach($data_matrix as $row) {
                $nilai_saw = 0;
                
                // Hitung untuk setiap kriteria
                for($i = 1; $i <= 8; $i++) {
                    $kriteria_col = 'C' . $i;
                    $nilai = $row[$kriteria_col];
                    $weight = $bobot[$kriteria_col];
                    
                    // Skip jika bobot 0 atau nilai max = 0
                    if ($weight == 0 || $max_values[$kriteria_col] == 0) {
                        continue;
                    }
                    
                    // Normalisasi berdasarkan jenis kriteria
                    if ($jenis_kriteria[$kriteria_col] == 'Benefit') {
                        // Benefit: nilai/max
                        $normalized = $nilai / $max_values[$kriteria_col];
                    } else {
                        // Cost: min/nilai (dengan handling pembagian dengan 0)
                        $normalized = ($nilai > 0) ? $min_values[$kriteria_col] / $nilai : 0;
                    }
                    
                    // Kalikan dengan bobot
                    $nilai_saw += $normalized * $weight;
                }
                
                $hasil_saw[] = array(
                    'id_warga' => $row['id_warga'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'nilai_saw' => $nilai_saw
                );
            }
            
            // Sort by nilai_saw descending
            usort($hasil_saw, function($a, $b) {
                return $b['nilai_saw'] <=> $a['nilai_saw'];
            });
            
            // Insert hasil ke database
            $success_count = 0;
            foreach($hasil_saw as $hasil) {
                $insert = mysqli_query($koneksi, "
                    INSERT INTO tbl_hasil_saw 
                    (id_warga, nilai_saw, created_at) 
                    VALUES 
                    ({$hasil['id_warga']}, {$hasil['nilai_saw']}, NOW())
                ");
                
                if ($insert) {
                    $success_count++;
                }
            }
            
            if ($success_count > 0) {
                echo "<script>
                        alert('Perhitungan SAW berhasil!\\n\\nTotal data: $success_count warga\\nHasil ranking sudah tersedia\\n\\nNilai tertinggi: " . number_format($hasil_saw[0]['nilai_saw'], 4) . " (" . $hasil_saw[0]['nama_lengkap'] . ")');
                        window.location.href='$aksi';
                      </script>";
            } else {
                echo "<script>
                        alert('Perhitungan SAW gagal!\\nTidak ada data yang berhasil disimpan.');
                        window.location.href='$aksi';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk melakukan perhitungan SAW!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "export_pdf":
        // Export hasil SAW to PDF
        if ($_SESSION['leveluser']=='admin') {
            require_once '../vendor/autoload.php';
            
            $hasil_saw = mysqli_query($koneksi, "
                SELECT h.*, w.nama_lengkap, w.alamat 
                FROM tbl_hasil_saw h 
                JOIN data_warga w ON h.id_warga = w.id_warga 
                ORDER BY h.nilai_saw DESC
            ");
            
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header h2 { color: #2c5aa0; margin: 5px 0; }
                    .header h3 { margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #2c5aa0; color: white; font-weight: bold; }
                    .text-center { text-align: center; }
                    .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
                    .rank-1 { background-color: #d4edda; }
                    .rank-2 { background-color: #cce7ff; }
                    .rank-3 { background-color: #fff3cd; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>SISTEM PENDUKUNG KEPUTUSAN PKH</h2>
                    <h2>METODE SIMPLE ADDITIVE WEIGHTING (SAW)</h2>
                    <h3>DINAS SOSIAL REPUBLIK INDONESIA</h3>
                    <hr>
                    <h4>LAPORAN HASIL RANKING KELAYAKAN PENERIMA BANTUAN PKH</h4>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th width="8%">Ranking</th>
                            <th width="30%">Nama Lengkap</th>
                            <th width="35%">Alamat</th>
                            <th width="12%">Nilai SAW</th>
                            <th width="15%">Status Kelayakan</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $rank = 1;
            $total_data = mysqli_num_rows($hasil_saw);
            
            while ($r = mysqli_fetch_array($hasil_saw)) {
                $nilai_saw = number_format($r['nilai_saw'], 4);
                
                // Tentukan status dan class
                if ($rank <= ceil($total_data * 0.3)) {
                    $status = 'Sangat Layak';
                    $class = 'rank-1';
                } elseif ($rank <= ceil($total_data * 0.6)) {
                    $status = 'Layak';
                    $class = 'rank-2';
                } elseif ($rank <= ceil($total_data * 0.8)) {
                    $status = 'Cukup Layak';
                    $class = 'rank-3';
                } else {
                    $status = 'Kurang Layak';
                    $class = '';
                }
                
                $html .= '
                        <tr class="'.$class.'">
                            <td class="text-center"><strong>'.$rank.'</strong></td>
                            <td>'.$r['nama_lengkap'].'</td>
                            <td>'.$r['alamat'].'</td>
                            <td class="text-center">'.$nilai_saw.'</td>
                            <td class="text-center">'.$status.'</td>
                        </tr>';
                $rank++;
            }
            
            $html .= '
                    </tbody>
                </table>
                
                <div class="footer">
                    <p><strong>Keterangan Status Kelayakan:</strong></p>
                    <p>• Sangat Layak (Top 30%): Prioritas utama penerima bantuan PKH</p>
                    <p>• Layak (31%-60%): Penerima bantuan PKH tahap kedua</p>
                    <p>• Cukup Layak (61%-80%): Cadangan penerima bantuan PKH</p>
                    <p>• Kurang Layak (81%-100%): Perlu evaluasi ulang kriteria</p>
                    <br>
                    <p>Dicetak pada: '.date('d/m/Y H:i:s').' | Total Data: '.($rank-1).' Warga</p>
                    <p>Sistem Pendukung Keputusan PKH - Dinas Sosial RI</p>
                </div>
            </body>
            </html>';
            
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("Hasil_Ranking_PKH_SAW_".date('Y-m-d').".pdf", array("Attachment" => false));
        }
        break;
        
    case "export_excel":
        // Export hasil SAW to Excel
        if ($_SESSION['leveluser']=='admin') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Hasil_Ranking_PKH_SAW_'.date('Y-m-d').'.xls"');
            header('Cache-Control: max-age=0');
            
            $hasil_saw = mysqli_query($koneksi, "
                SELECT h.*, w.nama_lengkap, w.alamat 
                FROM tbl_hasil_saw h 
                JOIN data_warga w ON h.id_warga = w.id_warga 
                ORDER BY h.nilai_saw DESC
            ");
            
            echo '
            <html>
            <head>
                <meta charset="utf-8">
                <title>Hasil Ranking PKH SAW</title>
            </head>
            <body>
                <h2>SISTEM PENDUKUNG KEPUTUSAN PKH</h2>
                <h3>HASIL RANKING KELAYAKAN PENERIMA BANTUAN PKH</h3>
                <h4>Metode Simple Additive Weighting (SAW)</h4>
                <h4>Dinas Sosial Republik Indonesia</h4>
                <br>
                
                <table border="1">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Nilai SAW</th>
                            <th>Status Kelayakan</th>
                            <th>Tanggal Hitung</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $rank = 1;
            $total_data = mysqli_num_rows($hasil_saw);
            
            while ($r = mysqli_fetch_array($hasil_saw)) {
                $nilai_saw = number_format($r['nilai_saw'], 4);
                
                if ($rank <= ceil($total_data * 0.3)) {
                    $status = 'Sangat Layak';
                } elseif ($rank <= ceil($total_data * 0.6)) {
                    $status = 'Layak';
                } elseif ($rank <= ceil($total_data * 0.8)) {
                    $status = 'Cukup Layak';
                } else {
                    $status = 'Kurang Layak';
                }
                
                echo '<tr>
                        <td>'.$rank.'</td>
                        <td>'.$r['nama_lengkap'].'</td>
                        <td>'.$r['alamat'].'</td>
                        <td>'.$nilai_saw.'</td>
                        <td>'.$status.'</td>
                        <td>'.date('d/m/Y H:i', strtotime($r['created_at'])).'</td>
                      </tr>';
                $rank++;
            }
            
            echo '
                    </tbody>
                </table>
                
                <br><br>
                <p><strong>Keterangan:</strong></p>
                <p>Sangat Layak (Top 30%): Prioritas utama penerima bantuan PKH</p>
                <p>Layak (31%-60%): Penerima bantuan PKH tahap kedua</p>
                <p>Cukup Layak (61%-80%): Cadangan penerima bantuan PKH</p>
                <p>Kurang Layak (81%-100%): Perlu evaluasi ulang kriteria</p>
                <br>
                <p>Dicetak pada: '.date('d/m/Y H:i:s').'</p>
                <p>Total Data: '.($rank-1).' Warga</p>
            </body>
            </html>';
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
