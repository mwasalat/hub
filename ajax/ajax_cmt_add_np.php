<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
//$start_date = date('Y-m-d',strtotime(killstring($_REQUEST['start_date'])));
$start_date = date('Y-m-d');
$company_ids                   = trim($_REQUEST['company_id']);
list($company_id,$unique_id_n) = explode("|",$company_ids);
$unique_id                     = killstring($_REQUEST['unique_id']);
?>
<select class="form-control select2" name="np[<?=$company_id?>]" style="width:100%;" id="np_<?=$unique_id?>">
      <option selected="selected" value="">---Select Network Provider--</option>
      <?php  
                                  $d1 = mysqli_query($conn,"SELECT `billing_network_id`, `billing_network` FROM `tbl_cmt_ins_direct_billing_network_master` WHERE `company_id`='$company_id' AND `is_active`=1 AND `customer_type`=1 order by `billing_network`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
      <option value="<?=$b1['billing_network_id']?>">
      <?=$b1['billing_network']?>
      </option>
      <?php		
      } 
      ?>
    </select>
   
   
