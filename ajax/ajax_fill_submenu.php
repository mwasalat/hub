<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag = killstring($_REQUEST['flag']);
        $rres4=  mysqli_query($conn, "select `sub_menu_id`,`sub_menu_name` from `tbl_sub_menu` where `main_menu_id`='$flag' AND `is_active`='1'");
		echo "<select class='validate[required] form-control' name='sub_menu' id='sub_menu'><option value=''>---Select an option---</option>";
        while ($rrow4=  mysqli_fetch_array($rres4)){
            echo "<option value='$rrow4[sub_menu_id]'><strong>$rrow4[sub_menu_name]</strong></ul>";
        }
		echo "</select>";
?> 