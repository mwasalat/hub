<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag = killstring($_REQUEST['flag']);
if(!empty($flag)){
    $res4=  mysqli_query($conn, "select tb1.`main_menu_id`,tb2.`main_menu_name` from `tbl_user_menu` tb1 LEFT JOIN `tbl_main_menu` tb2 ON tb1.`main_menu_id`=tb2.`main_menu_id` where tb1.`user_id`='$flag' GROUP BY tb1.`main_menu_id` ORDER BY tb2.`main_menu_name` ASC");
    while ($row4=  mysqli_fetch_array($res4)){
		$main_menu_id    = $row4['main_menu_id'];
		$main_menu_name  = $row4['main_menu_name'];	
            echo "<ul style='margin-top: 10px; margin-bottom: 0px'><strong>$main_menu_name</strong></ul>";
            $rrres4=  mysqli_query($conn, "SELECT tb1.`sub_menu_name` FROM `tbl_sub_menu` tb1 LEFT JOIN `tbl_user_menu` tb2 ON tb1.`sub_menu_id`=tb2.`sub_menu_id` WHERE tb1.`main_menu_id`='$main_menu_id' AND tb2.`user_id`='$flag' AND tb1.`is_active`='1' ORDER BY tb1.`sub_menu_name` ASC");
            while ($rrrow4=  mysqli_fetch_array($rrres4)){
			$sub_menu_name  = $rrrow4['sub_menu_name'];		
            echo "<li style='padding-left: 60px;'>$sub_menu_name</li>";
        }
    }
}
?> 