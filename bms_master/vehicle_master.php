<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$vehicle_name          = killstring($_REQUEST['vehicle_name']);
	$category              = killstring($_REQUEST['category']);
	$transmission          = killstring($_REQUEST['transmission']);
	$suitcases             = killstring($_REQUEST['suitcases']);
	$parking_sensors       = killstring($_REQUEST['parking_sensors']);
	$cruise_control        = killstring($_REQUEST['cruise_control']);
	$group                 = killstring($_REQUEST['group']);
	$erp_group             = killstring($_REQUEST['erp_group']);
	$sipp_code             = killstring($_REQUEST['sipp_code']);
	$doors                 = killstring($_REQUEST['doors']);
	$bluetooth             = killstring($_REQUEST['bluetooth']);
	$air_conditionar       = killstring($_REQUEST['air_conditionar']);
	$sunroof               = killstring($_REQUEST['sunroof']);
	$brand                 = killstring($_REQUEST['brand']);
	$fuel_type             = killstring($_REQUEST['fuel_type']);
	$passengers            = killstring($_REQUEST['passengers']);
	$air_bags              = killstring($_REQUEST['air_bags']);
	$infotainment_system   = killstring($_REQUEST['infotainment_system']);
	$rear_parking_camera   = killstring($_REQUEST['rear_parking_camera']);
	$description           = mysqli_real_escape_string($conn,$_REQUEST['description']);
	
	$vehicle_status        = killstring($_REQUEST['vehicle_status']);
	
	$car_start_date      = $_REQUEST['car_start_date'];
	$car_start_date      = date('Y-m-d',strtotime($car_start_date));
	
	$IsPageValid = true;	
	if(empty($vehicle_name)){
	$msg   = "Please enter vehicle name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT INTO `tbl_bms_vehicle_master`(`vehicle_name`,`category_id`, `transmission_id`, `suitcases`, `parking_sensors`, `cruise_control`, `group_id`, `erp_group_id`, `sipp_code`, `doors`, `air_conditionar`, `bluetooth`, `sunroof`, `brand_id`, `fuel_type_id`, `passengers`, `air_bags`, `infotainment_system`, `rear_parking_camera`, `description`, `car_start_date`, `is_active`,`entered_by`) VALUES ('$vehicle_name','$category','$transmission','$suitcases','$parking_sensors','$cruise_control','$group','$erp_group','$sipp_code','$doors','$air_conditionar','$bluetooth','$sunroof','$brand','$fuel_type','$passengers','$air_bags','$infotainment_system', '$rear_parking_camera', '$description', '$car_start_date','$vehicle_status','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  $last_id = mysqli_insert_id($conn);
		  if($result){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS vehicle master insertion for ID:$last_id','8','$ip_address','$userno')");
		  $msg = "Vehicle added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

?>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Vehicle List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Vehicle List</li>
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
          <h3 class="box-title">Vehicle</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Vehicle Name:</label>
                <input type="text" class="validate[required] form-control" name="vehicle_name" placeholder="Vehicle Name"/>
              </div>
              <div class="form-group">
                <label class="required">Category:</label>
                <select class="validate[required] form-control select2" name="category" id="category">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `category_name`, `category_id` FROM `tbl_bms_category_master` WHERE `is_active`=1 order by `category_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['category_id']?>">
                  <?=$b1['category_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Transmission:</label>
                <select class="validate[required] form-control select2" name="transmission" id="transmission">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `transmission_name`, `transmission_id` FROM `tbl_bms_transmission_master` WHERE `is_active`=1 order by `transmission_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['transmission_id']?>">
                  <?=$b1['transmission_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
              <div class="form-group">
                <label class="required">Parking Sensors:</label>
                <div class="input-group">
                  <input type="radio" class="" name="parking_sensors" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="parking_sensors" value="0"/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Cruise Control:</label>
                <div class="input-group">
                  <input type="radio" class="" name="cruise_control" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="cruise_control" value="0"/>
                  No </div>
              </div>
              
              <div class="form-group">
                <label class="required">Rear parking camera:</label>
                <div class="input-group">
                  <input type="radio" class="" name="rear_parking_camera" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="rear_parking_camera" value="0"/>
                  No </div>
              </div>
              
               <div class="form-group">
                <label class="required">SIPP Code:</label>
                <input type="text" class="validate[required] form-control" name="sipp_code" placeholder="Vehicle SIPP Code"/>
              </div>
              
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Group:</label>
                <select class="validate[required] form-control select2" name="group" id="group">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `group_name`, `group_id` FROM `tbl_bms_group_master` WHERE `is_active`=1 order by `group_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['group_id']?>">
                  <?=$b1['group_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Doors:</label>
                <select class="validate[required] form-control select2" name="doors" id="doors">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php for($i=1;$i<=15;$i++){?>
                  <option svalue="<?=$i?>">
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div>
              
              <div class="form-group">
                <label class="required">Passengers:</label>
                <select class="validate[required] form-control select2" name="passengers" id="passengers">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php for($i=1;$i<=15;$i++){?>
                  <option svalue="<?=$i?>">
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Air Conditionar:</label>
                <div class="input-group">
                  <input type="radio" class="" name="air_conditionar" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="air_conditionar" value="0"/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Bluetooth:</label>
                <div class="input-group">
                  <input type="radio" class="" name="bluetooth" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="bluetooth" value="0"/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Sunroof:</label>
                <div class="input-group">
                  <input type="radio" class="" name="sunroof" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="sunroof" value="0"/>
                  No </div>
              </div>
              
               <div class="form-group">
                <label class="required">ERP Group<code>(Mention the same car group in ERP)</code>:</label>
                <select class="validate[required] form-control select2" name="erp_group" id="erp_group">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `group_name`, `group_id` FROM `tbl_bms_group_master` WHERE `is_active`=1 order by `group_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['group_id']?>">
                  <?=$b1['group_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Brand:</label>
                <select class="validate[required] form-control select2" name="brand" id="brand">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `brand_name`, `brand_id` FROM `tbl_bms_brand_master` WHERE `is_active`=1 order by `brand_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['brand_id']?>">
                  <?=$b1['brand_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Fuel Type:</label>
                <select class="validate[required] form-control select2" name="fuel_type" id="fuel_type">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `fuel_type_name`, `fuel_type_id` FROM `tbl_bms_fuel_type_master` WHERE `is_active`=1 order by `fuel_type_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['fuel_type_id']?>">
                  <?=$b1['fuel_type_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Suitcases:</label>
                <select class="validate[required] form-control select2" name="suitcases" id="suitcases">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php for($i=1;$i<=15;$i++){?>
                  <option svalue="<?=$i?>">
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Air Bags:</label>
                <div class="input-group">
                  <input type="radio" class="" name="air_bags" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="air_bags" value="0"/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Infotainment System:</label>
                <div class="input-group">
                  <input type="radio" class="" name="infotainment_system" value="1" checked="checked"/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="infotainment_system" value="0"/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="vehicle_status" id="vehicle_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              
              <div class="form-group">
                <label class="required">Car Start Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="car_start_date" class="validate[required] datepickerA form-control" id="car_start_date" placeholder="Car Start Date"/>
                </div>
              </div>
              
            </div>
            <div class="col-md-12">
              <label class="required">Description:</label>
              <textarea class="form-control editor1 textarea" name="description" id="editor1" placeholder="Description"/></textarea>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
        </div>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Vehicle List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Car ID</th>
                      <th >Vehicle</th>
                      <th >Group</th>
                      <th >SIPP Code</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb2.`group_name` FROM `tbl_bms_vehicle_master` tb1 INNER JOIN `tbl_bms_group_master` tb2 ON tb1.group_id=tb2.group_id ORDER BY tb1.`vehicle_name` ASC");
										   $itr = 0;
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
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['vehicle_id']?></td>
                      <td><?=$build['vehicle_name']?></td>
                      <td><?=$build['group_name']?></td>
                      <td><?=$build['sipp_code']?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                      <td><a href="edit_vehicle_master.php?flag=<?=$build['vehicle_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
                    </tr>
                    <?php 
                                            }
                                        ?>
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
$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
});
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
	autoclose: true
});

</script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<!--<script src="../plugins/datatable_1_10_16/datatables.button.js"></script>-->
<script type="text/javascript" src="../plugins/datatable_1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/jszip.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/buttons.html5.min.js"></script>

<script>

  $(function () {
   
   $('#example2').dataTable({
      "aaSorting": [[ 1, "asc" ]], // Sort by first column ascending
      dom: 'Bfrtip',
         buttons: [
            {
               extend: 'csv',
               title: 'Vehcile Master',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Vehcile Master',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Vehcile Master',
			    className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
   });

  })
</script>
<!-- CK Editor -->
<script src="../plugins/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    CKEDITOR.replace('editor1')
  })
</script>
