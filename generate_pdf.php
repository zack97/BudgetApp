<?php
require('fpdf/fpdf.php');

// Start session to access session variables
session_start();

class PDF extends FPDF
{
    function Header()
    {
        // Logo path
        $logoPath = 'images/logo.png';  // Adjust this path if necessary

        // Add the logo
        $this->Image($logoPath, 10, 10, 10, 10); // X, Y, Width, Height in mm

        // Add the text next to the logo
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(20, 10); // Adjust X position to be right of the logo
        $this->Cell(0, 10, 'ZackProg', 0, 1, 'L');

        // Add a title
        $this->Ln(20); // Line break after header
        $this->Cell(0, 10, 'Expense Report', 0, 1, 'C');
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterTitle($num, $label)
    {
        // Arial 12
        $this->SetFont('Arial', 'B', 12);
        // Background color
        $this->SetFillColor(200, 220, 255);
        // Title
        $this->Cell(0, 10, 'Chapter ' . $num . ' : ' . $label, 0, 1, 'L', true);
        // Line break
        $this->Ln(4);
    }

    function ChapterBody($body)
    {
        // Read text file
        $this->SetFont('Arial', '', 12);
        // Output justified text
        $this->MultiCell(0, 10, $body);
        // Line break
        $this->Ln();
    }
}

// Create instance of FPDF class
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Check if session data is set
if (isset($_SESSION['total_amount'], $_SESSION['expenditure_value'], $_SESSION['balance_amount'], $_SESSION['expenses'])) {
    // Add budget and expenses
    $pdf->Cell(0, 10, 'Total Budget: ' . $_SESSION['total_amount'], 0, 1);
    $pdf->Cell(0, 10, 'Expenditure Value: ' . $_SESSION['expenditure_value'], 0, 1);
    $pdf->Cell(0, 10, 'Balance Amount: ' . $_SESSION['balance_amount'], 0, 1);
    $pdf->Ln(10); // Line break

    // Set up table for expenses
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 10, 'Title', 1);
    $pdf->Cell(0, 10, 'Amount', 1, 1, 'C');

    // Set font for table data
    $pdf->SetFont('Arial', '', 12);

    // Add each expense to the table
    foreach ($_SESSION['expenses'] as $expense) {
        $pdf->Cell(90, 10, $expense['title'], 1);
        $pdf->Cell(0, 10, $expense['amount'], 1, 1, 'C');
    }
} else {
    // Handle case where session data is not available
    $pdf->Cell(0, 10, 'No data available to generate the report.', 0, 1);
}

// Output PDF
$pdf->Output();
?>
