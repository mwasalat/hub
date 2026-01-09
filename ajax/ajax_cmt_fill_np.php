<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$company_id                   = killstring($_REQUEST['company_id']);
list($company,$company_code)  = explode('|',$company_id);
			echo "<select class='validate[required] form-control select' name='np_id' id='np_id' style='width:100%;' onChange='show_np_ac_copay(this.value,$unique_id)'>";
			 $rres4=  mysqli_query($conn, "SELECT `np_id`,`np_name` FROM `tbl_cmt_network_providers` WHERE `company_id`='$company' AND `status`='1' ORDER BY `np_name` ASC");
			 echo "<option value=''>---Select an option---</option>";
			if(mysqli_num_rows($rres4)>0){
			
				while ($rrow4=  mysqli_fetch_array($rres4)){
					echo "<option value='$rrow4[np_id]'><strong>$rrow4[np_name]</strong></ul>";
				}
			}
			echo "</select>";
		
?> 