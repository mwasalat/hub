<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

/*echo "<pre>";
var_dump($_POST);
echo "</pre>";*/

    $today               = date("Y-m-d");
	$customer            = killstring($_REQUEST['customer']);	
	$customer_full_name  = killstring($_REQUEST['customer_full_name']);
	$batch_name          = killstring($_REQUEST['batch_name']);
	$relation            = killstring($_REQUEST['relation']);
    $cnt_company         = 0;
	$company_id_now      = $_REQUEST['company_id'];
	$cnt_company         = count($company_id_now);
	$gender              = killstring($_REQUEST['gender']);
	$marital_status      = killstring($_REQUEST['marital_status']);
	$residency           = killstring($_REQUEST['residency']);
	$additionl_customer_first_name_now       = $_REQUEST['additionl_customer_first_name'];
	$cnt_additionl_customer                 = count($additionl_customer_first_name_now);
	$start_date    = date('Y-m-d',strtotime($_REQUEST['start_date']));
	$dob_primary   = date('Y',strtotime($_REQUEST['dob']));
    $year          = date('Y');
    $age_primary   = ($year - $dob_primary);
	$submit_status = 0;
	$IsPageValid   = true;
	if($IsPageValid==true){
	$error_cnt = 0; 
	
	$dental_covered           = killstring($_REQUEST['dental_covered']);
	$enhanced_maternity       = killstring($_REQUEST['enhanced_maternity']);
	$wellness_covered         = killstring($_REQUEST['wellness_covered']);
	$optical_covered          = killstring($_REQUEST['optical_covered']);
?>
<div class="col-md-12">
  <!--<a href="javascript:void(0);" onClick="premium_pdf()" class="pull-right"><i class="fa fa-file-pdf-o fa-lg"></i></a>-->
  <div class="table-responsive">
     <table id="example2"  class="table table-bordered table-hover table-striped stripe row-border order-column" border="1" role="grid" style="font-size:12px; border:1px solid #960;"> 
      <tbody>
      <tr>
      <td>Enhance Maternity: <input type="radio" class="" name="enhanced_maternity" value="1" <?php if($enhanced_maternity==1){echo "checked";}?> onclick="common_fn();"/>Yes &nbsp;
                    <input type="radio" class="" name="enhanced_maternity" value="0" <?php if($enhanced_maternity==0 || empty($enhanced_maternity)){echo "checked";}?> onclick="common_fn();"/>No</td>
       <td>Dental:  <input type="radio" class="" name="dental_covered" value="1" <?php if($dental_covered==1){echo "checked";}?>  onclick="common_fn();"/>Yes &nbsp;
                    <input type="radio" class="" name="dental_covered" value="0" <?php if($dental_covered==0 || empty($dental_covered)){echo "checked";}?>  onclick="common_fn();"/>No</td>
      <td>Wellness: <input type="radio" class="" name="wellness_covered" value="1"  <?php if($wellness_covered==1){echo "checked";}?>  onclick="common_fn();"/>Yes &nbsp;
                    <input type="radio" class="" name="wellness_covered" value="0"  <?php if($wellness_covered==0 || empty($wellness_covered)){echo "checked";}?>  onclick="common_fn();"/>No</td>
      <td>Optical:  <input type="radio" class="" name="optical_covered" value="1" <?php if($optical_covered==1){echo "checked";}?>  onclick="common_fn();"/>Yes &nbsp;
                    <input type="radio" class="" name="optical_covered" value="0" <?php if($optical_covered==0 || empty($optical_covered)){echo "checked";}?>  onclick="common_fn();"/>No</td>               
      </tr>
     </tbody>
     </table>
     <div class="pan_content">
     <table id="example2"  class="table table-bordered table-hover table-striped stripe row-border order-column" border="1" role="grid" style="font-size:12px; border:1px solid #960;"> 
      <tbody>
          <?php
							   for($i=0; $i < $cnt_company; $i++){ 
							   ?><tr style="border: 2px solid #cbb8b8;margin: 10px;">
                               <?php
							 
							    $price_batch_id  = "";
							    $premium_id      = "";
								$company_id = "";
							    $unique_id_n= "";
								$company_ids         = trim($_REQUEST['company_id'][$i]);
								list($company_id,$unique_id_n) = explode("|",$company_ids);
								$price_batch          = trim($_REQUEST['price_batch'][$company_id]);
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT tb1.`premium_name`,tb2.`company_name` FROM `tbl_cmt_premium_master` tb1 LEFT JOIN `tbl_cmt_ins_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` WHERE tb1.`premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$premium             = empty($rowQryA[0])?" ":$rowQryA[0];
								$company_name        = empty($rowQryA[1])?" ":$rowQryA[1];
								
								$billing_network     = trim($_REQUEST['np'][$company_id]);
								$sqlQryA             = mysqli_query($conn,"SELECT `billing_network`, `billing_network_id` FROM `tbl_cmt_ins_direct_billing_network_master` WHERE `billing_network_id`='$billing_network'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$billing_network     = empty($rowQryA[0])?" ":$rowQryA[0];
								
								$deductable_copay     = trim($_REQUEST['deductable_copay'][$company_id]);
								$sqlQryA             = mysqli_query($conn,"SELECT `deductable_copay`, `deductable_copay_id` FROM `tbl_cmt_ins_deductable_copay_master` WHERE `deductable_copay_id`='$deductable_copay'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$deductable_copay    = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td>Plan:<?=$premium?></td>
          <td>Company:<?=$company_name?>,
              <div class="clear" style="height:10px;"></div>
              Billing Network:<?=$billing_network?>
              <div class="clear" style="height:10px;"></div>
              Co-pay:<?=$deductable_copay?><br/>
          </td>
          
          <td>
          <?php
          		                $total_premium = 0;
								$premium       = 0;
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='$relation' AND `marital_status`='$marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date`  AND '$age_primary' BETWEEN `start_age` AND `end_age` AND `is_active`=1 ORDER BY price_id DESC LIMIT 1");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$premium             = empty($rowQryA[0])?0:$rowQryA[0];
								$total_premium+=$premium;
								
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
								
			 if(!empty($cnt_additionl_customer)){
			 for($j=0; $j < $cnt_additionl_customer; $j++){
				 $additionl_gender                      = trim($_REQUEST['additionl_gender'][$j]);
				 $additionl_marital_status              = trim($_REQUEST['additionl_marital_status'][$j]);
				 $dob_secondary  = date('Y',strtotime($_REQUEST['additionl_dob'][$j]));
				 $age_secondary  = (date('Y') - $dob_secondary);
				 $sqlQryB             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$additionl_gender' AND `relation_type`='D' AND `marital_status`='$additionl_marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND '$age_secondary' BETWEEN `start_age` AND `end_age` AND `is_active`=1 ORDER BY price_id DESC LIMIT 1");
				$rowQryB             = mysqli_fetch_row($sqlQryB);
				$premiumB            = empty($rowQryB[0])?0:$rowQryB[0];
				$total_premium+=$premiumB;
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
			 }
			 }
			?>
            AED <b><?=Checkvalid_number_or_not($total_premium,2);?> </b>
           </td>
           <td><input type="checkbox" name="select_plan[]" value="<?=$unique_id_n?>"><span> </span> Plan</td>  
           </tr>
          <?php
		  }
		 ?>
      </tbody>
    </table>  
    </div> 
   <?php
   }//valid true	 	
?>
  </div>
</div>
