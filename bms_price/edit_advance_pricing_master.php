<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$advance_id_now  = killstring($_REQUEST['advance_id_now']);
	$status          = killstring($_REQUEST['status']);
	$discount        = killstring($_REQUEST['discount']);
	$from            = killstring($_REQUEST['from']);
	$to              = killstring($_REQUEST['to']);
	$IsPageValid = true;	
	if(empty($advance_id_now)){
	$msg   = "Error on advance booking id!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 $sqlnewquery = mysqli_query($conn,"UPDATE `tbl_bms_advance_booking_pricing_master` SET `advance_from`='$from',`advance_to`='$to',`advance_discount`='$discount', `is_active`='$status', `updated_by`='$userno',`updated_date`=NOW() WHERE `advance_id`='$advance_id_now'");
		  if($sqlnewquery){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS advance pricing individual updation for ID:$advance_id_now','12','$ip_address','$userno')");   
		  $msg = "Advance booking price updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

$advance_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_advance_booking_pricing_master` WHERE `advance_id`='$advance_id'");
while($rowA = mysqli_fetch_array($qry)){
	$advance_id_now = $rowA['advance_id'];
	$broker_id      = $rowA['broker_id'];
	$advance_from   = $rowA['advance_from'];
	$advance_to     = $rowA['advance_to'];
	$advance_discount= $rowA['advance_discount'];
	$is_active      = $rowA['is_active'];
}
?>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Advance Booking Pricing  - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Advance Booking Pricing  - Edit</li>
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
          <h3 class="box-title">Advance Booking Pricing - Edit</h3>
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
                <select class="validate[required] form-control" name="broker" id="broker" style="width:100%;" disabled="disabled">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `broker_name`, `broker_id` FROM `tbl_bms_broker_master` WHERE `broker_id`='$broker_id'");
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
           </div>
            <div class="col-md-6">
            <div class="form-group">
                <label class="required">Status:</label>
                  <select class="validate[required] form-control" name="status" id="status">
                  <option value="1" <?php if($is_active==1){echo "selected";}?>>Active</option>
                  <option value="0" <?php if($is_active==0){echo "selected";}?>>Inactive</option>
                </select> 
              </div>
           </div>
           
            <div class="col-md-12">  
            <!--------------Range Pricing------------->
            <div class="table-responsive">
              
                  <table class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                  <tr><th> <label class="required">From</label></th><th> <label class="required">To</label></th><th> <label class="required">Discount</label></th></tr>
                  </thead>
                  <tbody id="row_val">                      
                    <tr>
                      <td>
                      <select class="validate[required] form-control select2" name="from"> 
                      <option selected="selected" value="">---Select an option---</option>
                      <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>" <?php if($i==$advance_from){echo "selected";}?>><?=$i?></option><?php }?>
                      </select>
                      </td>
                      <td>
                      <select class="validate[required] form-control select2" name="to"> 
                      <option selected="selected" value="">---Select an option---</option>
                      <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>" <?php if($i==$advance_to){echo "selected";}?>><?=$i?></option><?php }?>
                      </select>
                      </td>
                     <td><input type="text"  name="discount" value="<?=$advance_discount?>" class="validate[required,custom[number],min[0],max[75]] form-control" placeholder="Discount"/></td>
                    </tr>
                     </tbody>
                </table>
              </div>
              <!----------------------Range Pricing-------------->
              
            </div>
            
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
           <a href="advance_pricing_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='advance_id_now' value= '<?=$advance_id_now?>' >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Update</button>
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