<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
ini_set('max_execution_time', 0); //0=NOLIMIT
set_time_limit(0);
ini_set('memory_limit','2048M');
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors',TRUE);*/				
$today        = date('Y-m-d');
$msg          = ""; 
$flagCustomer = !empty($_REQUEST['flag'])?killstring($_REQUEST['flag']):NULL;
if ($refreshflag==3){
	if(isset($_POST['submit'])){
    $customer_first_name = killstring($_REQUEST['customer_first_name']);
	$customer_last_name  = killstring($_REQUEST['customer_last_name']);
	$customer_email      = killstring($_REQUEST['customer_email']);
	$nationality         = killstring($_REQUEST['nationality']);
	$relation            = killstring($_REQUEST['relation']);
	$dob                 = killstring($_REQUEST['dob']);
	$dob                 = date('Y-m-d',strtotime($dob));
	$dob_year            = date('Y',strtotime($dob));
	$year                = date('Y');
    $age_primary         = ($year - $dob_year);
	$gender              = killstring($_REQUEST['gender']);
	$marital_status      = killstring($_REQUEST['marital_status']);
	$customer_phone      = killstring($_REQUEST['customer_phone']);
	$batch_name          = killstring($_REQUEST['batch_name']);
	$location_active     = killstring($_REQUEST['residency']);
	$start_date          = killstring($_REQUEST['start_date']);
	$start_date          = date('Y-m-d',strtotime($start_date));
	/***********company**************/
    $cnt_company          = 0;
	$company_id_now       = $_REQUEST['company_id'];
	$cnt_company          = count($company_id_now);
	/***********company**********/
	/***********Dependent************/
    $cnt_dependent         = 0;
	$dependent             = $_REQUEST['additionl_customer_first_name'];
	$cnt_dependent         = count($dependent);
	/************Dependent*********/
	
	/*************Quotation details******************/
	$quotation_id        = killstring($_REQUEST['quotation_id']);
	$quotation_currency  = killstring($_REQUEST['quotation_currency']);
	$quotation_link      = killstring($_REQUEST['quotation_link']);
	$advisor_name        = killstring($_REQUEST['advisor_name']);
	$advisor_email       = killstring($_REQUEST['advisor_email']);
	$quotation_timestamp = date('Y-m-d h:i:s',strtotime($_REQUEST['quotation_timestamp']));
	/*************Quotation details******************/
	
	
	$dental_covered           = killstring($_REQUEST['dental_covered']);
	$dental_covered           = !empty($dental_covered)?$dental_covered:0;
	$enhanced_maternity       = killstring($_REQUEST['enhanced_maternity']);
	$enhanced_maternity       = !empty($enhanced_maternity)?$enhanced_maternity:0;
	$wellness_covered         = killstring($_REQUEST['wellness_covered']);
	$wellness_covered         = !empty($wellness_covered)?$wellness_covered:0;
	$optical_covered          = killstring($_REQUEST['optical_covered']);
	$optical_covered          = !empty($optical_covered)?$optical_covered:0;
	
	$select_plan          = $_REQUEST['select_plan'];
	$IsPageValid = true;
	if(empty($customer_first_name)){
	$msg   = "Please enter customer first name!";
	$alert_label = "alert-danger"; 
	$IsPageValid = false;
	}else if(empty($customer_last_name)){
	$alert_label = "alert-danger"; 
	$msg   =  "Please enter customer last name!";
	$IsPageValid = false;
	}else if(empty($customer_email)){
	$alert_label = "alert-danger"; 
	$msg   = "Please enter customer email address!";
	$IsPageValid = false;
	}else{
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	            $QueryCounterB =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_cmt_ins_member_file_no`");
				$rowQryB       = mysqli_fetch_row($QueryCounterB);
				$valueC        = $rowQryB[0];
				$QueryCounterC =  mysqli_query($conn,"UPDATE `tbl_cmt_ins_member_file_no` SET `auto_id`='$valueC'");
				$ordervalueA   = "CMT-".$valueC;
				$MyQuery  = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_premium_transactions` (`tr_no`,`customer_first_name`, `customer_last_name`, `customer_phone`,`customer_email`, `customer_nationality`, `customer_gender`, `customer_emirate_id`, `customer_dob`, `customer_age`, `customer_marital_status`,`customer_relation`,`ins_start_date`, `quotation_id`,`quotation_currency`,`quotation_link`,`advisor_name`,`advisor_email`,`quotation_timestamp`,`is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$customer_first_name."','".$customer_last_name."','".$customer_phone."','".$customer_email."','".$nationality."','".$gender."','".$location_active."','".$dob."','".$age_primary."','".$marital_status."','".$relation."','".$start_date."','".$quotation_id."','".$quotation_currency."','".$quotation_link."','".$advisor_name."','".$advisor_email."','".$quotation_timestamp."',1,'".$userno."')"); 
				if(empty($MyQuery)){$error_cnt++;}//error query - tbl_cmt_ind_premium_transactions
				$last_id  = mysqli_insert_id($conn);
				If($MyQuery){
					/***********premium amounts*******/
					if(!empty($cnt_company)){
					 for($j=0; $j < $cnt_company; $j++){
						 $amount                           = 0;
						 $premium                          = 0;
						 //$company_id                       = killstring($_REQUEST['company_id'][$j]);
						 $company_ids         = trim($_REQUEST['company_id'][$j]);
						 list($company_id,$unique_id_n) = explode("|",$company_ids);
						 if(in_array($unique_id_n, $select_plan)){
						 $price_batch                      = killstring($_REQUEST['price_batch'][$company_id]);
						 list($price_batch_id,$premium_id) = explode("|",$price_batch);
						 $billing_network                  = killstring($_REQUEST['np'][$company_id]);
						 $deductable_copay                 = killstring($_REQUEST['deductable_copay'][$company_id]);
						 $sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='P' AND `marital_status`='$marital_status' AND `emirate_id`='$location_active' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date`  AND '$age_primary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
						 $rowQryA             = mysqli_fetch_row($sqlQryA);
						 $premium             = empty($rowQryA[0])?0:$rowQryA[0];
						 	/*****************Check of extras click********************/
								//enhanced_materinity
								if(!empty($enhanced_maternity)){
								$qryA = mysqli_query($conn,"SELECT `enhanced_maternity_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$enhanced_maternity_amount = 0;
								$enhanced_maternity_amount = $row['0'];
								$total_premium+=$enhanced_maternity_amount;
								}
								if(!empty($dental_covered)){
								$qryA = mysqli_query($conn,"SELECT `dental_covered_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$dental_covered_amount = 0;
								$dental_covered_amount = $row['0'];
								$total_premium+=$dental_covered_amount;
								}
								if(!empty($wellness_covered)){
								$qryA = mysqli_query($conn,"SELECT `wellness_covered_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$wellness_covered_amount = 0;
								$wellness_covered_amount = $row['0'];
								$total_premium+=$wellness_covered_amount;
								}
								if(!empty($optical_covered)){
								$qryA = mysqli_query($conn,"SELECT `optical_covered_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$optical_covered_amount = 0;
								$optical_covered_amount = $row['0'];
								$total_premium+=$optical_covered_amount;
								}
								/*****************Check of extras click********************/
						 $MyQueryBA           = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_premium_amounts` (`ind_premium_dependent_id`,`dependent_id`,`customer_type`, `tr_no`,`company_id`, `price_batch_id`,`premium_id`, `billing_network`,  `deductable_copay`, `dental_covered`,`enhanced_maternity`,`wellness_covered`,`optical_covered`,`amount` ,`is_active`,`entered_by`) VALUES ('".$last_id."',0,'P','".$ordervalueA."','".$company_id."','".$price_batch_id."','".$premium_id."','".$billing_network."','".$deductable_copay."','".$dental_covered."','".$enhanced_maternity."','".$wellness_covered."','".$optical_covered."','".$premium."',1,'".$userno."')"); 
						 if(empty($MyQueryBA)){$error_cnt++;}//error query - tbl_cmt_ind_premium_amounts
						 }
					 }
					 }
					/***********premium amounts*******/
					/**********Dependent Addition****************/
					 if(!empty($cnt_dependent)){
					 for($j=0; $j < $cnt_dependent; $j++){
						 $dependent_first_name       = killstring($_REQUEST['additionl_customer_first_name'][$j]);
						 $dependent_last_name        = killstring($_REQUEST['additionl_customer_last_name'][$j]);
						 $dependent_gender           = killstring($_REQUEST['additionl_gender'][$j]);
						 $dependent_marital_status   = killstring($_REQUEST['additionl_marital_status'][$j]);
						 $dependent_relation         = killstring($_REQUEST['additionl_relation'][$j]);
						 $dependent_dob              = date('Y-m-d',strtotime($_REQUEST['additionl_dob'][$j]));
						 $dependent_dob_year         = date('Y',strtotime($dependent_dob));
						 $year                       = date('Y');
						 $age_dependent              = ($year - $dependent_dob_year);
						 $MyQueryA                   = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_dependants` (`ind_premium_id`,`tr_no`,`dependent_first_name`,`dependent_last_name`, `dependent_gender`,`dependent_marital_status`, `dependent_relation`, `dependent_dob`, `dependent_age`, `is_active`,`entered_by`) VALUES ('".$last_id."','".$ordervalueA."','".$dependent_first_name."','".$dependent_last_name."','".$dependent_gender."','".$dependent_marital_status."','".$dependent_relation."','".$dependent_dob."','".$age_dependent."',1,'".$userno."')"); 
						 if(empty($MyQueryA)){$error_cnt++;}//error query - tbl_cmt_ind_dependants
						 $dependent_last_id  = mysqli_insert_id($conn);
						 /***********premium amounts*******/
							if(!empty($cnt_company)){
							 for($j=0; $j < $cnt_company; $j++){
								 $amount                           = 0;
								 $premium                          = 0;
								 //$company_id                       = killstring($_REQUEST['company_id'][$j]);
								 $company_ids         = trim($_REQUEST['company_id'][$j]);
						         list($company_id,$unique_id_n) = explode("|",$company_ids);
						         if(in_array($unique_id_n, $select_plan)){
								 $price_batch                      = killstring($_REQUEST['price_batch'][$company_id]);
								 list($price_batch_id,$premium_id) = explode("|",$price_batch);
								 $billing_network                  = killstring($_REQUEST['np'][$company_id]);
								 $deductable_copay                 = killstring($_REQUEST['deductable_copay'][$company_id]);
								 $sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$dependent_gender' AND `relation_type`='D' AND `marital_status`='$dependent_marital_status' AND `emirate_id`='$location_active' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date`  AND '$age_dependent' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
								 $rowQryA             = mysqli_fetch_row($sqlQryA);
								 $premium             = empty($rowQryA[0])?0:$rowQryA[0];
								 	/*****************Check of extras click********************/
								//enhanced_materinity
								if(!empty($enhanced_maternity)){
								$qryA = mysqli_query($conn,"SELECT `enhanced_maternity_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$enhanced_maternity_amount = 0;
								$enhanced_maternity_amount = $row['0'];
								$total_premium+=$enhanced_maternity_amount;
								}
								if(!empty($dental_covered)){
								$qryA = mysqli_query($conn,"SELECT `dental_covered_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$dental_covered_amount = 0;
								$dental_covered_amount = $row['0'];
								$total_premium+=$dental_covered_amount;
								}
								if(!empty($wellness_covered)){
								$qryA = mysqli_query($conn,"SELECT `wellness_covered_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$wellness_covered_amount = 0;
								$wellness_covered_amount = $row['0'];
								$total_premium+=$wellness_covered_amount;
								}
								if(!empty($optical_covered)){
								$qryA = mysqli_query($conn,"SELECT `optical_covered_amount` FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND `is_active`=1");
								$row = mysqli_fetch_row($qryA);
								$optical_covered_amount = 0;
								$optical_covered_amount = $row['0'];
								$total_premium+=$optical_covered_amount;
								}
								/*****************Check of extras click********************/
								 $MyQueryB            = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_premium_amounts` (`ind_premium_dependent_id`,`dependent_id`,`customer_type`, `tr_no`,`company_id`, `price_batch_id`,`premium_id`, `billing_network`,  `deductable_copay`, `dental_covered`,`enhanced_maternity`,`wellness_covered`,`optical_covered`, `amount` ,`is_active`,`entered_by`) VALUES ('".$last_id."','".$dependent_last_id."','D','".$ordervalueA."','".$company_id."','".$price_batch_id."','".$premium_id."','".$billing_network."','".$deductable_copay."','".$dental_covered."','".$enhanced_maternity."','".$wellness_covered."','".$optical_covered."','".$premium."',1,'".$userno."')"); 
								 if(empty($MyQueryB)){$error_cnt++;}//error query - tbl_cmt_ind_premium_amounts
								 }
							 }
							 }
							/***********premium amounts*******/
					 }
					 }
					/**********Dependent Addition****************/ 
					/**********Insurance Premium Comapny Selection****************/
					 if(!empty($cnt_company)){
					 for($j=0; $j < $cnt_company; $j++){
						 //$company_id                       = killstring($_REQUEST['company_id'][$j]);
						 $company_ids         = trim($_REQUEST['company_id'][$j]);
						 list($company_id,$unique_id_n) = explode("|",$company_ids);
						 if(in_array($unique_id_n, $select_plan)){
						 $price_batch                      = killstring($_REQUEST['price_batch'][$company_id]);
						 list($price_batch_id,$premium_id) = explode("|",$price_batch);
						 $billing_network                  = killstring($_REQUEST['np'][$company_id]);
						 $deductable_copay                 = killstring($_REQUEST['deductable_copay'][$company_id]);
						 /*********************************************/
						 $annual_limit                 = killstring($_REQUEST['annual_limit'][$company_id]);
						 $geographical_coverage        = killstring($_REQUEST['geographical_coverage'][$company_id]);
						 $inpatient                    = killstring($_REQUEST['inpatient'][$company_id]);
						 $out_patient                  = killstring($_REQUEST['out_patient'][$company_id]);
						 $physiotherapy                = killstring($_REQUEST['physiotherapy'][$company_id]);
						 $emergency_evacuation         = killstring($_REQUEST['emergency_evacuation'][$company_id]);
						 $chronic_conditions           = killstring($_REQUEST['chronic_conditions'][$company_id]);
						 $pre_existing_cover           = killstring($_REQUEST['pre_existing_cover'][$company_id]);
						 $routine_maternity            = killstring($_REQUEST['routine_maternity'][$company_id]);
						 $maternity_waiting_period     = killstring($_REQUEST['maternity_waiting_period'][$company_id]);
						 $dental                       = killstring($_REQUEST['dental'][$company_id]);
						 $dental_waiting_period        = killstring($_REQUEST['dental_waiting_period'][$company_id]);
						 $optical_benefits             = killstring($_REQUEST['optical_benefits'][$company_id]);
						 $wellness                     = killstring($_REQUEST['wellness'][$company_id]);
						 $semi_annual_surcharge        = killstring($_REQUEST['semi_annual_surcharge'][$company_id]);
						 $quarterly_surcharge          = killstring($_REQUEST['quarterly_surcharge'][$company_id]);
						 $monthly_surcharge            = killstring($_REQUEST['monthly_surcharge'][$company_id]);
						 /*********************************************/
						 $MyQueryC                         = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_companies` (`ind_premium_id`,`tr_no`,`company_id`, `price_batch_id`,`premium_id`, `billing_network`,  `deductable_copay`,  `annual_limit`,  `geographical_coverage`,  `inpatient`,  `out_patient`,  `physiotherapy`,  `emergency_evacuation`,  `chronic_conditions`,  `pre_existing_cover`,  `routine_maternity`,  `maternity_waiting_period`,  `dental`,  `dental_waiting_period`, `optical_benefits`,`wellness`,`semi_annual_surcharge`,`quarterly_surcharge`,`monthly_surcharge`,`is_active`,`entered_by`) VALUES ('".$last_id."','".$ordervalueA."','".$company_id."','".$price_batch_id."','".$premium_id."','".$billing_network."','".$deductable_copay."','".$annual_limit."','".$geographical_coverage."','".$inpatient."','".$out_patient."','".$physiotherapy."','".$emergency_evacuation."','".$chronic_conditions."','".$pre_existing_cover."','".$routine_maternity."','".$maternity_waiting_period."','".$dental."','".$dental_waiting_period."','".$optical_benefits."','".$wellness."','".$semi_annual_surcharge."','".$quarterly_surcharge."','".$monthly_surcharge."',1,'".$userno."')"); 
						 if(empty($MyQueryC)){$error_cnt++;}//error query - tbl_cmt_ind_companies
						 }
					 }
					 }
					/**********Insurance Premium Comapny Selection****************/
				}
		/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
                        $alert_label = "alert-success"; 
                        $msg = "Insurance details added successfully! Your reference no is <b>$ordervalueA</b>.";
						$submit_status = 1;
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Insurance details not added completely!";
						$submit_status = 1;
                   }
	   /**********************************/
   }//valid true	 	
 }
}

$Newflag = !empty($_REQUEST['Newflag'])?killstring($_REQUEST['Newflag']):NULL;
if(!empty(Newflag)){
	$Query = "SELECT * FROM `tbl_cmt_ind_premium_transactions` WHERE `tr_no`='$Newflag'";
	$sqlQ  = mysqli_query($conn,$Query);
	while($build = mysqli_fetch_array($sqlQ)){	
	$customer_first_name  = $build['customer_first_name'];
	$customer_last_name   = $build['customer_last_name'];
	$customer_phone       = $build['customer_phone'];
	$customer_email       = $build['customer_email'];
	$customer_nationality = $build['customer_nationality'];
	$customer_gender      = $build['customer_gender'];
	$customer_emirate_id  = $build['customer_emirate_id'];
	$customer_dob         = $build['customer_dob'];
	$customer_marital_status  = $build['customer_marital_status'];
	$customer_relation    = $build['customer_relation'];
	$ins_start_date       = $build['ins_start_date'];
	}
}

  $QueryCounter =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_cmt_quotation_no`");
  $rowQry       =  mysqli_fetch_row($QueryCounter);
  $value        =  $rowQry[0];
  $QueryCounter =  mysqli_query($conn,"UPDATE `tbl_cmt_quotation_no` SET `auto_id`='$valueC'");
  $ordervalue   =  uniqid()."|".$value;
  $qtn_link     =  "http://mcr.enguae.com/hub/colemont/qtn_entry.php?flag=".$ordervalue;
?>
<!-- Content Wrapper. Contains page content -->
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>New Proposal</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Proposal</li>
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
          <li><a href="#tabs-1">Client Informations</a></li>
          <li><a href="#tabs-2">Manual Quote</a></li>
          <li><a href="#tabs-3">Filter & Select Plans</a></li>
          <li><a href="#tabs-4">Review & Submit</a></li>
        </ul>
        <!---------------------Start Tab 1----------------------->
        <div id="tabs-1">
          <div class="row">
            <div class="box box-default">
              <div class="box-body">
                <!------------------------New Customer--------->
                <div class="row col-md-12 new_customer">
                  <h4 class="tit-member">Member 1</h4>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required leb-size">First  Name:</label>
                      <input type="text" class="validate[required] form-control" name="customer_first_name" value="<?=!empty($customer_first_name)?$customer_first_name:NULL?>" placeholder="First Name"/>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Nationality:</label>
                      <select class="validate[required] form-control select" name="nationality" id="nationality" style="width:100%;">
                        <?php  
                      $d1 = mysqli_query($conn,"SELECT `name`, `id` FROM `tbl_country` WHERE `is_active`=1 order by `name`");
                      while($b1 = mysqli_fetch_array($d1)){
                      ?>
                        <option value="<?=$b1['id']?>" <?php if($b1['id']==$customer_nationality){echo "selected";}?>>
                        <?=$b1['name']?>
                        </option>
                        <?php		
                      } 
                     ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">DOB:</label>
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="dob" class="validate[required] datepickerA form-control" value="<?=!empty($customer_dob)?date('d-m-Y',strtotime($customer_dob)):NULL;?>"  placeholder="DOB"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Marital Status:</label>
                      <select class="validate[required] form-control select" name="marital_status" id="marital_status" style="width:100%;">
                        <option value="M" <?php if($customer_marital_status=='P'){echo "selected";}?>>Married</option>
                        <option value="S" <?php if($customer_marital_status=='P'){echo "selected";}?>>Single</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required leb-size">Last Name:</label>
                      <input type="text" class="validate[required] form-control" name="customer_last_name" value="<?=!empty($customer_last_name)?$customer_last_name:NULL?>" placeholder="Last Name"/>
                    </div>
                    <div class="form-group">
                      <label class="required leb-size">Email Address:</label>
                      <input type="text" class="validate[required,custom[email]] form-control" name="customer_email" value="<?=!empty($customer_email)?$customer_email:NULL?>" placeholder="Email Address"/>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Gender:</label>
                      <select class="validate[required] form-control select" name="gender" id="gender" style="width:100%;"  >
                        <option value="M" <?php if($customer_gender=='P'){echo "selected";}?>>Male</option>
                        <option value="F" <?php if($customer_gender=='P'){echo "selected";}?>>Female</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Relation :</label>
                      <select class="validate[required] form-control select" name="relation" style="width:100%;">
                        <option value="P" <?php if($customer_relation=='P'){echo "selected";}?>>Primary</option>
                        <option value="F" <?php if($customer_relation=='F'){echo "selected";}?>>Dependent</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required leb-size">Phone Number:</label>
                      <input type="text" class="validate[required,custom[phone]] form-control" name="customer_phone" placeholder="Phone Number" maxlength="20" value="<?=!empty($customer_phone)?$customer_phone:NULL?>" />
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Residency:</label>
                      <select class="validate[required] form-control select" name="residency" id="residency" style="width:100%;">
                        <?php  
                      $d1 = mysqli_query($conn,"SELECT `emirates_name`, `emirates_id` FROM `tbl_emirates_master` WHERE `is_active`=1 order by `emirates_name`");
                      while($b1 = mysqli_fetch_array($d1)){
                      ?>
                        <option value="<?=$b1['emirates_id']?>" <?php if($b1['emirates_id']==$customer_emirate_id){echo "selected";}?>>
                        <?=$b1['emirates_name']?>
                        </option>
                        <?php		
                      } 
                     ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Start Date:</label>
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="start_date" class="validate[required] datepickerB form-control" value="<?=date('d-m-Y')?>" placeholder="Start Date"/>
                      </div>
                    </div>
                  </div>
                </div>
                <!------------------------New Customer--------->
                <!----------------Additional Customer------------------------------------->
                <h4>Additional Members</h4>
                <?php
				if(!empty($Newflag)){
                $sqlQuery  = mysqli_query($conn,"SELECT * FROM `tbl_cmt_ind_dependants` WHERE `tr_no`='$Newflag'");
				while($row = mysqli_fetch_array($sqlQuery)){
				?>
                <div class="row col-md-12 new_customer" id="added_additional_members_dependent_<?=$row['dependent_id']?>" style="border:0.25px solid #cbb8b8;margin: 10px;">
                  <h4>Additional Member</h4>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="required leb-size">First Name:</label>
                       <input type="text" class="validate[required] form-control" name="additionl_customer_first_name[]" value="<?=!empty($row['dependent_first_name'])?$row['dependent_first_name']:NULL?>" placeholder="First Name"/>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">DOB:</label>
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="additionl_dob[]" class="validate[required] datepickerA form-control" value="<?=!empty($row['dependent_dob'])?$row['dependent_dob']:NULL?>"  placeholder="DOB"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Marital Status:</label>
                      <select class="validate[required] form-control select" name="additionl_marital_status[]" style="width:100%;">
                        <option value="M" <?php if($row['dependent_marital_status']=='M'){echo "selected";}?>>Married</option>
                        <option value="S" <?php if($row['dependent_marital_status']=='S'){echo "selected";}?>>Single</option>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <button type="button" class="btn btn-default pull-left user-removeBtn" onclick="fun_remove_additional_member_dependent('<?=$row['dependent_id']?>')">Remove Member</button>
                    </div>
                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                      <label class="required leb-size">Last Name:</label>
                      <input type="text" class="validate[required] form-control" name="additionl_customer_last_name[]" value="<?=!empty($row['dependent_last_name'])?$row['dependent_last_name']:NULL?>" placeholder="Last Name"/>
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Gender:</label>
                      <select class="validate[required] form-control select" name="additionl_gender[]" style="width:100%;">
                        <option value="M" <?php if($row['dependent_gender']=='M'){echo "selected";}?>>Male</option>
                        <option value="F" <?php if($row['dependent_gender']=='F'){echo "selected";}?>>Female</option>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label class="leb-size">Relation:</label>
                      <select class="validate[required] form-control select" name="additionl_relation[]" style="width:100%;">
                        <option value="S" <?php if($row['dependent_relation']=='S'){echo "selected";}?>>Spouse</option>
                        <option value="C" <?php if($row['dependent_relation']=='C'){echo "selected";}?>>Child</option>
                      </select>
                    </div>
                    
                  </div>
                </div>
                <?php
				}
				}
				?>
                <div id="additional_customer_section"></div>
                <button type="button" class="btn btn-default pull-left findBtn " name="additional_customer" id="additional_customer" onclick="add_additional_members()">Add Another</button>
                <!----------------Additional Customer------------------------------------->
                <!------------------------New QUotation Link--------->
                <div class="row col-md-12 new_customer">
                  <hr/>
                  <h4 class="tit-member">Quotation Details</h4>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required leb-size">Quotation ID:</label>
                      <input type="text" class="validate[required] form-control" name="quotation_id" value="<?=$ordervalue?>" placeholder="Quotation ID" readonly="readonly" />
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Currency:</label>
                      <select class="validate[required] form-control select" name="quotation_currency" id="quotation_currency" style="width:100%;">
                        <option value="AED">UAE Dirham</option>
                      </select>
                    </div>
                    
                    <div class="form-group">
                    <label class="leb-size">QRF (Quote Request Form) Link:</label>
                    <div class="input-group">
                   <input type="text" class="validate[required] form-control" name="quotation_link"  id="quotation_link" value="<?=$qtn_link?>" placeholder="Quotation Link" readonly="readonly" />
                    <div class="input-group-addon">
                    <a onclick="copyText()" href="void:(0);"><i class="fa fa-file-o"></i></a>
                    </div>
                    </div>
                    </div>

                 <!--   <div class="input-group">
                    <label class="">QRF (Quote Request Form) Link:</label>
                      <input type="text" class="validate[required] form-control" name="quotation_link" value="<?=$qtn_link?>" placeholder="Quotation Link" readonly="readonly" />
                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                    </div>-->
                    <!--<div class="input-group">
                      <label class="">QRF (Quote Request Form) Link:</label>
                      <input type="text" class="validate[required] form-control" name="quotation_link" value="<?=$qtn_link?>" placeholder="Quotation Link" readonly="readonly" />
                      <span class="input-group-addon"><i class="fa fa-check"></i></span>
                    </div>-->
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required leb-size">Advisor Name:</label>
                      <input type="text" class="validate[required] form-control" name="advisor_name" placeholder="Advisor Name" value="<?=$_SESSION['full_name_user']?>" readonly="readonly" />
                    </div>
                    <div class="form-group">
                      <label class="leb-size">Time stamp on Quotation:</label>
                      <input type="text" class="validate[required] form-control" name="quotation_timestamp" placeholder="Time stamp on Quotation" value="<?=date('d/m/Y h:i:s',time())?>" readonly="readonly" />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required leb-size">Advisor Email:</label>
                      <input type="text" class="validate[required,custom[email]] form-control" name="advisor_email" placeholder="Advisor Email" value="<?=$_SESSION['useremail']?>" readonly="readonly" />
                    </div>
                  </div>
                </div>
                <!------------------------New Customer--------->
              </div>
              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right nextBtn" name="submit_first" id="first_submit">NEXT</button>
              </div>
              <hr/>
            </div>
          </div>
        </div>
        <!---------------------End Tab 1----------------------->
        <!----------------------Start Tab 2-------------------->
        <div id="tabs-2">
          <div class="col-md-12">
            <hr/>
            <!----------------Additional Customer------------------------------------->
            <!--<div id="additional_customer_section"></div>-->
            <!----------------Additional Customer------------------------------------->
            <div class="table-responsive" >
              <table class="table table-bordered table-hover table-striped" role="grid">
                <tbody id="new_plan_section">
                </tbody>
              </table>
            </div>
            <div class="clear" style="height:10px;"></div>
            <button type="button" class="btn btn-default pull-left planBtn" name="add_new_plan" id="add_new_plan" onclick="add_new_plan_rows();">+ Add New Plan </button>
            <div class="clear" style="height:10px;"></div>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary pull-right nextBtn" name="submit_second" id="second_submit">Submit</button>
          </div>
        </div>
        <!----------------------End Tab-2-------------------->
        <!----------------------Start Tab 3-------------------->
        <div id="tabs-3">
          <div class="col-md-12">
            <hr/>
            <div id="third_stage_filter_data"></div>
            <div class="clear" style="height:10px;"></div>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary pull-right nextBtn" name="submit_third" id="third_submit">Create Quotation</button>
          </div>
        </div>
        <!----------------------End Tab-3-------------------->
        <!----------------------Start Tab-4------------------>
        <div id="tabs-4">
          <div id="preview_data"></div>
          <div class="box-footer">
            <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
            <button type="submit" class="btn btn-primary pull-right nextBtn" name="submit" id="fourth_submit">Save & Submit</button>
          </div>
        </div>
        <!-------------------End Tab-4------------------------>
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

//First Submit
$('#first_submit').click(function () {
  if($("#company-form").validationEngine('validate') == true){
		$( function() {  
		$( "#tabs" ).tabs( "enable", 1 );			
		$( "#tabs" ).tabs({
		"active": 1,
		"disabled": [2,3]
		}); 
		});  
	return true;  
	}
	else{
	return false;	
	}
});
//Second Submit
$('#second_submit').click(function () {
  if($("#company-form").validationEngine('validate') == true){
		/*****************Ajax Call**************/
			  var form = $('#company-form')[0];
              var formData = new FormData(form);
			        $.ajax({
					type: 'POST',
					//contentType: "application/json; charset=utf-8",
					url: "../ajax/ajax_cmt_ins_filter_stage.php",
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
							$("#third_stage_filter_data").html(res);
						}
						else {
						$("#third_stage_filter_data").html('');
						//alert(res);
						}
					}
				});
			 /******Ajax Calls****************/
		$( "#tabs" ).tabs( "enable", 2 );			
		$( "#tabs" ).tabs({
		"active": 2,
		"disabled": [3]
		}); 
	return true;  
	}
	else{
	return false;	
	}
});
//Third Submit
$('#third_submit').click(function () {
  if($("#company-form").validationEngine('validate') == true){
				   $("#preview_data").html('');
		/*****************Ajax Call**************/
			  var form = $('#company-form')[0];
              var formData = new FormData(form);
			        $.ajax({
					type: 'POST',
					//contentType: "application/json; charset=utf-8",
					url: "../ajax/ajax_cmt_ind_premium_preview.php",
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
						}
						else {
						$("#preview_data").html('');
						//alert(res);
						}
					}
				});
			 /******Ajax Calls****************/
		$( "#tabs" ).tabs( "enable", 3 );			
		$( "#tabs" ).tabs({
		"active": 3,
		"disabled": [4]
		}); 
	return true;  
	}
	else{
	return false;	
	}
});


function premium_pdf(){
		/*****************Ajax Call**************/
			  var form = $('#company-form')[0];
              var formData = new FormData(form);
			        $.ajax({
					type: 'POST',
					//contentType: "application/json; charset=utf-8",
					url: "../ajax/ajax_cmt_ind_premium_pdf.php",
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
						//$("#preview_data").html(res);
						}
						else {
						//$("#preview_data").html('');
						//alert(res);
						}
					}
				});
			 /******Ajax Calls****************/
		
}

</script>
<link rel="stylesheet" href="../bower_components/jquery-ui/themes/base/jquery-ui.css">
<link rel="stylesheet" href="ins_ind_members_premium.css">
<script src="../bower_components/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript">
  $( function() {
    $( "#tabs" ).tabs({
	"active": 0,
    "disabled": [1,2,3]
	});
  });
</script>
<script type="text/javascript">
function add_additional_members(){
var scntDiv = $('#additional_customer_section');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_add_additional_rows.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    $('.select').select2({
		placeholder: 'Select an option',
		width: 'resolve' // need to override the changed default
	}); 
	$('.datepickerA').datepicker({
        dateFormat: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
		maxDate: 0
	});
	$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
    }else{
    alert("No item found!");
    }
}

function fun_remove_additional_member(div_id){
$('#added_additional_members_'+div_id).remove();
}
/************************End Members*******************/
</script>
<script type="text/javascript">
$(document).ready(function(){
$("#company-form").validationEngine();
$('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
}); 
$('.datepickerA').datepicker({
		yearRange:'-90:+0',					 
        dateFormat: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
		maxDate: 0
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
$('.datepickerB').datepicker({
		yearRange:'-90:+0',					 
        dateFormat: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
		minDate: 0
});
$('.datepickerB').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
// Add Plan Rows
function add_new_plan_rows(){
	var start_date  = $("#start_date").val();
    var scntDiv     = $('#new_plan_section');
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_add_plan_rows.php?start_date="+start_date,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    $('.select').select2({
		placeholder: 'Select an option',
		width: 'resolve' // need to override the changed default
	}); 
	$('.datepickerA').datepicker({
        dateFormat: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
		maxDate: 0
	});
	$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
    }else{
    alert("No item found!");
    }
}
//Remove new plan
function plan_remove_item_added(tr_id){
    $('#tr_plan_'+tr_id).remove();
}
//Add plan dropdown
function show_plan(company_id,unique_id){
	$("#np_"+unique_id).children('option:not(:first-child)').remove();
	$("#copay_"+unique_id).children('option:not(:first-child)').remove();
	$("#ac_"+unique_id).children('option:not(:first-child)').remove();
    var scntDiv     = $('#td_plan_'+unique_id);
	scntDiv.html("");
	var start_date  = $("#start_date").val();
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_add_plan_dropdown.php?company_id="+company_id+"&start_date="+start_date+"&unique_id="+unique_id,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.html(xmlhttp.responseText);
    }else{
    alert("No item found!");
    }
}

//Add show np
function show_np(company_id,unique_id){
    var scntDiv     = $('#td_np_'+unique_id);
	scntDiv.html("");
	var start_date  = $("#start_date").val();
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_add_np.php?company_id="+company_id+"&start_date="+start_date+"&unique_id="+unique_id,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    }else{
    alert("No item found!");
    }
}
//Add ac
function show_ac(company_id,unique_id){
    var scntDiv     = $('#td_ac_'+unique_id);
	scntDiv.html("");
	var start_date  = $("#start_date").val();
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_add_ac.php?company_id="+company_id+"&start_date="+start_date+"&unique_id="+unique_id,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    }else{
    alert("No item found!");
    }
}

//Add show_copay
function show_copay(company_id,unique_id){
    var scntDiv     = $('#td_copay_'+unique_id);
	scntDiv.html("");
	var start_date  = $("#start_date").val();
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_add_copay.php?company_id="+company_id+"&start_date="+start_date+"&unique_id="+unique_id,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    }else{
    alert("No item found!");
    }
}


function fun_remove_additional_member_dependent(dependent_id){
   $('#added_additional_members_dependent_'+dependent_id).remove();
}


function copyText(){
	var textBox = document.getElementById("quotation_link");
    textBox.select(); // Select the text inside the text box
    document.execCommand("copy"); // Copy the selected text to clipboard
	noty({ text: 'Text has been copied to clipboard!', layout: 'topCenter', type: 'success', timeout: 2000 });
}

//enhanced_maternity	
function common_fn(){
	          $("#preview_data").html('');
              /*****************Ajax Call**************/
			  var form = $('#company-form')[0];
              var formData = new FormData(form);
			        $.ajax({
					type: 'POST',
					//contentType: "application/json; charset=utf-8",
					url: "../ajax/ajax_cmt_ins_filter_stage.php",
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
							$("#third_stage_filter_data").html(res);
						}
						else {
						$("#third_stage_filter_data").html('');
						//alert(res);
						}
					}
				});
			 /******Ajax Calls****************/
}

</script>
