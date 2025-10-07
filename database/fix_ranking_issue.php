<?php
include('../configurasi/koneksi.php');

echo "<h2>Debug dan Perbaikan Ranking Issue</h2>";

// Check current ranking data
echo "<h3>1. Checking Current Ranking Data:</h3>";
$current_rankings = mysqli_query($koneksi, "
    SELECT id_hasil, nama_warga, skor_akhir, ranking, rekomendasi 
    FROM tbl_hasil_saw 
    ORDER BY ranking ASC
");

if ($current_rankings && mysqli_num_rows($current_rankings) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    echo "<tr style='background-color: #f0f0f0;'>
            <th>ID</th>
            <th>Nama Warga</th>
            <th>Skor Akhir</th>
            <th>Ranking Saat Ini</th>
            <th>Rekomendasi</th>
          </tr>";
    
    $found_issue = false;
    $expected_rank = 1;
    
    while($row = mysqli_fetch_array($current_rankings)) {
        $row_color = '';
        if ($row['ranking'] != $expected_rank) {
            $row_color = 'style="background-color: #ffcccc;"'; // Red highlight for issues
            $found_issue = true;
        }
        
        echo "<tr $row_color>
                <td>{$row['id_hasil']}</td>
                <td>{$row['nama_warga']}</td>
                <td>" . number_format($row['skor_akhir'], 4) . "</td>
                <td>{$row['ranking']}</td>
                <td>{$row['rekomendasi']}</td>
              </tr>";
        $expected_rank++;
    }
    echo "</table>";
    
    if ($found_issue) {
        echo "<p style='color: red;'>❌ <strong>ISSUE FOUND:</strong> Rankings don't start from 1 or have gaps!</p>";
    } else {
        echo "<p style='color: green;'>✅ Rankings appear to be correct</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ No ranking data found in tbl_hasil_saw</p>";
}

// Check data ordered by score
echo "<h3>2. Data Ordered by Score (Should be ranking order):</h3>";
$score_ordered = mysqli_query($koneksi, "
    SELECT id_hasil, nama_warga, skor_akhir, ranking, rekomendasi 
    FROM tbl_hasil_saw 
    ORDER BY skor_akhir DESC
");

if ($score_ordered && mysqli_num_rows($score_ordered) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    echo "<tr style='background-color: #f0f0f0;'>
            <th>Expected Rank</th>
            <th>Nama Warga</th>
            <th>Skor Akhir</th>
            <th>Current Ranking</th>
            <th>Should Be</th>
          </tr>";
    
    $should_be_rank = 1;
    while($row = mysqli_fetch_array($score_ordered)) {
        $match_color = ($row['ranking'] == $should_be_rank) ? 'style="background-color: #ccffcc;"' : 'style="background-color: #ffcccc;"';
        
        echo "<tr $match_color>
                <td>{$should_be_rank}</td>
                <td>{$row['nama_warga']}</td>
                <td>" . number_format($row['skor_akhir'], 4) . "</td>
                <td>{$row['ranking']}</td>
                <td>{$should_be_rank}</td>
              </tr>";
        $should_be_rank++;
    }
    echo "</table>";
}

// Fix the ranking issue
echo "<h3>3. Fixing Ranking Issue:</h3>";

if (isset($_GET['fix']) && $_GET['fix'] == 'yes') {
    echo "<p>🔄 Starting ranking fix...</p>";
    
    // Get all results ordered by score DESC (highest score = rank 1)
    $results = mysqli_query($koneksi, "
        SELECT id_hasil, nama_warga, skor_akhir 
        FROM tbl_hasil_saw 
        ORDER BY skor_akhir DESC
    ");
    
    if ($results) {
        $rank = 1;
        $fixed_count = 0;
        
        while($row = mysqli_fetch_array($results)) {
            $update_query = "UPDATE tbl_hasil_saw SET ranking = $rank WHERE id_hasil = {$row['id_hasil']}";
            $update_result = mysqli_query($koneksi, $update_query);
            
            if ($update_result) {
                echo "<p style='color: green;'>✅ Updated {$row['nama_warga']} to rank $rank (Score: " . number_format($row['skor_akhir'], 4) . ")</p>";
                $fixed_count++;
            } else {
                echo "<p style='color: red;'>❌ Failed to update {$row['nama_warga']}: " . mysqli_error($koneksi) . "</p>";
            }
            
            $rank++;
        }
        
        echo "<p style='color: blue;'><strong>✅ Ranking fix completed! Updated $fixed_count records.</strong></p>";
        
        // Update recommendations based on new rankings
        $total_warga = $fixed_count;
        $top_30_percent = ceil($total_warga * 0.3);
        
        // Reset all to 'Tidak' first
        mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET rekomendasi = 'Tidak'");
        
        // Set top 30% to 'Ya'
        mysqli_query($koneksi, "UPDATE tbl_hasil_saw SET rekomendasi = 'Ya' WHERE ranking <= $top_30_percent");
        
        echo "<p style='color: blue;'>✅ Updated recommendations: Top $top_30_percent warga (30%) set to 'Ya'</p>";
        
        echo "<p><a href='fix_ranking_issue.php' style='background-color: #007cba; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>🔄 Refresh to See Results</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Error retrieving data: " . mysqli_error($koneksi) . "</p>";
    }
    
} else {
    echo "<p>Click the button below to fix the ranking issue:</p>";
    echo "<p><a href='fix_ranking_issue.php?fix=yes' 
             style='background-color: #d9534f; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'
             onclick='return confirm(\"Are you sure you want to fix the rankings? This will recalculate all rankings starting from 1.\");'>
             🔧 Fix Rankings Now
          </a></p>";
}

// Show final verification
if (isset($_GET['fix']) && $_GET['fix'] == 'yes') {
    echo "<h3>4. Final Verification:</h3>";
    
    $verify_query = mysqli_query($koneksi, "
        SELECT COUNT(*) as total,
               MIN(ranking) as min_rank,
               MAX(ranking) as max_rank,
               COUNT(DISTINCT ranking) as unique_ranks
        FROM tbl_hasil_saw
    ");
    
    if ($verify_query) {
        $verify = mysqli_fetch_array($verify_query);
        
        echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
        echo "<tr style='background-color: #f0f0f0;'>
                <th>Total Records</th>
                <th>Min Rank</th>
                <th>Max Rank</th>
                <th>Unique Ranks</th>
                <th>Status</th>
              </tr>";
        
        $status = "✅ CORRECT";
        $status_color = "green";
        
        if ($verify['min_rank'] != 1) {
            $status = "❌ Min rank is not 1";
            $status_color = "red";
        } elseif ($verify['max_rank'] != $verify['total']) {
            $status = "❌ Max rank doesn't match total";
            $status_color = "red";
        } elseif ($verify['unique_ranks'] != $verify['total']) {
            $status = "❌ Duplicate ranks found";
            $status_color = "red";
        }
        
        echo "<tr>
                <td>{$verify['total']}</td>
                <td>{$verify['min_rank']}</td>
                <td>{$verify['max_rank']}</td>
                <td>{$verify['unique_ranks']}</td>
                <td style='color: $status_color;'><strong>$status</strong></td>
              </tr>";
        echo "</table>";
    }
}

echo "<br><h3>Navigation:</h3>";
echo "<p>
        <a href='../administrator/media_admin.php?module=perankingan' style='background-color: #5cb85c; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; margin-right: 10px;'>
            📊 View Rankings
        </a>
        <a href='../administrator/media_admin.php?module=laporan&act=hitung_saw' style='background-color: #5bc0de; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; margin-right: 10px;'>
            🔄 Recalculate SAW
        </a>
        <a href='fix_kriteria_structure.php' style='background-color: #f0ad4e; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px;'>
            🔧 Database Tools
        </a>
      </p>";

?>
