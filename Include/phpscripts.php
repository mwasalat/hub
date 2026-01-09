<?php
// Valid extension
$valid_ext = array('png','jpeg','jpg','PNG','JPEG','JPG');

//File extension icon
function file_type_img_now($img){
        if($img=='doc' || $img=='docx' || $img=='DOC' || $img=='DOCX'){
		$extention   = "003-doc.png";
		}
		else if($img=='pdf' || $img=='PDF'){
		$extention   = "002-pdf.png";
		}
		else if($img=='jpg' || $img=='jpeg' || $img=='JPG' || $img=='JPEG'){
		$extention   = "001-jpg.png";
		}
		else if($img=='png' || $img=='PNG'){
		$extention   = "004-png.png";
		}
		else{
		$extn1   = "003-doc.png";
		}
		return $extention; 
}



// Get IP address
function getIP() {
      foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
         if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
               if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                  return $ip;
               }
            }
         }
      }
   }


// Compress image
function compressImage($source, $destination) {
            $info = getimagesize($source);
            if ($info['mime'] == 'image/jpeg'){ 
                $image = imagecreatefromjpeg($source);
				 imagejpeg($image, $destination, 40); 
				}
            elseif ($info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($source);
				 imagejpeg($image, $destination, 75);
				}
            elseif ($info['mime'] == 'image/png') {
			    /*header('Content-Type: image/png');
				imagepng($image, $destination, 75);
				imagedestroy($image);*/
				$background = imagecolorallocate($dimg , 0, 0, 0);
                $image = imagecreatefrompng($source);
			    imagecolortransparent($image, $background);
				imagepng($image, $destination, 7);
				imagedestroy($image);
				/*imagepng($image, $destination, 75);*/
				}
				else{
				//
				}
           
}
function Thumbnail($url, $filename, $width = 150, $height = true) {
 // download and create gd image
 $image = ImageCreateFromString(file_get_contents($url));
 // calculate resized ratio
 // Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
 $height = $height === true ? (ImageSY($image) * $width / ImageSX($image)) : $height;
 // create image 
 $output = ImageCreateTrueColor($width, $height);
 ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));
 // save image
 ImageJPEG($output, $filename, 95); 
 // return resized image
 return $output; // if you need to use it
}
	
function end_extention($file){
$file_extension = pathinfo($file, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);
return $file_extension;
}

