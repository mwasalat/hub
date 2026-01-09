<?php
/*
 * A Product of Emirates National Group -  SD
 */
ob_start();                            
session_start(); 
error_reporting(0);
header('Access-Control-Allow-Origin: *'); 
$db_host     = "130.61.83.59";
$db_username = "root";
$db_password = "F@st{rent}17";  
$db_name     = "db_lpo"; 
$conn        = new mysqli("$db_host","$db_username","$db_password","$db_name") or die ("Could not connect to MYSQL");
date_default_timezone_set('Asia/Dubai');
mysqli_query($conn,'SET CHARACTER SET utf8');

    $json     = file_get_contents('php://input');
    $decode[] = json_decode($json, true);
	foreach($decode as $val){
		 $uname        = killstring($val['username']);
		 $pwd          = trim($val['password']);
		 $user         = killstring($val['user']);//getHistory
		 /******get print**/
		 $id     = killstring($val['id']);
		 $name   = killstring($val['name']);
		 $code   = killstring($val['code']);
		 $amount = killstring($val['amount']);
		 $no_tickets   = killstring($val['no_tickets']);
		 $empid        = killstring($val['empid']);
		  /******get print**/
     }

if(!empty($uname) && !empty($pwd)){
$SqlNewQry= mysqli_query($conn,"SELECT `api_user_id` from `tbl_bts_api_user` WHERE `username`='$uname' AND `password`='$pwd' AND `is_active`=1");
$NumNewQry=(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
if($NumNewQry>0){//check for user exists.....

		if (isset($_REQUEST['action'])) {
				//Login Check
				if( $_REQUEST['action'] === 'login'){
					login();
				}
				//List Route
				if( $_REQUEST['action'] === 'list'){
					getList();
				}
				//Print
				if( $_REQUEST['action'] === 'print'){
					getPrint();
				}
				//History
				if( $_REQUEST['action'] === 'history'){
					getHistory();
				}
				//Get ID
				if( $_REQUEST['action'] === 'getid'){
					getID();
				}
				//Logout
				if( $_REQUEST['action'] === 'logout'){
					getLogout();
				}
		}
	}else{
		echo 'Invalid credentials or broker not exists!!!';
	}
}else{//uname & pwd exist
	    echo 'Credentials not provided!!!';
}
function login(){
 global $conn;	
 $username = killstring($_REQUEST['username']);
 $password = md5($_REQUEST['password']);
 	    $sql      = "SELECT `status`, `user_id`, `full_name`,`empid` FROM `tbl_login` WHERE `username`='$username' and BINARY `password`='$password'"; 
		$result   = mysqli_query($conn,$sql);  
		$no_rows  = mysqli_num_rows($result); 
		if($no_rows>0){
			$row            = mysqli_fetch_row($result);  
			$userstatus     = $row['0']; 
			$userno         = $row['1'];
			$full_name_user = $row['2'];
			$empid          = $row['3'];
				if($userstatus=='1'){ 
				    $data['userno']         = $userno;  
					$data['full_name_user'] = $full_name_user;
					$data['empid']          = $empid;
					$data['username']       = $username;
					$data['status']         = "Success";
				}else if($userstatus=='2'){ 
				$data['status'] =  "Your account has been closed by the administrator.";       
				}
				else{
			    $data['status'] =  "Kindly activate your account.";         
				}
		 
		}else{
			$data = "";
		}
 echo json_encode($data);
}
function getList(){
 global $conn;	
    $resL = array();
    $sqlA           = mysqli_query($conn,"SELECT  tb1.`route_id`,tb1.`route_name`,tb1.`route_code`,tb1.`amount`,tb2.`location_name` AS `route_from`,tb3.`location_name` AS `route_to` FROM `tbl_bts_route_master` tb1 INNER JOIN `tbl_bts_location_master` tb2 ON tb1.`route_from`=tb2.`location_id` AND tb2.`status`=1 INNER JOIN `tbl_bts_location_master` tb3 ON tb1.`route_to`=tb3.`location_id` AND tb3.`status`=1  WHERE tb1.`status`=1 AND tb1.`amount`>0 order by tb1.`route_name` ASC");
	$NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
	if($NumA>0){
	while($buildA = mysqli_fetch_array($sqlA)){ 
	$resL[] = $buildA;
	}
	}
	header('Content-Type: application/json');
	if (!empty($resL)) {
		echo json_encode($resL);
	} else {
		echo json_encode($resL);
	}
}

function getHistory(){
  global $conn;	
    $resL = array();
	$today = date('Y-m-d');
	//$user           = killstring($_REQUEST['user']);
    $sqlA           = mysqli_query($conn,"SELECT tbA.`booking_id`,tbA.`booking_no`,tbA.`route_id`,tbA.`no_of_tickets`,tbA.`amount`,tb1.`route_name`,tb1.`route_code`,tb2.`location_name` AS `route_from`,tb3.`location_name` AS `route_to` from tbl_bts_booking tbA INNER JOIN `tbl_bts_route_master` tb1 ON tbA.`route_id`=tb1.`route_id` INNER JOIN `tbl_bts_location_master` tb2 ON tb1.`route_from`=tb2.`location_id` AND tb2.`status`=1 INNER JOIN `tbl_bts_location_master` tb3 ON tb1.`route_to`=tb3.`location_id` AND tb3.`status`=1 WHERE tbA.`status`=1 AND tbA.`entered_by`='$user' AND DATE(tbA.`entered_date`)='$today' order by tbA.`booking_id` ASC");
	$NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
	if($NumA>0){
	while($buildA = mysqli_fetch_array($sqlA)){ 
	$resL[] = $buildA;
	}
	}
	header('Content-Type: application/json');
	if (!empty($resL)) {
		echo json_encode($resL);
	} else {
		echo json_encode($resL);
	}
}

function getPrint(){
 global $conn;	
 $id = killstring($_REQUEST['id']);
 $name = killstring($_REQUEST['name']);
  $code = killstring($_REQUEST['code']);
   $amount = killstring($_REQUEST['amount']);
    $no_tickets = killstring($_REQUEST['no_tickets']);
	 $total = (float) $no_tickets * $amount;	 
	 $empid = killstring($_REQUEST['empid']);
	   /******************Auto Transaction no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `auto_id`+1 AS `id` FROM `tbl_bts_auto_no`");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_bts_auto_no` SET `auto_id`='$value2A'");
					$ordervalue    = "BTS"."-".$value2A;
		/*********************Auto Transaction no*********************/
 	    $sql      = "INSERT INTO `tbl_bts_booking`(`booking_no`,`route_id`,`no_of_tickets`,`amount`,`total`,`entered_by`,`entered_date`)VALUES('".$ordervalue."','".$id."','".$no_tickets."','".$amount."','".$total."','".$empid."',NOW())"; 
		$result   = mysqli_query($conn,$sql);  
		if($result){
		$data['status'] = "success";
		}else{
		$data['status'] = "error";
		}
 echo json_encode($data);
}
function getID(){
global $conn;	
$data = array();
        /******************Auto Transaction no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `auto_id`+1 AS `id` FROM `tbl_bts_auto_no`");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_bts_auto_no` SET `auto_id`='$value2A'");
					$ordervalue    = "BTS"."-".$value2A;
		/*********************Auto Transaction no*********************/
		if($ordervalue){
		 $data['tid']         = $ordervalue;  
		}else{
		 $data['tid']         = NULL;  
		}
echo json_encode($data);
}

//Logout
function getLogout(){
session_destroy();      
session_unset();  
$data = "success";
echo json_encode($data);
exit(); 
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