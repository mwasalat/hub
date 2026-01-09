<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";

if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$pickup_emirate_id    = killstring($_REQUEST['pickup_emirate_id']);
	$dropoff_emirate_id   = killstring($_REQUEST['dropoff_emirate_id']);
	$cost                 = killstring($_REQUEST['cost']);
	$status               = killstring($_REQUEST['status']);
	$IsPageValid = true;
	if(empty($pickup_emirate_id)){
	$alert_label = "alert-danger"; 
	$msg   = "Please select pickup emirate"; 
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){
		  $sql          = "INSERT INTO `tbl_bms_inter_emirate_pricing`(`pickup_emirate_id`, `dropoff_emirate_id`,`inter_emirate_pricing`, `is_active`, `entered_by`) VALUES ('$pickup_emirate_id','$dropoff_emirate_id','$cost','$status','$userno')";
		  $result       = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Inter-emirate pricing added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error! Please check the same combination is there or not!"; 
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
    <h1>Inter Emirate Pricing List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Inter Emirate Pricing Sale List</li>
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
          <h3 class="box-title">Inter Emirate Pricing</h3>
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
                <select class="validate[required] form-control select2" name="pickup_emirate_id" id="pickup_emirate_id" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `city_name`, `city_id` FROM `tbl_bms_city_master` WHERE `is_active`=1 order by `city_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['city_id']?>">
                  <?=$b1['city_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Cost:</label>
               <input type="number" class="validate[required,custom[onlyNumber],min[0]] form-control" id="cost" value="0" name="cost" placeholder="Cost">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Dropoff Emirate:</label>
                <select class="validate[required] form-control select2" name="dropoff_emirate_id" id="dropoff_emirate_id" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `city_name`, `city_id` FROM `tbl_bms_city_master` WHERE `is_active`=1 order by `city_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['city_id']?>">
                  <?=$b1['city_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
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
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
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
                      <th >Pickup Emirate</th>
                      <th >Dropoff Emirate</th>
                      <th >Cost</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.`inter_emirate_id`,tb1.`inter_emirate_pricing`,tb1.`is_active`,tb2.`city_name` AS `pickup_emirate_name`,tb3.`city_name` AS `dropoff_emirate_name` FROM `tbl_bms_inter_emirate_pricing` tb1 INNER JOIN `tbl_bms_city_master` tb2 ON tb1.`pickup_emirate_id`=tb2.`city_id` INNER JOIN `tbl_bms_city_master` tb3 ON tb1.`dropoff_emirate_id`=tb3.`city_id` ORDER BY tb1.`entered_date` DESC");
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
                      <td><?=$build['pickup_emirate_name']?></td>
                      <td><?=$build['dropoff_emirate_name']?></td>
                      <td><?=$build['inter_emirate_pricing']?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                      <td><a href="edit_inter_emirate_pricing.php?flag=<?=$build['inter_emirate_id']?>&gtoken=<?=$token?>" title="edit" class="btn btn-default btn-sm"/><i class="fa fa-edit"></i></a></td>
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
