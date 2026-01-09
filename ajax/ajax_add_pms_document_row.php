<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$unique_id = uniqid();
	 ?>
<tr id="added_doc_<?=$unique_id?>">
                     <td><input type="text"  name="doc_name[]" value="" class="validate[required] form-control" placeholder="Name#"/></td>
                       <td><input type="file" name="doc_file[]" placeholder="Document"  accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlx, .xlsx" class="validate[checkFileType[jpg|jpeg|png|pdf|doc|docx|xlx|xlsx],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png | .pdf | .doc | .docx | .xls | .xlsx file only"></td>
                     <td><a href="javascript:void(0);" title="Remove" onclick="remove_doc_added('<?=$unique_id?>')" style="cursor:pointer;"><i class="fa fa-minus fa-lg"></i></a></td>
 </tr>