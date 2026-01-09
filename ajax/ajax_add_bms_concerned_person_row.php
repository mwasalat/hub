<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
?>
<tr id="person_added_service_<?=$unique_id?>">
  <td><input type="hidden" name="person_id[]" value=""/><input type="text" name="person_name[]" value="" class="validate[required] form-control" id="person_name<?=$unique_id?>" placeholder="Name * "/></td>
  <td><input type="text" name="person_position[]" value="" class="validate[required] form-control" id="person_position<?=$unique_id?>" placeholder="Position *"/></td>
  <td><input type="text" name="person_phone[]" value="" class="validate[required,custom[phone]] form-control" id="person_phone<?=$unique_id?>" placeholder="Phone *"/></td>
  <td><select name="person_status[]" class="validate[required] form-control">
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                            </select>
                            </td>
                            
  <td width="10%"><a href="javascript:void(0);" onClick="person_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash fa-lg"></i></a></td>
</tr>
