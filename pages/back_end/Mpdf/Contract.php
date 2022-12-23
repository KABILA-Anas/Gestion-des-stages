<?php
    
    require_once __DIR__ . '/vendor/autoload.php';

    $mpdf = new mPDF();
    $mpdf->WriteHTML('<h1 style="color:red">hh</h1>');
    $mpdf->Output("Contract.pdf");
?>