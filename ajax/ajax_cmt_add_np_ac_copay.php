<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
//$start_date = date('Y-m-d',strtotime(killstring($_REQUEST['start_date'])));
$start_date = date('Y-m-d');
$company_id                   = killstring($_REQUEST['company_id']);
$unique_id                    = killstring($_REQUEST['unique_id']);
?>
    <td><select class="form-control select2" name="np[<?=$company_id?>]" style="width:100%;">
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
    </select></td>
    <td><select class="form-control select2" name="ac[<?=$company_id?>]" style="width:100%;">
      <option selected="selected" value="">---Select Area Of Cover---</option>
      <?php  
                                  $d1 = mysqli_query($conn,"SELECT `ac_id`, `ac_name` FROM `tbl_cmt_area_of_cover` WHERE `company_id`='$company_id' AND `status`=1 order by `ac_name`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
      <option value="<?=$b1['ac_id']?>">
      <?=$b1['ac_name']?>
      </option>
      <?php		
      } 
      ?>
    </select></td>
  <td><select class="form-control select2" name="deductable_copay[<?=$company_id?>]" style="width:100%;">
      <option selected="selected" value="">---Select Deductible / Co-pay---</option>
      <?php  
                                  $d1 = mysqli_query($conn,"SELECT `deductable_copay`, `deductable_copay_id` FROM `tbl_cmt_ins_deductable_copay_master` WHERE `company_id`='$company_id' AND `is_active`=1  order by `deductable_copay`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
      <option value="<?=$b1['deductable_copay_id']?>">
      <?=$b1['deductable_copay']?>
      </option>
      <?php		
      }  
      ?>
    </select></td>
    <td><a href="javascript:void(0);"  onClick="plan_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash-o fa-lg"></i></a></td>
