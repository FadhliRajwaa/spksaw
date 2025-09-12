<?php
// Session is already started in media_admin.php, so don't start again

if(empty($_SESSION['username']) and empty($_SESSION['password'])){
    echo "<link href='style.css' rel='stylesheet' type='text/css'>
    <center>Untuk mengakses modul, Anda harus login <br>";
    echo "<a href='../../index.php'><b>LOGIN</b></a></center>";
} else {

switch($_GET['act']){
    default:
?>

<div class="col-xs-12">  
  <div class="box">
    <div class="box-header">
      <h3 class="box-title"><i class="fa fa-trophy"></i> Perankingan PKH SAW</h3>
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <a href="?module=ranking&act=generate" class="btn btn-primary">
            <i class="fa fa-refresh"></i> Generate Ranking Baru
          </a>
          <a href="?module=ranking&act=export_pdf" class="btn btn-danger">
            <i class="fa fa-file-pdf-o"></i> Export PDF
          </a>
        </div>
      </div>
      <br>
      
      <?php
      // Tampilkan hasil ranking SAW
      include "../../../configurasi/koneksi.php";
      
      echo "<div class='table-responsive'>";
      echo "<table class='table table-bordered table-striped' id='example1'>";
      echo "<thead>";
      echo "<tr style='background-color:#3c8dbc; color:white;'>";
      echo "<th width='5%'>Rank</th>";
      echo "<th width='25%'>Nama Warga</th>";
      echo "<th width='30%'>Alamat</th>";
      echo "<th width='15%'>Score SAW</th>";
      echo "<th width='15%'>Status</th>";
      echo "<th width='10%'>Aksi</th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      
      // Query untuk mendapatkan ranking berdasarkan score SAW
      $query = "SELECT w.*, 
                COALESCE(h.score_saw, 0) as score_saw,
                CASE 
                    WHEN COALESCE(h.score_saw, 0) >= 0.7 THEN 'Sangat Layak'
                    WHEN COALESCE(h.score_saw, 0) >= 0.5 THEN 'Layak' 
                    WHEN COALESCE(h.score_saw, 0) >= 0.3 THEN 'Cukup Layak'
                    ELSE 'Kurang Layak'
                END as status_kelayakan
                FROM data_warga w 
                LEFT JOIN tbl_hasil_saw h ON w.id_warga = h.id_warga 
                ORDER BY score_saw DESC, w.nama_lengkap ASC";
                
      $result = mysqli_query($koneksi, $query);
      $no = 1;
      
      if(mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_array($result)) {
              $rank_class = '';
              if($no == 1) $rank_class = 'success'; // Hijau untuk rank 1
              else if($no <= 3) $rank_class = 'info'; // Biru untuk rank 2-3
              else if($row['score_saw'] >= 0.5) $rank_class = 'warning'; // Kuning untuk layak
              else $rank_class = 'danger'; // Merah untuk kurang layak
              
              echo "<tr class='$rank_class'>";
              echo "<td><strong>$no</strong></td>";
              echo "<td><strong>{$row['nama_lengkap']}</strong></td>";
              echo "<td>{$row['alamat']}</td>";
              echo "<td><span class='badge bg-blue'>" . number_format($row['score_saw'], 4) . "</span></td>";
              echo "<td><span class='label label-" . ($row['score_saw'] >= 0.5 ? 'success' : 'default') . "'>{$row['status_kelayakan']}</span></td>";
              echo "<td>";
              echo "<a href='?module=warga&act=detail&id={$row['id_warga']}' class='btn btn-xs btn-info'>";
              echo "<i class='fa fa-eye'></i> Detail</a>";
              echo "</td>";
              echo "</tr>";
              $no++;
          }
      } else {
          echo "<tr><td colspan='6' class='text-center'>Belum ada data ranking. Klik 'Generate Ranking Baru' untuk memulai.</td></tr>";
      }
      
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
      ?>
      
    </div>
  </div>
</div>

<?php
    break;
    
    case "generate":
        include "../../../configurasi/koneksi.php";
        
        echo "<div class='col-xs-12'>";
        echo "<div class='box'>";
        echo "<div class='box-header'>";
        echo "<h3 class='box-title'><i class='fa fa-cogs'></i> Generate Ranking SAW</h3>";
        echo "</div>";
        echo "<div class='box-body'>";
        
        // Clear existing results
        mysqli_query($koneksi, "DELETE FROM tbl_hasil_saw");
        
        // Get all warga data
        $warga_query = mysqli_query($koneksi, "SELECT * FROM data_warga");
        
        // Get criteria weights
        $kriteria_query = mysqli_query($koneksi, "SELECT * FROM tbl_kriteria ORDER BY id_kriteria");
        $kriteria = [];
        while($k = mysqli_fetch_array($kriteria_query)) {
            $kriteria[] = $k;
        }
        
        echo "<h4>Proses Perhitungan SAW:</h4>";
        echo "<ol>";
        
        // Step 1: Normalization
        echo "<li><strong>Normalisasi Matrix Keputusan</strong><br>";
        
        // Find max values for each criteria (assuming all criteria are benefit type)
        $max_values = [];
        foreach($kriteria as $k) {
            $field_map = [
                1 => 'jumlah_lansia',
                2 => 'jumlah_disabilitas_berat', 
                3 => 'jumlah_anak_sd',
                4 => 'jumlah_anak_smp',
                5 => 'jumlah_anak_sma',
                6 => 'jumlah_balita',
                7 => 'jumlah_ibu_hamil'
            ];
            
            $field = isset($field_map[$k['id_kriteria']]) ? $field_map[$k['id_kriteria']] : 'jumlah_lansia';
            $max_query = mysqli_query($koneksi, "SELECT MAX($field) as max_val FROM data_warga");
            $max_row = mysqli_fetch_array($max_query);
            $max_values[$k['id_kriteria']] = $max_row['max_val'] > 0 ? $max_row['max_val'] : 1;
        }
        
        echo "Max values ditemukan untuk normalisasi.</li>";
        
        // Step 2: Calculate SAW score for each warga
        echo "<li><strong>Perhitungan Score SAW</strong><br>";
        
        $warga_scores = [];
        mysqli_data_seek($warga_query, 0); // Reset pointer
        
        while($warga = mysqli_fetch_array($warga_query)) {
            $saw_score = 0;
            
            foreach($kriteria as $k) {
                $field_map = [
                    1 => 'jumlah_lansia',
                    2 => 'jumlah_disabilitas_berat', 
                    3 => 'jumlah_anak_sd',
                    4 => 'jumlah_anak_smp',
                    5 => 'jumlah_anak_sma',
                    6 => 'jumlah_balita',
                    7 => 'jumlah_ibu_hamil'
                ];
                
                $field = isset($field_map[$k['id_kriteria']]) ? $field_map[$k['id_kriteria']] : 'jumlah_lansia';
                $nilai = $warga[$field];
                
                // Normalization (benefit criteria)
                $normalized = $max_values[$k['id_kriteria']] > 0 ? $nilai / $max_values[$k['id_kriteria']] : 0;
                
                // Weighted score
                $weighted_score = $normalized * ($k['bobot'] / 100);
                $saw_score += $weighted_score;
            }
            
            // Insert hasil SAW ke database
            $insert_query = "INSERT INTO tbl_hasil_saw (id_warga, score_saw, tanggal_hitung) 
                           VALUES ('{$warga['id_warga']}', '$saw_score', NOW())";
            mysqli_query($koneksi, $insert_query);
            
            $warga_scores[] = ['nama' => $warga['nama_lengkap'], 'score' => $saw_score];
        }
        
        echo count($warga_scores) . " data warga berhasil dihitung.</li>";
        echo "</ol>";
        
        echo "<div class='alert alert-success'>";
        echo "<i class='fa fa-check'></i> <strong>Ranking berhasil di-generate!</strong><br>";
        echo "Silakan kembali ke halaman ranking untuk melihat hasilnya.";
        echo "</div>";
        
        echo "<a href='?module=ranking' class='btn btn-primary'><i class='fa fa-arrow-left'></i> Kembali ke Ranking</a>";
        
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
    break;
    
    case "export_pdf":
        include "../../../configurasi/koneksi.php";
        
        // Load Dompdf when needed
        $vendor_path = __DIR__ . '/../../../vendor/autoload.php';
        if(file_exists($vendor_path)) {
            require_once $vendor_path;
            $options = new \Dompdf\Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new \Dompdf\Dompdf($options);
        } else {
            die("Error: DOMPDF library not found. Please install composer dependencies.");
        }
        
        $html = '<html><head><title>Laporan Ranking PKH SAW</title>';
        $html .= '<style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
                    .subtitle { font-size: 14px; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
                    .rank1 { background-color: #d4edda; }
                    .rank2-3 { background-color: #d1ecf1; }
                    .layak { background-color: #fff3cd; }
                    .kurang-layak { background-color: #f8d7da; }
                  </style>';
        $html .= '</head><body>';
        
        $html .= '<div class="header">';
        $html .= '<div class="title">LAPORAN PERANKINGAN PENERIMA BANTUAN PKH</div>';
        $html .= '<div class="subtitle">Menggunakan Metode Simple Additive Weighting (SAW)</div>';
        $html .= '<div>Tanggal: ' . date('d F Y') . '</div>';
        $html .= '</div>';
        
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th width="8%">Rank</th>';
        $html .= '<th width="25%">Nama Lengkap</th>';
        $html .= '<th width="35%">Alamat</th>';
        $html .= '<th width="12%">Score SAW</th>';
        $html .= '<th width="20%">Status Kelayakan</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $query = "SELECT w.*, 
                  COALESCE(h.score_saw, 0) as score_saw,
                  CASE 
                      WHEN COALESCE(h.score_saw, 0) >= 0.7 THEN 'Sangat Layak'
                      WHEN COALESCE(h.score_saw, 0) >= 0.5 THEN 'Layak' 
                      WHEN COALESCE(h.score_saw, 0) >= 0.3 THEN 'Cukup Layak'
                      ELSE 'Kurang Layak'
                  END as status_kelayakan
                  FROM data_warga w 
                  LEFT JOIN tbl_hasil_saw h ON w.id_warga = h.id_warga 
                  ORDER BY score_saw DESC, w.nama_lengkap ASC";
                  
        $result = mysqli_query($koneksi, $query);
        $no = 1;
        
        while($row = mysqli_fetch_array($result)) {
            $class = '';
            if($no == 1) $class = 'rank1';
            else if($no <= 3) $class = 'rank2-3'; 
            else if($row['score_saw'] >= 0.5) $class = 'layak';
            else $class = 'kurang-layak';
            
            $html .= '<tr class="' . $class . '">';
            $html .= '<td style="text-align: center;">' . $no . '</td>';
            $html .= '<td>' . $row['nama_lengkap'] . '</td>';
            $html .= '<td>' . $row['alamat'] . '</td>';
            $html .= '<td style="text-align: center;">' . number_format($row['score_saw'], 4) . '</td>';
            $html .= '<td style="text-align: center;">' . $row['status_kelayakan'] . '</td>';
            $html .= '</tr>';
            $no++;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $html .= '<div style="margin-top: 30px; font-size: 10px;">';
        $html .= '<p><strong>Keterangan Warna:</strong></p>';
        $html .= '<p>🟢 Hijau: Peringkat 1 (Prioritas Utama)</p>';
        $html .= '<p>🔵 Biru: Peringkat 2-3 (Prioritas Tinggi)</p>';
        $html .= '<p>🟡 Kuning: Layak PKH (Score ≥ 0.5)</p>';
        $html .= '<p>🔴 Merah: Kurang Layak PKH (Score < 0.5)</p>';
        $html .= '</div>';
        
        $html .= '</body></html>';
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'Laporan_Ranking_PKH_SAW_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, array('Attachment' => true));
        
    break;
}
}
?>
