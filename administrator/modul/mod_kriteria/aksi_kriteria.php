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

// Security function to prevent SQL injection
function anti_injection($data){
    global $koneksi;
    $filter = mysqli_real_escape_string($koneksi, stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter;
}

$module = $_GET['act'];
$aksi = "index.php?module=kriteria";

switch($module){
    case "update":
        // Process update kriteria
        if ($_SESSION['leveluser']=='admin') {
            $id_kriteria = (int)$_POST['id_kriteria'];
            $kode_kriteria = anti_injection($_POST['kode_kriteria']);
            $keterangan = anti_injection($_POST['keterangan']);
            $nilai = (float)$_POST['nilai'];
            $jenis = anti_injection($_POST['jenis']);
            
            // Validate required fields
            if (empty($keterangan) || $nilai <= 0) {
                echo "<script>
                        alert('Keterangan dan nilai kriteria harus diisi dengan benar!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Validate nilai range
            if ($nilai < 0.1 || $nilai > 1.0) {
                echo "<script>
                        alert('Nilai kriteria harus antara 0.1 - 1.0!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Validate jenis
            if (!in_array($jenis, ['Benefit', 'Cost'])) {
                echo "<script>
                        alert('Jenis kriteria harus Benefit atau Cost!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Check if kriteria exists
            $check_kriteria = mysqli_query($koneksi, "SELECT kode_kriteria FROM tbl_kriteria WHERE id_kriteria=$id_kriteria");
            if (mysqli_num_rows($check_kriteria) == 0) {
                echo "<script>
                        alert('Kriteria tidak ditemukan!');
                        window.location.href='$aksi';
                      </script>";
                exit;
            }
            
            $update = mysqli_query($koneksi, "UPDATE tbl_kriteria SET 
                                    keterangan='$keterangan',
                                    nilai=$nilai,
                                    jenis='$jenis',
                                    updated_at=NOW()
                                    WHERE id_kriteria=$id_kriteria");
            
            if ($update) {
                // Log the changes for audit trail
                $log_message = "Kriteria $kode_kriteria diupdate: keterangan='$keterangan', nilai=$nilai, jenis='$jenis'";
                
                echo "<script>
                        alert('Kriteria berhasil diupdate!\\n\\nPerubahan:\\n- Keterangan: $keterangan\\n- Bobot Nilai: $nilai\\n- Jenis: $jenis');
                        window.location.href='$aksi';
                      </script>";
            } else {
                echo "<script>
                        alert('Kriteria gagal diupdate! Error: " . mysqli_error($koneksi) . "');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk mengupdate kriteria!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "reset_bobot":
        // Reset all criteria weights to default values
        if ($_SESSION['leveluser']=='admin') {
            $default_weights = [
                'C1' => 0.15, // Lansia
                'C2' => 0.20, // Disabilitas berat  
                'C3' => 0.15, // Anak SD
                'C4' => 0.15, // Anak SMP
                'C5' => 0.10, // Anak SMA
                'C6' => 0.15, // Balita
                'C7' => 0.10, // Ibu hamil
                'C8' => 0.00  // Cadangan
            ];
            
            $success_count = 0;
            foreach ($default_weights as $kode => $nilai) {
                $update = mysqli_query($koneksi, "UPDATE tbl_kriteria SET nilai=$nilai WHERE kode_kriteria='$kode'");
                if ($update) {
                    $success_count++;
                }
            }
            
            if ($success_count == count($default_weights)) {
                echo "<script>
                        alert('Semua bobot kriteria berhasil direset ke nilai default!');
                        window.location.href='$aksi';
                      </script>";
            } else {
                echo "<script>
                        alert('Beberapa kriteria gagal direset. Silakan coba lagi.');
                        window.location.href='$aksi';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk mereset bobot kriteria!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "validasi_bobot":
        // Validate that total weights equal 1.0
        if ($_SESSION['leveluser']=='admin') {
            $total_query = mysqli_query($koneksi, "SELECT SUM(nilai) as total_bobot FROM tbl_kriteria WHERE nilai > 0");
            $total_data = mysqli_fetch_array($total_query);
            $total_bobot = round($total_data['total_bobot'], 2);
            
            if ($total_bobot == 1.00) {
                echo "<script>
                        alert('✓ Validasi Sukses!\\n\\nTotal bobot kriteria = $total_bobot\\nBobot sudah sesuai standar SAW.');
                        window.location.href='$aksi';
                      </script>";
            } else {
                $selisih = round(1.00 - $total_bobot, 2);
                $status = $total_bobot > 1.00 ? "berlebih" : "kurang";
                
                echo "<script>
                        alert('⚠ Peringatan Validasi!\\n\\nTotal bobot kriteria = $total_bobot\\nBobot $status $selisih dari standar (1.00)\\n\\nSilakan sesuaikan bobot kriteria agar total = 1.00');
                        window.location.href='$aksi';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk validasi bobot!');
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
