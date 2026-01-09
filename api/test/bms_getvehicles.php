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
		 $uname          = killstring($val['username']);
		 $pwd            = trim($val['password']);
	}
if(!empty($uname) && !empty($pwd)){
	$SqlNewQry      = mysqli_query($conn,"SELECT `broker_id` from `tbl_bms_broker_master` WHERE `broker_api_username`='$uname' AND `broker_api_password`='$pwd' AND `is_active`=1");
	$NumNewQry      =(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
	if($NumNewQry>0){
		$sql      = mysqli_query($conn,"SELECT tb1.`vehicle_id` AS `vehicle_id`,tb1.`vehicle_name` AS `description`,tb1.`sipp_code` AS `sipp_code`,tb2.`group_name` AS `groupCode`,tb1.`doors` AS `doors`,tb1.`passengers` AS `seats`,tb1.`suitcases` AS `bags`,CASE WHEN tb1.`air_conditionar`=1 THEN 'TRUE' ELSE 'FALSE' END AS `airConditioning`,CASE WHEN tb1.`transmission_id`=2 THEN 'TRUE' ELSE 'FALSE' END AS `manualTransmission`,tb1.`is_active` AS `is_active`,tb1.`updated_date` AS `updated_date`,tb1.`entered_date` AS `entered_date` from `tbl_bms_vehicle_master` tb1 INNER JOIN `tbl_bms_group_master` tb2 ON tb1.`group_id`=tb2.`group_id`  WHERE tb1.`is_active`=1 ORDER BY tb1.`vehicle_name` ASC");
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