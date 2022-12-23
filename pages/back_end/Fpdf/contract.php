
<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../images/FSTAGEl.png',90,15);
        // Line break
        $this->Ln(40);
    }

    function tit($label)
    {
        // Arial 12
        $this->SetFont('Arial','U',25);
        //color
        $this->SetTextColor(112,150,255);
        // Title
        $this->Cell(0,0,"$label",0,1,'C');
        // Line break
        $this->Ln(20);
    }

    function leftinf($label1, $label2,$x_marge)
    {
        // Arial 12
        $this->SetFont('Arial','B',16);
        $this->SetTextColor(16,16,16);
        
        // Title
        $w = $this->GetStringWidth($label1);
        $this->SetX($x_marge);
        $this->Cell($w+1,0,"$label1",0,0,'L',false);
        
        // Arial 12
        $this->SetFont('Arial','I',16);
        //$this->SetTextColor(16,16,16);
        // Title
        $w = $this->GetStringWidth($label2);
        
        $this->Cell($w+1,0,"$label2",0,0,'L',false);
        

    
    }

    function desc($label)
    {
        // Arial 12
        $this->SetFont('Arial','B',16);
        //color
        $this->SetTextColor(16,16,16);
        // Title
        $this->Cell(0,0,"$label",0,2,'C');
    }


}

// // Instanciation of inherited class

// $pdf = new PDF();

// $pdf->AddPage();

// $pdf->tit("Stage Infos :");

// $pdf->leftinf("Entreprise :","ALTEN",10);
// $pdf->leftinf("Ville :","RABAT",-80);
// $pdf->Ln(20);

// $pdf->leftinf("Poste :","Developper",10);
// $pdf->leftinf("Duree de stage :","4 mois",-80);

// $pdf->Ln(40);
// $pdf->tit("Stagiaire Infos :");
// $pdf->leftinf("Nom :","YASSINE",10);
// $pdf->leftinf("Prenom :","JRAYFY",-135);
// $pdf->leftinf("CIN :","XXXXXXXX",-65);

// $pdf->Ln(20);
// $pdf->desc("Etudiant en premiere annees cycle d'ingenieur");
// $pdf->Ln(10);
// $pdf->desc("(Ingenieurie Logiciel et Integration des Systemes Informatiques)");



// $pdf->Output("MyContract.pdf","F");

?>