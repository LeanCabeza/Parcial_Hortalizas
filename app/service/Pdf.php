<?php

use Fpdf\Fpdf;

class PdfServicio extends Fpdf{
    function Header()
    {
        $this->SetFont('Times','B',10);
        $w = $this->GetStringWidth("Ventas Hortalizas")+6;
        $this->SetX((210-$w)/2);
        $this->SetDrawColor(0,110,180);
        $this->SetFillColor(230,230,0);
        $this->SetTextColor(220,50,50);
        $this->SetLineWidth(1);
        $this->Cell($w,9,"Ventas Hortalizas",1,1,'C',true);
    }

    function Footer(){
        $this->SetY(-18);
        $this->SetFont('Arial','I',10);
        $this->SetTextColor(128);
        $this->Cell(0,10,'Leandro Cabeza',0,0,'C');
    }
}

?>