<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";

if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$stop_sale_id_now = $_REQUEST['stop_sale_id_now'];
	$start_date      = $_REQUEST['start_date'];
	$start_date      = date('Y-m-d',strtotime($start_date));
	$end_date        = $_REQUEST['end_date'];
	$end_date        = date('Y-m-d',strtotime($end_date));
	$broker          = killstring($_REQUEST['broker']);
	$vehicle         = killstring($_REQUEST['vehicle']);
	$location        = killstring($_REQUEST['location']);
	$status          = killstring($_REQUEST['status']);
	$IsPageValid = true;
	if(empty($stop_sale_id_now)){
	$alert_label = "alert-danger"; 
	$msg   = "Error on ID!";
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){
		  $sql = "UPDATE `tbl_bms_stop_sale` SET `is_active`='$status' WHERE `stop_sale_id`='$stop_sale_id_now'";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $query =   mysqli_query($conn,"UPDATE `tbl_bms_daily_price_master` SET `is_active`='$status' WHERE `price_broker_id`='$broker' AND `price_location_id`='$location' AND `vehicle_id`='$vehicle' AND `price_date` BETWEEN '$start_date' AND '$end_date'");
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS Stop Sale Updation for ID:$stop_sale_id_now','19','$ip_address','$userno')");
		  $msg = "Stop Sale Updated Successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
  }//valid rue	 	
 }
}

$stop_sale_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT tb1.*,tb2.`location_name`,tb3.`vehicle_name`,tb4.`broker_name`FROM `tbl_bms_stop_sale` tb1 LEFT JOIN `tbl_bms_location_master` tb2 ON tb1.`location_id`=tb2.`location_id` LEFT JOIN `tbl_bms_vehicle_master` tb3 ON tb1.`vehicle_id`=tb3.`vehicle_id` LEFT JOIN `tbl_bms_broker_master` tb4 ON tb1.`broker_id`=tb4.`broker_id` WHERE tb1.`stop_sale_id`='$stop_sale_id'");
while($rowA = mysqli_fetch_array($qry)){
	$stop_sale_id_now = $rowA['stop_sale_id'];
	$broker_id        = $rowA['broker_id'];
	$broker_name      = $rowA['broker_name'];
	$vehicle_id       = $rowA['vehicle_id'];
	$vehicle_name     = $rowA['vehicle_name'];
	$location_id      = $rowA['location_id'];
	$location_name    = $rowA['location_name'];
	$start_date       = $rowA['start_date'];
	$end_date         = $rowA['end_date'];
	$is_active        = $rowA['is_active'];
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
                <input type="text" name="broker_name" class="form-control" value="<?=$broker_name?>" readonly="readonly"/>
              </div>
              <div class="form-group">
                <label class="required">Vehicle:</label>
                <input type="text" name="vehicle_name" class="form-control" value="<?=$vehicle_name?>" readonly="readonly"/>
              </div>
               <div class="form-group">
                <label class="required">End Date:</label>
                <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="end_date" class="validate[required] datepickerA form-control" value="<?=!empty($end_date)?date('d-m-Y',strtotime($end_date)):NULL;?>" readonly="readonly"/></div>               
              </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location:</label>
              <input type="text" name="location_name" class="form-control" value="<?=$location_name?>" readonly="readonly"/>
              </div>
              
               <div class="form-group">
                <label class="required">Start Date:</label>
                 <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="start_date" class="validate[required] datepickerA form-control" value="<?=!empty($start_date)?date('d-m-Y',strtotime($start_date)):NULL;?>" readonly="readonly"/></div>      
              </div>
              
            
              <div class="form-group">
                <label class="required">Status:</label>
                  <select class="validate[required] form-control" name="status" id="status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1" <?php if($is_active==1){echo "selected";}?>>Active</option>
                  <option value="0" <?php if($is_active==0){echo "selected";}?>>Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="stop_sale.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          
           <input type="hidden" name='vehicle' value= '<?=$vehicle_id?>' >
           <input type="hidden" name='broker' value= '<?=$broker_id?>' >
            <input type="hidden" name='location' value= '<?=$location_id?>' >
             
          <input type="hidden" name='stop_sale_id_now' value= '<?=$stop_sale_id_now?>' >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Update</button>
        </div>
        <br/>
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
