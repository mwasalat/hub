<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
$msg = "";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$price_id_now          = killstring($_REQUEST['price_id_now']);
	$rate                  = killstring($_REQUEST['rate']);
	$cdw                   = killstring($_REQUEST['cdw']);
	$scdw                  = killstring($_REQUEST['scdw']);
	$pai                   = killstring($_REQUEST['pai']);
	$gps                   = killstring($_REQUEST['gps']);
	$baby_seat             = killstring($_REQUEST['baby_seat']);
	$driver                = killstring($_REQUEST['driver']);
	$is_active             = killstring($_REQUEST['price_status']);
	$IsPageValid = true;
	if(empty($price_id_now)){
	$alert_label = "alert-danger"; 
	$msg   = "Error on price id!";
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){
		  $sql              = "UPDATE `tbl_bms_daily_price_master` SET `rate`='$rate',`cdw`='$cdw',`scdw`='$scdw',`pai`='$pai',`gps`='$gps',`baby_seat`='$baby_seat',`driver`='$driver',`is_active`='$is_active',`updated_by`='$userno',`updated_date`=NOW() WHERE `price_id`='$price_id_now'";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS daily pricing individual updation for ID:$price_id_now','14','$ip_address','$userno')");    
		  $msg = "Price updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   
		
  }//valid rue	 	
 }
}


$price_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_daily_price_master` WHERE `price_id`='$price_id'");
while($rowA = mysqli_fetch_array($qry)){
	$price_id_now = $rowA['price_id'];
	$price_year   = $rowA['price_year'];
	$price_month  = $rowA['price_month'];
	$price_date   = $rowA['price_date'];
	$rate         = $rowA['rate'];
	$cdw          = $rowA['cdw'];
	$scdw         = $rowA['scdw'];
	$pai          = $rowA['pai'];
	$gps          = $rowA['gps'];
	$baby_seat    = $rowA['baby_seat'];
	$driver       = $rowA['driver'];
	$is_active    = $rowA['is_active'];
	$price_location_id  = $rowA['price_location_id'];
	$price_broker_id    = $rowA['price_broker_id'];
	$vehicle_id         = $rowA['vehicle_id'];
	$group_id           = $rowA['group_id'];
	$is_active          = $rowA['is_active'];
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Price Daily List - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Price Daily List - Edit</li>
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
          <h3 class="box-title">Price Daily - Edit</h3>
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
                <label class="required">Date:</label>
                <input type="text" class="form-control" id="price_date" value="<?=date('d-m-Y',strtotime($price_date))?>" name="price_date" readonly="readonly"/>
              </div>
              <div class="form-group">
                <label class="required">Broker:</label>
                <select class="validate[required] form-control select2" name="broker" id="broker" style="width:100%;" disabled="disabled">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `broker_name`, `broker_id` FROM `tbl_bms_broker_master` WHERE `broker_id`='$price_broker_id'");
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
                <label class="required">Rate:</label>
                <input type="text" class="form-control" id="rate" value="<?=$rate?>" name="rate" placeholder="Rate"/>
              </div>
              <div class="form-group">
                <label class="required">SCDW:</label>
                <input type="text" class="form-control" id="scdw" value="<?=$scdw?>" name="scdw" placeholder="SCDW"/>
              </div>
              <div class="form-group">
                <label class="required">GPS:</label>
                <input type="text" class="form-control" id="gps" value="<?=$gps?>" name="gps" placeholder="GPS"/>
              </div>
              <div class="form-group">
                <label class="required">Driver Fee:</label>
                <input type="text" class="form-control" id="driver" value="<?=$driver?>" name="driver" placeholder="Driver Fee"/>
              </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location:</label>
                <select class="validate[required] form-control select2" name="location" id="location" style="width:100%;" disabled="disabled">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_bms_location_master` WHERE `location_id`='$price_location_id'");
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
                <label class="required">Vehicle:</label>
                 <select class="validate[required] form-control select2" name="vehicle" id="vehicle" style="width:100%;" disabled="disabled">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `vehicle_name`, `vehicle_id` FROM `tbl_bms_vehicle_master` WHERE `vehicle_id`='$vehicle_id'");
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
                <label class="required">CDW:</label>
                <input type="text" class="form-control" id="cdw" value="<?=$cdw?>" name="cdw" placeholder="CDW"/>
              </div>
              <div class="form-group">
                <label class="required">PAI:</label>
                <input type="text" class="form-control" id="pai" value="<?=$pai?>" name="pai" placeholder="PAI"/>
              </div>
              <div class="form-group">
                <label class="required">Baby Seat:</label>
                <input type="text" class="form-control" id="baby_seat" value="<?=$baby_seat?>" name="baby_seat" placeholder="Baby Seat"/>
              </div>
               <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="price_status" id="price_status">
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
          <a href="upload_price_daily_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='price_id_now' value="<?=$price_id_now?>">
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
