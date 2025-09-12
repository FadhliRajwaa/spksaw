<?php
require __DIR__ . '/../vendor/autoload.php'; // sesuaikan path-nya
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// koneksi
$server = "localhost";
$user = "root";
$password = "";
$database = "spksaw";
$koneksi = mysqli_connect($server, $user, $password, $database) or die("Koneksi gagal");

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header laporan
$sheet->mergeCells('A1:F1')->setCellValue('A1', 'Laporan SPK SAW');
$sheet->mergeCells('A2:F2')->setCellValue('A2', 'Matrik Awal, Normalisasi, dan Ranking');
$sheet->mergeCells('A3:F3')->setCellValue('A3', 'Tanggal Cetak: ' . date('d-m-Y'));
$sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Mulai baris
$row = 5;

// ==========================
// 1. Matrik Awal (Nama)
// ==========================
$sheet->setCellValue('A'.$row, 'Matrik Awal (Nama)');
$row++;

$sheet->setCellValue('A'.$row, 'No')
      ->setCellValue('B'.$row, 'NIM')
      ->setCellValue('C'.$row, 'Nama');

$tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria");
$a = 1;
$colIndex = 'D';
while($f = mysqli_fetch_array($tampil_kriteria)){
    $sheet->setCellValue($colIndex.$row, 'C'.$a);
    $colIndex++;
    $a++;
}
$row++;

$no=1;
$tampil_klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi GROUP BY id_siswa");
while ($r=mysqli_fetch_array($tampil_klasifikasi)){
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));

    $sheet->setCellValue('A'.$row, $no);
    $sheet->setCellValue('B'.$row, $h['nim']);
    $sheet->setCellValue('C'.$row, $h['nama_lengkap']);

    $colIndex = 'D';
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE id_siswa = '$r[id_siswa]'");
    while ($n=mysqli_fetch_array($klasifikasi)){
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $sheet->setCellValue($colIndex.$row, $himpunankriteria['nama']);
        $colIndex++;
    }
    $no++;
    $row++;
}
$row += 2;

// ==========================
// 2. Matrik Awal (Nilai)
// ==========================
$sheet->setCellValue('A'.$row, 'Matrik Awal (Nilai)');
$row++;

$sheet->setCellValue('A'.$row, 'No')
      ->setCellValue('B'.$row, 'NIM')
      ->setCellValue('C'.$row, 'Nama');

$tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria");
$a = 1;
$colIndex = 'D';
while($f = mysqli_fetch_array($tampil_kriteria)){
    $sheet->setCellValue($colIndex.$row, 'C'.$a);
    $colIndex++;
    $a++;
}
$row++;

$no=1;
$tampil_klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi GROUP BY id_siswa");
while ($r=mysqli_fetch_array($tampil_klasifikasi)){
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));

    $sheet->setCellValue('A'.$row, $no);
    $sheet->setCellValue('B'.$row, $h['nim']);
    $sheet->setCellValue('C'.$row, $h['nama_lengkap']);

    $colIndex = 'D';
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE id_siswa = '$r[id_siswa]'");
    while ($n=mysqli_fetch_array($klasifikasi)){
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $sheet->setCellValue($colIndex.$row, $himpunankriteria['nilai']);
        $colIndex++;
    }
    $no++;
    $row++;
}
$row += 2;

// ==========================
// 3. Normalisasi
// ==========================
$sheet->setCellValue('A'.$row, 'Normalisasi');
$row++;

$sheet->setCellValue('A'.$row, 'No')
      ->setCellValue('B'.$row, 'NIM')
      ->setCellValue('C'.$row, 'Nama');

$tampil_kriteria = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria");
$a = 1;
$colIndex = 'D';
while($f = mysqli_fetch_array($tampil_kriteria)){
    $sheet->setCellValue($colIndex.$row, 'C'.$a);
    $colIndex++;
    $a++;
}
$row++;

$no=1;
$tampil_klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi GROUP BY id_siswa");
while ($r=mysqli_fetch_array($tampil_klasifikasi)){
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));

    $sheet->setCellValue('A'.$row, $no);
    $sheet->setCellValue('B'.$row, $h['nim']);
    $sheet->setCellValue('C'.$row, $h['nama_lengkap']);

    $colIndex = 'D';
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM v_analisa WHERE id_siswa = '$r[id_siswa]'");
    while ($n=mysqli_fetch_array($klasifikasi)){
        $crmax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT max(nilai) as nilaimax FROM v_analisa WHERE id_kriteria='$n[id_kriteria]'"));
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $nilaiok = $himpunankriteria['nilai'] / $crmax['nilaimax'];
        $sheet->setCellValue($colIndex.$row, $nilaiok);
        $colIndex++;
    }
    $no++;
    $row++;
}
$row += 2;

// ==========================
// 4. Ranking
// ==========================
$sheet->setCellValue('A'.$row, 'Ranking');
$row++;

$sheet->setCellValue('A'.$row, 'No')
      ->setCellValue('B'.$row, 'NIM')
      ->setCellValue('C'.$row, 'Nama')
      ->setCellValue('D'.$row, 'Total Nilai');

$row++;
$no=1;
$tampil_klasifikasi = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi GROUP BY id_siswa");
while ($r=mysqli_fetch_array($tampil_klasifikasi)){
    $h = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa ='$r[id_siswa]'"));

    $sheet->setCellValue('A'.$row, $no);
    $sheet->setCellValue('B'.$row, $h['nim']);
    $sheet->setCellValue('C'.$row, $h['nama_lengkap']);

    $totalnilai = 0;
    $klasifikasi = mysqli_query($koneksi, "SELECT * FROM v_analisa WHERE id_siswa = '$r[id_siswa]'");
    while ($n=mysqli_fetch_array($klasifikasi)){
        $crmax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT max(nilai) as nilaimax FROM v_analisa WHERE id_kriteria='$n[id_kriteria]'"));
        $himpunankriteria = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_himpunankriteria WHERE id_hk ='$n[id_hk]'"));
        $bobot = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM tbl_kriteria WHERE id = '$n[id_kriteria]'"));
        $nilaiok = $himpunankriteria['nilai'] / $crmax['nilaimax'];
        $rank = $nilaiok * $bobot['bobot'];
        $totalnilai += $rank;
    }
    $sheet->setCellValue('D'.$row, $totalnilai);
    $no++;
    $row++;
}

// Auto size kolom
foreach (range('A', $sheet->getHighestColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Output Excel
$filename = "Laporan_SPK_SAW_" . date('Y-m-d') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;