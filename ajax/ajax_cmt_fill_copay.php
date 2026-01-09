<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$company_id                   = killstring($_REQUEST['company_id']);
list($company,$company_code)  = explode('|',$company_id);
       
			echo "<select class='validate[required] form-control select' name='copay_id' id='copay_id' style='width:100%;'>";
			 $rres4=  mysqli_query($conn, "SELECT `copay_id`,`copay_name` FROM `tbl_cmt_copay_master` WHERE `company_id`='$company' AND `status`='1' ORDER BY `copay_name` ASC");
			if(mysqli_num_rows($rres4)>0){
			echo "<option value=''>---Select an option---</option>";
				while ($rrow4=  mysqli_fetch_array($rres4)){
					echo "<option value='$rrow4[copay_id]'><strong>$rrow4[copay_name]</strong></ul>";
				}
			}
			echo "</select>";
		
?> 