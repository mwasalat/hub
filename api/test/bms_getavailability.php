<?php
/*
 * A Product of Emirates Nation al Group -  SD
 */
ob_start();                            
session_start(); 
error_reporting(0);
date_default_timezone_set('Asia/Dubai');
//$started = microtime(true);
$db_host     = "130.61.83.59";
$db_username = "root";
$db_password = "F@st{rent}17";  
$db_name     = "db_lpo"; 
$conn        = new mysqli("$db_host","$db_username","$db_password","$db_name") or die ("Could not connect to MYSQL");
mysqli_query($conn,'SET CHARACTER SET utf8');
    $json     = file_get_contents('php://input');
    $decode[] = json_decode($json, true);
	foreach($decode as $val){
		 $uname        = killstring($val['username']);
		 $pwd          = trim($val['password']);
		 $pickUpDate   = killstring($val['pickUpDate']);
		 $returnDate   = killstring($val['returnDate']);
		 $pickUpLocation   = killstring($val['pickUpLocation']);
		 $returnLocation   = killstring($val['returnLocation']);
}
	
if(!empty($uname) && !empty($pwd)){
$SqlNewQry= mysqli_query($conn,"SELECT `broker_id`,`broker_gracetime`,`broker_vat_percentage` from `tbl_bms_broker_master` WHERE `broker_api_username`='$uname' AND `broker_api_password`='$pwd' AND `is_active`=1");
$NumNewQry=(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
if($NumNewQry>0){//check for broker exists.....
$row_br   = mysqli_fetch_row($SqlNewQry);
$broker_graceminutes      = 0;
$no_days                  = 0;
$pickUpDate_wkno          = 0;
$returnDate_wkno          = 0;
$pickUpDate_Time          = 0;
$returnDate_Time          = 0;
$broker_id                = $row_br['0'];
$broker_gracetime         = $row_br['1'];
$broker_vat_percentage    = $row_br['2'];
$broker_gracetime    = !empty($broker_gracetime)?$broker_gracetime:"00:00:00";
$broker_graceminutes = minutes($broker_gracetime);
$pickUpDate     = !empty($pickUpDate)?date('Y-m-d H:i:s',strtotime($pickUpDate)):NULL;
$pickUpDate_Time= !empty($pickUpDate)?date('H:i',strtotime($pickUpDate)):"00:00";
$pickUpDate_date= !empty($pickUpDate)?date('Y-m-d',strtotime($pickUpDate)):NULL;
$pickUpDate_wkno=date('N', strtotime($pickUpDate));
$returnDate     = !empty($returnDate)?date('Y-m-d H:i:s',strtotime($returnDate)):NULL;
$returnDate_date= !empty($returnDate)?date('Y-m-d',strtotime($returnDate)):NULL;
$returnDate_Time= !empty($returnDate)?date('H:i',strtotime($returnDate)):"00:00";
$returnDate_wkno=date('N', strtotime($returnDate));

$sqlQ = mysqli_query($conn,"INSERT IGNORE INTO `tbl_bms_api_requests`(`broker_id`,`pickUpDate`,`returnDate`,`pickUpLocation`, `returnLocation`,`entered_date`) VALUES ('".$broker_id."','".$pickUpDate."','".$returnDate."','".$pickUpLocation."','".$returnLocation."',NOW())");

    $journey    = journey_days($pickUpDate , $returnDate, $broker_graceminutes);
    $no_days    = $journey['total_days'];
    $returnDate =  $journey['dropoff_date'];

/***From today to days***********/
$difference     = 0;
$difference     = strtotime($pickUpDate) - strtotime(date('Y-m-d'));
$from_days      = floor($difference / (3600 * 24)) + 1;
$from_days      = ($from_days>1)?$from_days:1;
/***No of days***********/ 
/*************Location Buffer Time Calculation*******/
$current_date_time    = date('Y-m-d H:i:s');
$BQuery               = mysqli_query($conn,"SELECT `location_buffer_time` from `tbl_bms_location_master` WHERE `location_id`='$pickUpLocation' AND `is_active`=1");
$row_buffer           = mysqli_fetch_row($BQuery);
$buffer_time          = !empty($row_buffer['0'])?$row_buffer['0']:"00:00:00";
$buffer_timeminutes   = minutes($buffer_time)  * 60;
$buffer_extra_time    = strtotime($pickUpDate) - $buffer_timeminutes;
$buffer_time_to_check = date('Y-m-d H:i:s', $buffer_extra_time);
$a_buffer_time_to_check = new DateTime($buffer_time_to_check);
$b_current_date_time    = new DateTime($current_date_time);

/*************Location Buffer Time Calculation*******/

         /**********Conditions*************/
		 //1. one time airport fee calculation
		 //2. daily VMD calculation
		/**********Conditions*************/ 
		if(
			// allow only if pickup and dropoff locations are same
			($returnLocation==19 && $pickUpLocation!=19) ||  // Abu Dhabi International Airport - Meet & Greet
			($returnLocation==30 && $pickUpLocation!=30) ||  // Al Maktoum International Airport - Meet & Greet
			($returnLocation==3 && $pickUpLocation!=3) ||	// sheikh ziad rd
			($returnLocation==24 && $pickUpLocation!=24) || // RAK int. airport
			($returnLocation==25 && $pickUpLocation!=25) || // Rixos al madrid RAK
			($returnLocation==26 && $pickUpLocation!=26) || // Al Hamrah village RAK
			($returnLocation==27 && $pickUpLocation!=27) || // Al Hamrah mall RAK
			($returnLocation==28 && $pickUpLocation!=28) ||	// Al Hamrah fort RAK
			($returnLocation==32 && $pickUpLocation!=32) ||
			($returnLocation==33 && $pickUpLocation!=33) ||
			($returnLocation==34 && $pickUpLocation!=34) ||
			($returnLocation==35 && $pickUpLocation!=35) ||
			($returnLocation==36 && $pickUpLocation!=36) ||
			($returnLocation==37 && $pickUpLocation!=37) ||
			($returnLocation==38 && $pickUpLocation!=38) ||
			($returnLocation==39 && $pickUpLocation!=39) ||
			($returnLocation==40 && $pickUpLocation!=40) ||
			($returnLocation==41 && $pickUpLocation!=41) ||
			($returnLocation==42 && $pickUpLocation!=42) ||
			($returnLocation==43 && $pickUpLocation!=43) ||
			($returnLocation==44 && $pickUpLocation!=44) ||
			($returnLocation==45 && $pickUpLocation!=45) ||
			($returnLocation==46 && $pickUpLocation!=46) ||
			($returnLocation==47 && $pickUpLocation!=47) ||
			($returnLocation==48 && $pickUpLocation!=48)
			){
		$Qry   = "";	
		}else{
			
				if($a_buffer_time_to_check >= $b_current_date_time){//buffer time check	
				$Qry      = "SELECT `vehicle_id`,IFNULL((SUM(`total`)  + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`) - ROUND((SUM(`total`)+ `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`)*('".$broker_vat_percentage."'/100),2),0) AS `valueWithoutTax`,IFNULL(ROUND((SUM(`total`) + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`) * ('".$broker_vat_percentage."'/100),2), 0) AS `taxValue`,'".$broker_vat_percentage."' AS `taxRate`,
				IFNULL((SUM(`total`) + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`),0) AS `totalValue`,IFNULL(SUM(`cdw`),0) AS `cdw`,IFNULL(SUM(`scdw`),0) AS `scdw`,IFNULL(SUM(`pai`),0) AS `pai`,IFNULL(SUM(`gps`),0) AS `gps`,IFNULL(SUM(`baby_seat`),0) AS `baby_seat` , IFNULL(SUM(`driver`),0) AS `driver`
				FROM (SELECT tb2.`vehicle_id` AS `vehicle_id`, 
				IFNULL(ROUND(((CASE WHEN tb4.`range_price`>0 THEN tb4.`range_price` ELSE tb1.`rate` END) + IFNULL((tb13.`vmd_fee`),0) - IFNULL(ROUND(tb1.`rate`*(tb5.`advance_discount`/100),2), 0)),2), 0) AS `total`,
				IFNULL(tb13.`airport_fee`, 0) AS `airport_fee`, 
				IFNULL(tb14.`airport_fee`, 0) AS `return_airport_fee`, 
				IFNULL(tb4.`range_price`, 0) AS `range_price`, 
				IFNULL(tb12.`inter_emirate_pricing`, 0) AS `inter_emirate_pricing`,
				IFNULL(ROUND(tb1.`rate`*(tb5.`advance_discount`/100),2), 0) AS `advance_discount`,
				tb1.`rate` AS `rate`,tb1.`cdw` AS `cdw`,tb1.`scdw` AS `scdw`,tb1.`pai` AS `pai`,tb1.`gps` AS `gps`,tb1.`baby_seat` AS `baby_seat` , tb1.`driver` AS `driver`
				FROM `tbl_bms_daily_price_master` tb1
				INNER JOIN `tbl_bms_inventory_master` tbA ON tb1.`vehicle_id`=tbA.`vehicle_id`
				INNER JOIN `tbl_bms_vehicle_master` tb2 ON tb1.`vehicle_id`=tb2.`vehicle_id`
				INNER JOIN `tbl_bms_group_master` tb3 ON tb2.`group_id`=tb3.`group_id`
				INNER JOIN `tbl_bms_location_master` tb6 ON tb1.`price_location_id`=tb6.`location_id`
				LEFT JOIN `tbl_bms_range_pricing_master` tb4 ON tb1.`price_broker_id`=tb4.`broker_id` AND tb2.`group_id`=tb4.`group_id` AND tb6.`location_id`=tb4.`location_id` AND tb4.`parameter_id`=1  AND tb4.`is_active`=1 AND tb4.`range_id` = 
				(
				   SELECT MAX(`range_id`) 
				   FROM tbl_bms_range_pricing_master tb11
				   WHERE tb1.`price_broker_id`=tb11.`broker_id` AND tb2.`group_id`=tb11.`group_id` AND tb6.`location_id`=tb11.`location_id` AND tb11.`parameter_id`=1 AND '$no_days' BETWEEN tb11.`range_from` AND tb11.`range_to` AND tb1.`price_date` BETWEEN tb11.`start_date` AND tb11.`end_date` AND tb11.`is_active`=1
				)
				LEFT JOIN `tbl_bms_advance_booking_pricing_master` tb5 ON tb1.`price_broker_id`=tb5.`broker_id` AND '$from_days' BETWEEN tb5.`advance_from` AND tb5.`advance_to` AND tb5.`is_active`=1
				INNER JOIN `tbl_bms_location_timing_master` tb7 ON tb1.`price_location_id`=tb7.`location_id`
				INNER JOIN `tbl_bms_location_master` tb8 ON tb8.`location_id`='$returnLocation'
				INNER JOIN `tbl_bms_location_timing_master` tb9 ON tb8.`location_id`=tb9.`location_id`
				LEFT JOIN `tbl_bms_location_exception_timing_master` tb10 ON tb1.`price_location_id`=tb10.`location_id` AND tb10.`is_active`=1  AND tb10.`exception_date`='$pickUpDate_date' AND TIME('$pickUpDate_Time') BETWEEN tb10.`start_time` AND tb10.`end_time`
				LEFT JOIN `tbl_bms_inter_emirate_pricing` tb12 ON tb6.`location_city`=tb12.`pickup_emirate_id` AND tb8.`location_city`=tb12.`dropoff_emirate_id` AND tb12.`is_active`=1
				LEFT JOIN `tbl_bms_extra_fee` tb13 ON tb7.`location_id`=tb13.`location_id` AND tb13.`broker_id`='$broker_id'
				LEFT JOIN `tbl_bms_extra_fee` tb14 ON tb8.`location_id`=tb14.`location_id` AND tb7.`location_id`!=tb8.`location_id` AND tb14.`broker_id`='$broker_id'
				WHERE tb1.`is_active`=1 AND tb2.`is_active`=1 AND tb2.`car_start_date`< '$pickUpDate_date' AND tb1.`price_location_id`='$pickUpLocation' AND tb6.`location_id`='$pickUpLocation' AND tb8.`location_id`='$returnLocation' AND tb7.`location_id`='$pickUpLocation' AND tb9.`location_id`='$returnLocation' AND tb1.`price_broker_id`='$broker_id' AND tbA.`is_active`=1 AND tbA.`location_id`='$pickUpLocation' and tbA.`broker_id`='$broker_id' AND tbA.`inventory`>0 AND tbA.`inventory_date`='$pickUpDate_date'";
				$Qry.=" AND tb6.`is_active` =1 AND tb8.`is_active` =1 AND tb7.`week_day`='$pickUpDate_wkno' AND tb7.`is_closed`=0 AND tb7.`is_active`=1 AND (TIME('$pickUpDate_Time') BETWEEN tb7.`start_time_first` AND tb7.`end_time_first` OR TIME('$pickUpDate_Time') BETWEEN tb7.`start_time_second` AND tb7.`end_time_second` ) AND tb9.`week_day`='$returnDate_wkno' AND tb9.`is_closed`=0 AND tb9.`is_active`=1 AND (TIME('$returnDate_Time') BETWEEN tb9.`start_time_first` AND tb9.`end_time_first` OR TIME('$returnDate_Time') BETWEEN tb9.`start_time_second` AND tb9.`end_time_second` )";
				//$Qry.=" "; 
				$Qry.=" AND tb1.`price_date` BETWEEN DATE_FORMAT('$pickUpDate_date','%Y-%m-%d') AND DATE_FORMAT('$returnDate','%Y-%m-%d') ";
				$Qry.=" GROUP BY tb1.`price_location_id`,tb1.`vehicle_id`,tb1.`price_date`) AS X  GROUP BY X.`vehicle_id` ORDER BY X.`vehicle_id`";
				}//buffer time check
		}
		//echo json_encode($Qry);//((tb1.`rate` - tb5.`advance_discount` ) / tb5.`advance_discount` * 100)
		// echo $Qry;
		// exit;
    	$sql      = mysqli_query($conn,$Qry); 
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

/*$end        = microtime(true);
$difference = $end - $started;
$queryTime  = number_format($difference, 2);
echo "</br>SQL query took $queryTime seconds.";	*/

function killstring($mainstring){
	$mainstring = trim($mainstring);
	$badstrings="script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/|/�/�/\/";
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

function minutes($time){
$time = explode(':', $time);
return ($time[0]*60) + ($time[1]) + ($time[2]/60);
}

function journey_days($pickup_date_time = '', $dropoff_date_time = '' , $broker_graceminutes = ''){
	$broker_graceminutes =!empty($broker_graceminutes)?($broker_graceminutes*60):0;
    $pickup_date_time    = strtotime($pickup_date_time);
    $dropoff_date_time   = strtotime($dropoff_date_time)  - $broker_graceminutes;
    $diff         = ($dropoff_date_time - $pickup_date_time) / 86400;
    $ceil         = ceil($diff) - 1;
    $total_days   = ceil($diff);
    $pickup_date  = date('Y-m-d', $pickup_date_time);
    $dropoff_date = date('Y-m-d', strtotime($pickup_date." +".$ceil." days"));
    return array('total_days' => $total_days, 'dropoff_date' => $dropoff_date);
}
?>