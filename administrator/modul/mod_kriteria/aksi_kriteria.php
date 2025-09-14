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
$aksi = "../../media_admin.php?module=kriteria";

switch($module){
    case "update":
        // Process update kriteria
        if ($_SESSION['leveluser']=='admin') {
            $id_kriteria = (int)$_POST['id_kriteria'];
            $kode_kriteria = anti_injection($_POST['kode_kriteria']);
            $keterangan = anti_injection($_POST['keterangan']);
            $nilai_persen = (int)$_POST['nilai']; // Input dalam persen 1-100
            $nilai = $nilai_persen / 100; // Konversi ke desimal 0.01-1.00
            $jenis = anti_injection($_POST['jenis']);
            
            // Validate required fields
            if (empty($keterangan) || $nilai_persen < 0) {
                echo "<script>
                        alert('Keterangan harus diisi dan nilai tidak boleh negatif!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Validate nilai range (dalam persen) - allow 0
            if ($nilai_persen < 0 || $nilai_persen > 100) {
                echo "<script>
                        alert('Nilai kriteria harus antara 0% - 100%!');
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
                        alert('Kriteria berhasil diupdate!\\n\\nPerubahan:\\n- Keterangan: $keterangan\\n- Bobot Nilai: $nilai_persen%\\n- Jenis: $jenis');
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
        
    case "add_himpunan":
        // Process add himpunan data
        if ($_SESSION['leveluser']=='admin') {
            $id_kriteria = (int)$_POST['id_kriteria'];
            $keterangan = anti_injection($_POST['keterangan']);
            $nilai = (int)$_POST['nilai'];
            
            // Allow zero: only invalid if negative
            if (empty($keterangan) || $nilai < 0) {
                echo "<script>
                        alert('Keterangan harus diisi dan nilai tidak boleh negatif!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Check if table exists, if not create it
            $check_table = mysqli_query($koneksi, "SHOW TABLES LIKE 'tbl_himpunan'");
            if (mysqli_num_rows($check_table) == 0) {
                $create_table = mysqli_query($koneksi, "
                    CREATE TABLE tbl_himpunan (
                        id_himpunan int(11) NOT NULL AUTO_INCREMENT,
                        id_kriteria int(11) NOT NULL,
                        keterangan varchar(100) NOT NULL,
                        nilai int(11) NOT NULL,
                        PRIMARY KEY (id_himpunan),
                        FOREIGN KEY (id_kriteria) REFERENCES tbl_kriteria(id_kriteria) ON DELETE CASCADE
                    )
                ");
            }
            
            // Insert new himpunan data
            $insert = mysqli_query($koneksi, "
                INSERT INTO tbl_himpunan (id_kriteria, keterangan, nilai) 
                VALUES ('$id_kriteria', '$keterangan', '$nilai')
            ");
            
            if ($insert) {
                echo "<script>
                        alert('Data himpunan berhasil ditambahkan!');
                        window.location.href='$aksi&act=input&id=$id_kriteria';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal menambahkan data himpunan! Error: " . mysqli_error($koneksi) . "');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk menambah data!');
                    window.history.back();
                  </script>";
        }
        break;
        
    case "update_himpunan":
        // Process update himpunan data
        if ($_SESSION['leveluser']=='admin') {
            $id_himpunan = (int)$_POST['id_himpunan'];
            $id_kriteria = (int)$_POST['id_kriteria'];
            $keterangan = anti_injection($_POST['keterangan']);
            $nilai = (int)$_POST['nilai'];
            
            if (empty($keterangan) || $nilai < 0) {
                echo "<script>
                        alert('Keterangan harus diisi dan nilai tidak boleh negatif!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Update himpunan data
            $update = mysqli_query($koneksi, "
                UPDATE tbl_himpunan SET 
                keterangan='$keterangan', 
                nilai='$nilai' 
                WHERE id_himpunan='$id_himpunan'
            ");
            
            if ($update) {
                echo "<script>
                        alert('Data himpunan berhasil diupdate!');
                        window.location.href='$aksi&act=input&id=$id_kriteria';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal mengupdate data himpunan! Error: " . mysqli_error($koneksi) . "');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk mengupdate data!');
                    window.history.back();
                  </script>";
        }
        break;
        
    case "delete_himpunan":
        // Process delete himpunan data
        if ($_SESSION['leveluser']=='admin') {
            $id_himpunan = (int)$_GET['id'];
            $id_kriteria = (int)$_GET['kriteria_id'];
            
            // Delete himpunan data
            $delete = mysqli_query($koneksi, "DELETE FROM tbl_himpunan WHERE id_himpunan='$id_himpunan'");
            
            if ($delete) {
                echo "<script>
                        alert('Data himpunan berhasil dihapus!');
                        window.location.href='$aksi&act=input&id=$id_kriteria';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal menghapus data himpunan! Error: " . mysqli_error($koneksi) . "');
                        window.location.href='$aksi&act=input&id=$id_kriteria';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk menghapus data!');
                    window.location.href='$aksi&act=input&id=$id_kriteria';
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
