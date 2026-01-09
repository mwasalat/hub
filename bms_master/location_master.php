<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$location_name          = killstring($_REQUEST['location_name']);
	$location_mobile        = killstring($_REQUEST['location_mobile']);
	$location_landline      = killstring($_REQUEST['location_landline']);
	$location_email         = killstring($_REQUEST['location_email']);
	$location_city          = killstring($_REQUEST['location_city']);
	$location_address       = killstring($_REQUEST['location_address']);
	$location_latitude      = killstring($_REQUEST['location_latitude']);
	$location_longitude     = killstring($_REQUEST['location_longitude']);
	$location_iata          = killstring($_REQUEST['location_iata']);
	$location_status        = killstring($_REQUEST['location_status']);
	$location_is_airport    = killstring($_REQUEST['location_is_airport']);
	$location_buffer_time   = killstring($_REQUEST['location_buffer_time']);
	$location_buffer_time   = !empty($location_buffer_time) ? "'$location_buffer_time'" : "NULL";
	
	$airport_fee   = killstring($_REQUEST['airport_fee']);
	$airport_fee   = !empty($airport_fee) ? "'$airport_fee'" : "NULL";
	$vmd_fee   = killstring($_REQUEST['vmd_fee']);
	$vmd_fee   = !empty($vmd_fee) ? "'$vmd_fee'" : "NULL";
	$IsPageValid = true;	
	if(empty($location_name)){
	$msg   = "Please enter location name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 $sql     = "INSERT IGNORE INTO `tbl_bms_location_master`(`location_name`,`location_mobile`,`location_landline`,`location_email`, `location_city`, `location_address`, `location_iata`, `location_latitude`, `location_longitude`, `location_is_airport`, `location_buffer_time`, `airport_fee`, `vmd_fee`,`is_active`,`entered_by`) VALUES ('$location_name','$location_mobile','$location_landline','$location_email','$location_city', '$location_address','$location_iata','$location_latitude','$location_longitude','$location_is_airport',$location_buffer_time,$airport_fee,$vmd_fee,'$location_status','$userno')";
		  $result  = mysqli_query($conn,$sql);
		  $last_id = mysqli_insert_id($conn);
		  if($result){
		  /***Timing STorage**********/	  	  
		  $cntC                = 0;
		  $week_day           = $_REQUEST["week_day"];
		  $cntC                = count($week_day);
		  for($i=0; $i < 7; $i++){
		  $week_days           = killstring($_REQUEST['week_day'][$i]);
		  $location_open_time_first   = killstring($_REQUEST['location_open_time_first'][$i]);
		  $location_open_time_first   = !empty($location_open_time_first) ? "'$location_open_time_first'" : "NULL";
		  $location_close_time_first  = killstring($_REQUEST['location_close_time_first'][$i]);
		  $location_close_time_first  = !empty($location_close_time_first) ? "'$location_close_time_first'" : "NULL";
		  $location_open_time_second  = killstring($_REQUEST['location_open_time_second'][$i]);
		  $location_open_time_second  = !empty($location_open_time_second) ? "'$location_open_time_second'" : "NULL";
		  $location_close_time_second = killstring($_REQUEST['location_close_time_second'][$i]);
		  $location_close_time_second = !empty($location_close_time_second) ? "'$location_close_time_second'" : "NULL";
		  $location_closed     = killstring($_REQUEST['location_closed'][$i]);
		  $location_closed     = !empty($location_closed)?$location_closed:0;
		  $sqlQryA  = mysqli_query($conn,"INSERT IGNORE INTO `tbl_bms_location_timing_master`(`location_id`, `week_day`, `start_time_first`, `end_time_first`,  `start_time_second`, `end_time_second`,  `is_closed`, `entered_by` , `entered_date`) VALUES ('".$last_id."','".$week_days."',$location_open_time_first,$location_close_time_first,$location_open_time_second,$location_close_time_second,'".$location_closed."','".$userno."',NOW())");
		  }
		  /***Timing STorage**********/	
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS location master insertion for ID:$last_id','2','$ip_address','$userno')");
		  $msg = "Location added successfully";    
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

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Location List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Location List</li>
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
          <h3 class="box-title">Location</h3>
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
                <label class="required">Location Name:</label>
                <input type="text" class="validate[required] form-control" name="location_name" placeholder="Location Name"/>
              </div>
              <div class="form-group">
                <label class="required">Location Landline:</label>
                <input type="text" class="validate[required,custom[phone]] form-control" name="location_landline" placeholder="Location Landline"/>
              </div>
              <div class="form-group">
                <label class="required">Location City:</label>
                <select class="validate[required] form-control select2" name="location_city" id="location_city">
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
                <label class="">Location IATA Code:</label>
                <input type="text" class="form-control" name="location_iata" placeholder="Location IATA Code"/>
              </div>
              <div class="form-group">
                <label class="required">Location Latitude:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="location_latitude" placeholder="Location Latitude"/>
              </div>
              <div class="form-group">
                <label class="required">Location Longitude:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="location_longitude" placeholder="Location Longitude"/>
              </div>
              
              <div class="form-group">
                <label class="">Airport Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="0" name="airport_fee" placeholder="Airport Fee(If Applicable)"/>
              </div>
              
              
             <div class="form-group">
              <label>Buffer Time(hh:mm):</label>
              <div class="input-group">
                <input type="text" id="" class="form-control location_timepicker" name="location_buffer_time" placeholder="Buffer Time" value="0:00">
                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
              </div>
            </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location Mobile:</label>
                <input type="text" class="validate[required,custom[phone]] form-control" name="location_mobile" placeholder="Location Mobile"/>
              </div>
              <div class="form-group">
                <label class="required">Location Email:</label>
                <input type="text" class="validate[required] form-control" name="location_email" placeholder="Location Email(Multiple emails can be added with comma seperated)"/>
              </div>
              <div class="form-group">
                <label class="required">Location Address:</label>
                <textarea class="validate[required] form-control" name="location_address" placeholder="Location Address" rows="8"/></textarea>
              </div>
              <div class="form-group">
                <label class="required">Location Is Airport:</label>
                <select class="validate[required] form-control" name="location_is_airport" id="location_is_airport">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
              
               <div class="form-group">
                <label class="">VMD Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="0" name="vmd_fee" placeholder="VMD Fee(If Applicable)"/>
              </div>
              
              <div class="form-group">
                <label class="required">Status:</label>
                <select class="validate[required] form-control" name="location_status" id="location_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!----------------Start Timing------------------>
        <div class="box-header with-border">
          <h3 class="box-title">Timing List <span style="color:#F00; size:12px;">(If you have only one time slot, leave second time slot empty!)</span></h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" role="grid">
                <thead>
                    <tr>
                      <th >Day</th>
                      <th >Opening Time(First Slot)</th>
                      <th >Closing Time(First Slot)</th>
                      <th >Opening Time(Second Slot)</th>
                      <th >Closing Time(Second Slot)</th>
                      <th >Is Closed</th>
                    </tr>
                  </thead>
                  <tbody>
										<?php 
                                       $daysOfWeek=array("Monday"=>"1","Tuesday"=>"2","Wednesday"=>"3","Thursday"=>"4","Friday"=>"5","Saturday"=>"6","Sunday"=>"7");
									   $j=0;
                                       foreach ($daysOfWeek as $x_key=>$x_value) {
									   $val           = $x_value ;   
                                       ?>  
                                        <tr>
                                          <td><?=$x_key?><input type="hidden" name="week_day[]" value="<?=$val?>"/></td>
                                          <td><div class="form-group">
                                              <div class="input-group">
                                                <input type="text" class="form-control location_timepicker" name="location_open_time_first[]" placeholder="Opening Time" value="0:00">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                                              </div>
                                            </div></td>
                                          <td><div class="form-group">
                                              <div class="input-group">
                                                <input type="text" class="form-control location_timepicker" name="location_close_time_first[]" placeholder="Closing Time" value="23:59">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                                              </div>
                                            </div></td>
                                            
                                             <td><div class="form-group">
                                              <div class="input-group">
                                                <input type="text" class="form-control location_timepicker" name="location_open_time_second[]" placeholder="Opening Time(If Required)" >
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                                              </div>
                                            </div></td>
                                          <td><div class="form-group">
                                              <div class="input-group">
                                                <input type="text" class="form-control location_timepicker" name="location_close_time_second[]" placeholder="Closing Time(If Required)" >
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                                              </div>
                                            </div></td>
                                            
                                          <td>
                                               <input type="checkbox" name="location_closed[<?=$j?>]" value="1"> Is Closed.
                                          </td>
                                        </tr>
                                        <?php 
										    $j++;
                                           }
                                        ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!---------------------------End Timng-------------------------->
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
        </div>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Location List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Location</th>
                      <th >City</th>
                      <th >Airport Fee</th>
                      <th >VMD Fee</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.`entered_date`,tb1.`location_name`,tb1.`is_active`,tb1.`location_id`,IFNULL(tb1.`airport_fee`,0) AS `airport_fee`,IFNULL(tb1.`vmd_fee`,0) AS `vmd_fee`,  tb2.`city_name` FROM `tbl_bms_location_master` tb1 LEFT JOIN `tbl_bms_city_master` tb2 ON tb1.`location_city`=tb2.`city_id` ORDER BY tb1.`location_name` ASC");
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
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['city_name']?></td>
                      <td><?=$build['airport_fee']?></td>
                      <td><?=$build['vmd_fee']?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                      <td><a href="edit_location_master.php?flag=<?=$build['location_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
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
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 0, "desc" ]]})
  })
  $(function () {
	 //Timepicker
    $('.location_timepicker').timepicker({
        minuteStep: 1,
        showInputs: false,
        disableFocus: true,
        showMeridian: false,
		defaultTime: ''
		});
	
	
  })
</script>
