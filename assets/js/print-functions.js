/**
 * Modern Print Functions for SPK PKH System
 * Handles print functionality for all data modules
 */

// Print function for Perankingan (Ranking) data
function printPerankingan() {
    // Get the ranking table
    var tableContent = document.getElementById('rankingTable') ? 
        document.getElementById('rankingTable').outerHTML : 
        document.querySelector('.table').outerHTML;
    
    // Get summary statistics
    var statsContent = document.querySelector('.row .info-box') ? 
        document.querySelector('.row').outerHTML : '';
    
    var printWindow = window.open('', '_blank', 'width=1200,height=800');
    
    var printContent = `
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hasil Perankingan PKH - Cetak</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            body {
                font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 11px;
                line-height: 1.4;
                color: #1a1a1a;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                margin: 0;
                padding: 15px;
            }
            
            .document-container {
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                overflow: hidden;
                max-width: 100%;
            }
            
            /* Header Styling */
            .print-header {
                background: linear-gradient(135deg, #1e40af 0%, #3730a3 50%, #1e3a8a 100%);
                color: white;
                text-align: center;
                padding: 30px 25px;
                position: relative;
                overflow: hidden;
            }
            
            .print-header::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
                animation: float 30s infinite linear;
            }
            
            @keyframes float {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }
            
            .header-content {
                position: relative;
                z-index: 2;
            }
            
            .government-seal {
                width: 70px;
                height: 70px;
                background: #fff;
                border-radius: 50%;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 30px;
                font-weight: bold;
                color: #1e40af;
                box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            }
            
            .print-header h1 {
                font-size: 26px;
                font-weight: 700;
                margin: 0 0 8px 0;
                text-transform: uppercase;
                letter-spacing: 1.2px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            
            .print-header h2 {
                font-size: 18px;
                font-weight: 500;
                margin: 8px 0;
                color: #e0e7ff;
                text-transform: uppercase;
                letter-spacing: 0.8px;
            }
            
            .print-header p {
                margin: 6px 0;
                color: #cbd5e1;
                font-weight: 400;
                font-size: 12px;
            }
            
            .header-badge {
                display: inline-block;
                background: rgba(255,255,255,0.2);
                padding: 8px 16px;
                border-radius: 25px;
                font-size: 11px;
                font-weight: 500;
                margin-top: 15px;
                border: 2px solid rgba(255,255,255,0.3);
                backdrop-filter: blur(10px);
            }
            
            /* Summary Section */
            .executive-summary {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 25px;
                border-bottom: 2px solid #e2e8f0;
            }
            
            .summary-cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 20px;
                margin-bottom: 20px;
            }
            
            .summary-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border: 2px solid #e2e8f0;
                border-radius: 16px;
                padding: 25px 20px;
                text-align: center;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                position: relative;
                overflow: hidden;
                transition: transform 0.3s ease;
            }
            
            .summary-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4, #10b981);
            }
            
            .summary-card h3 {
                font-size: 32px;
                font-weight: 700;
                margin: 0 0 10px 0;
                color: #1e40af;
                text-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            
            .summary-card p {
                margin: 0;
                font-weight: 600;
                color: #475569;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.8px;
            }
            
            .summary-card.priority {
                border-color: #dc2626;
                background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            }
            
            .summary-card.priority h3 {
                color: #dc2626;
            }
            
            /* Methodology Section */
            .methodology {
                background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                border: 3px solid #3b82f6;
                border-radius: 16px;
                padding: 25px;
                margin: 25px 0;
                position: relative;
            }
            
            .methodology::before {
                content: '📊';
                position: absolute;
                top: -15px;
                left: 25px;
                background: #3b82f6;
                color: white;
                padding: 12px 16px;
                border-radius: 25px;
                font-size: 20px;
                box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
            }
            
            .methodology h4 {
                margin: 0 0 20px 0;
                color: #1e40af;
                font-size: 18px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            
            .methodology p {
                color: #374151;
                margin-bottom: 15px;
                font-weight: 500;
                font-size: 12px;
            }
            
            .methodology ul {
                margin: 20px 0;
                padding-left: 25px;
            }
            
            .methodology li {
                color: #374151;
                margin: 10px 0;
                font-weight: 500;
                position: relative;
                font-size: 11px;
            }
            
            .methodology li::before {
                content: '▶';
                color: #3b82f6;
                font-weight: bold;
                position: absolute;
                left: -20px;
                font-size: 10px;
            }
            
            /* Table Styling */
            .table-container {
                background: #ffffff;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                margin: 25px 0;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
                font-size: 11px;
            }
            
            th {
                background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
                color: #ffffff;
                font-weight: 600;
                text-align: center;
                padding: 15px 10px;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                border: none;
                position: relative;
            }
            
            th::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            }
            
            td {
                padding: 15px 10px;
                text-align: center;
                color: #374151;
                border: none;
                font-weight: 500;
                position: relative;
            }
            
            tr:nth-child(even) td {
                background-color: #f8fafc;
            }
            
            tr:nth-child(odd) td {
                background-color: #ffffff;
            }
            
            /* Ranking Badges */
            .ranking-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 35px;
                height: 35px;
                border-radius: 50%;
                font-weight: 700;
                font-size: 14px;
                color: white;
                text-shadow: 0 1px 3px rgba(0,0,0,0.3);
                box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            }
            
            /* Ranking Row Colors */
            .ranking-1 { 
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
                box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
            }
            .ranking-1 .ranking-badge { 
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); 
            }
            
            .ranking-2 { 
                background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
                box-shadow: 0 4px 15px rgba(209, 213, 219, 0.4);
            }
            .ranking-2 .ranking-badge { 
                background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); 
            }
            
            .ranking-3 { 
                background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);
                box-shadow: 0 4px 15px rgba(252, 211, 77, 0.4);
            }
            .ranking-3 .ranking-badge { 
                background: linear-gradient(135deg, #d97706 0%, #b45309 100%); 
            }
            
            /* Score Visualization */
            .score-bar {
                width: 100%;
                height: 8px;
                background: #e5e7eb;
                border-radius: 4px;
                overflow: hidden;
                margin-top: 6px;
            }
            
            .score-fill {
                height: 100%;
                background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
                border-radius: 4px;
                transition: width 0.5s ease;
            }
            
            /* Footer Styling */
            .print-footer {
                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                padding: 30px 25px;
                border-top: 4px solid #3b82f6;
                position: relative;
            }
            
            .print-footer::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 6px;
                background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
                border-radius: 0 0 15px 15px;
            }
            
            .footer-content {
                display: grid;
                grid-template-columns: 1fr auto 1fr;
                gap: 25px;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .ranking-legend {
                text-align: left;
            }
            
            .legend-item {
                display: flex;
                align-items: center;
                margin: 10px 0;
                font-size: 11px;
                font-weight: 500;
            }
            
            .legend-color {
                width: 25px;
                height: 15px;
                border-radius: 8px;
                margin-right: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .legend-gold { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
            .legend-silver { background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); }
            .legend-bronze { background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%); }
            
            .footer-logo {
                text-align: center;
            }
            
            .footer-seal {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
                border-radius: 50%;
                margin: 0 auto 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 22px;
                box-shadow: 0 6px 15px rgba(30, 64, 175, 0.3);
            }
            
            .footer-info {
                text-align: right;
                font-size: 10px;
                color: #64748b;
            }
            
            .footer-disclaimer {
                font-size: 10px;
                color: #64748b;
                margin-top: 20px;
                padding-top: 20px;
                border-top: 2px solid #e2e8f0;
                text-align: center;
            }
            
            .footer-disclaimer strong {
                color: #374151;
                font-weight: 600;
            }
            
            /* Print-specific styles */
            @media print {
                body { margin: 0; padding: 10px; }
                .document-container { box-shadow: none; }
                
                /* Hide action columns */
                th:last-child, td:last-child {
                    display: none !important;
                }
            }
            
            @page {
                size: A4 portrait;
                margin: 0.8cm;
            }
        </style>
    </head>
    <body>
        <div class="document-container">
            <div class="print-header">
                <div class="header-content">
                    <div class="government-seal">🏛️</div>
                    <h1>HASIL PERANKINGAN PENERIMA PKH</h1>
                    <h2>Metode Simple Additive Weighting (SAW)</h2>
                    <p>Sistem Pendukung Keputusan Program Keluarga Harapan</p>
                    <p><strong>Dinas Sosial Republik Indonesia</strong></p>
                    <div class="header-badge">Dicetak pada: ${new Date().toLocaleString('id-ID')}</div>
                </div>
            </div>
            
            <div class="executive-summary">
                <div class="summary-cards">
                    <div class="summary-card priority">
                        <h3 id="total-candidates">-</h3>
                        <p>Total Kandidat</p>
                    </div>
                    <div class="summary-card">
                        <h3 id="high-priority">-</h3>
                        <p>Prioritas Tinggi</p>
                    </div>
                    <div class="summary-card">
                        <h3 id="medium-priority">-</h3>
                        <p>Prioritas Menengah</p>
                    </div>
                    <div class="summary-card">
                        <h3 id="low-priority">-</h3>
                        <p>Prioritas Rendah</p>
                    </div>
                </div>
            </div>
        
            <div class="methodology">
                <h4>📊 METODE PERHITUNGAN SAW</h4>
                <p><strong>Simple Additive Weighting (SAW)</strong> menggunakan kriteria penilaian dengan bobot yang telah ditentukan:</p>
                <ul>
                    <li><strong>Jumlah Lansia</strong> - Bobot: 15% (Kriteria Benefit)</li>
                    <li><strong>Jumlah Disabilitas Berat</strong> - Bobot: 20% (Kriteria Benefit)</li>
                    <li><strong>Jumlah Anak SD</strong> - Bobot: 15% (Kriteria Benefit)</li>
                    <li><strong>Jumlah Anak SMP</strong> - Bobot: 10% (Kriteria Benefit)</li>
                    <li><strong>Jumlah Anak SMA</strong> - Bobot: 10% (Kriteria Benefit)</li>
                    <li><strong>Jumlah Balita</strong> - Bobot: 15% (Kriteria Benefit)</li>
                    <li><strong>Jumlah Ibu Hamil</strong> - Bobot: 15% (Kriteria Benefit)</li>
                </ul>
                <p><em>Semakin tinggi nilai pada setiap kriteria, semakin tinggi prioritas untuk menerima bantuan PKH.</em></p>
            </div>
            
            <div class="table-container">
                ${tableContent.replace(/<table/g, '<table id="enhanced-ranking-table"')}
            </div>
        
            <div class="print-footer">
                <div class="footer-content">
                    <div class="ranking-legend">
                        <h4 style="margin: 0 0 15px 0; color: #374151; font-size: 14px; font-weight: 600;">KETERANGAN RANKING:</h4>
                        <div class="legend-item">
                            <div class="legend-color legend-gold"></div>
                            <span>Ranking 1: Prioritas Sangat Tinggi</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-silver"></div>
                            <span>Ranking 2: Prioritas Tinggi</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-bronze"></div>
                            <span>Ranking 3: Prioritas Menengah</span>
                        </div>
                    </div>
                    
                    <div class="footer-logo">
                        <div class="footer-seal">🇮🇩</div>
                        <p style="margin: 0; font-size: 12px; font-weight: 600; color: #374151;">REPUBLIK INDONESIA</p>
                    </div>
                    
                    <div class="footer-info">
                        <p style="margin: 0; font-weight: 600;">Dokumen Resmi</p>
                        <p style="margin: 8px 0;">No. Dokumen: PKH-${new Date().getFullYear()}-${String(new Date().getMonth() + 1).padStart(2, '0')}-${String(new Date().getDate()).padStart(2, '0')}</p>
                        <p style="margin: 0;">Tgl. Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
                
                <div class="footer-disclaimer">
                    <p style="margin: 0;"><strong>DISCLAIMER:</strong> Dokumen ini dihasilkan secara otomatis oleh Sistem Pendukung Keputusan PKH. 
                    Hasil perhitungan menggunakan metode SAW berdasarkan data yang telah terverifikasi. 
                    Untuk informasi lebih lanjut, hubungi Dinas Sosial setempat.</p>
                    <br>
                    <p style="margin: 0;"><strong>© ${new Date().getFullYear()} Dinas Sosial Republik Indonesia</strong> - Sistem PKH v2.0</p>
                </div>
            </div>
        </div>
        
        <script>
            // Enhanced table styling after load
            setTimeout(function() {
                const table = document.getElementById('enhanced-ranking-table');
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach((row, index) => {
                        const rankCell = row.cells[0];
                        if (rankCell) {
                            const rankText = rankCell.textContent.trim();
                            const rank = parseInt(rankText.replace('#', ''));
                            
                            // Add ranking badge
                            rankCell.innerHTML = '<div class="ranking-badge">' + rank + '</div>';
                            
                            // Add row styling based on rank
                            if (rank === 1) row.classList.add('ranking-1');
                            else if (rank === 2) row.classList.add('ranking-2');
                            else if (rank === 3) row.classList.add('ranking-3');
                            
                            // Add score visualization to score column
                            const scoreCell = row.cells[2]; // Total Nilai column
                            if (scoreCell && !isNaN(parseFloat(scoreCell.textContent))) {
                                const score = parseFloat(scoreCell.textContent);
                                const maxScore = 2.0; // Based on the data shown
                                const percentage = (score / maxScore) * 100;
                                
                                scoreCell.innerHTML = scoreCell.innerHTML + 
                                    '<div class="score-bar"><div class="score-fill" style="width: ' + percentage + '%"></div></div>';
                            }
                        }
                    });
                    
                    // Update summary statistics
                    document.getElementById('total-candidates').textContent = rows.length;
                    document.getElementById('high-priority').textContent = Math.min(2, rows.length);
                    document.getElementById('medium-priority').textContent = Math.min(3, Math.max(0, rows.length - 2));
                    document.getElementById('low-priority').textContent = Math.max(0, rows.length - 5);
                }
            }, 200);
        </script>
    </body>
    </html>`;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 1000);
}

