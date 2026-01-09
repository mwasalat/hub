<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
	 ?>
<tr id="added_purchase_<?=$unique_id?>">
                     <td><input type="text"  name="purchase_no[]" value="" class="validate[required,custom[number]] form-control" placeholder="Purchase No#"/></td>
                     <td><input type="text"  name="purchase_name[]" value="" class="validate[required] form-control" placeholder="Purchase Name#"/></td>
                     <td><input type="text"  name="purchase_date[]" value="<?=date('d-m-Y');?>" class="validate[required,custom[number]] form-control datepickerA"  data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format"/></td>
                      <td><input type="text"  name="purchase_value[]" value="0.00" class="validate[required,custom[number]] form-control" placeholder="Purchase Value"/></td>
                       <td><input type="file" name="purchase_document[]" placeholder="Document"  accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlx, .xlsx" class="validate[checkFileType[jpg|jpeg|png|pdf|doc|docx|xlx|xlsx],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png | .pdf | .doc | .docx | .xls | .xlsx file only"></td>
                     <td><a href="javascript:void(0);" title="Remove" onclick="remove_purchase_added('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>