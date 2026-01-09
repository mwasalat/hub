<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag     = killstring($_REQUEST['flag']);
if(!empty($flag)){
$data     = mysqli_query($conn,"DELETE FROM `tbl_bms_location_exception_timing_master` WHERE `exception_id`='$flag'");
echo 1;
}else{
echo 0;
}   
?>