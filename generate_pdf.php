<?php
require('fpdf/fpdf.php');

// Start session to access session variables
session_start();

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo path
        $logoPath = 'images/logo.png';  // Adjust this path if necessary

        // Add the logo with 10px by 10px dimensions (2.64583 mm)
        $this->Image($logoPath, 10, 10, 10, 10); // X, Y, Width, Height in mm

        // Add the text next to the logo
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY( 20,10); // Adjust X position to be right of the logo with 20px (5.08mm) spacing
        $this->Cell(0, 10, 'ZackProg', 0, 1, 'L');

        // Add a title
        $this->Ln(20); // Line break after header
        $this->SetFont('Arial', 'B', 16);
        $this->SetFillColor(0, 102, 204); // Blue background
        $this->SetTextColor(255, 255, 255); // White text
        $this->Cell(0, 10, 'Expense Report', 0, 1, 'C', true);
        $this->SetTextColor(0); // Reset text color to black
        $this->Ln(10); // Line break after title
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128); // Gray color
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Function to create a colored cell
    function ColoredCell($width, $height, $text, $border = 1, $ln = 0, $align = 'L', $fill = false)
    {
        $this->SetFillColor(200, 220, 255); // Light blue background
        $this->SetTextColor(0); // Black text
        $this->SetFont('Arial', '', 12);
        $this->Cell($width, $height, $text, $border, $ln, $align, $fill);
    }

    // Function to create table header
    function TableHeader()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(0, 102, 204); // Blue background
        $this->SetTextColor(255); // White text
        $this->Cell(120, 10, 'Title', 1, 0, 'C', true);
        $this->Cell(70, 10, 'Amount', 1, 1, 'C', true);
        $this->SetTextColor(0); // Reset text color
    }

    // Function to create table rows
    function TableRow($title, $amount)
    {
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(240, 240, 240); // Light gray background
        $this->SetTextColor(0); // Black text
        $this->Cell(120, 10, $title, 1, 0, 'L', true);
        $this->Cell(70, 10, number_format($amount, 2), 1, 1, 'R', true);
    }
}

// Create instance of FPDF class
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Add budget and expenses
if (isset($_SESSION['total_amount'], $_SESSION['expenditure_value'], $_SESSION['balance_amount'], $_SESSION['expenses'])) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 220, 255); // Light blue background
    $pdf->SetTextColor(0); // Black text
    $pdf->Cell(0, 10, 'Total Budget: ' . number_format($_SESSION['total_amount'], 2), 0, 1, 'L', true);
    $pdf->Cell(0, 10, 'Expenditure Value: ' . number_format($_SESSION['expenditure_value'], 2), 0, 1, 'L', true);
    $pdf->Cell(0, 10, 'Balance Amount: ' . number_format($_SESSION['balance_amount'], 2), 0, 1, 'L', true);
    $pdf->Ln(10); // Line break

    // Set up table for expenses
    $pdf->TableHeader();

    // Add each expense to the table
    foreach ($_SESSION['expenses'] as $expense) {
        $pdf->TableRow($expense['title'], $expense['amount']);
    }
} else {
    // Handle case where session data is not available
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetTextColor(128); // Gray text
    $pdf->Cell(0, 10, 'No data available to generate the report.', 0, 1, 'C');
}

// Output PDF
$pdf->Output('I', 'expenses_report.pdf');
?>



