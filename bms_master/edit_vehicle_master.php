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
	$vehicle_id_now        = killstring($_REQUEST['vehicle_id_now']);
	
	$car_start_date      = $_REQUEST['car_start_date'];
	$car_start_date      = date('Y-m-d',strtotime($car_start_date));
	
	$IsPageValid = true;	
	if(empty($vehicle_id_now)){
	$msg   = "Error on ID!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 $sql              = "UPDATE `tbl_bms_vehicle_master` SET `vehicle_name`='$vehicle_name',`category_id`='$category',`transmission_id`='$transmission',`sipp_code`='$sipp_code',`suitcases`='$suitcases',`parking_sensors`='$parking_sensors',`cruise_control`='$cruise_control',`group_id`='$group',`erp_group_id`='$erp_group',`doors`='$doors',`bluetooth`='$bluetooth',`air_conditionar`='$air_conditionar',`sunroof`='$sunroof',`brand_id`='$brand',`fuel_type_id`='$fuel_type',`passengers`='$passengers',`air_bags`='$air_bags',`infotainment_system`='$infotainment_system',`rear_parking_camera`='$rear_parking_camera',`description`='$description',`car_start_date`='$car_start_date',`is_active`='$vehicle_status',`updated_by`='$userno',`updated_date`=NOW() WHERE `vehicle_id`='$vehicle_id_now' ";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS vehicle master updation for ID:$vehicle_id_now','5','$ip_address','$userno')");  
		  $msg = "Vehicle updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

