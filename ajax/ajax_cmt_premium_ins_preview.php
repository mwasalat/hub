<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/ 
    $today = date("Y-m-d");
	$customer            = killstring($_REQUEST['customer']);	
	$batch_name          = killstring($_REQUEST['batch_name']);
	$location_active     = killstring($_REQUEST['location_active']);
    $cnt_company          = 0;
	$company_id_now       = $_REQUEST['company_id'];
	$cnt_company          = count($company_id_now);
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
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($path);
		//var_dump($objReader);
	  } catch (Exception $e) {
		echo 'Error loading file "' . pathinfo($path, PATHINFO_BASENAME) . '": ' . $e->getMessage();
	  }
	  
	  ?>

<div class="col-md-12">
  <h4>Preview</h4>
  <div class="table-responsive">
    <table id="example2"  class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid" style="font-size:12px;">
      <thead>
        <tr>
          <th >Sl No.</th>
          <th >First Name</th>
          <th >Middle Name</th>
          <th >Last Name</th>
          <th >EID</th>
          <th >EID Appln No</th>
          <th >Passport No</th>
          <th >DOB</th>
          <th >Gender</th>
          <th >Relation Type</th>
          <th >Marital Status</th>
          <th >Mobile No</th>
          <th >Landline No</th>
          <th >Email</th>
          <th >Nationality</th>
          <th >Emirate Residence</th>
          <th >Location Residence</th>
          <th >Emirate Visa</th>
          <th >Visa Issuance Dt.</th>
          <th >Currently Insured</th>
		  <?php
		  for($i=0; $i < $cnt_company; $i++){
		  $company_id          = trim($_REQUEST['company_id'][$i]);
		  $sqlQryA             = mysqli_query($conn,"SELECT `company_name` FROM `tbl_cmt_ins_company_master` WHERE `company_id`='$company_id'");
		  $rowQryA             = mysqli_fetch_row($sqlQryA);
		  $company_name             = empty($rowQryA[0])?0:$rowQryA[0];
		  ?>
          <th><?=$company_name?></th>
          <?php
		  }
		  ?>
        </tr>
      </thead>
      <tbody>
        <?php
	  $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn(); 
	            for ($row = 1; $row <= $highestRow; $row++) { 
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
					 ?>
        <tr>
          <td><?=$sl_no?></td>
          <td><?=$first_name?></td>
          <td><?=$middle_name?></td>
          <td><?=$last_name?></td>
          <td><?=$emirates_id?></td>
          <td><?=$emirates_id_application_no?></td>
          <td><?=$passport_no?></td>
          <td><?=date('d-m-Y',strtotime($dob))?></td>
          <td><?=$gender?></td>
          <td><?=$relation_type?></td>
          <td><?=$marital_status?></td>
          <td><?=$mobile_no?></td>
          <td><?=$landline_no?></td>
          <td><?=$email?></td>
          <td><?=$nationality?></td>
          <td><?=$emirate_residence?></td>
          <td><?=$location_residence?></td>
          <td><?=$emirate_visa?></td>
          <td><?=date('d-m-Y',strtotime($bvisa_issuance_date))?></td>
          <td><?=$currently_insured?></td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								$company_id          = trim($_REQUEST['company_id'][$i]);
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='$relation_type' AND `marital_status`='$marital_status' AND `emirate_id`='$location_active' AND `price_batch_id`='$price_batch' AND '$today' BETWEEN `start_date` AND `end_date`");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$premium             = empty($rowQryA[0])?0:$rowQryA[0];
								?>
          <td><?=Checkvalid_number_or_not($premium,2);?></td>
          <?php
		  }
		 ?>
        </tr>
        <?php
			
             }
		}
		?>
         </tbody>
    </table>
        <?php
   }//valid true	 	
?>
  </div>
</div>
