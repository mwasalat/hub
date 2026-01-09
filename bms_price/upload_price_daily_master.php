<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
ini_set('max_execution_time', 0); //0=NOLIMIT
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$price_year          = killstring($_REQUEST['price_year']);
	$broker              = killstring($_REQUEST['broker']);
	$location            = $_REQUEST['location'];
	$cnt                 = 0;
	$cnt                 = count($location);
	$IsPageValid = true;	
    $filename        = killstring($_FILES["price_file"]["name"]);
    $error_cnt       = 0;
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
	$IsPageValid = true;
	if(!in_array($_FILES["price_file"]["type"],$allowedFileType)){
	$msg   = "Invalid file format!";
	$alert_label = "alert-danger"; 
	$IsPageValid = false;
	}else if(empty($filename)){
	$alert_label = "alert-danger"; 
	$msg   = "Please select an excel file!";
	$IsPageValid = false;
	}
	else{
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	/**************Price Upload*****/
	$error_cnt = 0;
    $path="";
	if($filename!='' && $filename!=NULL){  
	$extension_pos    = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
    $thumb            = "ENG_".substr($filename, 0, $extension_pos) .'_'.time(). substr($filename, $extension_pos);
	 if ($_FILES["price_file"]["error"] > 0 ){ 
	  echo "Error: " . $_FILES["price_file"]["error"] . "<br>";
	  }else{
	   $path = "../uploads/bms/daily_price_sheet/".$thumb;
	   move_uploaded_file($_FILES["price_file"]["tmp_name"],"../uploads/bms/daily_price_sheet/".$thumb);
	   }     
     }
	 try { 
		$inputFileType = PHPExcel_IOFactory::identify($path);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($path);
		//var_dump($objReader);
	  } catch (Exception $e) {
		echo 'Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage();
	  }
	  $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn(); 
	            $QueryCounterB =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_bms_daily_price_file_no` WHERE `type`='D'");
				$rowQryB       = mysqli_fetch_row($QueryCounterB);
				$valueC        = $rowQryB[0];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_bms_daily_price_file_no` SET `auto_id`='$valueC' WHERE `type`='D'");
				$ordervalueA   = "D-".$valueC;	
	            for ($row = 3; $row <= $highestRow; $row++) { 
			    /*$date             = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
			    $val_date         = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue())));*/
				$group_id         = "";
			    $group_name       = killstring($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue());
				$qryA       = mysqli_query($conn,"SELECT `group_id` FROM `tbl_bms_group_master` WHERE `group_name`='$group_name'");
				$rowval     = mysqli_fetch_row($qryA);
				$group_id   = $rowval['0']; //group id finds
			    $month            = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
				$month            = str_pad($month, 2, "0", STR_PAD_LEFT);
				      $rate_wd           = killstring($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue());
				      $rate_wd           = !empty( $rate_wd )? $rate_wd :0;
				      $cdw_wd            = killstring($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
				      $cdw_wd            = !empty( $cdw_wd )? $cdw_wd :0;
				      $scdw_wd           = killstring($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue());
					  $scdw_wd           = !empty( $scdw_wd )? $scdw_wd :0;
					  $pai_wd            = killstring($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue());
					  $pai_wd            = !empty( $pai_wd )? $pai_wd :0;
					  $gps_wd            = killstring($objPHPExcel->getActiveSheet()->getCell('H' . $row)->getValue());
					  $gps_wd            = !empty( $gps_wd )? $gps_wd :0;
					  $baby_seat_wd      = killstring($objPHPExcel->getActiveSheet()->getCell('I' . $row)->getValue());
					  $baby_seat_wd      = !empty( $baby_seat_wd )? $baby_seat_wd :0;
					  $driver_fee_wd     = killstring($objPHPExcel->getActiveSheet()->getCell('J' . $row)->getValue());
					  $driver_fee_wd     = !empty( $driver_fee_wd )? $driver_fee_wd :0; 
					  
					  $rate_we           = killstring($objPHPExcel->getActiveSheet()->getCell('K' . $row)->getValue());
				      $rate_we           = !empty( $rate_we )? $rate_we :0;
				      $cdw_we            = killstring($objPHPExcel->getActiveSheet()->getCell('L' . $row)->getValue());
				      $cdw_we            = !empty( $cdw_we )? $cdw_we :0;
				      $scdw_we           = killstring($objPHPExcel->getActiveSheet()->getCell('M' . $row)->getValue());
					  $scdw_we           = !empty( $scdw_we )? $scdw_we :0;
					  $pai_we            = killstring($objPHPExcel->getActiveSheet()->getCell('N' . $row)->getValue());
					  $pai_we            = !empty( $pai_we )? $pai_we :0;
					  $gps_we            = killstring($objPHPExcel->getActiveSheet()->getCell('O' . $row)->getValue());
					  $gps_we            = !empty( $gps_we )? $gps_we :0;
					  $baby_seat_we      = killstring($objPHPExcel->getActiveSheet()->getCell('P' . $row)->getValue());
					  $baby_seat_we      =!empty( $baby_seat_we )? $baby_seat_we :0;
					  $driver_fee_we     = killstring($objPHPExcel->getActiveSheet()->getCell('Q' . $row)->getValue());
					  $driver_fee_we     = !empty( $driver_fee_we )? $driver_fee_we :0;
                if (!empty($group_id) && !empty($month)) {
					/*****************Start Pricing Mutiple Location*******/
					 for($i=0; $i < $cnt; $i++){
		             $location = killstring($_REQUEST['location'][$i]);
					 /*********Price master***/
					 $MyQuery  = mysqli_query($conn,"INSERT INTO `tbl_bms_daily_price_main_master` (`price_file_no`,`price_location_id`, `price_broker_id`, `price_year`, `price_month`, `group_id`, `wd_rate`,`wd_cdw`,`wd_scdw`,`wd_pai`,`wd_gps`,`wd_baby_seat`,`wd_driver`,`we_rate`,`we_cdw`,`we_scdw`,`we_pai`,`we_gps`,`we_baby_seat`,`we_driver`,`is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$location."','".$broker."','".$price_year."','".$month."','".$group_id."','".$rate_wd."','".$cdw_wd."','".$scdw_wd."','".$pai_wd."','".$gps_wd."','".$baby_seat_wd."','".$driver_fee_wd."','".$rate_we."','".$cdw_we."','".$scdw_we."','".$pai_we."','".$gps_we."','".$baby_seat_we."','".$driver_fee_we."',1,'".$userno."') ON DUPLICATE KEY UPDATE `wd_rate`='".$rate_wd."',`wd_cdw`='".$cdw_wd."',`wd_scdw`='".$scdw_wd."',`wd_pai`='".$pai_wd."',`wd_gps`='".$gps_wd."', `wd_baby_seat`='".$baby_seat_wd."',`wd_driver`='".$driver_fee_wd."',`we_rate`='".$rate_we."',`we_cdw`='".$cdw_we."',`we_scdw`='".$scdw_we."',`we_pai`='".$pai_we."',`we_gps`='".$gps_we."', `we_baby_seat`='".$baby_seat_we."',`we_driver`='".$driver_fee_we."',`is_active`='1'"); 
					 if (empty($MyQuery)) {
								$error_cnt = $error_cnt + 1;
					  }
					 /*********Price master***/
				     $qry    = mysqli_query($conn,"SELECT `vehicle_id`,`is_active` FROM `tbl_bms_vehicle_master` WHERE `group_id`='$group_id' ORDER BY `vehicle_id` ASC");
					 while($rowA = mysqli_fetch_array($qry)){
					 $start_day = $price_year."-".$month."-01";
					 $start_day = ($start_day < date('Y-m-d'))?date('Y-m-d'):$start_day;//check start date is less than current day then start from current day.
                     $end_day = $price_year."-".$month."-31";
					 $end_day = ($end_day < date('Y-m-d'))?date('Y-m-d'):$end_day;//check end date is less than current day then end from current day.
					 while (strtotime($start_day) <= strtotime($end_day)) {
						    $day    = date('D',strtotime($start_day));
							$vehicle_id = "";
							$vehicle_id = $rowA['vehicle_id'];
							//$is_active  = !empty( $rowA['is_active'] )? $rowA['is_active'] :0;
							$is_active  = 1;//pricing will be active but will show if the car is active
							if($day=='Sat' || $day=='Sun'){
							$query  = "INSERT INTO `tbl_bms_daily_price_master` (`price_file_no`,`price_location_id`, `price_broker_id`, `price_year`, `price_month`, `group_id`, `vehicle_id`, `price_date`, `day_type`,`rate`,`cdw`,`scdw`,`pai`,`gps`,`baby_seat`,`driver`,`is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$location."','".$broker."','".$price_year."','".$month."','".$group_id."','".$vehicle_id."','".$start_day."','2','".$rate_we."','".$cdw_we."','".$scdw_we."','".$pai_we."','".$gps_we."','".$baby_seat_we."','".$driver_fee_we."','".$is_active."','".$userno."') ON DUPLICATE KEY UPDATE `rate`='".$rate_we."',`cdw`='".$cdw_we."',`scdw`='".$scdw_we."',`pai`='".$pai_we."',`gps`='".$gps_we."', `baby_seat`='".$baby_seat_we."',`driver`='".$driver_fee_we."',`is_active`='1'"; 
							}else{
							$query  = "INSERT INTO `tbl_bms_daily_price_master` (`price_file_no`,`price_location_id`, `price_broker_id`, `price_year`, `price_month`, `group_id`, `vehicle_id`, `price_date`, `day_type`,`rate`,`cdw`,`scdw`,`pai`,`gps`,`baby_seat`,`driver`,`is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$location."','".$broker."','".$price_year."','".$month."','".$group_id."','".$vehicle_id."','".$start_day."','1','".$rate_wd."','".$cdw_wd."','".$scdw_wd."','".$pai_wd."','".$gps_wd."','".$baby_seat_wd."','".$driver_fee_wd."','".$is_active."','".$userno."')  ON DUPLICATE KEY UPDATE `rate`='".$rate_wd."',`cdw`='".$cdw_wd."',`scdw`='".$scdw_wd."',`pai`='".$pai_wd."',`gps`='".$gps_wd."', `baby_seat`='".$baby_seat_wd."',`driver`='".$driver_fee_wd."',`is_active`='1'";	
							}
							$result = mysqli_query($conn, $query);
							if (empty($result)) {
								$error_cnt = $error_cnt + 1;
							}
							$start_day = date ("Y-m-d", strtotime("+1 days", strtotime($start_day)));  
						}
					}
				}
					/*****************End Pricing Mutiple Location*******/
             }else{
				 $error_cnt = $error_cnt + 1;
			 }
		}
		/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
						$ip_address       = getIP();	   
		                $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS daily pricing master upload for Master ID:$ordervalueA','13','$ip_address','$userno')");
                        $alert_label = "alert-success"; 
                        $msg = "Daily Price Sheet uploaded successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Daily Price Sheet not uploaded completely!";
                   }
	   /**********************************/
   }//valid rue	 	
 }
}

