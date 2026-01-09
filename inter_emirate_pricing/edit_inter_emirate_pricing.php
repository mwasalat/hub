<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";

if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$inter_emirate_id_now = $_REQUEST['inter_emirate_id_now'];
	$cost                 = killstring($_REQUEST['cost']);
	$status               = killstring($_REQUEST['status']);
	$IsPageValid = true;
	if(empty($inter_emirate_id_now)){
	$alert_label = "alert-danger"; 
	$msg   = "Error on ID!";
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){
		  $sql       = "UPDATE `tbl_bms_inter_emirate_pricing` SET `inter_emirate_pricing`='$cost',`is_active`='$status' WHERE `inter_emirate_id`='$inter_emirate_id_now'";
		  $result    = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Inter-Emirate Pricing Updated Successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
  }//valid rue	 	
 }
}

$inter_emirate_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT tb1.`inter_emirate_id`,tb1.`inter_emirate_pricing`,tb1.`is_active`,tb2.`city_name` AS `pickup_emirate_name`,tb3.`city_name` AS `dropoff_emirate_name` FROM `tbl_bms_inter_emirate_pricing` tb1 INNER JOIN `tbl_bms_city_master` tb2 ON tb1.`pickup_emirate_id`=tb2.`city_id` INNER JOIN `tbl_bms_city_master` tb3 ON tb1.`dropoff_emirate_id`=tb3.`city_id` WHERE tb1.`inter_emirate_id`='$inter_emirate_id'");
while($rowA = mysqli_fetch_array($qry)){
	$inter_emirate_id_now = $rowA['inter_emirate_id'];
	$inter_emirate_pricing=$rowA['inter_emirate_pricing'];
	$is_active            = $rowA['is_active'];
	$pickup_emirate_name  = $rowA['pickup_emirate_name'];
	$dropoff_emirate_name = $rowA['dropoff_emirate_name'];
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Edit - Inter Emirate Pricing List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Edit - Inter Emirate Pricing List</li>
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
          <h3 class="box-title">Edit - Inter Emirate Pricing</h3>
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
                <label class="required">Pickup Emirate:</label>
                <input type="text" name="pickup_emirate_id" class="form-control" value="<?=$pickup_emirate_name?>" readonly="readonly"/>
              </div>
              <div class="form-group">
                <label class="required">Cost:</label>
                <input type="number" name="cost" class="validate[required,custom[onlyNumber],min[0]] form-control" value="<?=$inter_emirate_pricing?>"/>
              </div>
               
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Dropoff Emirate:</label>
              <input type="text" name="dropoff_emirate_id" class="form-control" value="<?=$dropoff_emirate_name?>" readonly="readonly"/>
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
          <a href="inter_emirate_pricing.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='inter_emirate_id_now' value= '<?=$inter_emirate_id_now?>' >
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
