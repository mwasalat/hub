<?php
phpinfo();
exit();
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php');  
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$tr_id = killstring($_REQUEST['flag']);
$data=""; 
global  $company_phone;
global  $company_phone;
global  $company_fax;

$sql  = "SELECT tb1.*,tb2.*,tb3.* FROM `tbl_transactions` tb1 LEFT JOIN `tbl_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`  LEFT JOIN `tbl_supplier_master` tb3 ON tb1.`supplier_id`=tb3.`supplier_id` WHERE tb1.`transaction_id`='$tr_id' ORDER BY tb1.`entered_date` DESC";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 
$company_logo = "";
$u_pathn = '../uploads/company_logo/';
  if ($build['company_logo'] != '' && file_exists($u_pathn . $build['company_logo'])) {
  $company_logo   = $u_pathn . $build['company_logo'];
  }
  $transaction_no = $build['transaction_no'];
  $company_name   = $build['company_name'];
  $company_tr_no  = $build['company_tr_no'];
  $company_address= $build['company_address'];
  $company_phone  = $build['company_phone'];
  $company_fax    = $build['company_fax'];
  
  $date_of_entry = date('d-M-Y',strtotime($build['date_of_entry']));
  $supplier_name = $build['supplier_name'];
  $concerned_person      = $build['concerned_person'];
  $place_of_delivery     = $build['place_of_delivery'];
  $delivery_schedule     = $build['delivery_schedule'];
  $place_of_registration = $build['place_of_registration'];
  $terms_of_payment      = $build['terms_of_payment'];
  $special_terms         = $build['special_terms'];
  $vat_value             = 0;
  $total_value           = 0;
  $vat_value             = $build['vat_value'];
  $total_value           = $build['total_value'];
  $phone_no = $build['phone_no'];
  $fax_no   = $build['fax_no'];
  $email    = $build['email'];
 }
}
//This if function to convert numbers to Indian Currency
function getAECurrency(float $number){
$f = new NumberFormatter("ae", NumberFormatter::SPELLOUT);
$amt = $f->format($number). " Dirhams Only";
return ucwords(strtolower($amt)); // First letter upper case conversion only
}
/*function getIndianCurrency(float $number){
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    //$paise = ($decimal > 0) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Fils' : '';
	$paise = ($decimal > 0) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Fils' : '';
	return ($Rupees ? $Rupees . 'Dirhams ' : '') . $paise . ' Only';
    //return ($Rupees ? $Rupees . 'Dirhams Only ' : '');
}*/
//including files required to print pdf
//=============print pdf tcpdf==================
require_once('../plugins/tcpdf/config/lang/eng.php');
require_once('../plugins/tcpdf/tcpdf.php');
require('../plugins/tcpdf/htmlcolors.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

/***************Custom Footer Template****************/
$address = "";
// Extend the TCPDF class to create custom Footer
class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
		global $company_phone;
		global $company_fax;
		global $company_address;
        // Position at 15 mm from bottom
        $this->SetY(-50);
		// Add a footer line 
		$this->writeHTML('<div></div><table class="table"  width="100%" height="200px" border="0" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 2px #000;"><tr><td colspan="3"></td></tr><tr><td colspan="3"></td></tr><tr><td colspan="3"></td></tr><tr><td colspan="3"></td></tr><tr><td  style="font-weight:bold;" text-align:left;>Ordered By</td><td  style="font-weight:bold;text-align:center;">Verified By</td><td style="font-weight:bold; text-align:right;">Authorized By</td></tr></table><hr>', true, false, false, false, '');
        // Set font
        $this->SetFont('calibri', '', 10);
        // Page number
		$address = 'Tel: '.$company_phone.' - Fax: '.$company_fax.' '.$company_address;
		$this->Cell(0, 10, $address, 0, false, 'C', 0, '', 0, false, 'T', 'M');
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
$pdf->writeHTML('<table width="100%" border="0" cellpadding="5"><tr><td align="right"><img src="'.$company_logo.'" width="100"/></td></tr></table>', true, false, true, false, '');
}
$pdf->writeHTML('<table width="100%" border="0" cellpadding="1"><tr><td align="center"><h1>Purchase Order</h1></td></tr><tr align="right"><td>VAT ID:<b>'.$company_tr_no.'</b></td></tr></table>', true, false, true, false, '');
$data ='<table class="table table-bordered" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD">
                <tbody>
                <tr><td style="font-weight:bold;" colspan="2">Order No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;font-weight:bold;">'.$transaction_no.'</span></td><td style="font-weight:bold;">Date</td><td>'.$date_of_entry.'</td></tr>
				<tr><td style="font-weight:bold;" rowspan="4" colspan="2"><br/>To:<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$supplier_name.'</td><td style="font-weight:bold;">Attention:</td><td>'.$concerned_person.'</td></tr>
				<tr><td style="font-weight:bold;">Phone:</td><td>'.$phone_no.'</td></tr>
				<tr><td style="font-weight:bold;">Fax:</td><td>'.$fax_no.'</td></tr>
				<tr><td style="font-weight:bold;">Email:</td><td>'.$email.'</td></tr>
                ';
