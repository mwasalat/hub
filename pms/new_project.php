<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$project_name          = killstring($_REQUEST['project_name']);
	$client_type_id        = killstring($_REQUEST['client_type_id']);
	list($client_type_id,$client_type_code)  = explode("|",$client_type_id);
	$category_id           = killstring($_REQUEST['category_id']);
	list($category_id,$category_code)  = explode("|",$category_id);
	$mgr_id                = killstring($_REQUEST['mgr_id']);
	$mgr_id                = !empty($mgr_id) ? "'$mgr_id'" : "NULL";
	$client_id             = killstring($_REQUEST['client_id']);
	list($client_id,$client_code)  = explode("|",$client_id);
	$type_id               = killstring($_REQUEST['type_id']);
	$project_cost          = killstring($_REQUEST['project_cost']);
	$project_cost          = !empty($project_cost) ? "'$project_cost'" : "NULL";
	$project_value         = killstring($_REQUEST['project_value']);
	$project_value         = !empty($project_value) ? "'$project_value'" : "NULL";
	$profit_percentage     = killstring($_REQUEST['profit_percentage']);
	$profit_percentage     = !empty($profit_percentage) ? "'$profit_percentage'" : "NULL";
	$project_description   = killstring($_REQUEST['project_description']);
	$remarks               = killstring($_REQUEST['remarks']);
	$status_id             = killstring($_REQUEST['status_id']);
	
	$country_id                      = killstring($_REQUEST['country_id']);
	list($country_id,$country_code)  = explode("|",$country_id);
	
	$start_date      = killstring($_REQUEST['start_date']);
	$start_date      = !empty($start_date) ? date('Y-m-d',strtotime($start_date)) : NULL;
    $start_date      = !empty($start_date) ? "'$start_date'" : "NULL";
	
	$expected_end_date      = killstring($_REQUEST['expected_end_date']);
	$expected_end_date      = !empty($expected_end_date) ? date('Y-m-d',strtotime($expected_end_date)) : NULL;
    $expected_end_date      = !empty($expected_end_date) ? "'$expected_end_date'" : "NULL";
	
	
	$contact_person  = killstring($_REQUEST['contact_person']);
	$contact_phone   = killstring($_REQUEST['contact_phone']);
	$contact_email   = killstring($_REQUEST['contact_email']);
	
	$IsPageValid = true;	
	if(empty($project_name)){
	$msg   = "Please enter project name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	            /************************Project No Auto*******************/
                $QueryCounterB =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` from `tbl_pms_project_autono`");
				$rowQryB       =  mysqli_fetch_row($QueryCounterB);
				$valueC        =  $rowQryB[0];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_pms_project_autono` set `auto_id`='$valueC'");
				$ordervalueA   =  $valueC;	
				/************************Project No Auto*******************/
				$project_code  = $category_code."-". $country_code."-".$client_type_code."-".$client_code."-".date('y')."-".$ordervalueA ;  
		  $sql              = "INSERT INTO `tbl_pms_projects`(`project_name`, `project_code`, `mgr_id`, `client_type_id`, `client_id`, `category_id`, `country_id`, `service_type_id`, `start_date`, `expected_end_date`, `project_description`, `project_value`, `project_cost`, `profit_percentage`, `contact_person`, `contact_phone`, `contact_email`, `remarks`, `last_remarks`, `status_id`,`entered_by`) VALUES ('".$project_name."','".$project_code."',".$mgr_id.",'".$client_type_id."','".$client_id."','".$category_id."','".$country_id."','".$type_id."',".$start_date.",".$expected_end_date.",'".$project_description."',".$project_value.",".$project_cost.",".$profit_percentage.",'".$contact_person."','".$contact_phone."', '".$contact_email."', '".$remarks."', '".$remarks."','".$status_id."','".$userno."')";
		  $result           = mysqli_query($conn,$sql); 
		  $last_id          = mysqli_insert_id($conn);
		  if($result){
		  /************Purchases DETAILS**************/
		  $cntC                = 0;
		  $purchase_no         = $_REQUEST["purchase_no"];
		  $cntC                = count($purchase_no);
		  for($i=0; $i < $cntC; $i++){
		   $purchase_no         = killstring($_REQUEST['purchase_no'][$i]);
		   $purchase_name       = killstring($_REQUEST['purchase_name'][$i]);
		   $purchase_date       = killstring($_REQUEST['purchase_date'][$i]);
		   $purchase_date       = !empty($purchase_date) ? date('Y-m-d',strtotime($purchase_date)) : NULL;
           $purchase_date       = !empty($purchase_date) ? "'$purchase_date'" : "NULL";
		   $purchase_value   = killstring($_REQUEST['purchase_value'][$i]);
		   $purchase_value   = !empty($purchase_value) ? $purchase_value : 0;
		   $thumbk                     = ""; 
			$image_signature = $_FILES["purchase_document"]["name"][$i];
			$image_signature = killstring($image_signature);
			if($image_signature!='' && $image_signature!=NULL){  
			$file_extn                  = end_extention($image_signature);
			$extension_pos              = strrpos($image_signature, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "PMS_P_".substr($image_signature, 0, $extension_pos) .'_'.time(). substr($image_signature, $extension_pos);
			 if ($_FILES["purchase_document"]["error"][$i] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   /*compressImage($_FILES["purchase_document"]["tmp_name"][$i], "../uploads/pms/purchase/".$thumbk);
				   Thumbnail("../uploads/pms/purchase/".$thumbk, "../uploads/pms/purchase/".$thumbk);*/
				   move_uploaded_file($_FILES["purchase_document"]["tmp_name"][$i],"../uploads/pms/purchase/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["purchase_document"]["tmp_name"][$i],"../uploads/pms/purchase/".$thumbk); 
				   }
			   }     
			 }
		    if(!empty($purchase_no)){
			$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_pms_purchase_history`(`project_id`, `purchase_no`, `purchase_name`, `purchase_date`,`purchase_amount`, `purchase_document`,`entered_by`) VALUES ('".$last_id."','".$purchase_no."','".$purchase_name."',".$purchase_date.",".$purchase_value.",'".$thumbk."','".$userno."')");
			}
		 }
		 /************Purchases DETAILS**************/	
		 /************quotation DETAILS**************/
		  $cntC                = 0;
		  $quotation_no        = $_REQUEST["quotation_no"];
		  $cntC                = count($purchase_no);
		  for($i=0; $i < $cntC; $i++){
		   $quotation_no         = killstring($_REQUEST['quotation_no'][$i]);
		   $quotation_name       = killstring($_REQUEST['quotation_name'][$i]);
		   $quotation_date       = killstring($_REQUEST['quotation_date'][$i]);
		   $quotation_date       = !empty($quotation_date) ? date('Y-m-d',strtotime($quotation_date)) : NULL;
           $quotation_date       = !empty($quotation_date) ? "'$quotation_date'" : "NULL";
		   $quotation_value   = killstring($_REQUEST['quotation_value'][$i]);
		   $quotation_value   = !empty($quotation_value) ? $quotation_value : 0;
		   $thumbk                     = ""; 
			$image_signature = $_FILES["quotation_document"]["name"][$i];
			$image_signature = killstring($image_signature);
			if($image_signature!='' && $image_signature!=NULL){  
			$file_extn                  = end_extention($image_signature);
			$extension_pos              = strrpos($image_signature, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "PMS_Q_".substr($image_signature, 0, $extension_pos) .'_'.time(). substr($image_signature, $extension_pos);
			 if ($_FILES["quotation_document"]["error"][$i] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   /*compressImage($_FILES["quotation_document"]["tmp_name"][$i], "../uploads/pms/quotation/".$thumbk);
				   Thumbnail("../uploads/pms/quotation/".$thumbk, "../uploads/pms/quotation/".$thumbk);*/
				   move_uploaded_file($_FILES["quotation_document"]["tmp_name"][$i],"../uploads/pms/quotation/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["quotation_document"]["tmp_name"][$i],"../uploads/pms/quotation/".$thumbk); 
				   }
			   }     
			 }
		    if(!empty($quotation_no)){
			$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_pms_quotation_history`(`project_id`, `quotation_no`, `quotation_name`, `quotation_date`, `quotation_amount`, `quotation_document`,`entered_by`) VALUES ('".$last_id."','".$quotation_no."','".$quotation_name."','".$quotation_date."',".$quotation_value.",'".$thumbk."','".$userno."')");
			}
		 }
		 /************quotation DETAILS**************/	 
		 
		 /************invoice DETAILS**************/
		  $cntC                = 0;
		  $inv_no        = $_REQUEST["inv_no"];
		  $cntC                = count($purchase_no);
		  for($i=0; $i < $cntC; $i++){
		   $inv_no         = killstring($_REQUEST['inv_no'][$i]);
		   $inv_type       = killstring($_REQUEST['inv_type'][$i]);
		   $inv_type       = !empty($inv_type) ? $inv_type : 0;
		   $inv_date       = killstring($_REQUEST['inv_date'][$i]);
		   $inv_date       = !empty($inv_date) ? date('Y-m-d',strtotime($inv_date)) : NULL;
           $inv_date       = !empty($inv_date) ? "'$inv_date'" : "NULL";
		   $inv_value   = killstring($_REQUEST['inv_value'][$i]);
		   $inv_value   = !empty($inv_value) ? $inv_value : 0;
		   $thumbk                     = ""; 
			$image_signature = $_FILES["inv_document"]["name"][$i];
			$image_signature = killstring($image_signature);
			if($image_signature!='' && $image_signature!=NULL){  
			$file_extn                  = end_extention($image_signature);
			$extension_pos              = strrpos($image_signature, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "PMS_I_".substr($image_signature, 0, $extension_pos) .'_'.time(). substr($image_signature, $extension_pos);
			 if ($_FILES["inv_document"]["error"][$i] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   /*compressImage($_FILES["inv_document"]["tmp_name"][$i], "../uploads/pms/invoice/".$thumbk);
				   Thumbnail("../uploads/pms/invoice/".$thumbk, "../uploads/pms/invoice/".$thumbk);*/
				   move_uploaded_file($_FILES["inv_document"]["tmp_name"][$i],"../uploads/pms/invoice/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["inv_document"]["tmp_name"][$i],"../uploads/pms/invoice/".$thumbk); 
				   }
			   }     
			 }
		    if(!empty($inv_no)){
			$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_pms_invoice_history`(`project_id`, `invoice_no`, `invoice_type_id`, `invoice_date`, `invoice_amount`, `invoice_document`,`entered_by`) VALUES ('".$last_id."','".$inv_no."',".$inv_type.",'".$inv_date."',".$inv_value.",'".$thumbk."','".$userno."')");
			}
		 }
		 /************invoice DETAILS**************/	 
		 
		  /************Document DETAILS**************/
		  $cntC                = 0;
		  $doc_name        = $_REQUEST["doc_name"];
		  $cntC                = count($doc_name);
		  for($i=0; $i < $cntC; $i++){
		   $doc_name         = killstring($_REQUEST['doc_name'][$i]);
		    $thumbk                     = ""; 
			$image_signature = $_FILES["inv_document"]["name"][$i];
			$image_signature = killstring($image_signature);
			if($image_signature!='' && $image_signature!=NULL){  
			$file_extn                  = end_extention($image_signature);
			$extension_pos              = strrpos($image_signature, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "PMS_D_".substr($image_signature, 0, $extension_pos) .'_'.time(). substr($image_signature, $extension_pos);
			 if ($_FILES["doc_file"]["error"][$i] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   /*compressImage($_FILES["doc_file"]["tmp_name"][$i], "../uploads/pms/document/".$thumbk);
				   Thumbnail("../uploads/pms/document/".$thumbk, "../uploads/pms/document/".$thumbk);*/
				   move_uploaded_file($_FILES["doc_file"]["tmp_name"][$i],"../uploads/pms/document/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["doc_file"]["tmp_name"][$i],"../uploads/pms/document/".$thumbk); 
				   }
			   }     
			 }
		    if(!empty($doc_name)){
			$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_pms_documents_history`(`project_id`, `document_name`, `document`,`entered_by`) VALUES ('".$last_id."','".$doc_name."','".$thumbk."','".$userno."')");
			}
		 }
		 /************Document DETAILS**************/
		 
		  /************Document DETAILS**************/
		  if(!empty($remarks)){
			$sqlQryA           = mysqli_query($conn,"INSERT INTO `tbl_pms_remarks_history`(`project_id`, `remarks`, `status`,`entered_by`) VALUES ('".$last_id."','".$remarks."','".$status_id."','".$userno."')");
			}
		  /************Document DETAILS**************/
		 
		  $msg = "Project added successfully";   
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
<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Project New</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Project New</li>
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
          <h3 class="box-title">New Project</h3>
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
                <label class="required">Project Name:</label>
                <input type="text" class="validate[required] form-control" name="project_name" placeholder="Project Name"/>
              </div>
              <div class="form-group">
                <label class="required">Client Type:</label>
                <select class="validate[required] form-control select2" name="client_type_id" id="client_type_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `type_name`, `type_id`, `type_code` FROM `tbl_pms_client_type_master` WHERE `is_active`=1 order by `type_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['type_id']?>|<?=$b1['type_code']?>">
                  <?=$b1['type_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Service Category:</label>
                <select class="validate[required] form-control select2" name="category_id" id="category_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `category_name`, `category_id`, `category_code`  FROM `tbl_pms_service_category_master` WHERE `is_active`=1 order by `category_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['category_id']?>|<?=$b1['category_code']?>">
                  <?=$b1['category_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Start Dt:</label>
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="validate[required] datepickerA form-control" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="<?=date('d-m-Y')?>" name="start_date" >
                </div>
              </div>
              <div class="form-group">
                <label class="required">Project Cost:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="project_cost" placeholder="Project Cost"/>
              </div>
              <div class="form-group">
                <label class="required">Profit Percentage(%):</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="profit_percentage" placeholder="Profit Percentage(%)"/>
              </div>
              <div class="form-group">
                <label class="">Contact Person:</label>
                <input type="text" class="form-control" name="contact_person" placeholder="Contact Person"/>
              </div>
              <div class="form-group">
                <label class="">Contact Email:</label>
                <input type="text" class="validate[custom[email]] form-control" name="contact_email" placeholder="Contact Email"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Project Manager:</label>
                <select class="validate[required] form-control select2" name="mgr_id" id="mgr_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `emp_name`, `emp_code` FROM `tbl_pms_employees` WHERE `is_active`=1 order by `emp_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['emp_code']?>">
                  <?=$b1['emp_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Client:</label>
                <select class="validate[required] form-control select2" name="client_id" id="client_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `client_name`, `client_id`, `client_code` FROM `tbl_pms_clients` WHERE `is_active`=1 order by `client_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['client_id']?>|<?=$b1['client_code']?>">
                  <?=$b1['client_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Service Type:</label>
                <select class="validate[required] form-control select2" name="type_id" id="type_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `type_name`, `type_id` FROM `tbl_pms_service_type_master` WHERE `is_active`=1 order by `type_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['type_id']?>">
                  <?=$b1['type_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Expected Completion Dt:</label>
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="validate[required] datepickerA form-control"  data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="" name="expected_end_date">
                </div>
              </div>
              <div class="form-group">
                <label class="required">Project Value:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="project_value" placeholder="Project Value"/>
              </div>
              <div class="form-group">
                <label class="">Project Country:</label>
                <select class="validate[required] form-control select2" name="country_id" id="country_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `id`, `iso3`, `name` FROM `tbl_country` WHERE `is_active`=1 order by `id`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['id']?>|<?=$b1['iso3']?>">
                  <?=$b1['name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="">Contact Phone:</label>
                <input type="text" class="validate[custom[phone]] form-control" name="contact_phone" placeholder="Contact Phone"/>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Add Purchases</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 5px;">
                  <div class="col-md-6">
                    <div class="input-group"> <span class="input-group-btn">
                      <button class="btn btn-default" type="button" name="submit" onClick="add_purchase()">+ Add Purchase</button>
                      </span> </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Purchase No#</th>
                          <th>Purchase Name#</th>
                          <th>Purchase Date#</th>
                          <th>Purchase Amount#</th>
                          <th>Purchase Document#</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="cbm_tr_purchase">
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
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Add Quotations</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 5px;">
                  <div class="col-md-6">
                    <div class="input-group"> <span class="input-group-btn">
                      <button class="btn btn-default" type="button" name="submit" onClick="add_quotation()">+ Add Quotation</button>
                      </span> </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Quatation No#</th>
                          <th>Quatation Name#</th>
                          <th>Quatation Date#</th>
                          <th>Quatation Amount#</th>
                          <th>Quatation Document#</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="cbm_tr_quotation">
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
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Add Invoices</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 5px;">
                  <div class="col-md-6">
                    <div class="input-group"> <span class="input-group-btn">
                      <button class="btn btn-default" type="button" name="submit" onClick="add_invoices()">+ Add Invoice</button>
                      </span> </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Invoice No#</th>
                          <th>Invoice Type#</th>
                          <th>Invoice Date#</th>
                          <th>Invoice Amount#</th>
                          <th>Invoice Document#</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="cbm_tr_invoice">
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
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Add Documents</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 5px;">
                  <div class="col-md-6">
                    <div class="input-group"> <span class="input-group-btn">
                      <button class="btn btn-default" type="button" name="submit" onClick="add_document()">+ Add Document</button>
                      </span> </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Name#</th>
                          <th>Document#</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="cbm_tr_document">
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
            <div class="col-md-12">
              <div class="form-group">
                <label class="">Project Description(If Any):</label>
                <textarea class="form-control editor1 textarea" name="project_description" id="" placeholder="Project Description" rows="6"/></textarea>
              </div>
              <div class="form-group">
                <label class="">Remark(If Any):</label>
                <textarea class="form-control editor1 textarea" name="remarks" id="" placeholder="Remark" rows="6"/></textarea>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                <select class="validate[required] form-control select2" name="status_id" id="status_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `status_name`, `status_id` FROM `tbl_pms_status_master` WHERE `is_active`=1 order by `status_id`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['status_id']?>">
                  <?=$b1['status_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
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
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});

//Add new invoice
function add_invoices(){
    var scntDiv = $('#cbm_tr_invoice');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_pms_invoice_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    scntDiv.append(xmlhttp.responseText);
	$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
	autoclose: true
	});
	$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
	$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
    });
    }
}  
//Remove new invoices
function remove_inv_added(tr_id){
			$('#added_row_'+tr_id).remove();
}


//Add new document
function add_document(){
    var scntDiv = $('#cbm_tr_document');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_pms_document_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    scntDiv.append(xmlhttp.responseText);
    }
}  
//Remove new document
function remove_doc_added(tr_id){
			$('#added_doc_'+tr_id).remove();
}

//Add new Purchase
function add_purchase(){
    var scntDiv = $('#cbm_tr_purchase');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_pms_purchase_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    scntDiv.append(xmlhttp.responseText);
    }
}  
//Remove new Purchase
function remove_purchase_added(tr_id){
			$('#added_purchase_'+tr_id).remove();
}

//Add new Quotation
function add_quotation(){
    var scntDiv = $('#cbm_tr_quotation');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_pms_quotation_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    scntDiv.append(xmlhttp.responseText);
    }
}  
//Remove new Quotation
function remove_quotation_added(tr_id){
			$('#added_quotation_'+tr_id).remove();
}
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]]})
  })
</script>
<!-- CK Editor -->
<script src="../plugins/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    CKEDITOR.replace('editor1')
  })
</script>
