<?php 
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
ini_set('max_execution_time', 0); //0=NOLIMIT
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$company             = killstring($_REQUEST['company']);
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
    $thumb            = "DMS_".substr($filename, 0, $extension_pos) .'_'.time(). substr($filename, $extension_pos);
	 if ($_FILES["price_file"]["error"] > 0 ){ 
	  echo "Error: " . $_FILES["price_file"]["error"] . "<br>";
	  }else{
	   $path = "../uploads/dms/transactions/".$thumb;
	   move_uploaded_file($_FILES["price_file"]["tmp_name"],"../uploads/dms/transactions/".$thumb);
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
	            $QueryCounterB =  mysqli_query($conn,"SELECT `auto_no`+1 as `id`,`company_code` FROM `tbl_dms_company_master` WHERE `company_id`='$company'");
				$rowQryB       = mysqli_fetch_row($QueryCounterB);
				$valueC        = $rowQryB[0];
				$company_code  = $rowQryB[1];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_dms_company_master` SET `auto_no`='$valueC' WHERE `company_id`='$company'");
				$ordervalueA   = $company_code."-".$valueC;	
	            for ($row = 2; $row <= $highestRow; $row++) { 
				      $reference_no           = killstring($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue());
					  $reference_no           = killstring(utf8_decode($reference_no));
				      $payment_type           = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
					  $payment_type           = killstring(utf8_decode($payment_type));
                      $debit_iban_account_no  = killstring($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue());
					  $debit_iban_account_no  = killstring(utf8_decode($debit_iban_account_no));
					  $payment_amount         = killstring($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue());
					  $payment_amount         = !empty( $payment_amount )? str_replace(',', '',$payment_amount) :0;
					  $payment_currency       = killstring($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
					   
					  $cheque_print_date           = $objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue();
					  $cheque_print_date           = str_replace('/', '-', $cheque_print_date);
					  $cheque_print_date           = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cheque_print_date));
					  $cheque_print_date           = !empty($cheque_print_date)?"$cheque_print_date":"NULL";
					  
					  $beneficiary_name       = killstring($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue());
					  $beneficiary_name       = killstring(utf8_decode($beneficiary_name));
					  $beneficiary_address    = killstring($objPHPExcel->getActiveSheet()->getCell('H' . $row)->getValue());
					  $beneficiary_address    = killstring(utf8_decode($beneficiary_address));
					  $payment_details        = killstring($objPHPExcel->getActiveSheet()->getCell('I' . $row)->getValue());
					  $payment_details        = killstring(utf8_decode($payment_details));
					  
					  $cheque_date             = $objPHPExcel->getActiveSheet()->getCell('J' . $row)->getValue();
					  $cheque_date             = str_replace('/', '-', $cheque_date);
					  $cheque_date             = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($cheque_date));
					  $cheque_date             = !empty($cheque_date)?"$cheque_date":"NULL";
					  
					  $remarks            = killstring($objPHPExcel->getActiveSheet()->getCell('K' . $row)->getValue());
					  $remarks            = killstring(utf8_decode($remarks));
					  $additional_details = killstring($objPHPExcel->getActiveSheet()->getCell('AP' . $row)->getValue());
					  $additional_details = killstring(utf8_decode($additional_details));
                if (!empty($reference_no)) {
					/*****************Start Pricing Mutiple Location*******/
					 $sqlnewquery         = mysqli_query($conn,"INSERT INTO `tbl_dms_transactions`(`transaction_no`,`company_id`, `reference_no`,`payment_type`,`debit_iban_account_no`,`payment_amount`, `payment_currency`, `cheque_print_date`, `beneficiary_name`, `beneficiary_address`,`payment_details`, `cheque_date`,`remarks`,`additional_details`,`entered_by`) VALUES ('".$ordervalueA."','".$company."','".$reference_no."','".$payment_type."','".$debit_iban_account_no."','".$payment_amount."','".$payment_currency."', '$cheque_print_date','".$beneficiary_name."','".$beneficiary_address."','".$payment_details."','$cheque_date','".$remarks."','".$additional_details."','$userno')");
					 if(empty($sqlnewquery)){ $error_cnt++;}
					/*****************End Pricing Mutiple Location*******/
				 }
			}
		   /*******ERROR CHECK****************/
	                if ($error_cnt==0) {
                        $alert_label = "alert-success"; 
                        $msg = "Excel Sheet uploaded successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Excel Sheet not uploaded completely!";
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
    <h1>CHEQUE LIST UPLOAD</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">CHEQUE LIST UPLOAD</li>
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
          <h3 class="box-title">CHEQUE LIST UPLOAD</h3>
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
                <label class="required">Company:</label>
                <select class="validate[required] form-control select" name="company" id="company" style="width:100%;">
                <option value="">--Select an option--</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_id`, `company_name` FROM `tbl_dms_company_master` WHERE `is_active`=1 order by `company_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['company_id']?>">
                  <?=$b1['company_name']?>
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
        <h5 style="margin-left:18px;">Note:
        <div class="clear"></div>
         <div class="clear"></div>
          1. <font color="#990000">After data upload please wait for sometimes so that the data will be fully uploaded and show a success message.</font>
          <div class="clear"></div>
         <div class="clear"></div>
          2. <font color="#990000">While on data upload please don't click back button until price sheet is fully uploaded.</font>
          <div class="clear"></div>
        <div class="clear"></div>
          3. <font color="#990000">Please make sure that your excel sheet not contains any duplicate rows or wrong data. Cross verify excel sheet before uploading.</font>
          <div class="clear"></div>
         <div class="clear"></div>
          4. <font color="#990000">Please note that the inner cell data of each column will only upload in database. So please remove equations from the cells.</font>
          <div class="clear"></div>
          <div class="clear"></div>
          5. <font color="#990000">Excel Templte Format is </font><a href="../excel_template/dms/dms_cheque_upload_template.xlsx" target="_blank" style="color:#FFF;" class="btn btn-warning">TEMPLATE</a> </h5>
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