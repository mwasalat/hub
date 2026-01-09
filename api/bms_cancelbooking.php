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
		 $reservationNumber = killstring($val['reservationNumber']);
	}
$returnRespnose = array();
$returnRespnose['error']             =  "";
$returnRespnose['status']            =  "failed";
if(!empty($uname) && !empty($pwd)){ 
	$SqlNewQry      = mysqli_query($conn,"SELECT `broker_id`,`broker_name` from `tbl_bms_broker_master` WHERE `broker_api_username`='$uname' AND `broker_api_password`='$pwd' AND `is_active`=1");
	$NumNewQry      =(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
	if($NumNewQry>0){
		$row_br    = mysqli_fetch_row($SqlNewQry);
        $broker_id   = $row_br['0'];
		$broker_name = $row_br['1'];
		/********************************Here goes the insertion codes********/
		 if(!empty($reservationNumber)){//reservation no check
	     $query = mysqli_query($conn,"UPDATE `tbl_bms_booking` SET `status`='2',`updated_date`=NOW() WHERE `reservation_no`='$reservationNumber' AND `broker_id`='$broker_id'");
			 if($query){
				 /********Inventory Matching*************/
				 $sqlA = mysqli_query($conn,"SELECT `pickUpDate`,`returnDate`,`pickUpLocation`,`vehicle_id` FROM `tbl_bms_booking` WHERE `reservation_no`='$reservationNumber' AND `broker_id`='$broker_id'");
				 while($rowA = mysqli_fetch_array($sqlA)){
					 $start_day  = date('Y-m-d',strtotime($rowA['pickUpDate']));//check start date is less than current day then start from current day.
					 $end_day    = date('Y-m-d',strtotime($rowA['returnDate']));//check end date is less than current day then end from current day.
					 $vehicle_id = $rowA['vehicle_id'];
					 $pickUpLocation = $rowA['pickUpLocation'];
					 while (strtotime($start_day) <= strtotime($end_day)) {
					 $sqlB = mysqli_query($conn,"UPDATE `tbl_bms_inventory_master` SET `inventory`=(`inventory` + 1) WHERE `location_id`='$pickUpLocation' AND `vehicle_id`='$vehicle_id' AND `broker_id`='$broker_id' AND `inventory_date`='$start_day'");
					 $start_day = date ("Y-m-d", strtotime("+1 days", strtotime($start_day)));  
					 }
				 }
				 /********Inventory Matching*************/
				/***********************Email Trigger**************************/
				  $sql          = "SELECT tb1.*,tb2.`broker_name`,tb3.`location_name` AS `pickup_location`,tb4.`location_name` AS `return_location`,tb5.`vehicle_name` AS `vehicle_name`,tb5.`sipp_code` AS `sipp_code`,tb6.`group_name` AS `group_name`,tb7.`transmission_name` AS `transmission_name`,tb8.`category_name` AS `category_name`,tb5.`doors` AS `doors`,tb5.`passengers` AS `passengers`, CASE WHEN tb5.`air_conditionar`=0 THEN 'Non A/C' WHEN tb5.`air_conditionar`=1 THEN 'A/C' ELSE 'A/C' END AS `air_conditionar`, tb3.`location_email` AS `location_email`,tb4.`location_email` AS `return_location_email` FROM `tbl_bms_booking` tb1 LEFT JOIN `tbl_bms_broker_master` tb2 ON tb1.`broker_id`=tb2.`broker_id`  LEFT JOIN `tbl_bms_location_master` tb3 ON tb1.`pickUpLocation`=tb3.`location_id` LEFT JOIN `tbl_bms_location_master` tb4 ON tb1.`returnLocation`=tb4.`location_id` LEFT JOIN `tbl_bms_vehicle_master` tb5 ON tb1.`vehicle_id`=tb5.`vehicle_id` LEFT JOIN `tbl_bms_group_master` tb6 ON tb5.`group_id`=tb6.`group_id` LEFT JOIN `tbl_bms_transmission_master` tb7 ON tb5.`transmission_id`=tb7.`transmission_id` LEFT JOIN `tbl_bms_category_master` tb8 ON tb5.`category_id`=tb8.`category_id` WHERE tb1.`reservation_no`='$reservationNumber'";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 

  $broker_name         = $build['broker_name'];
  $reservation_no      = $build['reservation_no'];
  $externalReference   = $build['externalReference'];
  $flightNumber        = $build['flightNumber'];
  $entered_date        = $build['entered_date'];
  $customerName        = $build['customerName'];
  $customerPhone       = $build['customerPhone'];
  $customerEmail       = $build['customerEmail'];
  
  $pickUpDate         = date('d.M.Y H:i:s',strtotime($build['pickUpDate']));
  $returnDate         = date('d.M.Y H:i:s',strtotime($build['returnDate']));
   
  $pickup_location     = $build['pickup_location'];
  $return_location     = $build['return_location'];
  $location_email      = $build['location_email'];
  $return_location_email     = $build['return_location_email'];
  $return_location_email     = !empty($return_location_email)?$return_location_email:"marketing@autostrad.com";
  $vehicle_name        = $build['vehicle_name'];
  $sipp_code           = $build['sipp_code'];
  $group_name          = $build['group_name'];
  $transmission_name   = $build['transmission_name'];
  $category_name       = $build['category_name'];
  $doors               = $build['doors'];
  $passengers          = $build['passengers'];
  $air_conditionar     = $build['air_conditionar'];
  
  $totalValue          = $build['totalValue'];
  $noOfDays            = $build['noOfDays'];
  $accessories         = $build['accessories'];
  
  $entered_date = date('d.M.Y H:i:s',strtotime($build['entered_date']));
 }
}

				$date_time  = date('d_m_Y_H_i_s');
				$file_name  = ""; 
				$excel_save = "";
				$subject    = "Cancellation Booking - $reservation_no/$externalReference - $broker_name";       
				$title      = "Cancellation Booking - $reservation_no/$externalReference - $broker_name"; 
				$to             = !empty($location_email)?$location_email:"marketing@autostrad.com"; 
				$cc             = "marketing@autostrad.com,reservations@autostrad.com,".$return_location_email;
				$now_date_time  = date('d/m/Y',strtotime("-1 days"));
				$content        = 'Dear Team,<br />
											 Please note that there is a <b>Cancellation</b> of booking in BMS Portal. The details are below.<br /><br />
			<table class="table table-bordered table-striped" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><thead><tr><th colspan="2" style="color:red;font-weight:bold;text-align:center;"><b>Reservation & Customer</b></th><th colspan="2" style="color:red;font-weight:bold;text-align:center;"><b>Vehicle</b></th></tr></thead><tbody style="border-collapse:collapse;border:solid 1px #DDD">
                <tr><td style="font-weight:bold;">Broker:</td><td>'.$broker_name.'</td><td style="font-weight:bold;">Vehicle Name:</td><td>'.$vehicle_name.'</td></tr>
				<tr><td style="font-weight:bold;">Confirmation No:</td><td>'.$externalReference.'</td><td style="font-weight:bold;">SIPP Code:</td><td>'.$sipp_code.'</td></tr>
				<tr><td style="font-weight:bold;">Flight No:</td><td>'.$flightNumber.'</td><td style="font-weight:bold;">Vehicle Group:</td><td>'.$group_name.'</td></tr>
				<tr><td style="font-weight:bold;">Booked On:</td><td>'.$entered_date.'</td><td style="font-weight:bold;">Vehicle Class:</td><td>'.$category_name.'</td></tr>
				<tr><td style="font-weight:bold;">Customer Name:</td><td>'.$customerName.'</td><td style="font-weight:bold;">Transmssion:</td><td>'.$transmission_name.'</td></tr>
				<tr><td style="font-weight:bold;">Customer Phone:</td><td>'.$customerPhone.'</td><td style="font-weight:bold;">Doors / Seats:</td><td>'.$doors.' / '.$passengers.'</td></tr>
				<tr><td style="font-weight:bold;">Customer Email:</td><td>'.$customerEmail.'</td><td style="font-weight:bold;">Air Conditionar:</td><td>'.$air_conditionar.'</td></tr></tbody></table>
<table class="table table-bordered table-striped" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><thead><tr><th colspan="2" style="color:red;font-weight:bold;text-align:center;"><b>Location</b></th><th colspan="2" style="color:red;font-weight:bold;text-align:center;"><b>Cost of rental</b></th></tr></thead><tbody style="border-collapse:collapse;border:solid 1px #DDD">
                <tr><td style="font-weight:bold;">Pick-up date & time:</td><td>'.$pickUpDate.'</td><td style="font-weight:bold;">Status:</td><td>Cancelled</td></tr>
				<tr><td style="font-weight:bold;">Pick-up location:</td><td>'.$pickup_location.'</td><td style="font-weight:bold;">Accessories:</td><td>'.$accessories.'</td></tr>
				<tr><td style="font-weight:bold;">Return date & time:</td><td>'.$returnDate.'</td><td style="font-weight:bold;">No Of Days:</td><td>'.$noOfDays.'</td></tr>
				<tr><td style="font-weight:bold;">Return location:</td><td>'.$return_location.'</td><td style="font-weight:bold;">Total:<br/></td><td><br/>AED '.$totalValue.'</td></tr></tbody></table>';	 
				mail_send_attachment($to,$subject,$title,$content,$cc,$excel_save);
              /***********************Email Trigger**************************/
				 $returnRespnose['status']            =  "cancelled";
				 $returnRespnose['error']             =  "";
			 }else{
				$returnRespnose['error']             =  "Error";
			 }
		 }else{
		 $returnRespnose['error']             =  "Error on reservation number";	 
		 }
		/********************************Here goes the insertion codes********/
	}else{
		$returnRespnose['error']             =  "Invalid credentials or broker not exists";
	}
}else{//uname & pwd exist
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
				$mail->From     = "reservations@autostrad.com";    */
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