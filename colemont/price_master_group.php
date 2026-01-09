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
	$company             = killstring($_REQUEST['company']);
	$customer_type       = killstring($_REQUEST['customer_type']);
	$batch_name          = killstring($_REQUEST['batch_name']);
	list($company_id,$company_code)  = explode('|',$company);
	$start_date          = killstring($_REQUEST['start_date']);
	$start_date          = date('Y-m-d',strtotime($start_date));
	$end_date            = killstring($_REQUEST['end_date']);
	$end_date            = date('Y-m-d',strtotime($end_date));
	$location            = $_REQUEST['location'];
	$location_comma_value = implode(',', $location);
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
	   $path = "../uploads/colemont/price_list/".$thumb;
	   move_uploaded_file($_FILES["price_file"]["tmp_name"],"../uploads/colemont/price_list/".$thumb);
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
	            $QueryCounterB =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_cmt_price_file_no`");
				$rowQryB       = mysqli_fetch_row($QueryCounterB);
				$valueC        = $rowQryB[0];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_cmt_price_file_no` SET `auto_id`='$valueC'");
				$ordervalueA   = $company_code."-".$valueC;	
				$Query         = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_price_batch_master` (`tr_no`,`company_id`, `emirate_id`, `start_date`, `end_date`,`batch_name`, `customer_type`, `is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$company_id."','".$location_comma_value."','".$start_date."','".$end_date."','".$batch_name."','".$customer_type."',1,'".$userno."')"); 
				$last_id  = mysqli_insert_id($conn);
	            for ($row = 2; $row <= $highestRow; $row++) { 
				$relation_type    = "";
			    $relation_type    = killstring($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue());
				$gender           = "";
			    $gender           = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
				$marital_status   = "";
			    $marital_status   = killstring($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue());
				$start_age   = "";
			    $start_age   = killstring($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue());
				$start_age   = !empty( $start_age )? $start_age :0;
				$end_age     = "";
				$end_age     = killstring($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
			    $end_age     = !empty( $end_age )? $end_age :0;
				$price     = "";
				$price     = killstring($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue());
			    $price     = !empty( $price )? $price :0;
                if (!empty($company_id) && !empty($relation_type)) {
					/*****************Start Pricing Mutiple Location*******/
					 for($i=0; $i < $cnt; $i++){
		             $location = $_REQUEST['location'][$i];
					 /*********Price master***/
					 $MyQuery  = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_price_master` (`tr_no`, `price_batch_id`, `company_id`, `emirate_id`, `start_date`, `end_date`, `start_age`, `end_age`,`relation_type`,`gender`,`marital_status`,`price`, `customer_type`, `is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$last_id."','".$company_id."','".$location."','".$start_date."','".$end_date."','".$start_age."','".$end_age."','".$relation_type."','".$gender."','".$marital_status."','".$price."','".$customer_type."', 1,'".$userno."')"); 
					 if (empty($MyQuery)) {
								$error_cnt = $error_cnt + 1;
					  }
					 /*********Price master***/
				}
					/*****************End Pricing Mutiple Location*******/
             }else{
				 $error_cnt = $error_cnt + 1;
			 }
		}
		/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
						/*$ip_address       = getIP();	   
		                $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS daily pricing master upload for Master ID:$ordervalueA','13','$ip_address','$userno')");*/
                        $alert_label = "alert-success"; 
                        $msg = "Insurance Price Sheet uploaded successfully! Your Batch No is <b>$ordervalueA</b>.";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Insurance Price Sheet not uploaded completely!";
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
    <h1>New Premium</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Premium</li>
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
         <div style="height: 10px;"></div>
          <h3 class="box-title">Premium</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div style="height: 10px;"></div>
          <div style="height: 10px;"></div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Insurance Company:</label>
                <select class="validate[required] form-control select" name="company" id="company" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_name`, `company_code`, `company_id` FROM `tbl_cmt_ins_company_master` WHERE `is_active`=1 order by `company_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['company_id']?>|<?=$b1['company_code']?>"><?=$b1['company_name']?></option>
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
                <label class="">Customer Type:</label>
                <select class="validate[required] form-control select" name="customer_type" id="customer_type" style="width:100%;">
               <!-- <option value="0">Individual</option>-->
                <option value="1">Group</option>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Upload Excel File:</label>
                <input type="file" name="price_file" accept=".xlx, .xlsx" class="validate[required,checkFileType[xlx|xlsx],checkFileSize[10]] form-control" title=".xlx | .xlsx file only">
              </div>
                            
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Emirates:</label>
                <select class="validate[required] form-control select" name="location[]" id="location" style="width:100%;" multiple="multiple">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `emirates_name`, `emirates_id` FROM `tbl_emirates_master` WHERE `is_active`=1 order by `emirates_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['emirates_id']?>"><?=$b1['emirates_name']?></option>
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
                <label class="required">Batch Name:</label>
                <input type="text" name="batch_name"class="validate[required] form-control" placeholder="Batch Name">
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
          5. <font color="#990000">Excel Templte Format is </font><a href="../excel_template/cmt/cmt_ins_master_price_template.xlsx" target="_blank" style="color:#FFF;" class="btn btn-warning">TEMPLATE</a> </h6>
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
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
  /*  startDate: '-0d',*/
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
</script>
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
<script type="text/javascript">
$(document).ready(function() {
   $('#example2').DataTable({
      "aaSorting": [[ 0, "desc" ]]
   });
});     
</script>
