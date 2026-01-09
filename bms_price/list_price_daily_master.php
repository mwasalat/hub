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
    <h1>Price Daily List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Price Daily List</li>
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
          <h3 class="box-title">Price Daily</h3>
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
                <label class="">Vehicle:</label>
                <select class="form-control select2" name="vehicle" id="vehicle" style="width:100%;">
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
                <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="end_date" class="validate[required] datepickerA form-control" value="<?=!empty($_REQUEST['end_date'])?date('d-m-Y',strtotime($_REQUEST['end_date'])):NULL;?>"/></div>               
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
                 <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="start_date" class="validate[required] datepickerA form-control" value="<?=!empty($_REQUEST['start_date'])?date('d-m-Y',strtotime($_REQUEST['start_date'])):NULL;?>"/></div>      
              </div>
              
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer"> 
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Search</button>
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
	$broker              = killstring($_REQUEST['broker']);
	$location            = killstring($_REQUEST['location']);
	?>
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Year</th>
                      <th >Month</th>
                      <th >Location</th>
                      <th >Broker</th>
                      <th >Vehicle</th>
                      <th >Date</th>
                      <th >Rate</th>
                      <th >CDW</th>
                      <th >SCDW</th>
                      <th >PAI</th>
                      <th >GPS</th>
                      <th >Baby Seat</th>
                      <th >Driver Fee</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					
					                      $Query = "SELECT tb1.`price_id`,tb1.`price_year`,tb1.`price_month`,tb1.`price_date`,tb1.`rate`,tb1.`cdw`,tb1.`scdw`,tb1.`pai`,tb1.`gps`,tb1.`baby_seat`,tb1.`driver`,tb1.`is_active`,tb2.`location_name`,tb3 .`broker_name`,tb4.`vehicle_name`,tb5.`group_name`,CASE WHEN tb1.`day_type`=1 THEN 'WD' WHEN tb1.`day_type`=2 THEN 'WE' ELSE '' END AS `week_type` FROM `tbl_bms_daily_price_master` tb1 INNER JOIN `tbl_bms_location_master` tb2 ON tb1.`price_location_id`=tb2.`location_id` INNER JOIN `tbl_bms_broker_master` tb3 ON tb1.`price_broker_id`=tb3.`broker_id` INNER JOIN `tbl_bms_vehicle_master` tb4 ON tb1.`vehicle_id`=tb4.`vehicle_id` INNER JOIN `tbl_bms_group_master` tb5 ON tb1.`group_id`=tb5.`group_id` WHERE tb1.`price_broker_id`='$broker' AND tb1.`price_location_id`='$location' AND tb1.`price_date` BETWEEN '$start_date' AND '$end_date'";
										 if(!empty($_REQUEST['vehicle'])){
										 $Query.=" AND tb1.`vehicle_id`='$_REQUEST[vehicle]'";	 
										 }
										 $Query.=" ORDER BY tb1.`price_id` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
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
												$monthName = date('F', mktime(0, 0, 0, $build['price_month'], 10));
											?>
                    <tr>
                      <td><?=$build['price_year']?></td>
                      <td><?=$monthName?></td>
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['broker_name']?></td>
                      <td><?=$build['vehicle_name']?>(<?=$build['group_name']?>)</td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['price_date']))?>"><?=date('d-m-Y',strtotime($build['price_date']))?> (<?=$build['week_type']?>)</td>
                      <td><?=Checkvalid_number_or_not($build['rate'],2)?></td>
                      <td><?=Checkvalid_number_or_not($build['cdw'],2)?></td>
                      <td><?=Checkvalid_number_or_not($build['scdw'],2)?></td>
                      <td><?=Checkvalid_number_or_not($build['pai'],2)?></td>
                      <td><?=Checkvalid_number_or_not($build['gps'],2)?></td>
                      <td><?=Checkvalid_number_or_not($build['baby_seat'],2)?></td>
                      <td><?=Checkvalid_number_or_not($build['driver'],2)?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                      <td>
                      <?php if($build['price_date']>=date('Y-m-d')){?><a href="edit_price_daily_master.php?flag=<?=$build['price_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></a><?php }?>
                      </td>
                    </tr>
                    <?php 
					$i++;
                                            }
                                        ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php }}?>
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
