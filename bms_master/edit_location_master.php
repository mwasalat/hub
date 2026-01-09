<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$location_id_now        = killstring($_REQUEST['location_id_now']);
	$location_name          = killstring($_REQUEST['location_name']);
	$location_mobile        = killstring($_REQUEST['location_mobile']);
	$location_landline      = killstring($_REQUEST['location_landline']);
	$location_email         = killstring($_REQUEST['location_email']);
	$location_city          = killstring($_REQUEST['location_city']);
	$location_address       = killstring($_REQUEST['location_address']);
	$location_iata          = killstring($_REQUEST['location_iata']);
	$location_latitude      = killstring($_REQUEST['location_latitude']);
	$location_longitude     = killstring($_REQUEST['location_longitude']);
	$location_status        = killstring($_REQUEST['location_status']);
	$location_is_airport    = killstring($_REQUEST['location_is_airport']);
	$location_buffer_time   = killstring($_REQUEST['location_buffer_time']);
	$location_buffer_time   = !empty($location_buffer_time) ? "'$location_buffer_time'" : "NULL";
	
	$airport_fee   = killstring($_REQUEST['airport_fee']);
	$airport_fee   = !empty($airport_fee) ? "'$airport_fee'" : "NULL";
	$vmd_fee   = killstring($_REQUEST['vmd_fee']);
	$vmd_fee   = !empty($vmd_fee) ? "'$vmd_fee'" : "NULL";
	
	$IsPageValid = true;	
	if(empty($location_id_now)){
	$msg   = "Error on ID!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql     = "UPDATE `tbl_bms_location_master` SET `location_mobile`='$location_mobile',`location_landline`='$location_landline',`location_email`='$location_email',`location_city`='$location_city',`location_address`='$location_address',`location_iata`='$location_iata',`location_latitude`='$location_latitude',`location_longitude`='$location_longitude',`location_is_airport`='$location_is_airport',`location_buffer_time`=$location_buffer_time,`airport_fee`=$airport_fee,`vmd_fee`=$vmd_fee,`is_active`='$location_status',`updated_by`='$userno',`updated_date`=NOW() WHERE `location_id`='$location_id_now' ";
		  $result  = mysqli_query($conn,$sql); 
		  if($result){
		  /***Timing STorage**********/	  
		  $cntC                = 0;
		  $timing_id           = $_REQUEST["timing_id"];
		  $cntC                = count($timing_id);
		  for($i=0; $i < 7; $i++){
			  $timing_id_now       = 0;  
			  $location_closed     = 0;
			  $timing_id_now       = killstring($_REQUEST['timing_id'][$i]);
			  $week_days           = killstring($_REQUEST['week_day'][$i]);
			  $location_open_time_first  = killstring($_REQUEST['location_open_time_first'][$i]);
			  $location_open_time_first  = !empty($location_open_time_first) ? "'$location_open_time_first'" : "NULL";
			  $location_close_time_first = killstring($_REQUEST['location_close_time_first'][$i]);
			  $location_close_time_first = !empty($location_close_time_first) ? "'$location_close_time_first'" : "NULL";
			   
			  
			  $location_open_time_second  = killstring($_REQUEST['location_open_time_second'][$i]);
			  $location_open_time_second  = !empty($location_open_time_second) ? "'$location_open_time_second'" : "NULL";
			  $location_close_time_second = killstring($_REQUEST['location_close_time_second'][$i]);
			  $location_close_time_second = !empty($location_close_time_second) ? "'$location_close_time_second'" : "NULL";
			  
			  $location_closed     = killstring($_REQUEST['location_closed'][$i]);
			  $location_closed     = !empty($location_closed)?$location_closed:0;
			  if(empty($timing_id_now)){
			  $sqlQryA  = mysqli_query($conn,"INSERT IGNORE INTO `tbl_bms_location_timing_master`(`location_id`, `week_day`, `start_time_first`, `end_time_first`, `start_time_second`, `end_time_second`, `is_closed`, `entered_by` , `entered_date`) VALUES ('".$location_id_now."','".$week_days."',$location_open_time_first,$location_close_time_first,$location_open_time_second,$location_close_time_second,'".$location_closed."','".$userno."',NOW())");
			  }else{
			  $sqlQryA  = mysqli_query($conn,"UPDATE `tbl_bms_location_timing_master` SET `start_time_first`=$location_open_time_first, `end_time_first` =$location_close_time_first, `start_time_second`=$location_open_time_second, `end_time_second` =$location_close_time_second, `is_closed`='".$location_closed."', `updated_by`='".$userno."' , `updated_date`=NOW() WHERE `timing_id`='$timing_id_now'");  
			  }
		  }  
		  /***Timing STorage**********/	  
		  
		    /***Exception STorage**********/	  
		  $cntD               = 0;
		  $exception_name     = $_REQUEST["exception_name"];
		  $cntD               = count($exception_name);
		  for($i=0; $i < $cntD; $i++){
			  $exception_date       = killstring($_REQUEST['exception_date'][$i]);
			  $exception_date       = !empty($exception_date) ? date('Y-m-d',strtotime($exception_date)) : NULL;
			  $exception_date       = !empty($exception_date) ? "'$exception_date'" : "NULL";
			  $exception_name       = killstring($_REQUEST['exception_name'][$i]);
			  $exception_start_time = killstring($_REQUEST['exception_start_time'][$i]);
			  $exception_start_time = !empty($exception_start_time) ? "'$exception_start_time'" : "NULL";
			  $exception_end_time   = killstring($_REQUEST['exception_end_time'][$i]);
			  $exception_end_time   = !empty($exception_end_time) ? "'$exception_end_time'" : "NULL";
			  $exception_is_active  = killstring($_REQUEST['exception_is_active'][$i]);
			  $exception_id        = killstring($_REQUEST['exception_id'][$i]);
			  if(empty($exception_id)){
			  $sqlQryA  = mysqli_query($conn,"INSERT IGNORE INTO `tbl_bms_location_exception_timing_master`(`location_id`, `name`, `exception_date`, `start_time`, `end_time`, `is_active`, `entered_by` , `entered_date`) VALUES ('".$location_id_now."','".$exception_name."',$exception_date,$exception_start_time,$exception_end_time,'".$exception_is_active."','".$userno."',NOW())");
			  }else{
			  $sqlQryA  = mysqli_query($conn,"UPDATE `tbl_bms_location_exception_timing_master` SET `is_active`='".$exception_is_active."', `updated_by`='".$userno."' , `updated_date`=NOW() WHERE `exception_id`='$exception_id'");  
			  }
		  }  
		  /***Exception STorage**********/	 
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS location master updation for ID:$location_id_now','3','$ip_address','$userno')");
		  $msg = "Location updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}


$location_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_location_master` WHERE `location_id`='$location_id'");
while($rowA = mysqli_fetch_array($qry)){
	$location_id_now     = $rowA['location_id'];
	$location_name       = $rowA['location_name'];
	$location_mobile     = $rowA['location_mobile'];
	$location_landline   = $rowA['location_landline'];
	$location_email      = $rowA['location_email'];
	$location_city       = $rowA['location_city'];
	$location_address    = $rowA['location_address'];
	$location_iata       = $rowA['location_iata'];
	$location_latitude   = $rowA['location_latitude'];
	$location_longitude  = $rowA['location_longitude'];
	$location_is_airport = $rowA['location_is_airport'];
	$location_buffer_time= $rowA['location_buffer_time'];
	$airport_fee         = !empty($rowA['airport_fee'])?$rowA['airport_fee']:0;
	$vmd_fee             = !empty($rowA['vmd_fee'])?$rowA['vmd_fee']:0;
	$is_active           = $rowA['is_active'];
	
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Location - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Location - Edit</li>
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
        <h3 class="box-title">Location - Edit</h3>
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
              <input type="text" class="validate[required] form-control" name="location_name" placeholder="Location Name" value="<?=$location_name?>" readonly="readonly"/>
            </div>
            <div class="form-group">
              <label class="required">Location Landline:</label>
              <input type="text" class="validate[required,custom[phone]] form-control" name="location_landline" placeholder="Location Landline" value="<?=$location_landline?>" />
            </div>
            <div class="form-group">
              <label class="required">Location City:</label>
              <select class="validate[required] form-control select2" name="location_city" id="location_city">
                <option selected="selected" value="">---Select an option---</option>
                <?php   
				  $d1 = mysqli_query($conn,"SELECT `city_name`, `city_id` FROM `tbl_bms_city_master` WHERE `is_active`=1 order by `city_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                <option value="<?=$b1['city_id']?>" <?php if($b1['city_id']==$location_city){echo "selected";}?>>
                <?=$b1['city_name']?>
                </option>
                <?php		
                  } 
                 ?>
              </select>
            </div>
              <div class="form-group">
                <label class="">Location IATA Code:</label>
                <input type="text" class="form-control" name="location_iata" placeholder="Location IATA Code" value="<?=$location_iata?>"/>
              </div>
            <div class="form-group">
              <label class="required">Location Latitude:</label>
              <input type="text" class="validate[required,custom[number]] form-control" name="location_latitude" placeholder="Location Latitude" value="<?=$location_latitude?>"/>
            </div>
            <div class="form-group">
              <label class="required">Location Longitude:</label>
              <input type="text" class="validate[required,custom[number]] form-control" name="location_longitude" placeholder="Location Longitude" value="<?=$location_longitude?>"/>
            </div>
             <div class="form-group">
                <label class="">Airport Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="<?=$airport_fee?>" name="airport_fee" placeholder="Airport Fee(If Applicable)"/>
              </div>
              
            <div class="form-group">
              <label>Buffer Time(hh:mm):</label>
              <div class="input-group">
                <input type="text" id="" class="form-control location_timepicker" name="location_buffer_time" placeholder="Buffer Time" value="<?=$location_buffer_time?>">
                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="required">Location Mobile:</label>
              <input type="text" class="validate[required,custom[phone]] form-control" name="location_mobile" placeholder="Location Mobile" value="<?=$location_mobile?>"/>
            </div>
            <div class="form-group">
              <label class="required">Location Email:</label>
              <input type="text" class="validate[required] form-control" name="location_email" placeholder="Location Email(Multiple emails can be added with comma seperated)" value="<?=$location_email?>"/>
            </div>
            <div class="form-group">
              <label class="required">Location Address:</label>
              <textarea class="validate[required] form-control" name="location_address" placeholder="Location Address" rows="8"/><?=nl2br($location_address)?></textarea>
            </div>
            <div class="form-group">
              <label class="required">Location Is Airport:</label>
              <select class="validate[required] form-control" name="location_is_airport" id="location_is_airport">
                <option selected="selected" value="">---Select an option---</option>
                <option value="0" <?php if($location_is_airport==0){echo "selected";}?>>No</option>
                <option value="1" <?php if($location_is_airport==1){echo "selected";}?>>Yes</option>
              </select>
            </div>
              
               <div class="form-group">
                <label class="">VMD Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="<?=$vmd_fee?>" name="vmd_fee" placeholder="VMD Fee(If Applicable)"/>
              </div>
            <div class="form-group">
              <label class="required">Status:</label>
              <select class="validate[required] form-control" name="location_status" id="location_status">
                <option selected="selected" value="">---Select an option---</option>
                <option value="1" <?php if($is_active==1){echo "selected";}?>>Active</option>
                <option value="0" <?php if($is_active==0){echo "selected";}?>>Inactive</option>
              </select>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <!----------------Start Timing------------------>
        <div class="box-header with-border">
          <h3 class="box-title">Timing List</h3>
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
										 $timing_id     = "";
										 $start_time    = "0:00";  
										 $end_time      = "23:59";  
										 $is_closed     = 0;
										 $val           = $x_value ;             
										 $qry    = mysqli_query($conn,"SELECT `timing_id`,`start_time_first`,`end_time_first`,`start_time_second`,`end_time_second`,`is_closed` FROM `tbl_bms_location_timing_master` WHERE `location_id`='$location_id_now' AND `week_day`='$val'");
										 while($rowA = mysqli_fetch_array($qry)){
										 $timing_id     = $rowA['timing_id']; 
										 $start_time_first    = !empty($rowA['start_time_first'])?$rowA['start_time_first']:"0:00";  
										 $end_time_first      = !empty($rowA['end_time_first'])?$rowA['end_time_first']:"23:59";  
										 $start_time_second   = !empty($rowA['start_time_second'])?$rowA['start_time_second']:NULL;  
										 $end_time_second     = !empty($rowA['end_time_second'])?$rowA['end_time_second']:NULL;  
										 $is_closed           = $rowA['is_closed'];
										 }
                                       ?>
                    <tr>
                      <td><?=$x_key?>
                        <input type="hidden" name="timing_id[]" value="<?=$timing_id?>"/>
                        <input type="hidden" name="week_day[]" value="<?=$val?>"/></td>
                      <td><div class="form-group">
                          <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="location_open_time_first[]" placeholder="Opening Time" value="<?= $start_time_first?>">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div>
                        </div></td>
                      <td><div class="form-group">
                          <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="location_close_time_first[]" placeholder="Closing Time" value="<?= $end_time_first?>">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div>
                        </div></td>
                          <td><div class="form-group">
                          <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="location_open_time_second[]" placeholder="Opening Time(If Required)" value="<?= $start_time_second?>">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div>
                        </div></td>
                      <td><div class="form-group">
                          <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="location_close_time_second[]" placeholder="Closing Time(If Required)" value="<?= $end_time_second?>">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div>
                        </div></td>
                      <td><input type="checkbox" name="location_closed[<?=$j?>]" value="1" <?php if($is_closed=='1'){?> checked="checked"<?php }?>>&nbsp;Is Closed</td>
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
        <!------------------Start Exceptional Timing--------------------------->
           <div class="box-body">
            <div class="row">
              <div class="col-md-12"><hr/>
                <div class="input-group"> <span class="input-group-btn">
                  <button class="btn btn-default" type="button" name="feature_btn" onClick="add_exception();">+ Add Exception Item</button>
                  </span> </div>
              </div>
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-sriped" role="grid">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Dt.</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="cbm_tr_features">
                    <?php	
						 $total_value_hidden   = 0;
						 $total_qty_hidden     = 0;
						 $itr                  = 0;
						 $sqlA           = mysqli_query($conn,"SELECT * FROM `tbl_bms_location_exception_timing_master` WHERE `location_id`='$location_id_now'  ORDER BY `entered_date` DESC");
						 $NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
						 if($NumA>0){
						 while($buildA = mysqli_fetch_array($sqlA)){ 
						 $unique_id = uniqid();
						 $itr       = $unique_id;
						 ?>
                          <tr id="products_existing_service_<?=$itr?>">
                            <td><input type="hidden" name="exception_id[]" value="<?=$buildA['exception_id']?>"/><input type="text" name="exception_name[]" value="<?=$buildA['name']?>" class="validate[required] form-control" readonly="readonly"/></td>
                            <td>   <div class="input-group date">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="validate[required] form-control" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" name="exception_date[]" value="<?=date('d-m-Y',strtotime($buildA['exception_date']));?>" readonly="readonly"> 
                  </div>
                  </td>
<td>  <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="exception_start_time[]" placeholder="Start Time" value="<?=$buildA['start_time']?>" readonly="readonly">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div></td>	
<td>  <div class="input-group">
                            <input type="text" class="form-control location_timepicker" name="exception_end_time[]" placeholder="End Time" value="<?=$buildA['end_time']?>" readonly="readonly">
                            <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
                          </div></td>
<td>
 <select class="validate[required] form-control" name="exception_is_active[]">
                 <option value="1" <?php if($buildA['is_active']==1){echo "selected";}?>>Active</option>
                 <option value="0" <?php if($buildA['is_active']==0){echo "selected";}?>>Not Active</option>
                </select>
</td>
<td>
<?php if($buildA['exception_date']>=date('Y-m-d')){?>
<a href="javascript:void(0)" class="btn btn-danger" onClick="products_remove_item('<?=$buildA['exception_id']?>','<?=$itr?>');">DELETE</a>
<?php }?>
</td>
                          </tr>
                          <?php
			      $itr++;
				 }
			 }
			?>
                    </tbody>
                  </table>
                 <hr/>
                </div>
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
         <!------------------End Exceptional Timing---------------------------> 
        <div class="box-footer"> <a href="location_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">BACK</a>
          <input type="hidden" name="location_id_now" value= "<?=$location_id_now?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">UPDATE</button>
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
//Date picker
$('.datepicker').datepicker({
autoclose: true,
format: 'dd-mm-yyyy'
});
$('.datepicker').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]]})
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
  });
/************************ADD Items*******************/
function add_exception(){
	var scntDiv = $('#cbm_tr_features');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_exception_location_timing_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
	$(function () {
	 //Timepicker
		$('.location_timepicker').timepicker({
			minuteStep: 1,
			showInputs: false,
			disableFocus: true,
			showMeridian: false,
			defaultTime: ''
			});
	  });
		$('.datepicker').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
		startDate: '-0d',
		});
		$('.datepicker').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
    }
} 
//Remove new item
function products_remove_item_added(tr_id){
     var r = confirm("Are you want to remove this item from the list?");
			if (r == true) {
			$('#products_added_service_'+tr_id).remove();
			products_change_price(tr_id);  
          } 
}

//remove item
function products_remove_item(exception_id,tr_id){
     var r = confirm("Are you want to remove this item from the list?");
			if (r == true) {
			var xmlhttp=new XMLHttpRequest(); 
			xmlhttp.open("GET","../ajax/ajax_remove_exception_location_timing_item.php?flag="+exception_id,false);
			xmlhttp.send(null);
			if($.trim(xmlhttp.responseText)!='0'){
			$('#products_existing_service_'+tr_id).remove();
			}
          }   
}

/************************End Items*******************/  
</script>