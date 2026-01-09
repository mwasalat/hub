<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$userno           = $_SESSION['userno'];  
$tr_no            = killstring($_REQUEST['tr_no']);
if(!empty($tr_no)){
	$Query = mysqli_query($conn,"UPDATE `tbl_cmt_ind_premium_transactions` SET `is_active`=0,`updated_by`='$userno',`updated_date`=NOW() WHERE `tr_no`='$tr_no'");
	if($Query){
		echo 1;
	}else{
		echo 0;
	}
}
?>

   
