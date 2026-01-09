<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag = killstring($_REQUEST['flag']);
$unique_id = uniqid();
if(!empty($flag)){
?> 
  <tr id="added_row_<?=$unique_id?>">
                      <td><select class="form-control select2" name="from_[<?=$flag?>][]"> 
                          <option selected="selected" value="">---Select an option---</option>
                          <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?>
                          </select>
                      </td>
                      <td><select class="form-control select2" name="to_[<?=$flag?>][]">
                          <option selected="selected" value="">---Select an option---</option>
                          <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?>
                          </select>
                      </td>
                     <td><input type="text"  name="discount_[<?=$flag?>][]" value="0.00" class="validate[custom[number],min[0],max[75]] form-control" placeholder="Discount"/></td>
                     <td><input type="hidden" name="parameter_id[]" value="<?=$flag?>"/>
                     <a href="javascript:void(0);" title="Remove" onclick="remove_row('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>
<?php
}?>