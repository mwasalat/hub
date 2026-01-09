<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$company_id                   = killstring($_REQUEST['company_id']);
list($company,$company_code)  = explode('|',$company_id);
       
			echo "<select class='validate[required] form-control select' name='premium_id' id='premium_id' style='width:100%;'>";
			 $rres4=  mysqli_query($conn, "SELECT `premium_id`,`premium_name` FROM `tbl_cmt_premium_master` WHERE `company_id`='$company' AND `is_active`='1' ORDER BY `premium_name` ASC");
			if(mysqli_num_rows($rres4)>0){
			echo "<option value=''>---Select an option---</option>";
				while ($rrow4=  mysqli_fetch_array($rres4)){
					echo "<option value='$rrow4[premium_id]'><strong>$rrow4[premium_name]</strong></ul>";
				}
			}
			echo "</select>";
		
?> 