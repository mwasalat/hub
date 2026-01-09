<?php
require_once("../header_footer/header.php"); 
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$broker_name          = killstring($_REQUEST['broker_name']);
	$broker_mobile        = killstring($_REQUEST['broker_mobile']);
	$broker_landline      = killstring($_REQUEST['broker_landline']);
	$broker_email         = killstring($_REQUEST['broker_email']);
	$broker_city          = killstring($_REQUEST['broker_city']);
	$broker_country       = killstring($_REQUEST['broker_country']);
	$broker_address       = killstring($_REQUEST['broker_address']);
	$broker_status        = killstring($_REQUEST['broker_status']);
	$broker_id_now        = killstring($_REQUEST['broker_id_now']);
	$broker_gracetime     = killstring($_REQUEST['broker_gracetime']);
	$IsPageValid = true;	
	if(empty($broker_id_now)){
	$msg   = "PError in ID!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	      $sql = "UPDATE `tbl_bms_broker_master` SET `broker_name`='$broker_name',`broker_mobile`='$broker_mobile',`broker_landline`='$broker_landline',`broker_email`='$broker_email',`broker_city`='$broker_city',`broker_country`='$broker_country',`broker_address`='$broker_address',`broker_gracetime`='$broker_gracetime',`is_active`='$broker_status' WHERE `broker_id`='$broker_id_now'";
		  $result  = mysqli_query($conn,$sql); 
		  if($result){
		  $ip_address       = getIP();	  
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS broker master updation for ID:$broker_id_now','7','$ip_address','$userno')");
		  $msg = "Broker updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

$broker_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_broker_master` WHERE `broker_id`='$broker_id'");
while($rowA = mysqli_fetch_array($qry)){
	$broker_id_now   = $rowA['broker_id'];
	$broker_name     = $rowA['broker_name'];
	$broker_mobile   = $rowA['broker_mobile'];
	$broker_landline = $rowA['broker_landline'];
	$broker_email    = $rowA['broker_email'];
	$broker_city     = $rowA['broker_city'];
	$broker_address  = $rowA['broker_address'];
	$broker_country  = $rowA['broker_country'];
	$broker_gracetime= $rowA['broker_gracetime'];
	$is_active       = $rowA['is_active'];
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Broker List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Broker List</li>
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
          <h3 class="box-title">Broker</h3>
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
                <label class="required">Broker Name:</label>
                <input type="text" class="validate[required] form-control" name="broker_name" placeholder="Broker Name" value="<?=$broker_name?>"/>
              </div>
             
              <div class="form-group">
                <label class="required">Broker Landline:</label>
                <input type="text" class="validate[required,custom[phone]] form-control" name="broker_landline" placeholder="Broker Landline" value="<?=$broker_landline?>"/>
              </div>
              
                 <div class="form-group">
                <label class="required">Broker City:</label>
                 <input type="text" class="validate[required] form-control" name="broker_city" placeholder="Broker City" value="<?=$broker_city?>"/>
              </div>
             
              <div class="form-group">
                <label class="required">Broker Country:</label>
                 <select class="validate[required] form-control select2" name="broker_country" id="broker_country">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `name`, `id` FROM `tbl_country` order by `name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['id']?>" <?php if($b1['id']==$broker_country){echo "selected";}?>>
                  <?=$b1['name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
                 <div class="form-group">
              <label>Broker API Grace Period Time:</label>
              <div class="input-group">
                <input type="text" id="grace_timepicker" class="form-control" name="broker_gracetime" placeholder="API Grace Period Time" value="<?=$broker_gracetime?>">
                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
              </div>
            </div>
            
            
            </div>
            
            <div class="col-md-6">
             <div class="form-group">
                <label class="required">Broker Mobile:</label>
                <input type="text" class="validate[required,custom[phone]] form-control" name="broker_mobile" placeholder="Broker Mobile" value="<?=$broker_mobile?>"/>
              </div>
              <div class="form-group">
                <label class="required">Broker Email:</label>
                <input type="text" class="validate[required,custom[email]] form-control" name="broker_email" placeholder="Broker Email" value="<?=$broker_email?>"/>
              </div>
              
               <div class="form-group">
                <label class="required">Broker Address:</label>
                <textarea class="validate[required] form-control" name="broker_address" placeholder="Broker Address" rows="5"/><?=nl2br($broker_address)?></textarea>
              </div>
                <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="broker_status" id="broker_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1"  <?php if($is_active==1){echo "selected";}?>>Active</option>
                  <option value="0"  <?php if($is_active==0){echo "selected";}?>>Inactive</option>
                </select>
              </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="broker_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='broker_id_now' value= '<?=$broker_id_now?>' >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Update</button>
        </div>
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
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
	 //Timepicker
    $('#grace_timepicker').timepicker({
      showInputs: false,
	  showMeridian: false 
    });
  })
</script>