$data.='<tr><td style="font-weight:bold;" colspan="4">Please supply the following:</td></tr></tbody></table>';	
$data.='<table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody>';
                  			
				                           $sql  = "SELECT `item_id`, `transaction_id`, `item_description`, `item_quantity`, `item_unit_value`, `item_total_value` FROM `tbl_transactions_items` WHERE `transaction_id`='$tr_id' and `is_active`=1 ORDER BY `entered_date` ASC";
				                           $dataA = mysqli_query($conn,$sql);
										   $itr = 1;
										    if(mysqli_num_rows($dataA)>0){
											$data.='<tr style="background-color:#DDD;"><td width="10%" style="text-align:center;"><b>Sl.No.</b></td><td width="40%" style="text-align:center;"><b>Description</b></td><td width="10%" style="text-align:center;"><b>Quantity</b></td><td width="20%" style="text-align:center;"><b>Rate</b></td><td style="text-align:center;"><b width="20%">Amount</b></td></tr>';
											$total = 0;
                                            while($build = mysqli_fetch_array($dataA)){ 
											$item_description        = $build['item_description'];
											$item_quantity           = $build['item_quantity'];
											$item_unit_value         = $build['item_unit_value'];
											$item_total_value        = $build['item_total_value'];
											$total+=$build['item_total_value'];
                                            $data.='<tr><td style="text-align:center;">'.$itr.'</td><td>'.$item_description.'</td><td style="text-align:center;">'.$item_quantity.'</td><td style="text-align:right;">'.Checkvalid_number_or_not($item_unit_value,2).'</td><td style="text-align:right;">'.Checkvalid_number_or_not($item_total_value,2).'</td><td></td></tr>';
					                        $itr++;
											}
											}
											$cols = 10 - $itr;
											if($cols>0){
												for ($i=0;$i<=$cols;$i++){
												 $data.='<tr><td style="text-align:center;"></td><td></td><td style="text-align:center;"></td><td style="text-align:right;"></td><td style="text-align:right;"></td><td></td></tr>';	
												}
											}
											$sum_total_vat = $total+$vat_value;
                  $data.='<tr><td colspan="4">Sub Total</td><td style="text-align:right;">'.Checkvalid_number_or_not($total,2).'</td></tr>';
				  $data.='<tr><td colspan="4">VAT 5%</td><td style="text-align:right;">'.Checkvalid_number_or_not($vat_value,2).'</td></tr>';
				  $data.='<tr><td colspan="4">Total</td><td style="text-align:right;">'.Checkvalid_number_or_not(($sum_total_vat),2).'</td></tr>';
				   
$data.='<tr><td colspan="5"><b>Amount In Words: '.getAECurrency($sum_total_vat).'</b></td></tr></tbody></table>';

$data.='<table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD">
                <tbody>
                <tr><td style="font-weight:bold;">Delivery Schedule:</td><td style="font-color:red;">'.$delivery_schedule.'</td><td colspan="2">All amounts are in UAE Dirhams unless specified.</td></tr>
				  <tr><td style="font-weight:bold;">Payment Terms:</td><td style="font-color:red;">'.$terms_of_payment.'</td><td colspan="2" style="font-weight:bold;">This order is subjected to the general terms and conditions.</td></tr>
				  <tr><td style="font-weight:bold;">Place Of Delivery:</td><td style="font-color:red;">'.$place_of_delivery.'</td><td style="font-weight:bold;">Requested By:</td><td style="font-color:red;">'.$company_name.'</td></tr>
				  <tr><td style="font-weight:bold;">Place Of Registration:</td><td style="font-color:red;" colspan="3">'.$place_of_registration.'</td></tr>
				   <tr><td style="font-weight:bold;" rowspan="2">Special Terms & Conditions:</td><td style="font-color:red;" colspan="3" rowspan="2">'.$special_terms.'</td></tr>
				   <tr><td style="font-color:red;" colspan="3"></td></tr>
                </tbody>
                </table>';
$data.='';
				
$data = utf8_encode($data);
$pdf->writeHTML($data, true, false, true, false, '');     
//=========================last page=============//
$pdf->lastPage();
$pdfnamenew = "invoice_".date("d_m_Y_h_i_s").".pdf";  
ob_end_clean();
$pdf->Output($pdfnamenew, "I");
exit();
?>
