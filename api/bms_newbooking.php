<?php
/*
 * A Product of Emirates National Group -  SD
 */
ob_start();                            
session_start(); 
error_reporting(0);
$db_host     = "130.61.83.59";
$db_username = "root";
$db_password = "F@st{rent}17";  
$db_name     = "db_lpo"; 
$conn        = new mysqli("$db_host","$db_username","$db_password","$db_name") or die ("Could not connect to MYSQL");
date_default_timezone_set('Asia/Dubai');
mysqli_query($conn,'SET CHARACTER SET utf8');
    $json   = file_get_contents('php://input');
    $decode[] = json_decode($json, true);
	foreach($decode as $val){
		 $uname             = killstring($val['username']);
		 $pwd               = trim($val['password']);
		 $pickUpDate        = killstring($val['pickUpDate']);
		 $returnDate        = killstring($val['returnDate']);
		 $pickUpLocation    = killstring($val['pickUpLocation']);
		 $returnLocation    = killstring($val['returnLocation']);
		 $customerName      = killstring($val['customerName']);
		 $customerPhone     = killstring($val['customerPhone']);
		 $customerEmail     = killstring($val['customerEmail']);
		 $vehicle_id        = killstring($val['vehicle_id']);
		 $flightNumber      = killstring($val['flightNumber']);
		 $externalReference = killstring($val['externalReference']);
		 $valueWithoutTax   = killstring($val['valueWithoutTax']);
		 $taxValue          = killstring($val['taxValue']);
		 $totalValue        = killstring($val['totalValue']);
		 $noOfDays          = killstring($val['noOfDays']);
		 $accessories       = killstring($val['accessories']);
	}
