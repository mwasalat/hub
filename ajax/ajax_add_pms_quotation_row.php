<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
	 ?>
<tr id="added_quotation_<?=$unique_id?>">
                     <td><input type="text"  name="quotation_no[]" value="" class="validate[required,custom[number]] form-control" placeholder="Quotation No#"/></td>
                     <td><input type="text"  name="quotation_name[]" value="" class="validate[required] form-control" placeholder="Quotation Name#"/></td>
                     <td><input type="text"  name="quotation_date[]" value="<?=date('d-m-Y');?>" class="validate[required,custom[number]] form-control datepickerA"  data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format"/></td>
                      <td><input type="text"  name="quotation_value[]" value="0.00" class="validate[required,custom[number]] form-control" placeholder="Quotation Value"/></td>
                       <td><input type="file" name="quotation_document[]" placeholder="Document"  accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlx, .xlsx" class="validate[checkFileType[jpg|jpeg|png|pdf|doc|docx|xlx|xlsx],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png | .pdf | .doc | .docx | .xls | .xlsx file only"></td>
                     <td><a href="javascript:void(0);" title="Remove" onclick="remove_quotation_added('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>