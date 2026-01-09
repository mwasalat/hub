<?php
require_once 'plugins/PHPMailer/class.phpmailer.php';
require_once 'plugins/PHPMailer/class.smtp.php';
                    $mail = new PHPMailer();
					$mail->SMTPDebug = 1;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host       = 'smtp.office365.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'notification@autostrad.com';         // SMTP username
					$mail->Password   = 'N2886448!';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;  
					//$to  = "sanil.sadasivan@onetechnology.biz";
					$to  = "petersn21@yahoo.com";
					//$to  = "hashim.pudiyapura@onetechnology.biz";
				$mail->From     = "notification@autostrad.com";    
				$mail->FromName = "Emirates National Group";
				//To address and name
				$mail->addAddress($to); //Recipient name is optional
				//Address to which recipient will reply
				$mail->addReplyTo("arc-receipts@autostrad.com", "Emirates National Group");
				//$mail->addBCC("crm@aldanube.com");
				//Send HTML or Plain Text email
				$mail->isHTML(true);
                $mail->Subject = "Test Email";
				$mail->Body    = "Test Content";
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
				echo "Hai";
                exit();
				?>