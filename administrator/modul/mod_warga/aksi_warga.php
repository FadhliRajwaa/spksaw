<?php
session_start();

// Security check
if (empty($_SESSION['namauser']) AND empty($_SESSION['passuser'])){
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
$aksi = "../../media_admin.php?module=warga";

switch($module){
    case "input":
        // Process input data
        if ($_SESSION['leveluser']=='admin') {
            $nama_lengkap = anti_injection($_POST['nama_lengkap']);
            $alamat = anti_injection($_POST['alamat']);
            $jumlah_lansia = (int)$_POST['jumlah_lansia'];
            $jumlah_disabilitas_berat = (int)$_POST['jumlah_disabilitas_berat'];
            $jumlah_anak_sd = (int)$_POST['jumlah_anak_sd'];
            $jumlah_anak_smp = (int)$_POST['jumlah_anak_smp'];
            $jumlah_anak_sma = (int)$_POST['jumlah_anak_sma'];
            $jumlah_balita = (int)$_POST['jumlah_balita'];
            $jumlah_ibu_hamil = (int)$_POST['jumlah_ibu_hamil'];
            
            // Validate required fields
            if (empty($nama_lengkap) || empty($alamat)) {
                echo "<script>
                        alert('Nama lengkap dan alamat harus diisi!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Check for duplicate name
            $check_duplicate = mysqli_query($koneksi, "SELECT id_warga FROM data_warga WHERE nama_lengkap='$nama_lengkap'");
            if (mysqli_num_rows($check_duplicate) > 0) {
                echo "<script>
                        alert('Nama warga sudah ada dalam database!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            $simpan = mysqli_query($koneksi, "INSERT INTO data_warga 
                                    (nama_lengkap, alamat, jumlah_lansia, jumlah_disabilitas_berat, 
                                     jumlah_anak_sd, jumlah_anak_smp, jumlah_anak_sma, 
                                     jumlah_balita, jumlah_ibu_hamil, created_at, updated_at) 
                                    VALUES 
                                    ('$nama_lengkap', '$alamat', $jumlah_lansia, $jumlah_disabilitas_berat,
                                     $jumlah_anak_sd, $jumlah_anak_smp, $jumlah_anak_sma,
                                     $jumlah_balita, $jumlah_ibu_hamil, NOW(), NOW())");
            
            if ($simpan) {
                $id_warga = mysqli_insert_id($koneksi);
                
                // Auto-populate tbl_klasifikasi
                $insert_klasifikasi = mysqli_query($koneksi, "
                    INSERT INTO tbl_klasifikasi 
                    (id_warga, C1, C2, C3, C4, C5, C6, C7, C8, created_at) 
                    VALUES 
                    ($id_warga, $jumlah_lansia, $jumlah_disabilitas_berat, $jumlah_anak_sd, 
                     $jumlah_anak_smp, $jumlah_anak_sma, $jumlah_balita, $jumlah_ibu_hamil, 0, NOW())
                ");
                
                if ($insert_klasifikasi) {
                    echo "<script>
                            alert('Data warga berhasil disimpan dan klasifikasi telah dibuat!');
                            window.location.href='$aksi';
                          </script>";
                } else {
                    echo "<script>
                            alert('Data warga disimpan, tetapi gagal membuat klasifikasi!');
                            window.location.href='$aksi';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Data warga gagal disimpan! Error: " . mysqli_error($koneksi) . "');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk menambah data!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "update":
        // Process update data
        if ($_SESSION['leveluser']=='admin') {
            $id_warga = (int)$_POST['id_warga'];
            $nama_lengkap = anti_injection($_POST['nama_lengkap']);
            $alamat = anti_injection($_POST['alamat']);
            $jumlah_lansia = (int)$_POST['jumlah_lansia'];
            $jumlah_disabilitas_berat = (int)$_POST['jumlah_disabilitas_berat'];
            $jumlah_anak_sd = (int)$_POST['jumlah_anak_sd'];
            $jumlah_anak_smp = (int)$_POST['jumlah_anak_smp'];
            $jumlah_anak_sma = (int)$_POST['jumlah_anak_sma'];
            $jumlah_balita = (int)$_POST['jumlah_balita'];
            $jumlah_ibu_hamil = (int)$_POST['jumlah_ibu_hamil'];
            
            // Validate required fields
            if (empty($nama_lengkap) || empty($alamat)) {
                echo "<script>
                        alert('Nama lengkap dan alamat harus diisi!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            // Check for duplicate name (excluding current record)
            $check_duplicate = mysqli_query($koneksi, "SELECT id_warga FROM data_warga WHERE nama_lengkap='$nama_lengkap' AND id_warga != $id_warga");
            if (mysqli_num_rows($check_duplicate) > 0) {
                echo "<script>
                        alert('Nama warga sudah ada dalam database!');
                        window.history.back();
                      </script>";
                exit;
            }
            
            $update = mysqli_query($koneksi, "UPDATE data_warga SET 
                                    nama_lengkap='$nama_lengkap',
                                    alamat='$alamat',
                                    jumlah_lansia=$jumlah_lansia,
                                    jumlah_disabilitas_berat=$jumlah_disabilitas_berat,
                                    jumlah_anak_sd=$jumlah_anak_sd,
                                    jumlah_anak_smp=$jumlah_anak_smp,
                                    jumlah_anak_sma=$jumlah_anak_sma,
                                    jumlah_balita=$jumlah_balita,
                                    jumlah_ibu_hamil=$jumlah_ibu_hamil,
                                    updated_at=NOW()
                                    WHERE id_warga=$id_warga");
            
            if ($update) {
                // Update corresponding klasifikasi record
                $update_klasifikasi = mysqli_query($koneksi, "
                    UPDATE tbl_klasifikasi SET 
                    C1=$jumlah_lansia, C2=$jumlah_disabilitas_berat, C3=$jumlah_anak_sd,
                    C4=$jumlah_anak_smp, C5=$jumlah_anak_sma, C6=$jumlah_balita, 
                    C7=$jumlah_ibu_hamil, C8=0, updated_at=NOW()
                    WHERE id_warga=$id_warga
                ");
                
                if ($update_klasifikasi) {
                    echo "<script>
                            alert('Data warga berhasil diupdate dan klasifikasi telah diperbarui!');
                            window.location.href='$aksi';
                          </script>";
                } else {
                    echo "<script>
                            alert('Data warga diupdate, tetapi gagal memperbarui klasifikasi!');
                            window.location.href='$aksi';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Data warga gagal diupdate! Error: " . mysqli_error($koneksi) . "');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk mengupdate data!');
                    window.location.href='$aksi';
                  </script>";
        }
        break;
        
    case "hapus":
        // Process delete data
        if ($_SESSION['leveluser']=='admin') {
            $id_warga = (int)$_GET['id'];
            
            // Check if warga exists
            $check_warga = mysqli_query($koneksi, "SELECT nama_lengkap FROM data_warga WHERE id_warga=$id_warga");
            if (mysqli_num_rows($check_warga) == 0) {
                echo "<script>
                        alert('Data warga tidak ditemukan!');
                        window.location.href='$aksi';
                      </script>";
                exit;
            }
            
            $warga_data = mysqli_fetch_array($check_warga);
            
            // Implement cascade deletion - delete related records first
            // 1. Delete from tbl_hasil_saw (SAW calculation results)
            $delete_hasil_saw = mysqli_query($koneksi, "DELETE FROM tbl_hasil_saw WHERE id_warga=$id_warga");
            
            // 2. Delete from tbl_klasifikasi (classification data)
            $delete_klasifikasi = mysqli_query($koneksi, "DELETE FROM tbl_klasifikasi WHERE id_warga=$id_warga");
            
            // 3. Finally delete from data_warga (main record)
            $delete_warga = mysqli_query($koneksi, "DELETE FROM data_warga WHERE id_warga=$id_warga");
            
            if ($delete_warga) {
                echo "<script>
                        alert('Data warga \"" . $warga_data['nama_lengkap'] . "\" dan semua data terkait berhasil dihapus!');
                        window.location.href='$aksi';
                      </script>";
            } else {
                echo "<script>
                        alert('Data warga gagal dihapus! Error: " . mysqli_error($koneksi) . "');
                        window.location.href='$aksi';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Anda tidak memiliki akses untuk menghapus data!');
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
