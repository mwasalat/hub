<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
ini_set('max_execution_time', 0); //0=NOLIMIT
set_time_limit(0);
ini_set('memory_limit','2048M');
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$today               = date('Y-m-d');
$msg = "";
$flagCustomer = !empty($_REQUEST['flag'])?killstring($_REQUEST['flag']):NULL;
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$CustomerRadionBtn   = killstring($_REQUEST['CustomerRadionBtn']);		
	//$customer            = killstring($_REQUEST['customer']);	
	$batch_name          = killstring($_REQUEST['batch_name']);
	$location_active     = killstring($_REQUEST['location_active']);
	/****************************/
    $cnt_company          = 0;
	$company_id_now       = $_REQUEST['company_id'];
	$cnt_company          = count($company_id_now);
	/****************************/
	$submit_status = 0;
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
	
	/*********New Customer*********/
	if($CustomerRadionBtn==0){
	$customer_name          = killstring($_REQUEST['customer_name']);
	$customer_code          = killstring($_REQUEST['customer_code']);
	$customer_address       = killstring($_REQUEST['customer_address']);
	$customer_phone         = killstring($_REQUEST['customer_phone']);
	$customer_fax           = killstring($_REQUEST['customer_fax']);
	$customer_email         = killstring($_REQUEST['customer_email']);
	$customer_person        = killstring($_REQUEST['customer_person']);
	$sqlQry                 = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_customer_master`(`customer_name`, `customer_code`, `customer_person`, `customer_address`, `customer_fax`, `customer_phone`,`customer_email`, `customer_type`, `entered_by`) VALUES ('$customer_name','$customer_code','$customer_person','$customer_address','$customer_fax','$customer_phone','$customer_email',1,'$userno')");
	$customer            = mysqli_insert_id($conn);
	}else{
	$customer            = killstring($_REQUEST['customer']);	
	}
	/*********New Customer*********/
	/**************Price Upload*****/
	$error_cnt = 0;
    $path="";
	if($filename!='' && $filename!=NULL){  
	$extension_pos    = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
    $thumb            = "ENG_".substr($filename, 0, $extension_pos) .'_'.time(). substr($filename, $extension_pos);
	 if ($_FILES["price_file"]["error"] > 0 ){ 
	  echo "Error: " . $_FILES["price_file"]["error"] . "<br>";
	  }else{
	   $path = "../uploads/colemont/member_list/".$thumb;
	   move_uploaded_file($_FILES["price_file"]["tmp_name"],"../uploads/colemont/member_list/".$thumb);
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
	            $QueryCounterB =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_cmt_ins_member_file_no`");
				$rowQryB       = mysqli_fetch_row($QueryCounterB);
				$valueC        = $rowQryB[0];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_cmt_ins_member_file_no` SET `auto_id`='$valueC'");
				$ordervalueA   = "CMT-".$valueC;	
				$QryMaster  = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_members_master` (`tr_no`,`customer_id`, `emirate_id`, `batch_name`, `customer_type`, `is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$customer."','".$location_active."','".$batch_name."',1,1,'".$userno."')"); 
				$last_id  = mysqli_insert_id($conn);
	            for ($row = 2; $row <= $highestRow; $row++) { 
				$sl_no                = "";
			    $sl_no                = killstring($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue());
				$first_name           = "";
			    $first_name           = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
				$middle_name   = "";
			    $middle_name   = killstring($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue());
				$last_name   = "";
			    $last_name   = killstring($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue());
				$emirates_id   = "";
			    $emirates_id   = killstring($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
				$emirates_id_applin_no   = "";
			    $emirates_id_applin_no   = killstring($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue());
				$passport_no   = "";
			    $passport_no   = killstring($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue());
				$dob           = "";
			    $dob           = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(killstring($objPHPExcel->getActiveSheet()->getCell('H' . $row)->getValue())));
				$gender     = "";
			    $gender     = killstring($objPHPExcel->getActiveSheet()->getCell('I' . $row)->getValue());
				$relation_type   = "";
			    $relation_type   = killstring($objPHPExcel->getActiveSheet()->getCell('J' . $row)->getValue());
				$marital_status  = "";
			    $marital_status  = killstring($objPHPExcel->getActiveSheet()->getCell('K' . $row)->getValue());
				$mobile_no     = "";
				$mobile_no     = killstring($objPHPExcel->getActiveSheet()->getCell('L' . $row)->getValue());
				$landline_no     = "";
				$landline_no     = killstring($objPHPExcel->getActiveSheet()->getCell('M' . $row)->getValue());
				$email     = "";
				$email     = killstring($objPHPExcel->getActiveSheet()->getCell('N' . $row)->getValue());
				$nationality     = "";
				$nationality     = killstring($objPHPExcel->getActiveSheet()->getCell('O' . $row)->getValue());
				$emirate_residence     = "";
				$emirate_residence     = killstring($objPHPExcel->getActiveSheet()->getCell('P' . $row)->getValue());
				$location_residence     = "";
				$location_residence     = killstring($objPHPExcel->getActiveSheet()->getCell('Q' . $row)->getValue());
				$emirate_visa     = "";
				$emirate_visa     = killstring($objPHPExcel->getActiveSheet()->getCell('R' . $row)->getValue());
				$visa_issuance_date     = "";
				$visa_issuance_date     = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(killstring($objPHPExcel->getActiveSheet()->getCell('S' . $row)->getValue())));
				$currently_insured     = "";
				$currently_insured     = killstring($objPHPExcel->getActiveSheet()->getCell('T' . $row)->getValue());
                if (!empty($dob) && !empty($gender) && !empty($marital_status)) {
					/*****************Start Pricing Mutiple Location*******/
					 $MyQuery  = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_members` (`tr_no`,`insurance_master_id`, `customer_id`,`first_name`, `middle_name`, `last_name`, `emirates_id`, `emirates_id_applin_no`, `passport_no`,`dob`,`mobile_no`,`landline_no`,`email`,`nationality`,`emirate_residence`,`location_residence`,`emirate_visa`,`visa_issuance_date`,`currently_insured`,`relation_type`,`gender`,`marital_status`,`customer_type`, `is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$last_id."','".$customer."','".$first_name."','".$middle_name."','".$last_name."','".$emirates_id."','".$emirates_id_applin_no."','".$passport_no."','".$dob."','".$mobile_no."','".$landline_no."','".$email."','".$nationality."','".$emirate_residence."','".$location_residence."','".$emirate_visa."','".$visa_issuance_date."','".$currently_insured."','".$relation_type."','".$gender."','".$marital_status."',1,1,'".$userno."')"); 
					 $last_insert_id = "";
					 $last_insert_id = mysqli_insert_id($conn);
					 if (!empty($MyQuery)) {
						 /****Premium Calculation****************/
					            $premium             = 0;
								for($i=0; $i < $cnt_company; $i++){
								$company_id          = trim($_REQUEST['company_id'][$i]);
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='$relation_type' AND `marital_status`='$marital_status' AND `emirate_id`='$location_active' AND `price_batch_id`='$price_batch' AND '$today' BETWEEN `start_date` AND `end_date`");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$premium             = empty($rowQryA[0])?0:$rowQryA[0];
								$Query  = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_members_premium` (`tr_no`,`insurance_master_id`, `insurance_id`, `company_id`,`premium`,`customer_type`, `is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$last_id."','".$last_insert_id."','".$company_id."','".$premium."',1, 1,'".$userno."')"); 
						        }
						/****Premium Calculation****************/
					  }else{
						  $error_cnt = $error_cnt + 1;
					  }
					/*****************End Pricing Mutiple Location*******/
             }else{
				 $error_cnt = $error_cnt + 1;
			 }
		}
		/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
                        $alert_label = "alert-success"; 
                        $msg = "Insurance Member Sheet uploaded successfully! Your reference no is <b>$ordervalueA</b>.";
						$submit_status = 1;
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Insurance Member Sheet not uploaded completely!";
						$submit_status = 1;
                   }
				  //echo "location:submit_member_batch_list.php?tr_no='".$ordervalueA."'";
				  //header("location:submit_member_batch_list.php?tr_no=$ordervalueA");
				  //include_once('submit_member_batch_list.php');
	   /**********************************/
   }//valid true	 	
 }
}

if ($refreshflag==3){
	if(isset($_POST['preview'])){
	$today               = date('Y-m-d');
	$submit_status = 0;
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
	   $path = "../uploads/colemont/member_list/".$thumb;
	   move_uploaded_file($_FILES["price_file"]["tmp_name"],"../uploads/colemont/member_list/".$thumb);
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
	  
					$sheet         = $objPHPExcel->getSheet(0);
					$highestRow    = $sheet->getHighestRow();
					$highestColumn = $sheet->getHighestColumn(); 
					$objReader = PHPExcel_IOFactory::createReader('Excel2007');
					$objPHPExcel = $objReader->load("../excel_template/cmt/cmt_ins_excel_premium_template.xlsx");
					$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri') ->setSize(11);;
					$sqlA = mysqli_query($conn,"SELECT `company_name` FROM `tbl_cmt_ins_company_master` WHERE `is_active`='1'  ORDER BY `company_id` ASC");
					if(mysqli_num_rows($sqlA)>0){
						$letter = "U";
						$vt=1;
						while($rowA = mysqli_fetch_array($sqlA)){ 
						$objPHPExcel->getActiveSheet()->setCellValue($letter.$vt, $rowA['company_name']);
						$letter++;
						}
					}
					$i  = 1;
					$vt = 2;
	            for ($row = 2; $row <= $highestRow; $row++) { 
				$sl_no                = "";
			    $sl_no                = killstring($objPHPExcel->getActiveSheet()->getCell('A' . $row)->getValue());
				$first_name           = "";
			    $first_name           = killstring($objPHPExcel->getActiveSheet()->getCell('B' . $row)->getValue());
				$middle_name   = "";
			    $middle_name   = killstring($objPHPExcel->getActiveSheet()->getCell('C' . $row)->getValue());
				$last_name   = "";
			    $last_name   = killstring($objPHPExcel->getActiveSheet()->getCell('D' . $row)->getValue());
				$emirates_id   = "";
			    $emirates_id   = killstring($objPHPExcel->getActiveSheet()->getCell('E' . $row)->getValue());
				$emirates_id_applin_no   = "";
			    $emirates_id_applin_no   = killstring($objPHPExcel->getActiveSheet()->getCell('F' . $row)->getValue());
				$passport_no   = "";
			    $passport_no   = killstring($objPHPExcel->getActiveSheet()->getCell('G' . $row)->getValue());
				$dob           = "";
			    $dob           = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(killstring($objPHPExcel->getActiveSheet()->getCell('H' . $row)->getValue())));
				$gender     = "";
			    $gender     = killstring($objPHPExcel->getActiveSheet()->getCell('I' . $row)->getValue());
				$relation_type   = "";
			    $relation_type   = killstring($objPHPExcel->getActiveSheet()->getCell('J' . $row)->getValue());
				$marital_status  = "";
			    $marital_status  = killstring($objPHPExcel->getActiveSheet()->getCell('K' . $row)->getValue());
				$mobile_no     = "";
				$mobile_no     = killstring($objPHPExcel->getActiveSheet()->getCell('L' . $row)->getValue());
				$landline_no     = "";
				$landline_no     = killstring($objPHPExcel->getActiveSheet()->getCell('M' . $row)->getValue());
				$email     = "";
				$email     = killstring($objPHPExcel->getActiveSheet()->getCell('N' . $row)->getValue());
				$nationality     = "";
				$nationality     = killstring($objPHPExcel->getActiveSheet()->getCell('O' . $row)->getValue());
				$emirate_residence     = "";
				$emirate_residence     = killstring($objPHPExcel->getActiveSheet()->getCell('P' . $row)->getValue());
				$location_residence     = "";
				$location_residence     = killstring($objPHPExcel->getActiveSheet()->getCell('Q' . $row)->getValue());
				$emirate_visa     = "";
				$emirate_visa     = killstring($objPHPExcel->getActiveSheet()->getCell('R' . $row)->getValue());
				$visa_issuance_date     = "";
				$visa_issuance_date     = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP(killstring($objPHPExcel->getActiveSheet()->getCell('S' . $row)->getValue())));
				$currently_insured     = "";
				$currently_insured     = killstring($objPHPExcel->getActiveSheet()->getCell('T' . $row)->getValue());
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$vt,$i);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$vt,$first_name);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$vt,$middle_name);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$vt,$last_name);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$vt,$emirates_id);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$vt,$emirates_id_application_no);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$vt,$passport_no);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$vt,$dob);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$vt,$gender);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$vt,$relation_type);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$vt,$marital_status);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$vt,$mobile_no);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$vt,$landline_no);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$vt,$email);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$vt,$nationality);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$vt,$emirate_residence);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$vt,$location_residence);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$vt,$emirate_visa);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$vt,$visa_issuance_date);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$vt,$currently_insured);
					$i++;
					$vt++; 
					}
					$objPHPExcel->setActiveSheetIndex(0);
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					ob_end_clean();
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename=CMT-INS-'.date('d-m-Y-H-i-s').'.xlsx');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');
					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0
					$objWriter->save('php://output');  
					exit();
	/*****************End Pricing Mutiple Location*******/
   }//valid true	 	
 }
}
?>
<link rel="stylesheet" href="../bower_components/jquery-ui/themes/base/jquery-ui.css">
<!-- Content Wrapper. Contains page content -->
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>New Quotation</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Quotation</li>
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
      <?php if($submit_status == 1){ }?>
      <hr>
      <?php }?>
      <div id="tabs">
        <ul>
          <li><a href="#tabs-1">Customer Information</a></li>
          <li><a href="#tabs-2">Premium Plan details</a></li>
          <li><a href="#tabs-3">Review & Submit</a></li>
        </ul>
        <div id="tabs-1">
          <div class="row">
            <!--------------Tab 1 Content--------->
            <div class="box box-default">
              <div class="box-body">
                <div class="col-md-6">
                  <div class="form-group" style="display:inline-flex;">
                    <label class="required">Customer:</label>
                    <div class="clear"></div>
                    <div class="radio" style="margin-top:0px; margin-left:10px;">
                      <label>
                        <input type="radio" name="CustomerRadionBtn" id="optionsRadios2" value="1" checked="">
                        Existing </label>
                    </div>
                    <div class="radio" style="margin-top:0px; margin-left:10px;">
                      <label>
                        <input type="radio" name="CustomerRadionBtn" id="optionsRadios1" value="0" >
                        New </label>
                    </div>
                  </div>
                </div>
                <div style="height: 10px;"></div>
                <div style="height: 10px;"></div>
                <!------------------------New Customer--------->
                <div class="row col-md-12 new_customer" style="display:none;">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="required">Customer Name:</label>
                      <input type="text" class="validate[required] form-control" name="customer_name" placeholder="Customer Name"/>
                    </div>
                    <div class="form-group">
                      <label class="required">Customer Address:</label>
                      <input type="text" class="validate[required] form-control" name="customer_address" placeholder="Customer Address"/>
                    </div>
                    <div class="form-group">
                      <label class="">Customer Fax:</label>
                      <input type="text" class="form-control" name="customer_fax" placeholder="Customer Fax"/>
                    </div>
                    <div class="form-group">
                      <label class="required">Customer Email:</label>
                      <input type="text" class="validate[required,custom[email]] form-control" name="customer_email" placeholder="Customer Email"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="required">Customer Code:</label>
                      <input type="text" class="validate[required] form-control" name="customer_code" placeholder="Customer Code"/>
                    </div>
                    <div class="form-group">
                      <label class="required">Customer Phone:</label>
                      <input type="text" class="validate[required] form-control" name="customer_phone" placeholder="Customer Phone"/>
                    </div>
                    <div class="form-group">
                      <label class="">Customer Concerned Person:</label>
                      <input type="text" class=" form-control" name="customer_person" placeholder="Customer Concerned Person"/>
                    </div>
                  </div>
                </div>
                <!------------------------New Customer--------->
                <!------------------------Existing Customer--------->
                <div class="row col-md-12 existing_customer">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="required">Customer:</label>
                      <select class="validate[required] form-control select2" name="customer" id="customer" style="width:100%;">
                        <option selected="selected" value="">---Select an option---</option>
                        <?php  
						  $d1 = mysqli_query($conn,"SELECT `customer_name`, `customer_id` FROM `tbl_cmt_ins_customer_master` WHERE `is_active`=1 AND `customer_type`=1 order by `customer_name`");
						  while($b1 = mysqli_fetch_array($d1)){
						  ?>
                        <option value="<?=$b1['customer_id']?>" <?php if($flagCustomer==$b1['customer_id']){echo "selected";}?>>
                        <?=$b1['customer_name']?>
                        </option>
                        <?php		
						  } 
						 ?>
                      </select>
                    </div>
                  </div>
                </div>
                <!------------------------Existing Customer--------->
              </div>
              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" name="submit_first" id="first_submit">NEXT</button>
              </div>
              <hr/>
            </div>
            <!--------------Tab 1 Content--------->
          </div>
        </div>
        <div id="tabs-2">
          <div class="col-md-12">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Batch Name:</label>
                <input type="text" name="batch_name" class="validate[required] form-control" placeholder="Batch Name">
              </div>
              <div class="form-group">
                <label class="required">Upload Excel File:</label>
                <input type="file" name="price_file" accept=".xlx, .xlsx" class="validate[required,checkFileType[xlx|xlsx],checkFileSize[10]] form-control" title=".xlx | .xlsx file only">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Emirate:</label>
                <select class="validate[required] form-control select" name="location_active" id="location_active" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `emirates_name`, `emirates_id` FROM `tbl_emirates_master` WHERE `is_active`=1 order by `emirates_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['emirates_id']?>">
                  <?=$b1['emirates_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <hr/>
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped" role="grid">
                <thead>
                  <tr>
                    <th>Sl No.</th>
                    <th>Insurance Company</th>
                    <th>Price Batch</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
						  $i=1;
                          $Query = "SELECT tb1.`company_name`,tb1.`company_id` FROM `tbl_cmt_ins_company_master` tb1 INNER JOIN `tbl_cmt_ins_price_batch_master` tb2  ON tb1.`company_id`=tb2.`company_id` AND tb2.`customer_type`=1 WHERE tb1.`is_active`='1'  AND '$today' BETWEEN tb2.`start_date` AND tb2.`end_date` ORDER BY tb1.`company_name` ASC";	
                          $sqlQ  = mysqli_query($conn,$Query);
                          while($build = mysqli_fetch_array($sqlQ)){
							?>
                  <tr>
                    <td><?=$i?></td>
                    <td><input type="checkbox" name="company_id[]" value="<?=$build['company_id']?>">
                      &nbsp;
                      <?=$build['company_name']?></td>
                    <td><select class="form-control select2" name="price_batch[<?=$build['company_id']?>]" style="width:100%;">
                        <option selected="selected" value="">---Select an option---</option>
                        <?php  
                                  $d1 = mysqli_query($conn,"SELECT `batch_name`, `tr_no`, `price_batch_id` FROM `tbl_cmt_ins_price_batch_master` WHERE `company_id`='$build[company_id]' AND `is_active`=1 AND `customer_type`=1 AND '$today' BETWEEN `start_date` AND `end_date` order by `batch_name`");
                                  while($b1 = mysqli_fetch_array($d1)){
                                  ?>
                        <option value="<?=$b1['price_batch_id']?>">
                        <?=$b1['batch_name']?>
                        (
                        <?=$b1['tr_no']?>
                        ) </option>
                        <?php		
                                  } 
                                 ?>
                      </select></td>
                  </tr>
                  <?php  
						   $i++;
                          }
                          ?>
                </tbody>
              </table>
            </div>
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
              5. <font color="#990000">Excel Templte Format is </font><a href="../excel_template/cmt/cmt_ins_excel_template.xlsx" target="_blank" style="color:#FFF;" class="btn btn-warning btn-sm">TEMPLATE</a>
              <div class="clear"></div>
              6. <font color="#990000">The insurance company and premium batch list will appear only based on current period. So please upload the excel sheet for current period before Member Premium insurance generation.</font> </h6>
            <div class="clear"></div>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary pull-right" name="submit_second" id="second_submit">NEXT</button>
          </div>
        </div>
        <div id="tabs-3">
        <div id="preview_data"></div> 
           <div class="box-footer">
            <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
            <button type="submit" class="btn btn-primary pull-right" name="submit" id="third_submit">Save & Submit</button>
          </div>
        </div>
      </div>
      <!-- SELECT2 EXAMPLE -->
      <!-- /.box -->
      <!-- /.row -->
    </form>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
<script src="../bower_components/jquery-ui/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#tabs" ).tabs({
	"active": 0,
    "disabled": [1,2]
	});
  });
</script>
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

// function will get executed
$(document).ready(function() {
        $("#preview").click(function(e) {
		  //$(document).on("submit", "form", function(event)
           // var form = $("#company-form");
			//if($("#company-form").validationEngine('validate') == true){
		      //alert(form.serialize());
			/******Ajax****************/
			  var form = $('#company-form')[0];
              var formData = new FormData(form);
			        $.ajax({
					type: 'POST',
					//contentType: "application/json; charset=utf-8",
					url: "../ajax/ajax_cmt_premium_ins_preview.php",
					//dataType: 'json',
					data: formData,
					//crossDomain: true,
					//async: true,
					processData: false,
                    contentType: false,
					beforeSend: function () {
						$(".preloader").fadeIn(100);
					},
					success: function (res) {
						if (res != null) {
							$("#preview_data").html(res);
							$('#example2').DataTable({
								  "aaSorting": [[ 0, "desc" ]],
								  "select": {
											toggleable: false
										},
								  "autowidth":false,		
					 
							dom: 'Bfrtip',
						   buttons: [
								{
								   extend: 'csv',
								   title: 'Insurance Premium List - Colemont',
								   className: 'btn btn-primary'/*,
									exportOptions: {
										columns: ':not(:first-child)',
									}*/
								},
								{
								   extend: 'excel',
								   title: 'Insurance Premium List - Colemont',
									className: 'btn btn-success'/*,
									exportOptions: {
										columns: ':not(:first-child)',
									}*/
								},
								 {
									extend: 'pdf',
									title: 'Insurance Premium List - Colemont',
									className: 'btn btn-info',
									orientation: 'landscape',
									pageSize: 'LEGAL'/*,
									exportOptions: {
										columns: ':not(:first-child)',
									}*/
								}
								]
							});
							//alert(res);
						}
						else {
						$("#preview_data").html('');
						//alert(res);
						}
					}
				});
			 /******Ajax****************/
			//}
        });
    });
//radio		
$('input[type="radio"]').click(function () {
    var inputValue = $(this).attr("value");
    if (inputValue == "1") {
      $('.new_customer').hide();
      $('.existing_customer').show();
    }
    if (inputValue == "0") {
      $('.new_customer').show();
      $('.existing_customer').hide();
      /*$('#togBtn').prop('checked', true); */
    }
  });

$('#first_submit').click(function () {
  if($("#company-form").validationEngine('validate') == true){
		$( function() {  
		$( "#tabs" ).tabs( "enable", 1 );			
		$( "#tabs" ).tabs({
		"active": 1,
		"disabled": [2]
		}); 
		});  
	return true;  
	}
	else{
	return false;	
	}
});
$('#second_submit').click(function () {
  if($("#company-form").validationEngine('validate') == true){
		$( function() { 
		/*****************Ajax Call**************/
			  var form = $('#company-form')[0];
              var formData = new FormData(form);
			        $.ajax({
					type: 'POST',
					//contentType: "application/json; charset=utf-8",
					url: "../ajax/ajax_cmt_premium_ins_preview.php",
					//dataType: 'json',
					data: formData,
					//crossDomain: true,
					//async: true,
					processData: false,
                    contentType: false,
					beforeSend: function () {
						$(".preloader").fadeIn(100);
					},
					success: function (res) {
						if (res != null) {
							$("#preview_data").html(res);
							$('#example2').DataTable({
								  "aaSorting": [[ 0, "desc" ]],
								  "select": {
											toggleable: false
										},
								  "autowidth":false,		
							dom: 'Bfrtip',
						   buttons: [
								{
								   extend: 'csv',
								   title: 'Insurance Premium List - Colemont',
								   className: 'btn btn-primary'/*,
									exportOptions: {
										columns: ':not(:first-child)',
									}*/
								},
								{
								   extend: 'excel',
								   title: 'Insurance Premium List - Colemont',
									className: 'btn btn-success'/*,
									exportOptions: {
										columns: ':not(:first-child)',
									}*/
								}
								]
							});
							$('#example2 tbody tr:first').remove();    
							//alert(res);
						}
						else {
						$("#preview_data").html('');
						//alert(res);
						}
					}
				});
			 /******Ajax Calls****************/
		$( "#tabs" ).tabs( "enable", 2 );			
		$( "#tabs" ).tabs({
		"active": 2,
		"disabled": []
		}); 
		});  
	return true;  
	}
	else{
	return false;	
	}
});
</script>
