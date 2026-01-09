<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag = killstring($_REQUEST['flag']);
        $rres4=  mysqli_query($conn, "select `person_id`,`person_name` from `tbl_inv_concerned_person_master` where `supplier_id`='$flag' AND `is_active`='1' ORDER BY `person_name`");
		echo "<select class='validate[required] form-control select3' name='concerned_person[]' id='concerned_person' multiple><option value=''>---Select an option---</option>";
        while ($rrow4=  mysqli_fetch_array($rres4)){
            echo "<option value='$rrow4[person_id]'><strong>$rrow4[person_name]</strong></ul>";
        }
		echo "</select>";
?> 