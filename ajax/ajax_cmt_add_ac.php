 
 <?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
//$start_date = date('Y-m-d',strtotime(killstring($_REQUEST['start_date'])));
$start_date = date('Y-m-d');
$company_ids                   = trim($_REQUEST['company_id']);
list($company_id,$unique_id_n) = explode("|",$company_ids);
$unique_id                     = killstring($_REQUEST['unique_id']);
?><select class="form-control select2" name="ac[<?=$company_id?>]" style="width:100%;" id="ac_<?=$unique_id?>">
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
    </select>