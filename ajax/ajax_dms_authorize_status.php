<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$userno = $_SESSION['userno'];  
$flag1 = killstring($_REQUEST['flag1']);
$flag2 = killstring($_REQUEST['flag2']);
$status = $flag2 + 1;
 if($status==2){//CEO APPROVAL
	 $qry = "UPDATE `tbl_dms_transactions` SET `status`='$status',`first_approval_by`='$userno',`first_approval_date`=NOW(),`updated_by`='$userno',`updated_date`=NOW() WHERE `transaction_id`='$flag1'";
 }else if($status==3){//ACCOUNTANT APPROVAL
	  $qry = "UPDATE `tbl_dms_transactions` SET `status`='$status',`second_approval_by`='$userno',`second_approval_date`=NOW(),`updated_by`='$userno',`updated_date`=NOW() WHERE `transaction_id`='$flag1'";
 }else{
	 $qry = "";
 }
$sqlQry     = mysqli_query($conn,$qry);
		  if($sqlQry){
		  echo $status;
		  }else{
		  echo 0;    
		  }
?>
