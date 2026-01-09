<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php');  
$tr_id = killstring($_REQUEST['flag']);
$query = ""; 
global  $company_phone;
global  $company_email;
global  $company_name;
global  $supplier_name;
global  $currency_name;
$sqlQuery  = "SELECT tb1.*,tb2.*,tb3.*,tb4.`currency_name`,tb5.`duration_name` FROM `tbl_inv_transactions` tb1 LEFT JOIN `tbl_inv_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_inv_customer_master` tb3 ON tb1.`supplier_id`=tb3.`supplier_id` LEFT JOIN `tbl_currency_master` tb4 ON tb1.`currency_id`=tb4.`currency_id` LEFT JOIN `tbl_duration_master` tb5 ON tb1.`duration_id`=tb5.`duration_id` WHERE tb1.`transaction_id`='$tr_id' ORDER BY tb1.`entered_date` DESC";
$query = mysqli_query($conn,$sqlQuery);
if(mysqli_num_rows($query)>0){
while($build = mysqli_fetch_array($query)){ 
$company_logo = "";
$u_pathn = '../uploads/inv_company_logo/';
  if ($build['company_logo'] != '' && file_exists($u_pathn . $build['company_logo'])) {
  $company_logo   = $u_pathn . $build['company_logo'];
  }
  $transaction_no = $build['transaction_no'];
  $company_name   = $build['company_name'];
  $company_tr_no  = $build['company_tr_no'];
  $company_address= $build['company_address'];
  $company_phone  = $build['company_phone'];
  $company_fax    = $build['company_fax'];
  $company_email  = $build['company_email'];
  
  $date_of_entry = date('d/m/Y',strtotime($build['date_of_entry']));
  $supplier_name = $build['supplier_name'];
  $status        = $build['status'];
  $concerned_person      = explode(',',$build['concerned_person']);
  $cntB                  = count($concerned_person);
  $special_terms         = $build['special_terms'];
  $bank_details          = $build['bank_details'];
  $currency_name         = $build['currency_name'];
  $duration_name         = $build['duration_name'];
  $vat_value             = 0;
  $total_value           = 0;
  $vat_value             = $build['vat_value'];
  $total_value           = $build['total_value'];
  $supplier_name = $build['supplier_name'];
  $address       = $build['address'];
  $phone_no      = $build['phone_no'];
  $fax_no        = $build['fax_no'];
  $email         = $build['email'];
  $tr_no         = $build['tr_no'];
 }
}
//This if function to convert numbers to Indian Currency
function getAECurrency(float $number, $currency_name_s){
$f = new NumberFormatter("ae", NumberFormatter::SPELLOUT);
$amt = $f->format($number). " $currency_name_s Only";
return ucwords(strtolower($amt)); // First letter upper case conversion only
}
//including files required to print pdf
//=============print pdf tcpdf==================
require_once('../plugins/tcpdf/config/lang/eng.php');
require_once('../plugins/tcpdf/tcpdf.php');
require('../plugins/tcpdf/htmlcolors.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
/***************Custom Footer Template****************/
$addressA = "";
// Extend the TCPDF class to create custom Footer
class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
		global $company_phone;
		global $company_email;
		global $company_name;
		global $supplier_name;
		global $currency_name;
        // Position at 15 mm from bottom
        $this->SetY(-50);
		// Add a footer line 
		$this->writeHTML('<div></div><table class="table"  width="100%" height="200px" border="0" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 2px #000;"><tr><td colspan="3"></td></tr><tr><td colspan="3"></td></tr><tr><td colspan="3"></td></tr><tr><td colspan="3"></td></tr><tr><td  style="font-weight:bold;" text-align:left;>'.$company_name.'</td><td  style="font-weight:bold;text-align:center;"></td><td style="font-weight:bold; text-align:right;">'.$supplier_name.'</td></tr></table><hr>', true, false, false, false, '');
        // Set font
        $this->SetFont('calibri', '', 10);
        // Page number
		$addressA = 'Tel: '.$company_phone.' - Email: '.$company_email;
		$this->Cell(0, 10, $addressA, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
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

if ($company_logo != '' && file_exists($company_logo)) {
$pdf->writeHTML('<table width="100%" border="0" cellpadding="5"><tr><td align="left"><img src="'.$company_logo.'" width="100"/></td></tr></table>', true, false, true, false, '');
}
/*$pdf->writeHTML('<table width="100%" border="0" cellpadding="0"><tr><td align="center"><h1></h1></td></tr><tr align="right"><td>Date:<b>'.$date_of_entry.'</b></td></tr><tr><td align="center"><h1>INVOICE</h1></td></tr><tr align="right"><td>Invoice #:<b>'.$transaction_no.'</b></td></tr></table>', true, false, true, false, '');*/

$pdf->writeHTML('<table width="100%" border="0" cellpadding="1"><tr><td></td><td></td><td align="right">Date:<b>'.$date_of_entry.'</b></td></tr><tr><td></td><td align="center" style="color:#0EBA9E;font-weight:bold;text-align:center;"></td><td align="right">Invoice #:<b>'.$transaction_no.'</b></td></tr><tr><td></td><td align="center" style="color:#0EBA9E;font-weight:bold;text-align:center;"></td><td align="right">Billing Terms:<b>'.$duration_name.'</b></td></tr><tr><td></td><td align="center" style="color:#0EBA9E;font-weight:bold;text-align:center;"><h1>INVOICE</h1></td><td align="right">Currency:<b>'.$currency_name.'</b></td></tr></table>', true, false, true, false, '');
$data = "";
$data.='<table class="table table-bordered" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody><tr><td style="font-weight:bold;" rowspan="4" colspan="2"><span style="color:#A52A2A;">'.$company_name.'</span><br/>'.$company_address.'<br/>Phone: '.$company_phone.'<br/>TR No# '.$company_tr_no.'</td><td style="font-weight:bold;" rowspan="4" colspan="2"><span style="color:#A52A2A;">'.$supplier_name.'</span><br/>'.$address.'<br/>Phone: '.$phone_no.'<br/>TR No# '.$tr_no.'</td></tr><tr><td colspan="4"></td></tr></tbody></table><br/>';
//$data.='<tr><td style="font-weight:bold;" colspan="4">Please supply the following:</td></tr></tbody></table>';	
  if(!empty($concerned_person)){
	  $itrJ = 1;
	  $data.='<table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody>';	
    $data.='<tr style="background-color:#DDD;"><td width="10%" style="text-align:center;"><b>Sl.No.</b></td><td width="50%" style="text-align:center;"><b>Concerned Person</b></td><td width="20%" style="text-align:center;"><b>Position</b></td><td width="20%" style="text-align:center;"><b>Contact No</b></td></tr>';
  for($j=0; $j < $cntB; $j++){
  $concerned_person_id = $concerned_person[$j];
                                            if(!empty($concerned_person_id)){
												$sqlJ  = "SELECT `person_name`, `person_position`, `person_phone` FROM `tbl_inv_concerned_person_master` WHERE `person_id`='$concerned_person_id'";
											    $dataAJ = mysqli_query($conn,$sqlJ);
											    
												if(mysqli_num_rows($dataAJ)>0){
												while($buildAJ = mysqli_fetch_array($dataAJ)){ 
												$person_name        = $buildAJ['person_name'];
												$person_position    = $buildAJ['person_position'];
												$person_phone       = $buildAJ['person_phone'];
												$data.='<tr><td style="text-align:center;">'.$itrJ.'</td><td  style="text-align:center;">'.$person_name.'</td><td style="text-align:center;">'.$person_position.'</td><td style="text-align:center;">'.$person_phone.'</td></tr>';
												$itrJ++;
												}
												}
											}
   }
   $data.='</tbody></table><br/>';
  }
$data.='<table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody>';
				                           $sql  = "SELECT `item_id`, `transaction_id`, `item_description`, `item_quantity`, `item_unit_value`, `item_total_value` FROM `tbl_inv_transactions_items` WHERE `transaction_id`='$tr_id' and `is_active`=1 ORDER BY `entered_date` ASC";
				                           $dataA = mysqli_query($conn,$sql);
										   $itr = 1;
										    if(mysqli_num_rows($dataA)>0){
											$data.='<tr style="background-color:#DDD;"><td width="10%" style="text-align:center;"><b>Sl.No.</b></td><td width="40%" style="text-align:center;"><b>Description</b></td><td width="10%" style="text-align:center;"><b>Quantity</b></td><td width="20%" style="text-align:center;"><b>Rate</b></td><td style="text-align:center;"><b width="20%">Amount</b></td></tr>';
											$total = 0;
                                            while($buildA = mysqli_fetch_array($dataA)){ 
											$item_description        = $buildA['item_description'];
											$item_quantity           = $buildA['item_quantity'];
											$item_unit_value         = $buildA['item_unit_value'];
											$item_total_value        = $buildA['item_total_value'];
											$total+=$buildA['item_total_value'];
                                            $data.='<tr><td style="text-align:center;">'.$itr.'</td><td>'.$item_description.'</td><td style="text-align:center;">'.$item_quantity.'</td><td style="text-align:right;">'.Checkvalid_number_or_not($item_unit_value,2).'</td><td style="text-align:right;">'.Checkvalid_number_or_not($item_total_value,2).'</td><td></td></tr>';
					                        $itr++;
											}
											}
											$cols = 5 - $itr;
											if($cols>0){
												for ($i=0;$i<=$cols;$i++){
												 $data.='<tr><td style="text-align:center;"></td><td></td><td style="text-align:center;"></td><td style="text-align:right;"></td><td style="text-align:right;"></td><td></td></tr>';	
												}
											}
											$sum_total_vat = $total+$vat_value;
                  $data.='<tr><td colspan="4">Sub Total</td><td style="text-align:right;">'.Checkvalid_number_or_not($total,2).' '.$currency_name.'</td></tr>';
				  $data.='<tr><td colspan="4">VAT 5%</td><td style="text-align:right;">'.Checkvalid_number_or_not($vat_value,2).' '.$currency_name.'</td></tr>';
				  $data.='<tr><td colspan="4">Total</td><td style="text-align:right;">'.Checkvalid_number_or_not(($sum_total_vat),2).' '.$currency_name.'</td></tr>';
$data.='<tr><td colspan="5"><b>Amount In Words: '.getAECurrency($sum_total_vat,$currency_name).'</b></td></tr></tbody></table><br/>';

$data.='<table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD">
                <tbody>
				 <tr><td style="font-weight:bold;">Terms & Conditions:</td><td style="font-weight:bold;">Bank Details:</td></tr>
				 <tr><td style="font-color:red;" >'.$special_terms.'</td><td style="font-color:red;">'.$bank_details.'</td></tr>
                </tbody>
                </table>';
$data.='';
$dataQuery = utf8_encode($data);
$pdf->writeHTML($dataQuery, true, false, true, false, '');     
//=========================last page=============//
$pdf->lastPage();
$pdfnamenew = "invoice_".date("d_m_Y_h_i_s").".pdf";  
ob_end_clean();
$pdf->Output($pdfnamenew, "I");
exit();
?>