$vehicle_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_vehicle_master` WHERE `vehicle_id`='$vehicle_id'");
while($rowA = mysqli_fetch_array($qry)){
	$vehicle_id_now     = $rowA['vehicle_id'];
	$vehicle_name       = $rowA['vehicle_name'];
	$category_id        = $rowA['category_id'];
	$transmission_id    = $rowA['transmission_id'];
	$suitcases          = $rowA['suitcases'];
	$parking_sensors    = $rowA['parking_sensors'];
	$cruise_control     = $rowA['cruise_control'];
	$group_id           = $rowA['group_id'];
	$erp_group_id       = $rowA['erp_group_id'];
	$sipp_code          = $rowA['sipp_code'];
	$doors              = $rowA['doors'];
	$air_conditionar    = $rowA['air_conditionar'];
	$bluetooth          = $rowA['bluetooth'];
	$sunroof            = $rowA['sunroof'];
	$brand_id           = $rowA['brand_id'];
	$fuel_type_id       = $rowA['fuel_type_id'];
	$passengers         = $rowA['passengers'];
	$air_bags           = $rowA['air_bags'];
	$infotainment_system= $rowA['infotainment_system'];
	$rear_parking_camera= $rowA['rear_parking_camera'];
	$description        = $rowA['description'];
	$is_active          = $rowA['is_active'];
	$car_start_date     = $rowA['car_start_date'];
}
?>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Vehicle  - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Vehicle - Edit</li>
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
          <h3 class="box-title">Vehicle  - Edit</h3>
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
                <input type="text" class="validate[required] form-control" name="vehicle_name" placeholder="Vehicle Name" value="<?=$vehicle_name?>"/>
              </div>
              <div class="form-group">
                <label class="required">Category:</label>
                <select class="validate[required] form-control select2" name="category" id="category">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `category_name`, `category_id` FROM `tbl_bms_category_master` WHERE `is_active`=1 order by `category_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['category_id']?>" <?php if($b1['category_id']==$category_id){echo "selected";}?>>
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
                  <option value="<?=$b1['transmission_id']?>" <?php if($b1['transmission_id']==$transmission_id){echo "selected";}?>>
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
                  <input type="radio" class="" name="parking_sensors" value="1" <?php if($parking_sensors==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="parking_sensors" value="0" <?php if($parking_sensors==0){echo "checked";}?>/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Cruise Control:</label>
                <div class="input-group">
                  <input type="radio" class="" name="cruise_control" value="1" <?php if($cruise_control==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="cruise_control" value="0" <?php if($cruise_control==0){echo "checked";}?>/>
                  No </div>
              </div>
              
              <div class="form-group">
                <label class="required">Rear parking camera:</label>
                <div class="input-group">
                  <input type="radio" class="" name="rear_parking_camera" value="1" <?php if($rear_parking_camera==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="rear_parking_camera" value="0" <?php if($rear_parking_camera==0){echo "checked";}?>/>
                  No </div>
              </div>
              
                <div class="form-group">
                <label class="required">SIPP Code:</label>
                <input type="text" class="validate[required] form-control" name="sipp_code" placeholder="Vehicle SIPP Code" value="<?=$sipp_code?>"/>
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
                  <option value="<?=$b1['group_id']?>" <?php if($b1['group_id']==$group_id){echo "selected";}?>>
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
                  <option svalue="<?=$i?>" <?php if($i==$doors){echo "selected";}?>>
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
                  <option svalue="<?=$i?>" <?php if($i==$passengers){echo "selected";}?>>
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Air Conditionar:</label>
                <div class="input-group">
                  <input type="radio" class="" name="air_conditionar" value="1" <?php if($air_conditionar==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="air_conditionar" value="0" <?php if($air_conditionar==0){echo "checked";}?>/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Bluetooth:</label>
                <div class="input-group">
                  <input type="radio" class="" name="bluetooth" value="1" <?php if($bluetooth==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="bluetooth" value="0" <?php if($bluetooth==0){echo "checked";}?>/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Sunroof:</label>
                <div class="input-group">
                  <input type="radio" class="" name="sunroof" value="1" <?php if($sunroof==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="sunroof" value="0" <?php if($sunroof==0){echo "checked";}?>/>
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
                  <option value="<?=$b1['group_id']?>" <?php if($b1['group_id']==$erp_group_id){echo "selected";}?>>
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
                  <option value="<?=$b1['brand_id']?>" <?php if($b1['brand_id']==$brand_id){echo "selected";}?>>
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
                  <option value="<?=$b1['fuel_type_id']?>" <?php if($b1['fuel_type_id']==$fuel_type_id){echo "selected";}?>>
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
                  <option svalue="<?=$i?>" <?php if($i==$suitcases){echo "selected";}?>>
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Air Bags:</label>
                <div class="input-group">
                  <input type="radio" class="" name="air_bags" value="1" <?php if($air_bags==1){echo "checked";}?>//>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="air_bags" value="0" <?php if($air_bags==0){echo "checked";}?>//>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Infotainment System:</label>
                <div class="input-group">
                  <input type="radio" class="" name="infotainment_system" value="1" <?php if($infotainment_system==1){echo "checked";}?>/>
                  Yes&nbsp;&nbsp;&nbsp;
                  <input type="radio" class="" name="infotainment_system" value="0" <?php if($infotainment_system==0){echo "checked";}?>/>
                  No </div>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="vehicle_status" id="vehicle_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1" <?php if($is_active==1){echo "selected";}?>>Active</option>
                  <option value="0" <?php if($is_active==0){echo "selected";}?>>Inactive</option>
                </select>
              </div>
              
               <div class="form-group">
                <label class="required">Car Start Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="car_start_date" class="validate[required] datepickerA form-control" id="car_start_date" placeholder="Car Start Date" value="<?=!empty($car_start_date)?date('d-m-Y',strtotime($car_start_date)):"";?>"/>
                </div>
              </div>
              
              
            </div>
            <div class="col-md-12">
              <label class="required">Description:</label>
              <textarea class="form-control editor1 textarea" name="description" id="editor1" placeholder="Description"/><?=$description?></textarea>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="vehicle_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='vehicle_id_now' value= '<?=$vehicle_id_now?>' >
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
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    //startDate: '-0d',
	autoclose: true
});
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
<!-- CK Editor -->
<script src="../plugins/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    CKEDITOR.replace('editor1')
  })
</script>
