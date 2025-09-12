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
$aksi = "index.php?module=klasifikasi";

switch($module){
    case "refresh":
        // Refresh single klasifikasi data from warga
        if ($_SESSION['leveluser']=='admin') {
            $id_klasifikasi = (int)$_GET['id'];
            
            // Get klasifikasi and warga data
            $get_data = mysqli_query($koneksi, "
                SELECT k.id_warga, w.nama_lengkap, w.jumlah_lansia, w.jumlah_disabilitas_berat, 
                       w.jumlah_anak_sd, w.jumlah_anak_smp, w.jumlah_anak_sma, w.jumlah_balita, w.jumlah_ibu_hamil
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                WHERE k.id_klasifikasi = $id_klasifikasi
            ");
            
            if (mysqli_num_rows($get_data) == 0) {
                echo "<script>
                        alert('Data klasifikasi tidak ditemukan!');
                        window.location.href='$aksi';
                      </script>";
                exit;
            }
            
            $data = mysqli_fetch_array($get_data);
            
            // Update klasifikasi with fresh data from warga
            $update = mysqli_query($koneksi, "
                UPDATE tbl_klasifikasi SET 
                C1 = {$data['jumlah_lansia']},
                C2 = {$data['jumlah_disabilitas_berat']},
                C3 = {$data['jumlah_anak_sd']},
                C4 = {$data['jumlah_anak_smp']},
                C5 = {$data['jumlah_anak_sma']},
                C6 = {$data['jumlah_balita']},
                C7 = {$data['jumlah_ibu_hamil']},
                C8 = 0,
                updated_at = NOW()
                WHERE id_klasifikasi = $id_klasifikasi
            ");
            
            if ($update) {
                echo "<script>
                        alert('Data klasifikasi untuk \"" . $data['nama_lengkap'] . "\" berhasil direfresh!');
                        window.location.href='$aksi';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal refresh data klasifikasi! Error: " . mysqli_error($koneksi) . "');
                        window.location.href='$aksi';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk refresh data!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "refresh_all":
        // Refresh all klasifikasi data from warga
        if ($_SESSION['leveluser']=='admin') {
            // Get all warga data
            $warga_data = mysqli_query($koneksi, "
                SELECT id_warga, nama_lengkap, jumlah_lansia, jumlah_disabilitas_berat, 
                       jumlah_anak_sd, jumlah_anak_smp, jumlah_anak_sma, jumlah_balita, jumlah_ibu_hamil
                FROM data_warga
            ");
            
            $success_count = 0;
            $error_count = 0;
            
            while ($w = mysqli_fetch_array($warga_data)) {
                // Check if klasifikasi exists
                $check_klasifikasi = mysqli_query($koneksi, "SELECT id_klasifikasi FROM tbl_klasifikasi WHERE id_warga = {$w['id_warga']}");
                
                if (mysqli_num_rows($check_klasifikasi) > 0) {
                    // Update existing klasifikasi
                    $update = mysqli_query($koneksi, "
                        UPDATE tbl_klasifikasi SET 
                        C1 = {$w['jumlah_lansia']},
                        C2 = {$w['jumlah_disabilitas_berat']},
                        C3 = {$w['jumlah_anak_sd']},
                        C4 = {$w['jumlah_anak_smp']},
                        C5 = {$w['jumlah_anak_sma']},
                        C6 = {$w['jumlah_balita']},
                        C7 = {$w['jumlah_ibu_hamil']},
                        C8 = 0,
                        updated_at = NOW()
                        WHERE id_warga = {$w['id_warga']}
                    ");
                    
                    if ($update) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                } else {
                    // Create new klasifikasi
                    $insert = mysqli_query($koneksi, "
                        INSERT INTO tbl_klasifikasi 
                        (id_warga, C1, C2, C3, C4, C5, C6, C7, C8, created_at) 
                        VALUES 
                        ({$w['id_warga']}, {$w['jumlah_lansia']}, {$w['jumlah_disabilitas_berat']}, 
                         {$w['jumlah_anak_sd']}, {$w['jumlah_anak_smp']}, {$w['jumlah_anak_sma']}, 
                         {$w['jumlah_balita']}, {$w['jumlah_ibu_hamil']}, 0, NOW())
                    ");
                    
                    if ($insert) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
            }
            
            $total_processed = $success_count + $error_count;
            
            echo "<script>
                    alert('Refresh data klasifikasi selesai!\\n\\nTotal diproses: $total_processed\\nBerhasil: $success_count\\nGagal: $error_count');
                    window.location.href='$aksi';
                  </script>";
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk refresh semua data!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "export_excel":
        // Export klasifikasi to Excel format
        if ($_SESSION['leveluser']=='admin') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Data_Klasifikasi_PKH_'.date('Y-m-d').'.xls"');
            header('Cache-Control: max-age=0');
            
            $tampil_klasifikasi = mysqli_query($koneksi, "
                SELECT k.*, w.nama_lengkap, w.alamat 
                FROM tbl_klasifikasi k 
                JOIN data_warga w ON k.id_warga = w.id_warga 
                ORDER BY w.nama_lengkap
            ");
            
            echo '
            <html>
            <head>
                <meta charset="utf-8">
                <title>Data Klasifikasi PKH</title>
            </head>
            <body>
                <h2>SISTEM PENDUKUNG KEPUTUSAN PKH</h2>
                <h3>DATA KLASIFIKASI WARGA PKH</h3>
                <h4>Dinas Sosial Republik Indonesia</h4>
                <br>
                
                <table border="1">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Warga</th>
                            <th rowspan="2">Alamat</th>
                            <th colspan="8">Kriteria PKH</th>
                        </tr>
                        <tr>
                            <th>C1 (Lansia)</th>
                            <th>C2 (Disabilitas)</th>
                            <th>C3 (Anak SD)</th>
                            <th>C4 (Anak SMP)</th>
                            <th>C5 (Anak SMA)</th>
                            <th>C6 (Balita)</th>
                            <th>C7 (Ibu Hamil)</th>
                            <th>C8 (Lainnya)</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            $no = 1;
            while ($r = mysqli_fetch_array($tampil_klasifikasi)) {
                echo '<tr>
                        <td>'.$no.'</td>
                        <td>'.$r['nama_lengkap'].'</td>
                        <td>'.$r['alamat'].'</td>
                        <td>'.$r['C1'].'</td>
                        <td>'.$r['C2'].'</td>
                        <td>'.$r['C3'].'</td>
                        <td>'.$r['C4'].'</td>
                        <td>'.$r['C5'].'</td>
                        <td>'.$r['C6'].'</td>
                        <td>'.$r['C7'].'</td>
                        <td>'.$r['C8'].'</td>
                      </tr>';
                $no++;
            }
            
            echo '
                    </tbody>
                </table>
                
                <br><br>
                <p>Dicetak pada: '.date('d/m/Y H:i:s').'</p>
                <p>Total Data: '.($no-1).' Klasifikasi</p>
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
