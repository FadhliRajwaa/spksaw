<?php
// Test TCPDF installation
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

try {
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'TCPDF Test Successful!', 0, 1, 'C');
    $pdf->Output('test.pdf', 'D');
    echo "TCPDF is working correctly!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
