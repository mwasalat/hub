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
		 $uname              = killstring($val['username']);
		 $pwd                = trim($val['password']);
		 $reservationNumber  = killstring($val['reservationNumber']);
		 $flightNumber       = killstring($val['flightNumber']);
		 $customerName       = killstring($val['customerName']);
		 $customerPhone      = killstring($val['customerPhone']);
	}
$returnRespnose = array();
$returnRespnose['error']             =  "";
$returnRespnose['status']            =  "failed";
if(!empty($uname) && !empty($pwd)){
	$SqlNewQry      = mysqli_query($conn,"SELECT `broker_id` from `tbl_bms_broker_master` WHERE `broker_api_username`='$uname' AND `broker_api_password`='$pwd' AND `is_active`=1");
	$NumNewQry      =(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
	if($NumNewQry>0){
		$row_br    = mysqli_fetch_row($SqlNewQry);
        $broker_id = $row_br['0'];
		/********************************Here goes the insertion codes********/
		 if(!empty($reservationNumber)){//reservation no check
	     $query = mysqli_query($conn,"UPDATE `tbl_bms_booking` SET `customerName`='$customerName',`customerPhone`='$customerPhone',`flightNumber`='$flightNumber',`updated_date`=NOW() WHERE `reservation_no`='$reservationNumber' AND `broker_id`='$broker_id'");
			 if($query){
				 $returnRespnose['status']            =  "modified";
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