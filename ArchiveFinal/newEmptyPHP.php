<?php
require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('logofin.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(40,10,'Truck Booking',1,0,'C');
    // Line break
    $this->Ln(20);
}


}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=20;$i++){
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);
    $pdf->Cell(0,10,'Printing line number '.$i,0,100);
}
    

//$buf = $pdf->get_buffer();
//$len = strlen($buf);
    
//header("Content-type: application/pdf");
//    header("Content-Length: $len");
//    header("Content-Disposition: attachment; filename=hello.pdf");
    
    //print $buf;
//$pdf->Output("hello.pdf",'D');
$pdf->Output();
?>