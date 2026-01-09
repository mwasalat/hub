<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag = killstring($_REQUEST['flag']);
        $rres4=  mysqli_query($conn, "select `sub_module_id`,`sub_name` from `tbl_tms_sub_module` where `module_id`='$flag' AND `status`='1'");
		if(mysqli_num_rows($rres4)>0){
			echo "<label>Ticket Sub Module:</label>";
			echo "<select class='form-control select2' name='sub_module' id='sub_module'>
			<option value=''>---Select an option---</option>";
			while ($rrow4=  mysqli_fetch_array($rres4)){
				echo "<option value='$rrow4[sub_module_id]'><strong>$rrow4[sub_name]</strong></ul>";
			}
			echo "</select>";
		}
?> 