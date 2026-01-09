<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$start_date      = $_REQUEST['start_date'];
	$start_date      = date('Y-m-d',strtotime($start_date));
	$end_date        = $_REQUEST['end_date'];
	$end_date        = date('Y-m-d',strtotime($end_date));
	$broker          = killstring($_REQUEST['broker']);
	$vehicle         = killstring($_REQUEST['vehicle']);
	$location        = killstring($_REQUEST['location']);
	$rate            = killstring($_REQUEST['rate']);
	$cdw             = killstring($_REQUEST['cdw']);
	$cdw             = ($cdw>=0 && $cdw!=NULL)?"'$cdw'":"NULL";
	$scdw            = killstring($_REQUEST['scdw']);
	$scdw            = ($scdw>=0 && $scdw!=NULL)?"'$scdw'":"NULL";
	$baby_seat       = killstring($_REQUEST['baby_seat']);
	$baby_seat       = ($baby_seat>=0 && $baby_seat!=NULL)?"'$baby_seat'":"NULL";
	$pai             = killstring($_REQUEST['pai']);
	$pai             = ($pai>=0 && $pai!=NULL)?"'$pai'":"NULL";
	$gps             = killstring($_REQUEST['gps']);
	$gps             = ($gps>=0 && $gps!=NULL)?"'$gps'":"NULL";
	$driver          = killstring($_REQUEST['driver']);
	$driver          = ($driver>=0 && $driver!=NULL)?"'$driver'":"NULL";
	$error_cnt       = 0;
	$IsPageValid = true;
	if(empty($vehicle)){
	$alert_label = "alert-danger"; 
	$msg   = "Please select vehicle"; 
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){
		
		  $sql     = "INSERT IGNORE INTO `tbl_bms_bulk_price_edit`(`broker_id`, `vehicle_id`,`start_date`, `end_date`, `location_id`, `rate`,`cdw`,`scdw`,`baby_seat`,`pai`,`gps`,`driver`,`entered_by`) VALUES ('$broker','$vehicle','$start_date','$end_date','$location','$rate',$cdw,$scdw,$baby_seat,$pai,$gps,$driver,'$userno')";
		  $resultA  = mysqli_query($conn,$sql); 
		  $last_id  = mysqli_insert_id($conn);
		  if($resultA){
		  /***Daywise price update******/
					 $start_day = $start_date;//check start date is less than current day then start from current day.
					 $end_day   = $end_date;//check end date is less than current day then end from current day.
					 while (strtotime($start_day) <= strtotime($end_day)) {
						    $day        = date('D',strtotime($start_day));
							$vehicle_id = $vehicle;
							$query  = "UPDATE `tbl_bms_daily_price_master` SET `rate`='".$rate."'";
							if($cdw>=0 && $cdw!=NULL){
							$query.= ",`cdw`='".$cdw."'";	
							}
							if($scdw>=0 && $scdw!=NULL){
							$query.= ",`scdw`='".$scdw."'";	
							}
							if($pai>=0 && $pai!=NULL){
							$query.= ",`pai`='".$pai."'";	
							}
							if($gps>=0 && $gps!=NULL){
							$query.= ",`gps`='".$gps."'";	
							}
							if($baby_seat>=0 && $baby_seat!=NULL){
							$query.= ",`baby_seat`='".$baby_seat."'";	
							}
							if($driver>=0 && $driver!=NULL){
							$query.= ",`driver`='".$driver."'";	
							}
							$query.= ",`updated_by`='".$userno."',`updated_date`=NOW() WHERE `price_broker_id`='$broker' AND `price_location_id`='$location' AND `vehicle_id`='$vehicle' AND `price_date`='$start_day'"; 	
							//echo $query;
							$result = mysqli_query($conn, $query);
							if (empty($result)) {
								$error_cnt = $error_cnt + 1;
							}
							$start_day = date ("Y-m-d", strtotime("+1 days", strtotime($start_day)));  
						}
		   /***Daywise price update******/			
		  }
		/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
						$ip_address       = getIP();	   
		                $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS price master bulk edit for Master ID:$last_id','15','$ip_address','$userno')");
                        $alert_label = "alert-success"; 
                        $msg = "Daily Price Sheet updated successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Daily Price Sheet not updated completely!";
                   }
	   /**********************************/
	   
  }//valid rue	 	
 }
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Bulk Price Edit List <small><font color="#FF0000" style="font-weight:bold;">If there is no change in accessories, don't enter any values.</font></small></h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Bulk Price Edit List</li>
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
          <h3 class="box-title">Bulk Price Edit</h3>
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
                  <option value="<?=$b1['broker_id']?>">
                  <?=$b1['broker_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Vehicle:</label>
                <select class="validate[required] form-control select2" name="vehicle" id="vehicle" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `vehicle_name`, `vehicle_id` FROM `tbl_bms_vehicle_master` WHERE `is_active`=1 order by `vehicle_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['vehicle_id']?>">
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
                  <input type="text" name="end_date" class="validate[required] datepickerA form-control" id="end_date"/>
                </div>
              </div>
              <div class="form-group">
                <label class="">SCDW:</label>
                <input type="text" class="validate[custom[number],min[0]] form-control" id="scdw" value="" name="scdw" placeholder="SCDW"/>
              </div>
              <div class="form-group">
                <label class="">GPS:</label>
                <input type="text" class="validate[custom[number],min[0]] form-control" id="gps" value="" name="gps" placeholder="GPS"/>
              </div>
              <div class="form-group">
                <label class="">Driver Fee:</label>
                <input type="text" class="validate[custom[number],min[0]] form-control" id="driver" value="" name="driver" placeholder="Driver Fee"/>
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
                  <option value="<?=$b1['location_id']?>">
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
                  <input type="text" name="start_date" class="validate[required] datepickerA form-control" id="start_date"/>
                </div>
              </div>
              
              <div class="form-group">
                <label class="required">Rate:</label>
                <input type="text" class="validate[required,custom[number],min[0]] form-control" id="rate" value="" name="rate" placeholder="Rate"/>
              </div>
              <div class="form-group">
                <label class="">CDW:</label>
                <input type="text" class="validate[custom[number],min[0]] form-control" id="cdw" value="" name="cdw" placeholder="CDW"/>
              </div>
              <div class="form-group">
                <label class="">PAI:</label>
                <input type="text" class="validate[custom[number],min[0]] form-control" id="pai" value="" name="pai" placeholder="PAI"/>
              </div>
              <div class="form-group">
                <label class="">Baby Seat:</label>
                <input type="text" class="validate[custom[number],min[0]] form-control" id="baby_seat" value="" name="baby_seat" placeholder="Baby Seat"/>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer bulk_price_value_div">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit" onSubmit="return confirm('Are you sure to change the price for the period?')">Submit</button>
        </div>
        <br/>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
                  <thead>
                    <tr>
                      <th >Broker</th>
                      <th >Location</th>
                      <th >Vehicle</th>
                      <th >Start Dt.</th>
                      <th >End Dt.</th>
                      <th >Rate</th>
                      <th >CDW</th>
                      <th >SCDW</th>
                      <th >PAI</th>
                      <th >GPS</th>
                      <th >Baby Seat</th>
                      <th >Additional<br/>Driver</th>
                      <th ></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb1.`start_date`,tb1.`end_date`,tb1.`is_active`,tb2.`location_name`,tb3 .`broker_name`,tb4.`vehicle_name`,tb5.`group_name` FROM `tbl_bms_bulk_price_edit` tb1 INNER JOIN `tbl_bms_location_master` tb2 ON tb1.`location_id`=tb2.`location_id` INNER JOIN `tbl_bms_broker_master` tb3 ON tb1.`broker_id`=tb3.`broker_id` INNER JOIN `tbl_bms_vehicle_master` tb4 ON tb1.`vehicle_id`=tb4.`vehicle_id` INNER JOIN `tbl_bms_group_master` tb5 ON tb4.`group_id`=tb5.`group_id` ORDER BY tb1.`bulk_price_id` DESC");
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
                      <td><?=$build['broker_name']?></td>
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['vehicle_name']?>(<?=$build['group_name']?>)</td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['end_date']))?>"><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                     <?php /*?> <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td><?php */?>
                     <td><?=$build['rate']?></td>
                     <td><?=$build['cdw']?></td>
                     <td><?=$build['scdw']?></td>
                     <td><?=$build['pai']?></td>
                     <td><?=$build['gps']?></td>
                     <td><?=$build['baby_seat']?></td>
                     <td><?=$build['driver']?></td>
                      <td><?php if($build['end_date']>date('Y-m-d')){?>
                        <a href="edit_bulk_price.php?flag=<?=$build['bulk_price_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i>
                        <?php }?></td>
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
  $('#example2').DataTable({ "autowidth":false,"aaSorting": [[ 0, "desc" ]]})
})
</script>