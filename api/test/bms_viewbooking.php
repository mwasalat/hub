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
	}
if(!empty($uname) && !empty($pwd)){
	$SqlNewQry      = mysqli_query($conn,"SELECT `broker_id` from `tbl_bms_broker_master` WHERE `broker_api_username`='$uname' AND `broker_api_password`='$pwd' AND `is_active`=1");
	$NumNewQry      =(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
	if($NumNewQry>0){
		$sql      = mysqli_query($conn,"SELECT `reservation_no` AS `reservationNumber`, `pickUpDate`,`returnDate`,`pickUpLocation`,`returnLocation`,`customerName`,`customerPhone`,`customerEmail`,`vehicle_id`,`flightNumber`,`externalReference`,`totalValue`,`noOfDays` ,`accessories`,`entered_date` AS `EntryDate` FROM `tbl_bms_booking_test` WHERE `reservation_no`='$reservationNumber' AND `status`=1");
		$resL     = array();
		while ($row = mysqli_fetch_array($sql)) {
			 $row = array_map('htmlentities',$row);
			 $resL[] = $row;
		}
		header('Content-Type: application/json');
		if (!empty($resL)) {
			echo json_encode($resL);
		} else {
			echo 'No Data!!!';
		}
	}else{
		echo 'Invalid credentials or broker not exists!!!';
	}
}else{//uname & pwd exist
	    echo 'Credentials not provided!!!';
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