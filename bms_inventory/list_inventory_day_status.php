<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Inventory Daily Status List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Inventory Daily Status List</li>
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
      <br/>
      <br/>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Inventory Day Status List</h3>
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
                  <option value="<?=$b1['broker_id']?>" <?php if($_REQUEST['broker']==$b1['broker_id']){echo "selected";}?>>
                  <?=$b1['broker_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Vehicle:</label>
                <select class="validate[required] form-control select2" name="vehicle" id="vehicle" style="width:100%;" >
                <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `vehicle_name`, `vehicle_id` FROM `tbl_bms_vehicle_master` WHERE `is_active`=1 order by `vehicle_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['vehicle_id']?>" <?php if($_REQUEST['vehicle']==$b1['vehicle_id']){echo "selected";}?>>
                  <?=$b1['vehicle_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">End Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="end_date" class="validate[required] datepickerA form-control" id="end_date" value="<?=!empty($_REQUEST['end_date'])?date('d-m-Y',strtotime($_REQUEST['end_date'])):NULL;?>"/>
                </div>
              </div>
             
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location:</label>
                <select class="validate[required] form-control select2" name="location" id="location" style="width:100%;">
                <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_bms_location_master` WHERE `is_active`=1 order by `location_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['location_id']?>" <?php if($_REQUEST['location']==$b1['location_id']){echo "selected";}?>>
                  <?=$b1['location_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Start Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="start_date" class="validate[required] datepickerA form-control" id="start_date" value="<?=!empty($_REQUEST['start_date'])?date('d-m-Y',strtotime($_REQUEST['start_date'])):NULL;?>"/>
                </div>
              </div>
              
              
           
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer bulk_price_value_div">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Sarch</button>
        </div>
        <br/>
      </div>
      <!-- /.box -->
      <?php 
    if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$start_date          = $_REQUEST['start_date'];
	$start_date          = date('Y-m-d',strtotime($start_date));
	$end_date            = $_REQUEST['end_date'];
	$end_date            = date('Y-m-d',strtotime($end_date));
	$vehicle             = killstring($_REQUEST['vehicle']);
	$broker              = killstring($_REQUEST['broker']);
	$location            = killstring($_REQUEST['location']);
	?>
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
                  <thead>
                    <tr>
                      <th>Broker</th>
                      <th>Location</th>
                      <th>Vehicle</th>
                      <th>Date</th>
                      <th>Current <br/>Inventory</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb1.`inventory_date`,tb1.`is_active`,tb2.`location_name`,tb3 .`broker_name`,tb4.`vehicle_name`,tb5.`group_name` FROM `tbl_bms_inventory_master` tb1 INNER JOIN `tbl_bms_location_master` tb2 ON tb1.`location_id`=tb2.`location_id` INNER JOIN `tbl_bms_broker_master` tb3 ON tb1.`broker_id`=tb3.`broker_id` INNER JOIN `tbl_bms_vehicle_master` tb4 ON tb1.`vehicle_id`=tb4.`vehicle_id` INNER JOIN `tbl_bms_group_master` tb5 ON tb4.`group_id`=tb5.`group_id` WHERE tb1.`vehicle_id`='$vehicle' AND tb1.`location_id`='$location' AND tb1.`broker_id`='$broker' AND tb1.`inventory_date` BETWEEN '$start_date' AND '$end_date' ORDER BY tb1.`inventory_id` DESC");
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
                      <td data-order="<?=date('Y-m-d',strtotime($build['inventory_date']))?>"><?=date('d-m-Y',strtotime($build['inventory_date']))?></td>
                      <td><?=$build['inventory']?></td>
                    </tr>
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php 
	  }
	}
	?>
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
<script src="../bower_components/select2/select2.js" type="text/javascript"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<!--<script src="../plugins/datatable_1_10_16/datatables.button.js"></script>-->
<script type="text/javascript" src="../plugins/datatable_1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/jszip.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/buttons.html5.min.js"></script>
<script src="../plugins/datatable_1_10_16/dataTables.select.min.js"></script>
<script>
$(document).ready( function () {
  var oTable = $('#example2').DataTable({
       "aaSorting": [[ 0, "asc" ]],
	    dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Inventory List - BMS',
			   className: 'btn btn-primary'
            },
            {
               extend: 'excel',
               title: 'Inventory List - BMS',
			    className: 'btn btn-success'
            },
			 {
                extend: 'pdf',
                title: 'Inventory List - BMS',
			    className: 'btn btn-info'
            }
			]
  });
   });
</script>