function Checkvalid_number_or_not($number,$decimal)
{
	 if(!is_infinite($number) && !is_nan ($number)) {
	 $no = number_format($number,$decimal);
	 return $no;
	 }
	 else{
	  return 0;
	 }
}
function file_type_img($img){
        if($img=='doc' || $img=='docx' || $img=='DOC' || $img=='DOCX'){
		$extention   = "doc.svg";
		}
		else if($img=='pdf' || $img=='PDF'){
		$extention   = "pdf.svg";
		}
		else if($img=='jpg' || $img=='jpeg' || $img=='JPG' || $img=='JPEG'){
		$extention   = "jpg.svg";
		}
		else if($img=='png' || $img=='PNG'){
		$extention   = "png.svg";
		}
		else if($img=='ppt' || $img=='pptx'){
		$extention   = "ppt.svg";
		}
		else if($img=='xls' || $img=='xlsx'){
		$extention   = "xls.svg";
		}
		else{
		$extention   = "all.svg";
		}
		return $extention; 
}	
function mail_send($to,$subject,$title,$content,$cc){	
require_once dirname(__DIR__).'/plugins/PHPMailer/class.phpmailer.php';
require_once dirname(__DIR__).'/plugins/PHPMailer/class.smtp.php';
				   $mail = new PHPMailer();
					$mail->SMTPDebug = 0;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
				    $mail->Host       = 'mail.autostradtransport.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'notification@autostradtransport.com';         // SMTP username
					$mail->Password   = 'Auto&Strad@2023';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;     
				//From email address and name
				$mail->From     = "notification@autostradtransport.com"; 
				$mail->FromName = "Emirates National Group";
				//To address and name
				//To address and name
				/*$mail->addAddress("recepient1@example.com", "Recepient Name");*/
				$mail->addAddress($to); //Recipient name is optional
				//Address to which recipient will reply
				$mail->addReplyTo("arc-receipts@autostrad.com", "Emirates National Group");
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
    <td bgcolor='#3c8dbc'>
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
  <tr>
    <td align='center' style='font-size:11px; color:#666; line-height:20px'><table width='100%' border='0' cellspacing='5' cellpadding='5'>
      <tr>
        <td>&nbsp;</td>
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


//To Uniform _request_to__ CHAT_BOT
function mail_send_attachment($to,$subject,$title,$content,$cc,$bcc,$excel_save){ 	
require_once dirname(__DIR__).'/plugins/PHPMailer/class.phpmailer.php';
require_once dirname(__DIR__).'/plugins/PHPMailer/class.smtp.php';
				    $mail = new PHPMailer();
					$mail->SMTPDebug = 0;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host       = 'mail.autostradtransport.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'notification@autostradtransport.com';         // SMTP username
					$mail->Password   = 'Auto&Strad@2023';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;     
				//From email address and name
				$mail->From     = "notification@autostradtransport.com"; 
				$mail->FromName = "ACCOUNTS";
				//To address and name
				//To address and name
				/*$mail->addAddress("recepient1@example.com", "Recepient Name");*/
				$mail->addAddress($to); //Recipient name is optional
				//Address to which recipient will reply
				$mail->addReplyTo("arc-receipts@autostrad.com", "ACCOUNTS");
				//CC List
				$cc_mailList = array();
				$cc_mailList = explode(",",$cc);
				if(!empty($cc_mailList)){
					foreach($cc_mailList as $ccmail){
					$mail->addCC($ccmail);
					}
				}	
				//BCC LIST
				/*$bcc_mailList = array();
				$bcc_mailList = explode(",",$bcc);
				if(!empty($bcc_mailList)){
					foreach($bcc_mailList as $bccmail){
					$mail->addBCC($bccmail);
					}
				}*/
				
				$mail->AddAttachment($excel_save);
				//$mail->addBCC("crm@aldanube.com");
				//Send HTML or Plain Text email
				$mail->isHTML(true);
$messages = "<body style='background-color:#eeeeee'>
<table width='90%' border='0' align='center' cellpadding='0' cellspacing='0' style='font-family:Arial, Helvetica, sans-serif; font-size:14px;'>
  <tr>
    <td bgcolor='#3c8dbc'>
    <table width='100%' border='0' cellspacing='10' cellpadding='10'>
      <tr>
        <td align='center' style='font-family:Arial, Helvetica, sans-serif; font-size:18px; line-height:21px; color: #fff'><strong>".$title."</strong></td>
      </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td bgcolor='#FFFFFF'>
    <table width='100%' border='0' cellspacing='10' cellpadding='10'>
      <tr>
        <td style='font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:25px; color: #666'>
         ".$content."</b><br/><br/>
         <hr style='display: block;height: 1px;border: 0;border-top: 1px solid #DDD;margin: 1em 0 0 0;padding: 0;' />
        </td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td align='center' style='font-size:11px; color:#666; line-height:20px'><table width='100%' border='0' cellspacing='5' cellpadding='5'>
      <tr>
        <td>&nbsp;</td>
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


//Mail Ticket Management System	
function mail_send_tms($to,$subject,$title,$content,$cc){	
require_once dirname(__DIR__).'/plugins/PHPMailer/class.phpmailer.php';
require_once dirname(__DIR__).'/plugins/PHPMailer/class.smtp.php';
				   $mail = new PHPMailer();
					$mail->SMTPDebug = 0;                                   // Enable verbose debug output
					$mail->isSMTP();                                        // Set mailer to use SMTP
				    $mail->Host       = 'smtp.office365.com';               // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'no-reply@onetechnology.biz';       // SMTP username
					$mail->Password   = 'noreply@123';                      // SMTP password
					$mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;     
				//From email address and name
				$mail->From     = "no-reply@onetechnology.biz"; 
				$mail->FromName = "One Technology";
				//To address and name
				//To address and name
				/*$mail->addAddress("recepient1@example.com", "Recepient Name");*/
				$mail->addAddress($to); //Recipient name is optional
				//Address to which recipient will reply
				$mail->addReplyTo("no-reply@onetechnology.biz", "One Technology");
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
<table width='80%' border='0' align='center' cellpadding='0' cellspacing='0' style='font-family:Cambria,serif; font-size:16px;'>
  <tr>
    <td><table width='100%' border='0' cellpadding='5' cellspacing='5' bgcolor='#FFFFFF'  style='margin-top:10px;'>
      <tr>
        <td><br />
<table width='100%' border='0' align='center'>  
          <tr>
            <td align='left'><img src='http://mcr.enguae.com/hub/images/one_tech_logo.jpg'  width='120'></td>
            <td align='right'></td>
          </tr>
        </table><br />
</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor='#3c8dbc'>
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
  <tr>
    <td align='center' style='font-size:11px; color:#666; line-height:20px'><table width='100%' border='0' cellspacing='5' cellpadding='5'>
      <tr>
        <td  align='center' ><b>One Technology Team<b><br/>
		P.O. Box 9004 Abu Dhabi, U.A.E.<br/>
		Tollfree: 8001TECH (8324)<br/>
		Landline: +971 2 815 2717<br/>
		</td>
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

//fill drop down list already bulit , provided query , selection has to be made
function filldropwithselection($sql_class,$val_class)
	 {
		$res_class = mysqli_query($conn,$sql_class);
		if(mysqli_num_rows($res_class)> 0) {
			$resultHtml="";
			while ($row = mysqli_fetch_array($res_class)) {
				if($row[0] == $val_class)
				$resultHtml .= "<option value=\"$row[0]\" selected> $row[1]</option>";
				else
				$resultHtml .= "<option value=\"$row[0]\"> $row[1]</option>";
			//$resultHtml .= "<option value='$row[0]'> $row[1]</option>";

			}
			echo $resultHtml;
		}

	}


function valid_number_or_not_check($number)
{
	 if(!is_infinite($number) && !is_nan ($number)) {
	 $no = round($number,0);
	 return $no;
	 }
	 else{
	  return 0;
	 }
}
?>
