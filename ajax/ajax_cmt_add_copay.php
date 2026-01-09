 
 <?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
//$start_date = date('Y-m-d',strtotime(killstring($_REQUEST['start_date'])));
$start_date = date('Y-m-d');
$company_ids                   = trim($_REQUEST['company_id']);
list($company_id,$unique_id_n) = explode("|",$company_ids);
$unique_id                     = killstring($_REQUEST['unique_id']);
?><select class="form-control select2" name="deductable_copay[<?=$company_id?>]" style="width:80%; "  id="copay_<?=$unique_id?>">
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
    </select>
    
    
     <td><a style="
     font-size: 18px;
    color: red;
    float: right;
    margin-top: -30px;" href="javascript:void(0);"  onClick="plan_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash-o fa-lg"></i></a></td>