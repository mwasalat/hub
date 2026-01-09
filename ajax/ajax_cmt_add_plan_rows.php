<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid(); 
//$start_date = date('Y-m-d',strtotime(killstring($_REQUEST['start_date'])));
$start_date = date('Y-m-d'); 
?>
<tr id="tr_plan_<?=$unique_id?>" style="border: 2px solid #cbb8b8;margin: 10px;">
  <td><select class="form-control select2" name="company_id[]"  onChange="show_plan(this.value,'<?=$unique_id?>')">
      <option selected="selected" value="">Select Company</option>
      <?php  
      $d1 = mysqli_query($conn,"SELECT tb1.`company_name`,tb1.`company_id` FROM `tbl_cmt_ins_company_master` tb1 INNER JOIN `tbl_cmt_ins_price_batch_master` tb2  ON tb1.`company_id`=tb2.`company_id` AND tb2.`customer_type`=0 WHERE tb1.`is_active`='1'  AND '$start_date' BETWEEN tb2.`start_date` AND tb2.`end_date` GROUP BY tb1.`company_id` ORDER BY tb1.`company_name` ASC");
      while($b1 = mysqli_fetch_array($d1)){
      ?>
      <option value="<?=$b1['company_id']?>|<?=$unique_id?>"><?=$b1['company_name']?></option>
      <?php		
      } 
      ?>
    </select></td>
    
   <td id="td_plan_<?=$unique_id?>"></td>
   <td id="td_np_<?=$unique_id?>"></td>
   <td id="td_ac_<?=$unique_id?>"></td>
   <td id="td_copay_<?=$unique_id?>"></td>    
 <?php /*?> <td><select class="form-control select2" name="price_batch[<?=$build['company_id']?>]" style="width:100%;">
      <option selected="selected" value="">---Select Plan---</option>
      <?php  
                                  $d1 = mysqli_query($conn,"SELECT tb1.`batch_name`, tb1.`tr_no`, tb1.`price_batch_id`,tb2.`premium_name`,tb2.`premium_id` FROM `tbl_cmt_ins_price_batch_master` tb1 LEFT JOIN `tbl_cmt_premium_master` tb2 ON tb1.`premium_id`=tb2.`premium_id` WHERE tb1.`company_id`='$build[company_id]' AND tb1.`is_active`=1 AND tb1.`customer_type`=0 AND '$start_date' BETWEEN tb1.`start_date` AND tb1.`end_date` order by tb1.`batch_name`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
      <option value="<?=$b1['price_batch_id']?>|<?=$b1['premium_id']?>">
      <?=$b1['premium_name']?>
      (
      <?=$b1['batch_name']?>
      ) </option>
      <?php		
      } 
      ?>
    </select></td>
  <td><select class="form-control select2" name="billing_network[<?=$build['company_id']?>]" style="width:100%;">
      <option selected="selected" value="">---Select Direct Billing Network---</option>
      <?php  
                                  $d1 = mysqli_query($conn,"SELECT `billing_network`, `billing_network_id` FROM `tbl_cmt_ins_direct_billing_network_master` WHERE `company_id`='$build[company_id]' AND `is_active`=1 AND `customer_type`=1 order by `billing_network`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
      <option value="<?=$b1['billing_network_id']?>">
      <?=$b1['billing_network']?>
      </option>
      <?php		
      } 
      ?>
    </select></td>
  <td><select class="form-control select2" name="deductable_copay[<?=$build['company_id']?>]" style="width:100%;">
      <option selected="selected" value="">---Select Deductible / Co-pay---</option>
      <?php  
                                  $d1 = mysqli_query($conn,"SELECT `deductable_copay`, `deductable_copay_id` FROM `tbl_cmt_ins_deductable_copay_master` WHERE `company_id`='$build[company_id]' AND `is_active`=1 AND `customer_type`=1 order by `deductable_copay`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
      <option value="<?=$b1['deductable_copay_id']?>">
      <?=$b1['deductable_copay']?>
      </option>
      <?php		
      }  
      ?>
    </select></td>
    <td><a href="javascript:void(0);"  onClick="plan_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash-o fa-lg"></i></a></td><?php */?>
</tr>
