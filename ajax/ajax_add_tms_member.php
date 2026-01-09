<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
	 ?>
<tr id="added_row_<?=$unique_id?>">
                     <td> <select class="validate[required] form-control select2" name="member_empid[]">
                          <option selected="selected" value="">---Select an option---</option>
                          <?php  
                          $d1 = mysqli_query($conn,"SELECT `empid`, `full_name` FROM `tbl_login` WHERE `status`=1 AND `is_IT`=1 order by `full_name`");
                          while($b1 = mysqli_fetch_array($d1)){
                          ?>
                          <option value="<?=$b1['empid']?>">
                          <?=$b1['full_name']?> - <?=$b1['empid']?> 
                          </option>
                          <?php		
                          } 
                         ?>
                        </select>
                      </td>
                     <td><a href="javascript:void(0);" title="Remove" onclick="remove_member_added('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>