<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 

$start_date = date('Y-m-d',strtotime(killstring($_REQUEST['start_date'])));
$start_date = date('Y-m-d');
$company_ids         = trim($_REQUEST['company_id']);
list($company_id,$unique_id_n) = explode("|",$company_ids);
$unique_id = killstring($_REQUEST['unique_id']);
?>
<select class="form-control select2" name="price_batch[<?=$company_id?>]"  onChange="show_np('<?=$company_id?>','<?=$unique_id?>');show_ac('<?=$company_id?>','<?=$unique_id?>');show_copay('<?=$company_id?>','<?=$unique_id?>');"> 
      <option selected="selected" value="">---Select Plan---</option>
      <?php  
      $d1 = mysqli_query($conn,"SELECT tb1.`batch_name`, tb1.`tr_no`, tb1.`price_batch_id`,tb2.`premium_name`,tb2.`premium_id` FROM `tbl_cmt_ins_price_batch_master` tb1 LEFT JOIN `tbl_cmt_premium_master` tb2 ON tb1.`premium_id`=tb2.`premium_id` WHERE tb1.`company_id`='$company_id' AND tb1.`is_active`=1 AND tb1.`customer_type`=0 AND '$start_date' BETWEEN tb1.`start_date` AND tb1.`end_date` order by tb1.`batch_name`");
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
</select>