<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";

if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$start_date      = $_REQUEST['start_date'];
	$start_date      = date('Y-m-d',strtotime($start_date));
	$end_date        = $_REQUEST['end_date'];
	$end_date        = date('Y-m-d',strtotime($end_date));
	$broker          = killstring($_REQUEST['broker']);
	
	$cntA            = 0;
	$location        = $_REQUEST['location'];
	$cntA            = count($location);
	$cntB            = 0;
	$vehicle         = $_REQUEST['vehicle'];
	$cntB            = count($vehicle);
	$inventory       = killstring($_REQUEST['inventory']);
	
	$status          = killstring($_REQUEST['status']);
	$IsPageValid = true;
	if(empty($vehicle)){
	$alert_label = "alert-danger"; 
	$msg   = "Please select vehicle"; 
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){
		   for($i=0; $i < $cntA; $i++){//location end loop
		   $location = killstring($_REQUEST['location'][$i]);
		   for($j=0; $j < $cntB; $j++){//vehicle end loop
		   $vehicle = killstring($_REQUEST['vehicle'][$j]);
		  $sql = "INSERT IGNORE INTO `tbl_bms_stop_sale`(`broker_id`, `vehicle_id`,`start_date`, `end_date`, `location_id`, `is_active`, `entered_by`) VALUES ('$broker','$vehicle','$start_date','$end_date','$location','$status','$userno')";
		  $result       = mysqli_query($conn,$sql); 
		  $last_id      = mysqli_insert_id($conn);
		  if($result){
			  if($status==1){  
			  $query =   mysqli_query($conn,"UPDATE `tbl_bms_daily_price_master` SET `is_active`=0 WHERE `price_broker_id`='$broker' AND `price_location_id`='$location' AND `vehicle_id`='$vehicle' AND `price_date` BETWEEN '$start_date' AND '$end_date'");
			   if(!$query){$error_cnt = $error_cnt + 1;}
			  }
		    }else{$error_cnt = $error_cnt + 1;}
		   }
		  }
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS Stop Sale Insertion for ID:$last_id','18','$ip_address','$userno')");  
	     	/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
                        $alert_label = "alert-success"; 
                        $msg = "Stop Sale  Added successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Stop Sale not added completely!";
                   }
	   /**********************************/
  }//valid rue	 	
 }
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Stop Sale List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Stop Sale List</li>
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
      <?php }?><br/><br/>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Stop Sale</h3>
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
                <label class="required">Broker:</label>
                <select class="validate[required] form-control select2" name="broker" id="broker" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `broker_name`, `broker_id` FROM `tbl_bms_broker_master` WHERE `is_active`=1 order by `broker_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['broker_id']?>">
                  <?=$b1['broker_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Vehicle:</label>
                <select class="validate[required] form-control select2" name="vehicle[]" id="vehicle" style="width:100%;"  multiple="multiple">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `vehicle_name`, `vehicle_id` FROM `tbl_bms_vehicle_master` WHERE `is_active`=1 order by `vehicle_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['vehicle_id']?>">
                  <?=$b1['vehicle_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">End Date:</label>
                <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="end_date" class="validate[required] datepickerA form-control"/></div>               
              </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location:</label>
                <select class="validate[required] form-control select2" name="location[]" id="location" style="width:100%;" multiple="multiple">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_bms_location_master` WHERE `is_active`=1 order by `location_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['location_id']?>">
                  <?=$b1['location_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Start Date:</label>
                 <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="start_date" class="validate[required] datepickerA form-control"/></div>      
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                  <select class="validate[required] form-control" name="status" id="status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Stop Sale</button>
        </div>
        <br/>
      </div>
      <!-- /.box -->
    
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Broker</th>
                      <th >Location</th>
                      <th >Vehicle</th>
                      <th >Start Dt.</th>
                      <th >End Dt.</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.`stop_sale_id`,tb1.`start_date`,tb1.`end_date`,tb1.`is_active`,tb2.`location_name`,tb3 .`broker_name`,tb4.`vehicle_name`,tb5.`group_name` FROM `tbl_bms_stop_sale` tb1 INNER JOIN `tbl_bms_location_master` tb2 ON tb1.`location_id`=tb2.`location_id` INNER JOIN `tbl_bms_broker_master` tb3 ON tb1.`broker_id`=tb3.`broker_id` INNER JOIN `tbl_bms_vehicle_master` tb4 ON tb1.`vehicle_id`=tb4.`vehicle_id` INNER JOIN `tbl_bms_group_master` tb5 ON tb4.`group_id`=tb5.`group_id` ORDER BY tb1.`stop_sale_id` DESC");
										   $itr = 0;
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
												 // Status
											   if($build['is_active'] == "1") {
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
                      <td><?=$build['broker_name']?></td>
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['vehicle_name']?>(<?=$build['group_name']?>)</td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['end_date']))?>"><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                      <td>
                      <?php if($build['end_date']>date('Y-m-d')){?><a href="edit_stop_sale.php?flag=<?=$build['stop_sale_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i><?php }?>
                      </td>
                    </tr>
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
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
$('.select').select2({
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
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
$(document).ready( function () {
  $('#example2').DataTable({ "aaSorting": [[ 0, "desc" ]]})
})
</script>