$returnRespnose = array();
$returnRespnose['error']             =  "";
$returnRespnose['status']            =  "failed";
$returnRespnose['reservationNumber'] =  "";
if(!empty($uname) && !empty($pwd)){
	$SqlNewQry      = mysqli_query($conn,"SELECT `broker_id`,`broker_vat_percentage`  from `tbl_bms_broker_master` WHERE `broker_api_username`='$uname' AND `broker_api_password`='$pwd' AND `is_active`=1");
	$NumNewQry      =(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
	if($NumNewQry>0){
		$row_br    = mysqli_fetch_row($SqlNewQry);
        $broker_id             = $row_br['0'];
		$broker_vat_percentage = !empty($row_br['1'])?$row_br['1']:0;
		/********************************Here goes the insertion codes********/
		 
		 $pickUpDate        = !empty($pickUpDate)?date('Y-m-d H:i:s',strtotime($pickUpDate)):NULL;
		 $pickUpDate_Date   = !empty($pickUpDate)?date('Y-m-d',strtotime($pickUpDate)):NULL;
		 $pickUpDate        = !empty($pickUpDate) ? "'$pickUpDate'" : "NULL";
		 $returnDate        = !empty($returnDate)?date('Y-m-d H:i:s',strtotime($returnDate)):NULL;
		 $returnDate_Date   = !empty($returnDate)?date('Y-m-d',strtotime($returnDate)):NULL;
		 $returnDate        = !empty($returnDate) ? "'$returnDate'" : "NULL";
		 $pickUpLocation    = !empty($pickUpLocation)?$pickUpLocation:0;
		 $returnLocation    = !empty($returnLocation)?$returnLocation:0;
		 $vehicle_id        = !empty($vehicle_id)?$vehicle_id:0;
		 /****************value vat**********************************/
		 /*$valueWithoutTax   = !empty($valueWithoutTax)?$valueWithoutTax:0;
		 $taxValue          = !empty($taxValue)?$taxValue:0;
		 $totalValue        = !empty($totalValue)?$totalValue:0;*/
		 $totalValueWithoutAccssories   = !empty($totalValue)?$totalValue:0;
		 $totalValue                    = !empty($totalValue)?$totalValue:0;
		 if(!empty(accessories)){ 
			 $acc = implode(',',$accessories);
			 $acc = explode(',',$accessories);
			 foreach($acc as $acce){
			 $acces 	 = array(); 
			 $acces      = explode(':',$acce);
			 $totalValue += $acces['1'];
			 }
		 }
		 $taxValue                      = round(($broker_vat_percentage*($totalValue/100)),2);
		 $taxValue                      = !empty($taxValue)?$taxValue:0;
		 $valueWithoutTax               = round(($totalValue - $taxValue),2);
		 $valueWithoutTax               = !empty($valueWithoutTax)?$valueWithoutTax:0;
		 $acc = "";
		 
		 //$taxValue          = !empty($taxValue)?$taxValue:0;
		 /****************value vat**********************************/
		 $noOfDays          = !empty($noOfDays)?$noOfDays:0;
		 if(!empty($noOfDays) && !empty($externalReference) && !empty($vehicle_id)){
			     /******************Auto Reservation no***********************/
				    $QueryCounter  =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` from `tbl_bms_reservation_no`");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_bms_reservation_no` set `auto_id`='$value2A'");
					$ordervalue    = $value2A;
				/**************************************************/
			
	     /*$query = mysqli_query($conn,"INSERT INTO `tbl_bms_booking`(`reservation_no`, `pickUpDate`, `returnDate`, `pickUpLocation`, `returnLocation`, `customerName`, `customerPhone`, `customerEmail`, `vehicle_id`, `flightNumber`, `externalReference`, `valueWithoutTax`, `taxValue`, `totalValue`, `noOfDays`, `accessories`, `broker_id`, `entered_date`) VALUES ('".$ordervalue."',".$pickUpDate.",".$returnDate.",'".$pickUpLocation."','".$returnLocation."','".$customerName."','".$customerPhone."','".$customerEmail."','".$vehicle_id."','".$flightNumber."','".$externalReference."','".$valueWithoutTax."','".$taxValue."','".$totalValue."','".$noOfDays."','".$accessories."','".$broker_id."',NOW())");*/
		 $query = mysqli_query($conn,"INSERT INTO `tbl_bms_booking`(`reservation_no`, `pickUpDate`, `returnDate`, `pickUpLocation`, `returnLocation`, `customerName`, `customerPhone`, `customerEmail`, `vehicle_id`, `flightNumber`, `externalReference`, `valueWithoutTax`, `taxValue`, `totalValue`, `totalValueWithoutAccssories`,`noOfDays`, `accessories`, `broker_id`, `entered_date`) VALUES ('".$ordervalue."',".$pickUpDate.",".$returnDate.",'".$pickUpLocation."','".$returnLocation."','".$customerName."','".$customerPhone."','".$customerEmail."','".$vehicle_id."','".$flightNumber."','".$externalReference."','".$valueWithoutTax."','".$taxValue."','".$totalValue."','".$totalValueWithoutAccssories."','".$noOfDays."','".$accessories."','".$broker_id."',NOW())");
		     $last_id = mysqli_insert_id($conn);
			 if($query){
				 /********Inventory Matching*************/
				 $start_day = $pickUpDate_Date;//check start date is less than current day then start from current day.
				 $end_day   = $returnDate_Date;//check end date is less than current day then end from current day.
				 while (strtotime($start_day) <= strtotime($end_day)) {
				 $rowA = mysqli_query($conn,"UPDATE `tbl_bms_inventory_master` SET `inventory`=(`inventory` - 1) WHERE `location_id`='$pickUpLocation' AND `vehicle_id`='$vehicle_id' AND `broker_id`='$broker_id' AND `inventory_date`='$start_day'");
				 $start_day = date ("Y-m-d", strtotime("+1 days", strtotime($start_day)));  
				 }
				 /********Inventory Matching*************/
				 $returnRespnose['status']            =  "confirmed";
				 $returnRespnose['reservationNumber'] =  $ordervalue;
				 $returnRespnose['error']             =  "";
				 /***********************Email Trigger**************************/
				include_once('bms_booking_pdf.php');
				$date_time  = date('d_m_Y_H_i_s');
				$file_name  = ""; 
				$excel_save = "";
				$excel_save  = $pdfnamenew;	
				$subject    = "New Booking - BMS Portal - $ordervalue/$externalReference - $broker_name";       
				$title      = "New Booking - BMS Portal - $ordervalue/$externalReference - $broker_name"; 
				$to             = !empty($location_email)?$location_email:"marketing@autostrad.com"; 
				$cc             = "marketing@autostrad.com,reservations@autostrad.com,".$return_location_email;
				//$to             = "sanil.sadasivan@onetechnology.biz"; 
				//$cc             = "hashim.pudiyapura@onetechnology.biz";
				//$cc             = "hashim.pudiyapura@onetechnology.biz,marketing@autostrad.com,sanil.sadasivan@onetechnology.biz";
				$now_date_time  = date('d/m/Y',strtotime("-1 days"));
				$content         = "Dear Team,<br />
											 Please note that there is a new booking in BMS Portal. <br /><br />
						<table width='100%' border='1' style='border-collapse:collapse;border:solid 1px #DDD'>
                        <tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Reservation No.</strong></td><td colspan='2' style='padding:10px'>$reservation_no</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Broker</strong></td><td colspan='2' style='padding:10px'>$broker_name</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Confirmation No</strong></td><td colspan='2' style='padding:10px'>$externalReference</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Flight No</strong></td><td colspan='2' style='padding:10px'>$flightNumber</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Booked On</strong></td><td colspan='2' style='padding:10px'>$entered_date</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Customer Name</strong></td><td colspan='2' style='padding:10px'>$customerName</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Customer Phone</strong></td><td colspan='2' style='padding:10px'>$customerPhone</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Customer Email</strong></td><td colspan='2' style='padding:10px'>$customerEmail</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Pick-up date & time</strong></td><td colspan='2' style='padding:10px'>$pickUpDate</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Pick-up location</strong></td><td colspan='2' style='padding:10px'>$pickup_location</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Return date & time</strong></td><td colspan='2' style='padding:10px'>$returnDate</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Return location</strong></td><td colspan='2' style='padding:10px'>$return_location</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Vehicle Name</strong></td><td colspan='2' style='padding:10px'>$vehicle_name</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Vehicle Group</strong></td><td colspan='2' style='padding:10px'>$group_name</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Accessories</strong></td><td colspan='2' style='padding:10px'>$accessories</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>No Of Days</strong></td><td colspan='2' style='padding:10px'>$noOfDays</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Total</strong></td><td colspan='2' style='padding:10px'>AED $totalValue</td></tr>
						<tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Net Rate</strong></td><td colspan='2' style='padding:10px'>AED $totalValueWithoutAccssories</td></tr><tr><td colspan='3' style='padding:10px;color:crimson;'><strong>Status</strong></td><td colspan='2' style='padding:10px'>Confirmed</td></tr>
						<table>"; 
				mail_send_attachment($to,$subject,$title,$content,$cc,$excel_save);
				unlink($pdfnamenew);
              /***********************Email Trigger**************************/
			 }else{
				$returnRespnose['status']            =  "Error";
				$returnRespnose['reservationNumber'] =  "";
				$returnRespnose['error']             =  "Error";
			 }
	     }
		/********************************Here goes the insertion codes********/
	}else{
		$returnRespnose['status']            =  "Error";
		$returnRespnose['reservationNumber'] =  "";
		$returnRespnose['error']             =  "Invalid credentials or broker not exists";
		//echo 'Invalid credentials or broker not exists!!!';
	}
}else{//uname & pwd exist
        $returnRespnose['status']            =  "Error";
		$returnRespnose['reservationNumber'] =  "";
        $returnRespnose['error']             =  "Credentials not provided";
}
echo json_encode($returnRespnose);
exit();
//Mail Booking with Attachment
function mail_send_attachment($to,$subject,$title,$content,$cc,$excel_save){	
require_once dirname(__DIR__).'/plugins/PHPMailer/class.phpmailer.php';
require_once dirname(__DIR__).'/plugins/PHPMailer/class.smtp.php';

                    $mail = new PHPMailer();
					$mail->SMTPDebug = 0;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					/*$mail->Host       = 'mail.autostrad.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'arc-receipts@autostrad.com';         // SMTP username
					$mail->Password   = 'A2886272!';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;     
				//From email address and name
				$mail->From     = "reservations@autostrad.com"; */  
					$mail->Host       = 'mail.autostradtransport.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'notification@autostradtransport.com';         // SMTP username
					$mail->Password   = 'Auto&Strad@2023';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;     
				//From email address and name
				$mail->From     = "notification@autostradtransport.com";    
				$mail->FromName = "Reservation";
				//To address and name
				//To address and name
				/*$mail->addAddress("recepient1@example.com", "Recepient Name");*/
				$to_mailList = array();
				$to_mailList = explode(",",$to);
				if(!empty($to_mailList)){
					foreach($to_mailList as $tomail){
					$mail->addAddress($tomail); //Recipient name is optional
					}
				}	
				//Address to which recipient will reply
				$mail->addReplyTo("reservations@autostrad.com", "Reservation");
				$mail->AddAttachment($excel_save);
				//CC and BCC
				$cc_mailList = array();
				$cc_mailList = explode(",",$cc);
				if(!empty($cc_mailList)){
					foreach($cc_mailList as $ccmail){
					$mail->addCC($ccmail);
					}
				}	
				//$mail->addBCC("crm@aldanube.com");
				//Send HTML or Plain Text email
				$mail->isHTML(true);
$messages = "<body style='background-color:#eeeeee'>
<table width='90%' border='0' align='center' cellpadding='0' cellspacing='0' style='font-family:Cambria,serif; font-size:16px;'>
  <tr>
    <td><table width='100%' border='0' cellpadding='5' cellspacing='5' bgcolor='#FFFFFF'  style='margin-top:10px;'>
      <tr>
        <td><br />
<table width='100%' border='0' align='center'>  
          <tr>
            <td align='left'><img src='https://www.autostrad.com/assets/images/logo.png'  width='220'></td>
            <td align='right'><img src='https://www.autostrad.com/assets/images/logo.png' width='220'></td>
          </tr>
        </table><br />
</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor='#B4CB64'>
    <table width='100%' border='0' cellspacing='10' cellpadding='10'>
      <tr>
        <td align='center' style='font-family:Cambria,serif; font-size:18px; line-height:30px; color: #fff'><strong>".$title."</strong></td>
      </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td bgcolor='#FFFFFF'>
    <table width='100%' border='0' cellspacing='10' cellpadding='10'>
      <tr>
        <td style='font-family:Cambria,serif; font-size:16px; line-height:25px; color: #666'>
         ".$content."</b><br/><br/>
         <hr style='display: block;height: 1px;border: 0;border-top: 1px solid #DDD;margin: 1em 0 0 0;padding: 0;' />
        </td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
  </tr>
    </table></td>
  </tr>
</table>
</body>
";
                $mail->Subject = $subject;
				$mail->Body    = $messages;
				/*$mail->AltBody = "This is the plain text version of the email content";*/
				/*if(file_exists($filename)){
				$mail->AddAttachment( $filename );
				}*/
				if(!$mail->send()) 
				{
					$mail_msg  = "Mailer Error: " . $mail->ErrorInfo;
					//echo "Mailer Error: " . $mail->ErrorInfo;
				} 
				else 
				{
				   $mail_msg = "Mail send successfuully!";
				}     
}				

function killstring($mainstring){
	$mainstring = trim($mainstring);
	$badstrings="script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/|/“/Â/\/";
	$badarray=explode("/",$badstrings);
	foreach( $badarray as $key =>$badsubstring)  
	{
		$mainstring = str_replace($badsubstring," ",$mainstring);
	}
	$mainstring = str_replace('"', " ", $mainstring);//Remove double quotes
	$mainstring = preg_replace('!\s+!', ' ', $mainstring);//
	$mainstring = trim($mainstring);
	return($mainstring);
}

?>