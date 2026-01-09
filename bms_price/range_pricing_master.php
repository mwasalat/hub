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
	/*$price_year          = killstring($_REQUEST['price_year']);*/
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
	   $path = "../uploads/bms/range_price_sheet/".$thumb;
	   move_uploaded_file($_FILES["price_file"]["tmp_name"],"../uploads/bms/range_price_sheet/".$thumb);
	   }     
     }
	 try { 
		$inputFileType = PHPExcel_IOFactory::identify($path);
		$objReader     = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel   = $objReader->load($path);
		//var_dump($objReader);
	  } catch (Exception $e) {
		echo 'Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage();
	  }
	  $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn(); 
	            $QueryCounterB =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_bms_daily_range_file_no`");
				$rowQryB       = mysqli_fetch_row($QueryCounterB);
				$valueC        = $rowQryB[0];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_bms_daily_range_file_no` SET `auto_id`='$valueC'");
				$ordervalueA   = "D-".$valueC;	
	            for ($row = 2; $row <= $highestRow; $row++) { 
				      $parameter           = killstring($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue());
				      $parameter           = !empty( $parameter )? $parameter :0;
				      $group               = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
				      $group               = !empty( $group )? $group :0;
					  	$group_id         = "";
						$qryA       = mysqli_query($conn,"SELECT `group_id` FROM `tbl_bms_group_master` WHERE `group_name`='$group'");
						$rowval     = mysqli_fetch_row($qryA);
						$group_id   = $rowval['0']; //group id finds
				  	  //echo $dateInTimestampValue = PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue());
					 

					  $start_date           = $objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue();
					  $start_date           = str_replace('/', '-', $start_date);
					  $start_date           = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($start_date));
					  $start_date           = !empty($start_date)?"$start_date":"NULL";
					  
					  $end_date             = $objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue();
					  $end_date             = str_replace('/', '-', $end_date);
					  $end_date             = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($end_date));
					  $end_date             = !empty($end_date)?"$end_date":"NULL";
					  
					  $start_day            = killstring($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
					  $start_day             = !empty( $start_day )? $start_day :0;
					  
					  $end_day              = killstring($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue());
					  $end_day             = !empty( $end_day )? $end_day :0;
					 
					  $price                = killstring($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue());
					  $price                = !empty( $price )? $price :0; 
					  
                if (!empty($group_id) && !empty($parameter)) {
					/*****************Start Pricing Mutiple Location*******/
					 for($i=0; $i < $cnt; $i++){
		             $location = killstring($_REQUEST['location'][$i]);
					 /*********Price master***/
					 //$sqlnewquery         = mysqli_query($conn,"INSERT INTO `tbl_bms_range_pricing_master`(`file_no`,`broker_id`, `location_id`,`parameter_id`,`group_id`,`range_from`, `range_to`, `range_price`, `start_date`, `end_date`,`is_active`, `entered_by`) VALUES ('".$ordervalueA."','".$broker."','".$location."','".$parameter."','".$group_id."','".$start_day."','".$end_day."','".$price."', '$start_date','$end_date',1,'$userno')");
					 $sqlnewquery         = mysqli_query($conn,"INSERT INTO `tbl_bms_range_pricing_master`(`file_no`,`broker_id`, `location_id`,`parameter_id`,`group_id`,`range_from`, `range_to`, `range_price`, `start_date`, `end_date`,`is_active`, `entered_by`) VALUES ('".$ordervalueA."','".$broker."','".$location."','".$parameter."','".$group_id."','".$start_day."','".$end_day."','".$price."', '$start_date','$end_date',1,'$userno') ON DUPLICATE KEY UPDATE `range_price`='$price',`updated_by`='$userno',`updated_date`=NOW()");
					 if(empty($sqlnewquery)){ $error_cnt++;}
				     }
					/*****************End Pricing Mutiple Location*******/
				 }else{
					 $error_cnt++;
				 }
			}
		   /*******ERROR CHECK****************/
	                if ($error_cnt==0) {
						$ip_address       = getIP();	   
		                $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS range pricing master upload for Master ID:$ordervalueA','6','$ip_address','$userno')");
                        $alert_label = "alert-success"; 
                        $msg = "Range Price Sheet uploaded successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Range Price Sheet not uploaded completely!";
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
    <h1>Range Pricing List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Range Pricing List</li>
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
          <h3 class="box-title">Range Price List (<span style="color:#F00">Before uploading range pricing for a period, make sure that you already uploaded daily pricing for that period.</span>)</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <?php /*?><div class="form-group">
                <label class="required">Year:</label>
                <select class="validate[required] form-control select" name="price_year" id="price_year" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php for($i=date('Y');$i<=date('Y')+3;$i++){?>
                  <option value="<?=$i?>">
                  <?=$i?>
                  </option>
                  <?php }?>
                </select>
              </div><?php */?>
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
              <div class="form-group">
                <label class="required">Upload Excel File:</label>
                <input type="file" name="price_file" accept=".xlx, .xlsx" class="validate[required,checkFileType[xlx|xlsx],checkFileSize[10]] form-control" title=".xlx | .xlsx file only">
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
          <h5 style="margin-left:14px;">Note:
            <div class="clear"></div>
            1. <font color="#990000">After data upload please wait for sometimes so that the data will be fully uploaded and show a success message.</font>
            <div class="clear"></div>
            2. <font color="#990000">While on data upload please don't click back button until price sheet is fully uploaded.</font>
            <div class="clear"></div>
            3. <font color="#990000">Please make sure that your excel sheet not contains any duplicate rows or wrong data. Cross verify excel sheet before uploading.</font>
            <div class="clear"></div>
            4. <font color="#990000">Please note that the inner cell data of each column will only upload in database. So please remove equations from the cells.</font>
            <div class="clear"></div>
            5. <font color="#990000">Excel Templte Format is </font><a href="../excel_template/bms/daily_range_bms_template.xlsx" target="_blank" style="color:#FFF;" class="btn btn-warning">TEMPLATE</a> </h5>
        </div>
        <div class="clear"></div>
      </div>
      <!-- /.box -->
      <?php /*?><div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Range Pricing List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" role="grid" id="example2">
                  <thead>
                    <tr>
                      <th>Dt.</th>
                      <th>Broker</th>
                      <th>Location</th>
                      <th>Parameter</th>
                      <th>Group</th>
                      <th>From Dt.</th>
                      <th>To Dt.</th>
                      <th>From Day</th>
                      <th>To Day</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.`range_id`,tb1.`parameter_id`,tb1.`start_date`,tb1.`end_date`,tb1.`range_from`,tb1.`range_to`,tb1.`range_price`,tb1.`is_active`,tb1.`entered_date`,tb2.`broker_name`,tb3.`display_name`,tb4.`location_name`,tb5.`group_name` FROM `tbl_bms_range_pricing_master` tb1 LEFT JOIN `tbl_bms_broker_master` tb2 ON tb1.`broker_id`=tb2.`broker_id` LEFT JOIN `tbl_bms_pricing_parameter_master` tb3 ON tb1.`parameter_id`=tb3.`parameter_id`  LEFT JOIN `tbl_bms_location_master` tb4 ON tb1.`location_id`=tb4.`location_id` LEFT JOIN `tbl_bms_group_master` tb5 ON tb1.`group_id`=tb5.`group_id` ORDER BY tb1.`entered_date` DESC");
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
                      <td><?=$build['broker_name']?></td>
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['display_name']?></td>
                      <td><?=$build['group_name']?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['end_date']))?>"><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                      <td><?=$build['range_from']?></td>
                      <td><?=$build['range_to']?></td>
                      <td><?=Checkvalid_number_or_not($build['range_price'],2)?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                      <td><a href="edit_range_pricing_master.php?flag=<?=$build['range_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
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
      </div><?php */?>
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
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<!--<script src="../plugins/datatable_1_10_16/datatables.button.js"></script>-->
<script type="text/javascript" src="../plugins/datatable_1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/jszip.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/buttons.html5.min.js"></script>
<script>
$(document).ready( function () {
    $('#example2').DataTable({ 
	"aaSorting": [[ 0, "desc" ]],
	 dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Range Master',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Range Master',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Range Master',
			    className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
})
</script>