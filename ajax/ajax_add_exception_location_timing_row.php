<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid(); 
?>
<tr id="products_added_service_<?=$unique_id?>">
<td><input type="hidden" name="exception_id[]" vale=""/><input type="text" name="exception_name[]" value="" class="validate[required] form-control" placeholder="Name"/></td>
<td>   <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control datepicker" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="" name="exception_date[]"> 
                  </div>
                  </td>
<td>  <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="exception_start_time[]" placeholder="Start Time" value="00:00">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div></td>	
<td>  <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="exception_end_time[]" placeholder="End Time" value="23:59">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div></td>
<td>
 <select class="validate[required] form-control" name="exception_is_active[]">
                 <option value="1">Active</option>
                 <option value="0">Not Active</option>
                </select>
</td>
<td><a href="javascript:void(0);" onClick="products_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash fa-lg"></i></a></td>
</tr>