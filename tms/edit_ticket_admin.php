<?php
require_once("../header_footer/header.php"); 
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
    $ticket_id_now                = killstring($_REQUEST['ticket_id_now']);
	//list($module_id,$module_code) = explode('|',$_REQUEST['module']);
	$ticket_no_now                = killstring($_REQUEST['ticket_no_now']);
	$subject_now                  = killstring($_REQUEST['subject_now']);
	list($assigned_to,$assigned_name) = explode('|',$_REQUEST['assigned_to']);
	$priority                     = killstring($_REQUEST['priority']);
	list($status,$status_name)    = explode('|',$_REQUEST['status']);
	$remark                       = killstring($_REQUEST['remark']);
	$empid_user_now               = killstring($_REQUEST['empid_user_now']);
	$IsPageValid                  = true;	
	if(empty($ticket_id_now)){
	$msg   = "Ticket ID Error!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	
		    $thumbk        = ""; 
			$qryNow        = mysqli_query($conn,"SELECT tb1.`email` from `tbl_emp_master` tb1 WHERE tb1.`empid`='$assigned_to'");
			$rowNOW        = mysqli_fetch_row($qryNow);
			$emp_email     = !empty($rowNOW[0])?$rowNOW[0]:NULL;
			
			$qryNowA        = mysqli_query($conn,"SELECT tb1.`email` from `tbl_emp_master` tb1 WHERE tb1.`empid`='$empid_user_now'");
			$rowNOWA        = mysqli_fetch_row($qryNowA);
			$emp_user_email = !empty($rowNOWA[0])?$rowNOWA[0]:NULL;
			/*$document = $_FILES["document"]["name"];
			$document = killstring($document);
			if($document!='' && $document!=NULL){  
			$file_extn                  = end_extention($document);
			$extension_pos              = strrpos($document, '.'); 
			$thumbk                     = "TMS_A_".substr($document, 0, $extension_pos) .'_'.time(). substr($document, $extension_pos);
			 if ($_FILES["document"]["error"] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   move_uploaded_file($_FILES["document"]["tmp_name"],"../uploads/tms/documents/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["document"]["tmp_name"],"../uploads/tms/documents/".$thumbk); 
				   }
			   }     
			 }*/
			  if($_SESSION['is_IT_SA']==1){
				 $sql     = "UPDATE `tbl_tms_ticket` SET `priority`='$priority',`last_remark`='$remark',`last_remark_by`='$userno',`last_remark_date`=NOW(),`status`='$status',`assigned_to`='$assigned_to',`updated_by`='$userno',`updated_date`=NOW() WHERE ticket_id='$ticket_id_now'";
			  }else{
				$sql     = "UPDATE `tbl_tms_ticket` SET `last_remark_by`='$userno',`last_remark_date`=NOW(),`status`='$status',`updated_by`='$userno',`updated_date`=NOW() WHERE ticket_id='$ticket_id_now'";  
			  }
			  $result  = mysqli_query($conn,$sql); 
			  if($result){
			 /********Document Section****/  
			 /* if(!empty($thumbk)){
				 $document_name    = !empty(killstring($_REQUEST['document_name']))?killstring($_REQUEST['document_name']):"Document";   
				$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_tms_documents`(`ticket_id`, `document_name`, `document`, `document_by`,`status`,`entered_by` , `entered_date`) VALUES ('".$ticket_id_now."','".$document_name."','".$thumbk."',2,'".$status."','".$userno."',NOW())");  
			  }*/
				/********Document Section****/
				/********Remarks Section****/  
			  if(!empty($remark)){
				$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_tms_remarks`(`ticket_id`, `remark`, `status`,`entered_by` , `entered_date`) VALUES ('".$ticket_id_now."','".$remark."','".$status."','".$userno."',NOW())");  
			  }
		       /************EMAIL Trigger**************************/
				$date_time  = date('d-m-Y H:i:s');
				$file_name  = ""; 
				$excel_save = "";
				$subject_val = "Ticket Updation : $ticket_no_now : $status_name";       
				$title       = "Ticket Updation : $ticket_no_now : $status_name"; 
				//$to             = !empty($emp_email)?$emp_email:"peter.natividad@onetechnology.biz"; 
				$to             = "sanil.sadasivan@onetechnology.biz"; 
				//$cc             = "peter.natividad@onetechnology.biz,sanil.sadasivan@onetechnology.biz,".$emp_user_email;
				$cc             = "peter.natividad@onetechnology.biz,sanil.sadasivan@onetechnology.biz";
				/*if(!empty($emp_email)){
				$cc.= ",$emp_email";	
				}*/
				/*if(!empty($emp_user_email)){
				$cc.= ",$emp_user_email";	
				}*/
				$content        = 'Dear Sir/Madame,<br />
								   Please note that there is an updation of your ticket number <b>'.$ordervalue.'</b>.<br /><br />
			<table class="table table-bordered table-striped" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody style="border-collapse:collapse;border:solid 1px #DDD">
                <tr><td style="font-weight:bold;">Date & Time:</td><td>'.$date_time.'</td></tr>
				<tr><td style="font-weight:bold;">Subject:</td><td>'.$subject_now.'</td></tr>
				<tr><td style="font-weight:bold;">Assignee:</td><td>'.$assigned_name.'</td></tr>
				<tr><td style="font-weight:bold;">Status:</td><td>'.$status_name.'</td></tr>
				<tr><td style="font-weight:bold;">Remarks/Comments:</td><td>'.$remark.'</td></tr></tbody></table>';	 
				mail_send_tms($to,$subject_val,$title,$content,$cc);
             /***********************Email Trigger**************************/
		    /********Remarks Section****/
		  $msg = "Ticket updated successfully.";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

if(isset($_REQUEST['flag'])){
	$task = $_REQUEST["flag"];
	$sql  = mysqli_query($conn,"SELECT * FROM `tbl_tms_ticket` WHERE `ticket_id` ='$task'");
	while($build = mysqli_fetch_array($sql)){ 
	   $ticket_id     =  $build['ticket_id'];
	   $ticket_no     =  $build['ticket_no'];
	   $subject       =  $build['subject'];
	   $description   =  $build['description'];
	   $module_id     =  $build['module_id'];
	   $sub_module_id =  $build['sub_module_id'];
	   $priority      =  $build['priority'];
	   $status        =  $build['status'];
	   $assigned_to   =  $build['assigned_to'];
	   $empid_user    =  $build['empid'];
	}
}
					                       
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>TMS Ticket</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">TMS Ticket -
        <?=$ticket_no?>
      </li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <?php if ($msg) { ?>
      <hr>
      <div class="alert <?=$alert_label?>">
        <?=$msg?>
      </div>
      <hr>
      <?php }?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">TMS Ticket  -
            <?=$ticket_no?>
          </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Ticket Module:</label>
                <select class="validate[required] form-control bg-gray disabled color-palette" name="module" id="module" disabled="disabled">
                  <option value="">---Select a modulue---</option>
                  <?php  
							 $sql  = "SELECT `module_id`, `code`, `name` FROM `tbl_tms_module` ORDER BY `name`";
				             $data = mysqli_query($conn,$sql);
                             while($build = mysqli_fetch_array($data)){
							 ?>
                  <option value="<?=$build['module_id']?>|<?=$build['code']?>" <?php if($module_id==$build['module_id']){echo "selected";}?>>
                  <?=$build['name']?>
                  </option>
                  <?php 
							 }?>
                </select>
              </div>
              <?php if(!empty($sub_module_id)){?>
              <div class="form-group">
                <label class="">Ticket Sub Module:</label>
                <select class="form-control bg-gray disabled color-palette" name="sub_module_id" id="sub_module_id" disabled="disabled">
                  <option value="">---Select a Sub modulue---</option>
                  <?php  
							 $sql  = "SELECT `sub_module_id`, `sub_name` FROM `tbl_tms_sub_module` ORDER BY `sub_name`";
				             $data = mysqli_query($conn,$sql);
                             while($build = mysqli_fetch_array($data)){
							 ?>
                  <option value="<?=$build['sub_module_id']?>" <?php if($sub_module_id==$build['sub_module_id']){echo "selected";}?>>
                  <?=$build['sub_name']?>
                  </option>
                  <?php 
							 }?>
                </select>
              </div>
              <?php }?>
              <div class="form-group">
                <label class="required">Ticket Subject:</label>
                <input type="text" class="validate[required] form-control bg-gray disabled color-palette" name="subject" placeholder="Ticket Subject" value="<?=$subject?>" disabled="disabled"/>
              </div>
              
              
              <div class="form-group">
                <label class="required">Priority:</label>
                <select class="validate[required] form-control select2" name="priority" id="priority" <?php if($_SESSION['is_IT_SA']==0){?>disabled="disabled"<?php }?>>
                  <option value="">---Select a Priority---</option>
                  <?php  
							 $sql  = "SELECT `priority_id`, `priority_name` FROM `tbl_tms_priority_master` WHERE `is_active`=1 ORDER BY `priority_id` ASC ";
				             $data = mysqli_query($conn,$sql);
                             while($build = mysqli_fetch_array($data)){
							 ?>
                  <option value="<?=$build['priority_id']?>" <?php if($priority==$build['priority_id']){echo "selected";}?>>
                  <?=$build['priority_name']?>
                  </option>
                  <?php 
							 }?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="">Ticket Description:</label>
                <textarea type="text" class="form-control bg-gray disabled color-palette" name="description" placeholder="Ticket Description" rows="5" disabled="disabled" ><?=nl2br($description)?>
</textarea>
              </div>
              <div class="form-group">
                <label class="required">Assigned To:</label>
                <select class="validate[required] form-control select2" name="assigned_to" id="assigned_to" <?php if($_SESSION['is_IT_SA']==0){?>disabled="disabled"<?php }?>>
                 <option value="">---Select an assignee ---</option>
                 <?php  
				            $sqlQry  = mysqli_query($conn,"SELECT tb1.`empid`, tb2.`full_name` FROM `tbl_tms_sub_module_members`tb1 INNER JOIN `tbl_login` tb2 ON tb1.`empid`=tb2.`empid` WHERE tb1.`sub_module_id`='$sub_module_id' AND tb1.`status`=1 ORDER BY tb2.`full_name` ASC ");
				             if(mysqli_num_rows($sqlQry)==0){
							 $sqlQry  = mysqli_query($conn,"SELECT tb1.`empid`, tb2.`full_name` FROM `tbl_tms_module_members`tb1 INNER JOIN `tbl_login` tb2 ON tb1.`empid`=tb2.`empid` WHERE tb1.`module_id`='$module_id' AND tb1.`status`=1 ORDER BY tb2.`full_name` ASC ");
				             if(mysqli_num_rows($sqlQry)==0){
							 $sqlQry  = mysqli_query($conn,"SELECT `empid`,`full_name` FROM `tbl_login` WHERE `is_IT`=1 AND `status`=1 ORDER BY `full_name` ASC ");	 
							 }
							 }
                             while($build = mysqli_fetch_array($sqlQry)){
							 ?>
                  <option value="<?=$build['empid']?>|<?=$build['full_name']?>" <?php if($assigned_to==$build['empid']){echo "selected";}?>>
                  <?=$build['full_name']?> - <?=$build['empid']?> 
                  </option>
                  <?php 
							 }?>
                </select>
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!-- /.box-body -->
         <!---------------Documents Section--------------->
        <?php 
        $sqlA           = mysqli_query($conn,"SELECT tb1.`document_name`, tb1.`document`, tb1.`entered_date`,tb2.`full_name` FROM `tbl_tms_documents` tb1 LEFT JOIN `tbl_login` tb2 ON tb1.`entered_by`=tb2.`user_id` WHERE tb1.`ticket_id`='$ticket_id' AND tb1.`is_active`=1 ORDER BY tb1.`entered_date` DESC");
		$NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
		if($NumA>0){
		?>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Documents History</h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Dt.</th>
                          <th>Name</th>
                          <th>Document</th>
                          <th>By</th>
                        </tr>
                      </thead>
                      <tbody >
                        <?php		  
						 while($buildA = mysqli_fetch_array($sqlA)){ 
						 $u_pathn = '../uploads/tms/documents/'; 
						 ?>
                        <tr>
                          <td><?=date('d-m-Y',strtotime($buildA['entered_date']))?></td>
                          <td><?=$buildA['document_name']?></td>
                          <td><?php if ($buildA['document'] != '' && file_exists($u_pathn . $buildA['document'] )) {?>
                            <a href="<?=$u_pathn . $buildA['document']?>" target="_blank"><i class="fa fa-file-o fa-lg"></i></a>
                            <?php }?></td>
                          <td><?=$buildA['full_name']?></td>
                        </tr>
                        <?php
							 }
						?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php }?>
        <!---------------Documents Section--------------->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Remarks  History</h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Dt.</th>
                          <th>Remark</th>
                          <th>Status</th>
                          <th>By</th>
                        </tr>
                      </thead>
                      <tbody >
                        <?php		  
						$sqlA           = mysqli_query($conn,"SELECT tb1.`remark`, tb1.`entered_date`,tb2.`full_name`,tb3.`status_name` FROM `tbl_tms_remarks` tb1 LEFT JOIN `tbl_login` tb2 ON tb1.`entered_by`=tb2.`user_id` LEFT JOIN `tbl_tms_status_master` tb3 ON tb1.`status`=tb3.`status_id` WHERE tb1.`ticket_id`='$ticket_id' ORDER BY tb1.`entered_date` DESC");
						 $NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
						 if($NumA>0){
						 while($buildA = mysqli_fetch_array($sqlA)){ 
						 ?>
                        <tr>
                          <td><?=date('d-m-Y',strtotime($buildA['entered_date']))?></td>
                          <td><?=$buildA['remark']?></td>
                          <td><?=$buildA['status_name']?></td>
                          <td><?=$buildA['full_name']?></td>
                        </tr>
                        <?php
							 }
						 }else{
						 echo "<tr><td colspan='4'>No remarks found!</td></tr>";
						 }
						?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Remarks/Comments:</label>
                <textarea type="text" class="validate[required] form-control" name="remark" placeholder="Remarks/Comments" rows="4"></textarea>
              </div>
            </div>
            
             <div class="col-md-6">
          <?php /*?>    <div class="form-group">
                <label class="">Document(If any):</label>
                <div style="width:100%; display:inline-flex"/>
                <input type="text" name="document_name" value="" class="form-control" style="width:48%" placeholder="Document Name">
                <input type="file" name="document" placeholder="Document"  accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .xlx, .xlsx" class="validate[checkFileType[jpg|jpeg|png|pdf|doc|docx|xlx|xlsx],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png | .pdf | .doc | .docx | .xls | .xlsx file only"   style="width:48%; margin-left:10px;" >
              </div><?php */?>
                <div class="form-group">
                <label class="required">Status:</label>
                <select class="validate[required] form-control select2" name="status" id="status">
                  <option value="">---Select a Status---</option>
                  <?php
							 $sql  = "SELECT `status_id`, `status_name` FROM `tbl_tms_status_master` WHERE `is_active`=1 ORDER BY `status_id` ASC ";
				             $data = mysqli_query($conn,$sql);
                             while($build = mysqli_fetch_array($data)){
							 ?>
                  <option value="<?=$build['status_id']?>|<?=$build['status_name']?>" <?php if($status==$build['status_id']){echo "selected";}?>>
                  <?=$build['status_name']?>
                  </option>
                  <?php 
							 }?>
                </select>
              </div>
             </div>
            <!-- /.row -->
          </div>
        </div>
        <div class="box-footer">
          <input type="hidden" name='ticket_id_now' value= '<?=$ticket_id?>' >
          <input type="hidden" name='ticket_no_now' value= '<?=$ticket_no?>' >
          <input type="hidden" name='subject_now' value= '<?=$subject?>' >
           <input type="hidden" name='description_now' value= '<?=$description?>' >
          <input type="hidden" name='empid_user_now' value= '<?=$empid_user?>' >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
           <a type="submit" class="btn btn-warning pull-left" href="list_ticket_admin.php?gtoken=<?=$token?>">Back</a>
          <?php if($status!=4 && $status!=5){?><button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button><?php }?>
        </div>
      </div>
      <!-- /.box -->
      <!-- /.row -->
    </form>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
<script type="text/javascript">
$(document).ready(function(){
$("#company-form").validationEngine();
$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]]})
  })
  
</script>