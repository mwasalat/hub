<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$brand_name          = killstring($_REQUEST['brand_name']);
	$brand_status        = killstring($_REQUEST['brand_status']);
	$brand_id_now        = killstring($_REQUEST['brand_id_now']);
	$IsPageValid = true;	
	if(empty($brand_name)){
	$msg   = "Please enter brand name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql              = "UPDATE `tbl_bms_brand_master` SET `is_active`='$brand_status',`updated_by`='$userno',`updated_date`=NOW() WHERE `brand_id`='$brand_id_now' ";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $ip_address       = getIP();	  
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS brand master updation for ID:$brand_id_now','4','$ip_address','$userno')");
		  $msg         = "Brand updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg         = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

$brand_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_brand_master` WHERE `brand_id`='$brand_id'");
while($rowA = mysqli_fetch_array($qry)){
	$brand_id_now   = $rowA['brand_id'];
	$brand_name     = $rowA['brand_name'];
	$is_active      = $rowA['is_active'];
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Brand Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Brand Edit</li>
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
          <h3 class="box-title">Brand Edit</h3>
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
                <label class="required">Brand Name:</label>
                <input type="text" class="validate[required] form-control" name="brand_name" placeholder="Brand Name" value="<?=$brand_name?>" readonly="readonly"/>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="brand_status" id="brand_status">
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
          <a href="brand_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='brand_id_now' value= '<?=$brand_id_now?>' >
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