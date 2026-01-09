<?php 
require_once("../header_footer/header.php");
//Asset Submit
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$asset_id_now        = killstring($_REQUEST['asset_id_now']);
	$company_id          = killstring($_REQUEST['company_id']);
	$emp_id              = killstring($_REQUEST['emp_id']);
	$os_id               = killstring($_REQUEST['os_id']);
	$location_id         = killstring($_REQUEST['location_id']);
	$asset_type_id       = killstring($_REQUEST['asset_type_id']);
	$asset_name          = killstring($_REQUEST['asset_name']);
	$ip_address          = killstring($_REQUEST['ip_address']);
	
	$purchase_date             = killstring($_REQUEST['purchase_date']);
    $purchase_date             = !empty($purchase_date) ? date('Y-m-d',strtotime($purchase_date)) : NULL;
    $purchase_date             = !empty($purchase_date) ? "'$purchase_date'" : "NULL";
	
	$warranty_end_date             = killstring($_REQUEST['warranty_end_date']);
    $warranty_end_date             = !empty($warranty_end_date) ? date('Y-m-d',strtotime($warranty_end_date)) : NULL;
    $warranty_end_date             = !empty($warranty_end_date) ? "'$warranty_end_date'" : "NULL";
	
	$support_end_date             = killstring($_REQUEST['support_end_date']);
    $support_end_date             = !empty($support_end_date) ? date('Y-m-d',strtotime($support_end_date)) : NULL;
    $support_end_date             = !empty($support_end_date) ? "'$support_end_date'" : "NULL";

	$comments                      = killstring($_REQUEST['comments']);
	$IsPageValid = true;	
	if(empty($asset_id_now)){
	$msg   = "Please select asset id!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true	 
		  $sql              = "UPDATE `tbl_ams_assets` SET `company_id`='$company_id', `location_id`='$location_id',`emp_id`='$emp_id',`asset_type_id`='$asset_type_id',`os_id`='$os_id',`asset_name`='$asset_name',`ip_address`='$ip_address',`comments`='$comments',`purchase_date`=$purchase_date,`warranty_end_date`=$warranty_end_date,`support_end_date`=$support_end_date,`updated_by`='$userno',`updated_date`=NOW() WHERE `asset_id`='$asset_id_now'"; 
		  $result           = mysqli_query($conn,$sql);
		  if($result){
		  $msg = "Asset updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}
//Submit Warranty
if ($refreshflag==3){
	if(isset($_POST['submit_warranty'])){
	$asset_id_now_d                  = killstring($_REQUEST['asset_id_now_d']);
	$warranty_start_date             = killstring($_REQUEST['warranty_start_date']);
    $warranty_start_date             = !empty($warranty_start_date) ? date('Y-m-d',strtotime($warranty_start_date)) : NULL;
    $warranty_start_date             = !empty($warranty_start_date) ? "'$warranty_start_date'" : "NULL";
	
	$warranty_end_date_d             = killstring($_REQUEST['warranty_end_date_d']);
    $warranty_end_date_d             = !empty($warranty_end_date_d) ? date('Y-m-d',strtotime($warranty_end_date_d)) : NULL;
    $warranty_end_date_d             = !empty($warranty_end_date_d) ? "'$warranty_end_date_d'" : "NULL";

	$remarks                         = killstring($_REQUEST['remarks']);
	$IsPageValid = true;	
	if(empty($asset_id_now)){
	$msg   = "Please select asset id!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true	 
		 $sqlQry           = mysqli_query($conn,"INSERT INTO `tbl_ams_asset_warranty_history`(`asset_id`, `start_date`, `end_date`, `remarks` ,`entered_by`) VALUES ('$asset_id_now_d',$warranty_start_date,$warranty_end_date,'$remarks','$userno')");
		  $result           = mysqli_query($conn,$sql);
		  if($result){
		  $msg = "Warranty added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}
$tr_id = killstring($_REQUEST['flag']);
$sql  = "SELECT * FROM `tbl_ams_assets` WHERE `asset_id`='$tr_id'";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 
  $asset_id_now      = $build['asset_id'];
  $status            = $build['status'];
  $asset_no          = $build['asset_no'];
  $purchase_date     = $build['purchase_date'];
  $warranty_end_date = $build['warranty_end_date'];
  $support_end_date  = $build['support_end_date'];
  $company_id        = $build['company_id'];
  $location_id       = $build['location_id'];
  $asset_type_id     = $build['asset_type_id'];
  $os_id             = $build['os_id'];
  $emp_id            = $build['emp_id'];
  $comments          = $build['comments'];
  $ip_address        = $build['ip_address'];
  $asset_name        = $build['asset_name'];
  $ip_address        = $build['ip_address'];
 }
}
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Edit Asset</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Asset</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <form role="form" name="trn-form" id="trn-form" method="post" action="#">
       <?php if ($msg) { ?>
      <hr>
      <div class="alert <?=$alert_label?>">
        <?=$msg?>
      </div>
      <hr>
      <?php }?>
        <!-- SELECT2 EXAMPLE -->
        <div class="box box-default" data-select2-id="16">
          <div class="box-header with-border">
            <h3 class="box-title">Edit Asset - <?=$asset_no?></h3>
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
                  <label class="required">Company:</label>
                   <select class="validate[required] form-control select2" name="company_id" id="company_id">
                  <option selected="selected" value="">---Select a Company---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_name`, `company_id` FROM `tbl_emp_company_master` order by `company_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['company_id']?>" <?php if($b1['company_id']==$company_id){echo "selected";}?>>
                  <?=$b1['company_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
                </div>
                <div class="form-group">
                  <label class="required">Employee:</label>
                  <select class="validate[required] form-control select2" name="emp_id" id="emp_id">
                  <option selected="selected" value="">---Select an Employee---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `empid`, `full_name` FROM `tbl_emp_master` WHERE `status` IN ('ACTIVE','RESUME DUTY') order by `full_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['empid']?>" <?php if($b1['empid']==$emp_id){echo "selected";}?>>
                  <?=$b1['full_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
                </div>
                
                <div class="form-group">
                  <label class="required">Operating System(OS):</label>
                  <select class="validate[required] form-control select2" name="os_id" id="os_id">
                  <option selected="selected" value="">---Select an Operating System---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `os_id`, `os_name` FROM `tbl_ams_os_master` WHERE `status`=1 order by `os_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['os_id']?>" <?php if($b1['os_id']==$os_id){echo "selected";}?>>
                  <?=$b1['os_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
                </div>
                
                <div class="form-group">
                  <label class="required">Purchase Date:</label>
                  <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control" id="datepicker"  data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="<?=date('d-m-Y',strtotime($purchase_date))?>" name="purchase_date"> 
                  </div>
                </div>
                
                    <div class="form-group">
                  <label class="required">Support End Date:</label>
                  <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control datepicker1" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="<?=date('d-m-Y',strtotime($support_end_date))?>" name="support_end_date"> 
                  </div>
                </div>
                
             
                 <div class="form-group">
                  <label class="">Comments:</label>
                  <textarea rows="4" name="comments" placeholder="Comments" class="form-control"><?=nl2br($comments)?></textarea>
                </div>
                
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Location:</label>
                  <select class="validate[required] form-control select2" name="location_id" id="location_id">
                  <option selected="selected" value="">---Select a supplier---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_emp_location_master` WHERE `is_active`=1 order by `location_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['location_id']?>" <?php if($b1['location_id']==$location_id){echo "selected";}?>>
                  <?=$b1['location_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
                </div>
                
                <div class="form-group">
                  <label class="required">Asset Type:</label>
                  <select class="validate[required] form-control select2" name="asset_type_id" id="asset_type_id">
                  <option selected="selected" value="">---Select an Asset Type---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `type_id`, `type_name` FROM `tbl_ams_type_master` WHERE `status`=1 order by `type_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['type_id']?>" <?php if($b1['type_id']==$asset_type_id){echo "selected";}?>>
                  <?=$b1['type_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
                </div>
                
                <div class="form-group">
                  <label class="required">Asset Name:</label>
                 <input type="text" class="validate[required] form-control" name="asset_name" placeholder="Asset Name" value="<?=$asset_name?>"/>
                </div>
                
                
                <div class="form-group">
                  <label class="required">Warranty End Date:</label>
                  <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control datepicker1" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="<?=date('d-m-Y',strtotime($warranty_end_date))?>"  name="warranty_end_date"> 
                  </div>
                </div>
                      <div class="form-group">
                  <label class="">IP Address:</label>
                 <input type="text" class="form-control" name="ip_address" placeholder="IP Address" value="<?=$ip_address?>"/>
                </div>
                
                
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
          <input type="hidden" name="asset_id_now" value="<?=$asset_id_now?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
          </div>
        </div>
        <!-- /.box -->
        
        
        <div class="box box-default" data-select2-id="16">
          <div class="box-header with-border">
            <h3 class="box-title">Warranty History</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                    <div class="form-group">
                  <label class="required">Start Date:</label>
                  <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control datepicker1" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="" name="warranty_start_date"> 
                  </div>
                </div>
                
                   <div class="form-group">
                  <label class="required">End Date:</label>
                  <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control datepicker1" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="" name="warranty_end_date_d"> 
                  </div>
                </div>
             
                 <div class="form-group">
                  <label class="">Remarks:</label>
                  <textarea rows="4" name="remarks" placeholder="Remarks" class="form-control"></textarea>
                </div>
                
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
          <input type="hidden" name="asset_id_now_d" value="<?=$asset_id_now?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit_warranty">Submit</button>
          </div>
        </div>
        <!-- /.row -->
        
        
        <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Warranty List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >Start Dt.</th>
                      <th >End Dt.</th>
                      <th >Remarks</th>
                  </thead>
                  <tbody>
                    <?php 
					
					                       $Query = "SELECT * FROM `tbl_ams_asset_warranty_history` WHERE  `asset_id` ='$asset_id_now'  ORDER BY `entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
                                            while($build = mysqli_fetch_array($sqlQ)){
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['end_date']))?>"><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                      <td><?=$build['remarks']?></td>  
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
      </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php require_once("../header_footer/footer.php");?>
<script type="text/javascript">
//Date picker
$('#datepicker').datepicker({
autoclose: true,
format: 'dd-mm-yyyy'
});
$('#datepicker').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });

$('.datepicker1').datepicker({
autoclose: true,
format: 'dd-mm-yyyy',
startDate: '-0d'
});
$('.datepicker1').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });

  $(document).ready(function(){
  $("#trn-form").validationEngine();
  });
  function change_status(val){
	  if(val==2){
	  $("#cancel_reason_div").show();
	  $("#cancel_button").show();
	  }else{
	  $("#cancel_button").hide();
	  $("#cancel_reason_div").hide();	  
	  }
  }
</script>