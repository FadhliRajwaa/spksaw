<?php
// Simplified ranking module for testing
?>

<div class="col-xs-12">  
  <div class="box">
    <div class="box-header">
      <h3 class="box-title"><i class="fa fa-trophy"></i> Perankingan PKH SAW - TEST</h3>
    </div>
    <div class="box-body">
      <div class="alert alert-info">
        <h4>🎯 Sistem Perankingan PKH SAW</h4>
        <p>Modul ini menampilkan hasil perankingan menggunakan metode Simple Additive Weighting (SAW).</p>
        <p><strong>Session Info:</strong></p>
        <ul>
          <li>Username: <?php echo $_SESSION['username'] ?? 'NOT SET'; ?></li>
          <li>Level: <?php echo $_SESSION['leveluser'] ?? 'NOT SET'; ?></li>
        </ul>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <button class="btn btn-primary">
            <i class="fa fa-refresh"></i> Generate Ranking Baru (Coming Soon)
          </button>
          <button class="btn btn-danger">
            <i class="fa fa-file-pdf-o"></i> Export PDF (Coming Soon)
          </button>
        </div>
      </div>
      <br>
      
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr style='background-color:#3c8dbc; color:white;'>
              <th>Rank</th>
              <th>Nama Warga</th>
              <th>Alamat</th>
              <th>Score SAW</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="5" class="text-center">
                <p>Module Perankingan berhasil dimuat!</p>
                <p>Fungsi perhitungan SAW akan diimplementasikan selanjutnya.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
    </div>
  </div>
</div>
