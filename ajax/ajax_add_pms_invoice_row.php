<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
	 ?>
<tr id="added_row_<?=$unique_id?>">
                     <td><input type="text"  name="inv_no[]" value="" class="validate[required,custom[number]] form-control" placeholder="Invoice No#"/></td>
                     <td> <select class="validate[required] form-control select2" name="inv_type[]">
                          <option selected="selected" value="">---Select an option---</option>
                          <?php  
                          $d1 = mysqli_query($conn,"SELECT `invoice_name`, `invoice_id` FROM `tbl_pms_invoicing_master` WHERE `is_active`=1 order by `invoice_name`");
                          while($b1 = mysqli_fetch_array($d1)){
                          ?>
                          <option value="<?=$b1['invoice_id']?>">
                          <?=$b1['invoice_name']?>
                          </option>
                          <?php		
                          } 
                         ?>
                        </select>
                      </td>
                     <td><input type="text"  name="inv_date[]" value="<?=date('d-m-Y');?>" class="validate[required,custom[number]] form-control datepickerA"  data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format"/></td>
                      <td><input type="text"  name="inv_value[]" value="0.00" class="validate[required,custom[number]] form-control" placeholder="Invoice Value"/></td>
                       <td><input type="file" name="inv_document[]" placeholder="Document"  accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlx, .xlsx" class="validate[checkFileType[jpg|jpeg|png|pdf|doc|docx|xlx|xlsx],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png | .pdf | .doc | .docx | .xls | .xlsx file only"></td>
                     <td><a href="javascript:void(0);" title="Remove" onclick="remove_inv_added('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>