// Print function for Data Warga
function printWarga() {
    var tableContent = document.getElementById('wargaTable') ? 
        document.getElementById('wargaTable').outerHTML : 
        document.querySelector('.table').outerHTML;
    
    var printWindow = window.open('', '_blank', 'width=1200,height=800');
    
    var printContent = `
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Warga PKH - Cetak</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            body {
                font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 11px;
                line-height: 1.4;
                color: #1a1a1a;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                margin: 0;
                padding: 15px;
            }
            
            .document-container {
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                overflow: hidden;
                max-width: 100%;
            }
            
            .print-header {
                background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
                color: white;
                text-align: center;
                padding: 30px 25px;
                position: relative;
            }
            
            .government-seal {
                width: 70px;
                height: 70px;
                background: #fff;
                border-radius: 50%;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 30px;
                color: #059669;
                box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            }
            
            .print-header h1 {
                font-size: 26px;
                font-weight: 700;
                margin: 0 0 8px 0;
                text-transform: uppercase;
                letter-spacing: 1.2px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            
            .table-container {
                background: #ffffff;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                margin: 25px;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
            }
            
            th {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                color: white;
                font-weight: 600;
                text-align: center;
                padding: 15px 8px;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            td {
                padding: 12px 8px;
                text-align: center;
                color: #374151;
                font-weight: 500;
            }
            
            tr:nth-child(even) td {
                background-color: #f8fafc;
            }
            
            tr:nth-child(odd) td {
                background-color: #ffffff;
            }
            
            .print-footer {
                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                padding: 25px;
                text-align: center;
                border-top: 3px solid #059669;
            }
            
            @media print {
                body { margin: 0; padding: 10px; }
                .document-container { box-shadow: none; }
                th:last-child, td:last-child { display: none !important; }
            }
            
            @page {
                size: A4 landscape;
                margin: 0.8cm;
            }
        </style>
    </head>
    <body>
        <div class="document-container">
            <div class="print-header">
                <div class="government-seal">👥</div>
                <h1>DATA WARGA CALON PENERIMA PKH</h1>
                <h2>Program Keluarga Harapan</h2>
                <p><strong>Dinas Sosial Republik Indonesia</strong></p>
                <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
            </div>
            
            <div class="table-container">
                ${tableContent}
            </div>
            
            <div class="print-footer">
                <p><strong>© ${new Date().getFullYear()} Dinas Sosial Republik Indonesia</strong> - Sistem PKH v2.0</p>
            </div>
        </div>
    </body>
    </html>`;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 1000);
}
