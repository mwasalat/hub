<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$company_id                   = killstring($_REQUEST['company_id']);
list($company,$company_code)  = explode('|',$company_id);
			echo "<select class='validate[required] form-control select' name='ac_id' id='ac_id' style='width:100%;'>";
			 $rres4=  mysqli_query($conn, "SELECT `ac_id`,`ac_name` FROM `tbl_cmt_area_of_cover` WHERE `company_id`='$company' AND `status`='1' ORDER BY `ac_name` ASC");
			if(mysqli_num_rows($rres4)>0){
			echo "<option value=''>---Select an option---</option>";
				while ($rrow4=  mysqli_fetch_array($rres4)){
					echo "<option value='$rrow4[ac_id]'><strong>$rrow4[ac_name]</strong></ul>";
				}
			}
			echo "</select>";
?> 