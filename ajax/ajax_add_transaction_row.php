<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
?>
<tr id="products_added_service_<?=$unique_id?>">
<td><input type="text" name="products_description[]" value="" class="validate[required] form-control" id="products_description<?=$unique_id?>" placeholder="Description *"/></td>
<td><input type="number" name="products_quantity[]" value="1" class="validate[required] form-control" id="products_quantity_<?=$unique_id?>" onChange="products_change_price('<?=$unique_id?>')" placeholder="Quantity *"/></td>	
<td><input type="text" name="products_unit_price[]" value="" class="validate[required] form-control" id="products_unit_price_<?=$unique_id?>" onChange="products_change_price('<?=$unique_id?>')" placeholder="Unit Value *"/></td>
<td><input type="text" name="products_total_price[]" value="" class="products_total_price form-control" id="products_total_price_<?=$unique_id?>" readonly/></td>
<td><a href="javascript:void(0);" onClick="products_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash fa-lg"></i></a></td>
</tr>