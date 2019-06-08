<?php
include_once "../config/database.php";
include_once "../classes/request.php";
require("../util/fpdf/fpdf.php");

$database = new Database();
$db = $database->getConnection();

if(isset($_GET['requestId'])){
	$request = new Request($db);
	$request->requestId = base64_decode($_GET['requestId']);
	$request->viewOne();
	$details = $request->row;
}


$Y_Fields_Name_position = 0.5;

$labels = array("Control Number","Reason for Request", "Part Number", "Part Description");
$values = array($details['requestId'], $details['requestType'], $details['partNo'], $details['partDescription']);
$width_cell = array(1,1.5,1.8,3.6,5.4);

$pdf = new FPDF('P','in', array(8, 8));

// $pdf = new FPDF();
$pdf->AddPage();
$pdf->SetTitle('Request-Transcript');
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(211,211,211);
$pdf->Cell(0,0,'PART REPLACEMENT REQUEST',0,0,'C');
$pdf->Ln(.3);

$pdf->SetFont('Arial','B',10);

$pdf->Cell($width_cell[0],.2, 'For Picking',0,0,'L');
$pdf->Cell(.2,.2,' ',1,1,'L');
$pdf->ln(.1);

$pdf->Cell($width_cell[2],.3,'Request ID:',1,0,'C',true);
$pdf->Cell($width_cell[2],.3,$details['requestId'],1,0,'C');
$pdf->Cell($width_cell[2],.3,'Reason for Request:',1,0,'C', true);
$pdf->Cell($width_cell[2],.3,$details['requestType'],1,1,'C');
$pdf->Cell($width_cell[2],.3,'Part Number:',1,0,'C', true);
$pdf->Cell($width_cell[4],.3,$details['partNo'],1,1,'C');
$pdf->Cell($width_cell[2],.3,'Part Description:',1,0,'C',true);
$pdf->Cell($width_cell[4],.3,$details['partDescription'],1,1,'C');
$pdf->Cell($width_cell[2],.3,'Assigned Picker:',1,0,'C',true);
$pdf->Cell($width_cell[4],.3,$details['pickedBy'],1,1,'C');
if($details['requestTypeId']  == 1){
	$workorders = explode(':', $details['workOrder']);
	$quantities = explode(':', $details['quantity']);
	$pdf->ln(.1);
	$pdf->Cell($width_cell[3],.3,'Workorder',1,0,'C',true);
	$pdf->Cell($width_cell[3],.3,'Quantity',1,1,'C',true);

	foreach($workorders as $key => $workorder){
		$quantity = $quantities[$key];
		$pdf->Cell($width_cell[3],.3,$workorder,1,0,'C');
		$pdf->Cell($width_cell[3],.3,$quantity,1,1,'C');
	}
}else if($details['requestTypeId'] == 3){
	$pdf->Cell($width_cell[2],.3,'Quantity:',1,0,'C',true);
	$pdf->Cell($width_cell[4],.3,$details['quantity'],1,1,'C');
	$pdf->Cell($width_cell[2],.3,'Bin Location:',1,0,'C',true);
	$pdf->Cell($width_cell[4],.3,$details['binLocation'],1,1,'C');
}else{
	$pdf->Cell($width_cell[2],.3,'Quantity:',1,0,'C',true);
	$pdf->Cell($width_cell[4],.3,$details['quantity'],1,1,'C');
}
$pdf->ln(.2);
$pdf->Cell($width_cell[0],.2, 'For Delivery',0,0,'L');
$pdf->Cell(.2,.2,' ',1,0,'L');
$pdf->Cell($width_cell[2],.2,'Delivery Personnel:',0,0,'R');
$pdf->ln(.3);
$pdf->Cell(1,.2, 'Received    ',0,0,'L');
$pdf->Cell(.2,.2,' ',1,0,'L');
$pdf->Cell($width_cell[2],.2,'Received By:',0,0,'R');

$title = 'Part Replacement Form';
$pdf->SetAuthor('Anthony D. Ballug Jr.');
$pdf->Output('CN:1232313.pdf','I');
?>