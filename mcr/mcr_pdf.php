<?php
$afterpoint = substr($amount, strpos($amount, ".") + 1);
$len_after_point = strlen($afterpoint);
if ($len_after_point > 1 && substr($afterpoint, 0, 1) == '0') {

   $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  
    $amount_in_words = $f->format($amount);
    $amount_in_words = str_replace("point", "AND", $amount_in_words);
    $amount_in_words = str_replace("zero", "", $amount_in_words);
    
} else {
  
    $amount_in_words = numberTowords($amount);
    if ($afterpoint == 1) {
        
        $amount_in_words = numberTowords($amount);
       
    }
    
     else {
        $pieces    = explode(' ', $amount_in_words);
        $last_word = array_pop($pieces);
        if ($last_word == 'ZERO') {
            $amount_in_words = preg_replace('/\W\w+\s*(\W*)$/', '$1', $amount_in_words);
        }

        if(strpos($amount_in_words, "AND") !== false){
    $amount_in_words=$amount_in_words." FILS";
} else{
   $amount_in_words=$amount_in_words;
}
    }
    
}
$amount_in_words= "AED " . $amount_in_words . " ONLY";
/*require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');   
require_once('../Include/phpscripts.php'); */ 
//$tr_id = killstring($_REQUEST['flag']);

/*
$company_logo_final = "";
$u_pathn = '../uploads/mcr/company_logo/';
if ($company_logo  != '' && file_exists($u_pathn . $company_logo )) {
$company_logo_final   = $u_pathn . $build['company_logo'];
}
  
$sql  = "SELECT tb1.*,tb2.*,tb3.* FROM `tbl_transactions` tb1 LEFT JOIN `tbl_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`  LEFT JOIN `tbl_supplier_master` tb3 ON tb1.`supplier_id`=tb3.`supplier_id` WHERE tb1.`transaction_id`='$tr_id' ORDER BY tb1.`entered_date` DESC";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 

  $transaction_no = $build['transaction_no'];
  $company_name   = $build['company_name'];
  $company_tr_no  = $build['company_tr_no'];
 }
}*/
//This if function to convert numbers to Indian Currency
/*function getAECurrency(float $number){
$f = new NumberFormatter("ae", NumberFormatter::SPELLOUT);
$amt = $f->format($number). " Dirhams Only";
return ucwords(strtolower($amt)); // First letter upper case conversion only
}*/
//This if function to convert numbers to numbers
//including files required to print pdf
//=============print pdf tcpdf==================
$data=""; 
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
/***************Custom Footer Template****************/
$address = "";
// Extend the TCPDF class to create custom Footer
class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        //$this->SetY(-50);
		// Add a footer line 
		/*$this->writeHTML('<div></div><table class="table" width="100%" height="200px" border="0" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 2px #000;"><tr><td colspan="3">This receipt is temporary and will not be considered a settlement of the respective debt contained unless the actual realization
of cheque.</td></tr></table><hr>', true, false, false, false, '');*/
        // Set font
        /*$this->SetFont('calibri', '', 10);*/
        // Page number
	/*	$address = 'Tel: '.$company_phone.' - Fax: '.$company_fax.' '.$company_address;
		$this->Cell(0, 10, $address, 0, false, 'C', 0, '', 0, false, 'T', 'M');*/
		$this->writeHTML("<hr>", true, false, false, false, '');
        $this->Cell(0, 0, 'This receipt is temporary and will not be considered a settlement of the respective debt contained unless the actual realization of cheque.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
/***************Custom Footer Template****************/

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTextColor($col1 = 5,$col2 = -1,$col3 = -1,$col4 = -1,$ret = false,$name = '');	
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
$pdf->setPageOrientation("P");
//$pdf->SetFont('freesans', '', 12);
$pdf->SetFont('calibri', '', 10);
$pdf->SetMargins(9, true);
$pdf->AddPage(); 
$company_logo_final = "../uploads/mcr/company_logo/".$company_logo ;
if ($company_logo_final != '' && file_exists($company_logo_final)) {
$pdf->writeHTML('<br/><br/><table width="100%" border="0" cellpadding="5"><tr><td align="left"><img src="'.$company_logo_final.'" width="200"/></td><td>MCR#:'.$ordervalue.'<br/>DATE:'.date('d-m-Y').'</td></tr></table>', true, false, true, false, '');
}
$pdf->writeHTML('<table width="100%" border="0" cellpadding="5"><tr><td align="center"><h1>RECEIPT</h1></td></tr></table>', true, false, true, false, '');

$pdf->writeHTML('<table width="100%" border="0" cellpadding="5"><tr><td align="center"><h4>RECEIVED FROM : '.$glcod.'  / '.$pscode.' / '.$customer.'</h4></td></tr></table>', true, false, true, false, '');
$data ='<table class="table table-bordered" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD">
                <tbody>
                <tr><td style="font-weight:bold;">Customer Name</td><td>'.$customer.'</td></tr>
				<tr><td style="font-weight:bold;">Mode of payment</td><td>'.$trans_type.'</td></tr>';
				if($payment_mode=='CHEQUE'){
$data.='<tr><td style="font-weight:bold;">Cheque Bank</td><td>'.$cq_bank.'</td></tr>
				<tr><td style="font-weight:bold;">Cheque Date</td><td>'.$cq_cheque_date_chk.'</td></tr>
				<tr><td style="font-weight:bold;">Cheque Number</td><td>'.$cq_cheque_no.'</td></tr>';
				}
				if($payment_mode=='BANK TRANSFER'){
$data.='<tr><td style="font-weight:bold;">Bank Name</td><td>'.$cq_bank.'</td></tr>
				<tr><td style="font-weight:bold;">Transferred Date</td><td>'.$cq_cheque_date_chk.'</td></tr>';
				}
				if($payment_mode=='CREDIT CARD'){
$data.='<tr><td style="font-weight:bold;">Credit Card</td><td>'.$cc_type.'</td></tr>';
				}
$data.='<tr><td style="font-weight:bold;">Invoice Number</td><td>'.$invoice.'</td></tr>
				<tr><td style="font-weight:bold;">Total Amount</td><td>AED '.Checkvalid_number_or_not($amount,2).'</td></tr>
				<tr><td style="font-weight:bold;">Amount in Words </td><td>'.$amount_in_words.'</td></tr>
                ';
$data.='</tbody></table>';					
$data = utf8_encode($data);
$pdf->writeHTML($data, true, false, true, false, '');     
//=========================last page=============//
$pdf->lastPage();
$pdfnamenew = "MCR-".$ordervalue.".pdf";  
//ob_end_clean();
$pdf_save = '../uploads/mcr/'.$pdfnamenew;
$pdf->Output($pdf_save, "F");
            $subject       = "MCR RECIPT OF $customer - $glcod (MCR NUMBER : $ordervalue)";       
			$title         = "MCR RECIPT OF $customer - $glcod (MCR NUMBER : $ordervalue)";       
			$to            = "sanil.sadasivan@onetechnology.biz"; 
			//$cc            = "crm@aldanube.com";
			//$cc = "hashim@enguae.com";
			$content       = "Dear Sir /Madam,<br />
						     Greeting from ".$company_name_active.".Thanks for the payment and please find attached the receipt.<br /><br />Regards,<br /><b>".$company_name_active."</b>"; 
mail_send_attachment($to,$subject,$title,$content,$cc,$bcc,$pdf_save);   
//exit();
?>