?>
<!-- Content Wrapper. Contains page content -->
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
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
      <?php }?>
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
                <label class="required">Year:</label>
                <select class="validate[required] form-control select" name="price_year" id="price_year" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php for($i=date('Y');$i<=date('Y')+3;$i++){?>
                  <option value="<?=$i?>">
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Broker:</label>
                <select class="validate[required] form-control select" name="broker" id="broker" style="width:100%;">
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location:</label>
                <select class="validate[required] form-control select" name="location[]" id="location" style="width:100%;" multiple="multiple">
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
                <label class="required">Upload Excel File:</label>
                <input type="file" name="price_file" accept=".xlx, .xlsx" class="validate[required,checkFileType[xlx|xlsx],checkFileSize[10]] form-control" title=".xlx | .xlsx file only">
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
        <div class="box-footer box-comments">
        <h6 style="margin-left:14px;">Note:<br/>
          <div class="clear"></div>
          1. <font color="#990000">After data upload please wait for sometimes so that the data will be fully uploaded and show a success message.</font><br/>
          <div class="clear"></div>
          2. <font color="#990000">While on data upload please don't click back button until price sheet is fully uploaded.</font><br/>
          <div class="clear"></div>
          3. <font color="#990000">Please make sure that your excel sheet not contains any duplicate rows or wrong data. Cross verify excel sheet before uploading.</font><br/>
          <div class="clear"></div>
          4. <font color="#990000">Please note that the inner cell data of each column will only upload in database. So please remove equations from the cells.</font><br/>
          <div class="clear"></div>
          5. <font color="#990000">Excel Templte Format is </font><a href="../excel_template/bms/daily_pricing_bms_template.xlsx" target="_blank" style="color:#FFF;" class="btn btn-warning">TEMPLATE</a> </h6>
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
});
</script>