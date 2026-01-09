<?php
require_once("../header_footer/header.php"); 
/*phpinfo();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	list($module_id,$module_name) = explode('|',$_REQUEST['module']);
	$subject                      = killstring($_REQUEST['subject']);
	$description                  = killstring($_REQUEST['description']);
	$sub_module                  = killstring($_REQUEST['sub_module']);
	$sub_module                  = !empty($sub_module)?$sub_module:"NULL";
	$IsPageValid                 = true;	
	if(empty($module_id)){
	$msg   = "Please select module!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 /******************Auto RFH NO***********************/
		    //$qryNow = mysqli_query($conn,"SELECT tb2.`company_code`,tb1.`email` from `tbl_emp_master` tb1 LEFT JOIN `tbl_emp_company_master` tb2 ON tb1.`company`=tb2.`company_name` WHERE tb1.`empid`='$empid'");
			$qryNow = mysqli_query($conn,"SELECT `company_code` from `tbl_emp_company_master` WHERE `company_id`='$_SESSION[usercompany]'");
			$rowNOW        = mysqli_fetch_row($qryNow);
			$comp_code     = !empty($rowNOW[0])?$rowNOW[0]:"OTH";
			//$emp_email     = $rowNOW[1];
			$month_year    = str_pad(date('m'), 2, "0", STR_PAD_LEFT)."".date('Y');
			$QueryCounter  = mysqli_query($conn,"SELECT `auto_id`+1 as `id` from `tbl_tms_auto_no`");
			$rowQryA       = mysqli_fetch_row($QueryCounter);
			$value2A       = $rowQryA[0];
			$value2        = str_pad($value2A, 5, "0", STR_PAD_LEFT);
			$QueryCounterA = mysqli_query($conn,"UPDATE `tbl_tms_auto_no` set `auto_id`='$value2A'");
			$ordervalue    = $comp_code."-".$month_year."-".$value2;
		 /**************************************************/
		  $sql     = "INSERT IGNORE INTO `tbl_tms_ticket`(`module_id`,`sub_module_id`,`ticket_no`,`subject`,`description`, `company_id`,`empid`, `entered_by`,`entered_date`) VALUES ('$module_id',$sub_module,'$ordervalue','$subject','$description','$_SESSION[usercompany]','$empid','$userno',NOW())";
		  $result  = mysqli_query($conn,$sql); 
		  $last_id = mysqli_insert_id($conn);
		  if($result){
		  /************Document DETAILS**************/
		  $cntC                = 0;
		  $doc_name        = $_REQUEST["doc_name"];
		  $cntC                = count($doc_name);
		  for($i=0; $i < $cntC; $i++){
		    $doc_name = killstring($_REQUEST['doc_name'][$i]);
		    $thumbk   = ""; 
			$document = $_FILES["document"]["name"][$i];
			$document = killstring($document);
			if($document!='' && $document!=NULL){  
			$file_extn                  = end_extention($document);
			$extension_pos              = strrpos($document, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "TMS_U_".substr($document, 0, $extension_pos) .'_'.time(). substr($document, $extension_pos);
			 if ($_FILES["document"]["error"][$i] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   /*compressImage($_FILES["purchase_document"]["tmp_name"][$i], "../uploads/tms/documents/".$thumbk);
				   Thumbnail("../uploads/tms/documents/".$thumbk, "../uploads/tms/documents/".$thumbk);*/
				   move_uploaded_file($_FILES["document"]["tmp_name"][$i],"../uploads/tms/documents/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["document"]["tmp_name"][$i],"../uploads/tms/documents/".$thumbk); 
				   }
			   }     
			 }
		    if(!empty($doc_name)){
			$sqlQryA           = mysqli_query($conn,"INSERT IGNORE INTO `tbl_tms_documents`(`ticket_id`, `document_name`, `document`, `document_by`, `entered_by` , `entered_date`) VALUES ('".$last_id."','".$doc_name."','".$thumbk."',1,'".$userno."',NOW())");
			}
		 }
		 /************Document DETAILS**************/	
		  /************EMAIL Trigger**************************/
				$date_time  = date('d-m-Y H:i:s');
				$file_name  = ""; 
				$excel_save = "";
				$subject_val = "New Ticket : $ordervalue : $subject";       
				$title       = "New Ticket : $ordervalue : $subject"; 
				//$to             = !empty($emp_email)?$emp_email:"peter.natividad@onetechnology.biz"; 
				$to             = "sanil.sadasivan@onetechnology.biz"; 
				$cc             = "peter.natividad@onetechnology.biz,sanil.sadasivan@onetechnology.biz";
				/*if(!empty($_SESSION['useremail'])){
				$cc.= ",$_SESSION[useremail]";	
				}*/
				$content        = 'Dear Sir/Madame,<br />
								   Thank you for contacting One Technology Team, your request has been logged with ticket number <b>'.$ordervalue.'</b>.<br /><br />
			<table class="table table-bordered table-striped" width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody style="border-collapse:collapse;border:solid 1px #DDD">
                <tr><td style="font-weight:bold;">Date & Time:</td><td>'.$date_time.'</td></tr>
				<tr><td style="font-weight:bold;">Module:</td><td>'.$module_name.'</td></tr>
				<tr><td style="font-weight:bold;">Subject:</td><td>'.$subject.'</td></tr>
				<tr><td style="font-weight:bold;">Description:</td><td>'.$description.'</td></tr>
				</tbody></table>';	 
				mail_send_tms($to,$subject_val,$title,$content,$cc);
             /***********************Email Trigger**************************/
			  
		  $msg = "Ticket created successfully. Your Ticket No.is <span style='font-weight:bold;color: yellow;'>$ordervalue</span>.";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
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
      <li class="active">TMS Ticket</li>
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
          <h3 class="box-title">TMS Ticket</h3>
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
                             <select class="validate[required] form-control select2" name="module" id="module">
							 <option value="">---Select a modulue---</option>
							 <?php  
							 $sql  = "SELECT `module_id`, `code`, `name` FROM `tbl_tms_module` WHERE `status`=1 ORDER BY `name`";
				             $data = mysqli_query($conn,$sql);
                             while($build = mysqli_fetch_array($data)){
							 ?>
							 <option value="<?=$build['module_id']?>|<?=$build['name']?>"><?=$build['name']?></option>
							 <?php 
							 }?>
                           </select>
              </div>
               <div class="form-group" id="sub_module_div"></div>
               
            </div>
            
            <div class="col-md-6">
               <div class="form-group">
                <label class="required">Ticket Subject:</label>
                <input type="text" class="validate[required] form-control" name="subject" placeholder="Ticket Subject"/>
              </div>
            </div>
            
            
            <div class="col-md-12">
              <div class="form-group">
                <label class="">Ticket Description:</label>
                <textarea type="text" class="validate[required] form-control" name="description" placeholder="Ticket Description" rows="6"></textarea>
              </div>
            </div>
            
            <!-- /.row -->
          </div>
          </div>
          <!-- /.box-body -->
          <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Supporting Documents (If Any)</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 5px;">
                  <div class="col-md-6">
                    <div class="input-group"> <span class="input-group-btn">
                      <button class="btn btn-default" type="button" name="submit" onClick="add_document()">+ Add Document Row</button>
                      </span> </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Document</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="cbm_tr_member">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="box-footer">
            <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
            <button type="submit" class="btn btn-primary btn-lg pull-right" name="submit" style="width:100%;">SUBMIT</button>
          </div>
        </div>
      <!-- /.box -->
      <?php /*?><div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">TMS Module List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Module</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_tms_module` ORDER BY `name` ASC");
										   $itr = 0;
                                            while($build = mysqli_fetch_array($sqlQ)){
												 // Status
											   if($build['status'] == "1") {
													$class = 'bg-success';
													$label = "label-success";
													$type = 'Acive';
												} else{
													$class = 'bg-danger';
													$type  = 'Inactive';
													$label = "label-danger";
												}
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['name']?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
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
      </div><?php */?>
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
	 //Timepicker
    $('#grace_timepicker').timepicker({
      showInputs: false,
	  showMeridian: false 
    });
  })
  
 //Add new Purchase
function add_document(){
    var scntDiv = $('#cbm_tr_member');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_tms_document.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    scntDiv.append(xmlhttp.responseText);
	$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
    });
    }
}  
//Remove new Purchase
function remove_member_added(tr_id){
			$('#added_row_'+tr_id).remove();
} 

//check sub module
$(document).ready(function(){
$("#module").change(function(){
	$("#sub_module_div").html("");
    var id       = this.value;
	var id_val   = id.split('|');
    var id_value = id_val[0];
	var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_tms_fill_submodule.php?flag="+id_value,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    $('#sub_module_div').html(xmlhttp.responseText);
	$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
    });
    }
}); 

});
</script>