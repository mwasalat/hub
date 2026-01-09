<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
?> 
  <tr id="added_row_<?=$unique_id?>">
                      <td><select class="form-control select2" name="from[]"> 
                          <option selected="selected" value="">---Select an option---</option>
                          <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?>
                          </select>
                      </td> 
                      <td><select class="form-control select2" name="to[]">
                          <option selected="selected" value="">---Select an option---</option>
                          <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?>
                          </select>
                      </td>
                     <td><input type="text"  name="discount[]" value="0.00" class="validate[custom[number]] form-control" placeholder="Discount"/></td>
                     <td><a href="javascript:void(0);" title="Remove" onclick="remove_row('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>