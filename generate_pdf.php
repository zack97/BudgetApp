<?php
require('fpdf/fpdf.php');
session_start();

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Title
        $this->Cell(0, 10, 'Budget Report', 0, 1, 'C');
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }

    // Load data
    function LoadData($data)
    {
        return $data;
    }

    // Simple table
    function BasicTable($header, $data)
    {
        // Header
        foreach($header as $col)
            $this->Cell(40, 7, $col, 1);
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40, 6, $col, 1);
            $this->Ln();
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Column headings
$header = array('Title', 'Amount');
// Data loading
$data = [];
foreach ($_SESSION['expenses'] as $expense) {
    $data[] = [$expense['title'], $expense['amount']];
}

$pdf->Cell(0, 10, 'Total Budget: ' . $_SESSION['total_amount'], 0, 1);
$pdf->Cell(0, 10, 'Total Expenses: ' . $_SESSION['expenditure_value'], 0, 1);
$pdf->Cell(0, 10, 'Balance: ' . $_SESSION['balance_amount'], 0, 1);
$pdf->Ln(10);

$pdf->BasicTable($header, $data);
$pdf->Output();
?>
