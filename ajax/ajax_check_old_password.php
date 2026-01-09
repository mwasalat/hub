<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag       = trim($_REQUEST['flag']);
$flag_n     = md5($flag);
$userno     = $_SESSION['userno'];
	$SqlNewQry      = mysqli_query($conn,"SELECT `user_id` FROM `tbl_login` WHERE `user_id`='$userno' and `password`='$flag_n'");
	$NumNewQry      =(mysqli_num_rows($SqlNewQry))?mysqli_num_rows($SqlNewQry):0; 
	if($NumNewQry > 0){
	echo 1;
	exit();
	}      
	else      
	{
	echo 0;
	exit();  
	}   
 
?> 