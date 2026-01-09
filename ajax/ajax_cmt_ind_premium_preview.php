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
    $select_plan          = $_REQUEST['select_plan'];
    $today                = date("Y-m-d");
	$customer             = killstring($_REQUEST['customer']);	
	$customer_first_name  = killstring($_REQUEST['customer_first_name']);
	$customer_last_name   = killstring($_REQUEST['customer_last_name']);
	$batch_name           = killstring($_REQUEST['batch_name']);
    $cnt_company          = 0;
	$company_id_now       = $_REQUEST['company_id'];
	$cnt_company          = count($company_id_now);
	$gender               = killstring($_REQUEST['gender']);
	$marital_status       = killstring($_REQUEST['marital_status']);
	$residency            = killstring($_REQUEST['residency']);
	$additionl_customer_first_name_now  = $_REQUEST['additionl_customer_first_name'];
	$cnt_additionl_customer            = count($additionl_customer_first_name_now);
	$start_date   = date('Y-m-d',strtotime($_REQUEST['start_date']));
	$dob_primary  = date('Y',strtotime($_REQUEST['dob']));
    $year         = date('Y');
    $age_primary  = ($year - $dob_primary);
	$submit_status = 0;
	$IsPageValid   = true;
	if($IsPageValid==true){
	$error_cnt = 0;
	
	$dental_covered           = killstring($_REQUEST['dental_covered']);
	$enhanced_maternity       = killstring($_REQUEST['enhanced_maternity']);
	$wellness_covered         = killstring($_REQUEST['wellness_covered']);
	$optical_covered          = killstring($_REQUEST['optical_covered']);
?>
<style type="text/css">
.border_none {
	border:none;
}
.border_none:hover {
	border:none;
	background-color:#CCC;
}
.bcgd_hover:hover {
	background-color:#CCC;
}
</style>
<div class="col-md-12">
  <h4><u>Preview</u></h4>
  <!--<a href="javascript:void(0);" onClick="premium_pdf()" class="pull-right"><i class="fa fa-file-pdf-o fa-lg"></i></a>-->
  <div class="table-responsive">
    <!-----------start header-------------->
    <div class="row col-md-12 new_customer">
      <div class="col-md-4">
        <div class="form-group">
          <label>Client:</label>
          <div class="input-group">
            <?=$customer_first_name?>
            <?=$customer_last_name?>
          </div>
        </div>
        <div class="form-group">
          <label class="">Quote Date:</label>
          <div class="input-group">
            <?=date('d-m-Y');?>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="required">Advisor:</label>
          <div class="input-group">
            <?=$_SESSION['full_name_user']?>
          </div>
        </div>
        <div class="form-group">
          <label class="">Start Date:</label>
          <div class="input-group">
            <?=date('d-m-Y',strtotime($_REQUEST['start_date']))?>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="">Contact:</label>
          <div class="input-group">
            <?=$_SESSION['useremail']?>
          </div>
        </div>
      </div>
    </div>
    <!------------------end header-------------------->
    <table id="example2"  class="table table-bordered table-hover table-striped stripe row-border order-column" border="1" role="grid" style="font-size:12px; border:1px solid #960;">
      <thead>
        <tr>
          <th></th>
          <?php
		  for($i=0; $i < $cnt_company; $i++){
		  list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
		  if(in_array($unique_id_n, $select_plan)){
		  $sqlQryA             = mysqli_query($conn,"SELECT `company_name` FROM `tbl_cmt_ins_company_master` WHERE `company_id`='$company_id'");
		  $rowQryA             = mysqli_fetch_row($sqlQryA);
		  $company_name        = empty($rowQryA[0])?0:$rowQryA[0];
		  ?>
          <th style="font-size:20px ; background: #005AAB ; color:#fff ; line-height:1.2 ; text-align:center" ><?=$company_name?></th>
          <?php
		   }
		  }
		  ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Plan Name</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								   
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$premium             = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><?=$premium?></td>
          <?php
								 }
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Direct Billing Network</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$billing_network     = trim($_REQUEST['np'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								//list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `billing_network`, `billing_network_id` FROM `tbl_cmt_ins_direct_billing_network_master` WHERE `billing_network_id`='$billing_network'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$billing_network     = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td ><?=$billing_network?></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px  ; color:#333333 ; font-weight:600">Deductible / Co-pay</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								 if(in_array($unique_id_n, $select_plan)){
								$deductable_copay     = trim($_REQUEST['deductable_copay'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								//list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `deductable_copay`, `deductable_copay_id` FROM `tbl_cmt_ins_deductable_copay_master` WHERE `deductable_copay_id`='$deductable_copay'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$deductable_copay     = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><?=$deductable_copay?></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr style="background:#CF9;">
          <td style="font-size:16px; color:#333333 ; font-weight:600">Benefits Guide</td>
          <?php for($i=0; $i < $cnt_company; $i++){?>
          <td></td>
          <?php
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px  ; color:#333333 ; font-weight:600">Annual Limit</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `annual_limit` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$annual_limit             = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td class="bcgd_hover">AED<button type="button" data-toggle="modal" data-target="#modal_annual_limit_<?=$premium_id?>" class="border_none" id="btn_annual_limit_<?=$premium_id?>">
            <?=Checkvalid_number_or_not($annual_limit,2);?>
            </button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="annual_limit[<?=$company_id?>]" id="annual_limit_<?=$premium_id?>" value="<?=$annual_limit?>"/>
          <div class="modal fade" id="modal_annual_limit_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Annual Limit</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="annual_limit_modal_<?=$premium_id?>"><?=Checkvalid_number_or_not($annual_limit,2);?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_annual_limit(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px; color:#333333 ; font-weight:600">Geographical Coverage</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `geographical_coverage` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$geographical_coverage             = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_geographical_coverage_<?=$premium_id?>" class="border_none" id="btn_geographical_coverage_<?=$premium_id?>"><?=$geographical_coverage?></button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="geographical_coverage[<?=$company_id?>]" id="geographical_coverage_<?=$premium_id?>" value="<?=$geographical_coverage?>"/>
          <div class="modal fade" id="modal_geographical_coverage_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Geographical Coverage</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="geographical_coverage_modal_<?=$premium_id?>"><?=$geographical_coverage?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_geographical_coverage(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Inpatient (Hospitalization)</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `inpatient` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$inpatient           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_inpatient_<?=$premium_id?>" class="border_none" id="btn_inpatient_<?=$premium_id?>"><?=$inpatient?></button></td>
         <!---------------------Start Modal------------------->
          <input type="hidden" name="inpatient[<?=$company_id?>]" id="inpatient_<?=$premium_id?>" value="<?=$inpatient?>"/>
          <div class="modal fade" id="modal_inpatient_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Inpatient (Hospitalization)</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="inpatient_modal_<?=$premium_id?>"><?=$inpatient?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_inpatient(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13pxB ; color:#333333 ; font-weight:600">Out Patient</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `out_patient` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$out_patient           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_out_patient_<?=$premium_id?>" class="border_none" id="btn_out_patient_<?=$premium_id?>"><?=$out_patient?></button></td>
            <!---------------------Start Modal------------------->
          <input type="hidden" name="out_patient[<?=$company_id?>]" id="out_patient_<?=$premium_id?>" value="<?=$out_patient?>"/>
          <div class="modal fade" id="modal_out_patient_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Out Patient</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="out_patient_modal_<?=$premium_id?>"><?=$out_patient?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_out_patient(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Physiotherapy</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `physiotherapy` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$physiotherapy           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_physiotherapy_<?=$premium_id?>" class="border_none" id="btn_physiotherapy_<?=$premium_id?>"><?=$physiotherapy?></button></td>
            <!---------------------Start Modal------------------->
          <input type="hidden" name="physiotherapy[<?=$company_id?>]" id="physiotherapy_<?=$premium_id?>" value="<?=$physiotherapy?>"/>
          <div class="modal fade" id="modal_physiotherapy_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Physiotherapy</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="physiotherapy_modal_<?=$premium_id?>"><?=$physiotherapy?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_physiotherapy(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px  ; color:#333333 ; font-weight:600">Emergency Evacuation</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
							    if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `emergency_evacuation` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$emergency_evacuation           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_emergency_evacuation_<?=$premium_id?>" class="border_none" id="btn_emergency_evacuation_<?=$premium_id?>"><?=$emergency_evacuation?></button></td>
           <!---------------------Start Modal------------------->
          <input type="hidden" name="emergency_evacuation[<?=$company_id?>]" id="emergency_evacuation_<?=$premium_id?>" value="<?=$emergency_evacuation?>"/>
          <div class="modal fade" id="modal_emergency_evacuation_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Emergency Evacuation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="emergency_evacuation_modal_<?=$premium_id?>"><?=$emergency_evacuation?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_emergency_evacuation(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Chronic Conditions</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `chronic_conditions` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$chronic_conditions           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_chronic_conditions_<?=$premium_id?>" class="border_none" id="btn_chronic_conditions_<?=$premium_id?>"><?=$chronic_conditions?></button></td>
            <!---------------------Start Modal------------------->
          <input type="hidden" name="chronic_conditions[<?=$company_id?>]" id="chronic_conditions_<?=$premium_id?>" value="<?=$chronic_conditions?>"/>
          <div class="modal fade" id="modal_chronic_conditions_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Chronic Conditions</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="chronic_conditions_modal_<?=$premium_id?>"><?=$chronic_conditions?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_chronic_conditions(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px  ; color:#333333 ; font-weight:600">Pre-existing Cover</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `pre_existing_cover` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$pre_existing_cover           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_pre_existing_cover_<?=$premium_id?>" class="border_none" id="btn_pre_existing_cover_<?=$premium_id?>"><?=$pre_existing_cover?></button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="pre_existing_cover[<?=$company_id?>]" id="pre_existing_cover_<?=$premium_id?>" value="<?=$pre_existing_cover?>"/>
          <div class="modal fade" id="modal_pre_existing_cover_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Pre-existing Cover</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="pre_existing_cover_modal_<?=$premium_id?>"><?=$pre_existing_cover?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_pre_existing_cover(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <?php //if(!empty($maternity_covered)){?>
        <tr style="background:#CF9;">
          <td style="font-size:16px ; color:#333333 ; font-weight:600">Maternity Benefits</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){  
		   ?>
          <td></td>
          <?php
		   }
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px  ; color:#333333 ; font-weight:600">Routine Maternity</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
							    if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `routine_maternity` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$routine_maternity           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_routine_maternity_<?=$premium_id?>" class="border_none" id="btn_routine_maternity_<?=$premium_id?>"><?=$routine_maternity?></button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="routine_maternity[<?=$company_id?>]" id="routine_maternity_<?=$premium_id?>" value="<?=$routine_maternity?>"/>
          <div class="modal fade" id="modal_routine_maternity_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Routine Maternity</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="routine_maternity_modal_<?=$premium_id?>"><?=$routine_maternity?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_routine_maternity(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px; color:#333333 ; font-weight:600">Maternity Waiting Period</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `maternity_waiting_period` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$maternity_waiting_period           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_maternity_waiting_period_<?=$premium_id?>" class="border_none" id="btn_maternity_waiting_period_<?=$premium_id?>"><?=$maternity_waiting_period?></button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="maternity_waiting_period[<?=$company_id?>]" id="maternity_waiting_period_<?=$premium_id?>" value="<?=$maternity_waiting_period?>"/>
          <div class="modal fade" id="modal_maternity_waiting_period_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Maternity Waiting Period</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="maternity_waiting_period_modal_<?=$premium_id?>"><?=$maternity_waiting_period?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_maternity_waiting_period(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <?php //}?>
        <?php //if(!empty($dental_covered)){?>
        <tr style="background:#CF9;">
          <td style="font-size:16px ; color:#333333 ; font-weight:600">Dental Benefits</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){    
		   ?>
          <td></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Dental</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){  
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `dental` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$dental           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_dental_<?=$premium_id?>" class="border_none" id="btn_dental_<?=$premium_id?>"><?=$dental?></button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="dental[<?=$company_id?>]" id="dental_<?=$premium_id?>" value="<?=$dental?>"/>
          <div class="modal fade" id="modal_dental_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Dental</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="dental_modal_<?=$premium_id?>"><?=$dental?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_dental(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
								}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Dental Waiting Period</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){  
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `dental_waiting_period` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$dental_waiting_period           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_dental_waiting_period_<?=$premium_id?>" class="border_none" id="btn_dental_waiting_period_<?=$premium_id?>"><?=$dental_waiting_period?></button></td>
            <!---------------------Start Modal------------------->
          <input type="hidden" name="dental_waiting_period[<?=$company_id?>]" id="dental_waiting_period_<?=$premium_id?>" value="<?=$dental_waiting_period?>"/>
          <div class="modal fade" id="modal_dental_waiting_period_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Dental Waiting Period</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="dental_waiting_period_modal_<?=$premium_id?>"><?=$dental_waiting_period?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_dental_waiting_period(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
			}
		  }
		 ?>
        </tr>
        <?php //}?>
        <?php //if(!empty($wellness_covered)){?>
        <tr style="background:#CF9;">
          <td style="font-size:16px  ; color:#333333 ; font-weight:600">Wellness Benefits</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){      
			  ?>
          <td></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Optical Benefits</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){    
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `optical_benefits` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$optical_benefits           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_optical_benefits_<?=$premium_id?>" class="border_none" id="btn_optical_benefits_<?=$premium_id?>"><?=$optical_benefits?></button></td>
             <!---------------------Start Modal------------------->
          <input type="hidden" name="optical_benefits[<?=$company_id?>]" id="optical_benefits_<?=$premium_id?>" value="<?=$optical_benefits?>"/>
          <div class="modal fade" id="modal_optical_benefits_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Optical Benefits</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="optical_benefits_modal_<?=$premium_id?>"><?=$optical_benefits?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_optical_benefits(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
								}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px; color:#333333 ; font-weight:600">Wellness</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){    
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `wellness` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$wellness           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_wellness_<?=$premium_id?>" class="border_none" id="btn_wellness_<?=$premium_id?>"><?=$wellness?></button></td>
           <!---------------------Start Modal------------------->
          <input type="hidden" name="wellness[<?=$company_id?>]" id="wellness_<?=$premium_id?>" value="<?=$wellness?>"/>
          <div class="modal fade" id="modal_wellness_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Wellness</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="wellness_modal_<?=$premium_id?>"><?=$wellness?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_wellness(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
								}
		  }
		 ?>
        </tr>
        <?php //}?>
        <tr style="background:#CF9;">
          <td style="font-size:16px  ; color:#333333 ; font-weight:600">Payment Information</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){   
			  ?>
          <td></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px; color:#333333 ; font-weight:600">Semi Annual Surcharge</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){  
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `semi_annual_surcharge` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$semi_annual_surcharge           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_semi_annual_surcharge_<?=$premium_id?>" class="border_none" id="btn_semi_annual_surcharge_<?=$premium_id?>"><?=$semi_annual_surcharge?></button></td>
           <!---------------------Start Modal------------------->
          <input type="hidden" name="semi_annual_surcharge[<?=$company_id?>]" id="semi_annual_surcharge_<?=$premium_id?>" value="<?=$semi_annual_surcharge?>"/>
          <div class="modal fade" id="modal_semi_annual_surcharge_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Semi Annual Surcharge</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="semi_annual_surcharge_modal_<?=$premium_id?>"><?=$semi_annual_surcharge?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_semi_annual_surcharge(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
								}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">Quarterly Surcharge</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){ 
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `quarterly_surcharge` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$quarterly_surcharge           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_quarterly_surcharge_<?=$premium_id?>" class="border_none" id="btn_quarterly_surcharge_<?=$premium_id?>"><?=$quarterly_surcharge?></button></td>
          <!---------------------Start Modal------------------->
          <input type="hidden" name="quarterly_surcharge[<?=$company_id?>]" id="quarterly_surcharge_<?=$premium_id?>" value="<?=$quarterly_surcharge?>"/>
          <div class="modal fade" id="modal_quarterly_surcharge_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Quarterly Surcharge</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="quarterly_surcharge_modal_<?=$premium_id?>"><?=$quarterly_surcharge?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_quarterly_surcharge(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
								}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px; color:#333333 ; font-weight:600">Monthly Surcharge</td>
          <?php
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){  
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								//echo "SELECT `premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$price_batch'";
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `monthly_surcharge` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
								$rowQryA             = mysqli_fetch_row($sqlQryA);
								$monthly_surcharge           = empty($rowQryA[0])?" ":$rowQryA[0];
								?>
          <td><button type="button" data-toggle="modal" data-target="#modal_monthly_surcharge_<?=$premium_id?>" class="border_none" id="btn_monthly_surcharge_<?=$premium_id?>"><?=$monthly_surcharge?></button></td>
           <!---------------------Start Modal------------------->
          <input type="hidden" name="monthly_surcharge[<?=$company_id?>]" id="monthly_surcharge_<?=$premium_id?>" value="<?=$monthly_surcharge?>"/>
          <div class="modal fade" id="modal_monthly_surcharge_<?=$premium_id?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Monthly Surcharge</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                  <textarea name="" rows="6" placeholder="Enter value" class="form-control" id="monthly_surcharge_modal_<?=$premium_id?>"><?=$monthly_surcharge?>
</textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="click_monthly_surcharge(<?=$premium_id?>)">Save changes</button>
                </div>
              </div>
            </div>
          </div>
          <!---------------------End Modal------------------->
          <?php
								}
		  }
		 ?>
        </tr>
        <tr style="background:#CF9;">
          <td style="font-size:16px; color:#333333 ; font-weight:600">Members Information</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			  list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){  
			  ?>
          <td></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td><?=$customer_full_name?>
            ,Age:
            <?=$age_primary?>
            ,
            <?=$gender?></td>
          <?php
	  
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){ 
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='P' AND `marital_status`='$marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date`  AND '$age_primary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
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
								?>
          <td><?=Checkvalid_number_or_not($premium,2);?></td>
          <?php
								}
		  }
		 ?>
        </tr>
        <?php if(!empty($cnt_additionl_customer)){
			 for($j=0; $j < $cnt_additionl_customer; $j++){
				 $additionl_customer_first_name         = trim($_REQUEST['additionl_customer_first_name'][$j]);
				 $additionl_customer_last_name          = trim($_REQUEST['additionl_customer_last_name'][$j]);
				 $additionl_gender                      = trim($_REQUEST['additionl_gender'][$j]);
				 $additionl_marital_status              = trim($_REQUEST['additionl_marital_status'][$j]);
				 $dob_secondary  = date('Y',strtotime($_REQUEST['additionl_dob'][$j]));
				 $age_secondary  = (date('Y') - $dob_secondary);
			?>
        <tr>
          <td><?=$additionl_customer_first_name?>
            <?=$additionl_customer_last_name?>
            ,Age:
            <?=$age_secondary?>
            ,
            <?=$additionl_gender?></td>
          <?php
	  
							   for($i=0; $i < $cnt_company; $i++){
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){ 
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$additionl_gender' AND `relation_type`='D' AND `marital_status`='$additionl_marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND '$age_secondary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
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
								?>
          <td><?=Checkvalid_number_or_not($premium,2);?></td>
          <?php
								}
		  }
		 ?>
        </tr>
        <?php }}?>
        <tr style="background:#CF9;">
          <td style="font-size:16px ; color:#333333 ; font-weight:600">Total Premium</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			  list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){    
			  ?>
          <td></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">UAE Dirham excluding (TAX) AED</td>
          <?php
	   $total_premium = 0;
	  
							   for($i=0; $i < $cnt_company; $i++){
								$total_premium = 0;
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){ 
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='P' AND `marital_status`='$marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date`  AND '$age_primary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
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
								$total_premium+=$premium;
								
			 if(!empty($cnt_additionl_customer)){
			 for($j=0; $j < $cnt_additionl_customer; $j++){
				 $additionl_customer_full_name          = trim($_REQUEST['additionl_customer_full_name'][$j]);
				 $additionl_gender                      = trim($_REQUEST['additionl_gender'][$j]);
				 $additionl_marital_status              = trim($_REQUEST['additionl_marital_status'][$j]);
				 $dob_secondary  = date('Y',strtotime($_REQUEST['additionl_dob'][$j]));
				 $age_secondary  = (date('Y') - $dob_secondary);
				 $sqlQryB             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$additionl_gender' AND `relation_type`='D' AND `marital_status`='$additionl_marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND '$age_secondary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
				$rowQryB             = mysqli_fetch_row($sqlQryB);
				$premiumB            = empty($rowQryB[0])?0:$rowQryB[0];
				
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
				$total_premium+=$premiumB;
			 }
			 }
								?>
          <td><button style="    
	height: 36px;
    width: 150px;
    font-size: 16px;
    font-weight: 500;
    background: #005AAB;
    border: none;
    color: white;" disabled="disabled">
            <?=Checkvalid_number_or_not($total_premium,2);?>
            </button></td>
          <?php
							   }
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px ; color:#333333 ; font-weight:600">UAE Dirham Including (TAX) AED</td>
          <?php
	   
	  
							   for($i=0; $i < $cnt_company; $i++){
								   $total_premium     = 0;
								   $total_premium_vat = 0;
								list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
								if(in_array($unique_id_n, $select_plan)){ 
								$price_batch         = trim($_REQUEST['price_batch'][$company_id]);
								list($price_batch_id,$premium_id) = explode("|",$price_batch);
								$sqlQryA             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$gender' AND `relation_type`='P' AND `marital_status`='$marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date`  AND '$age_primary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
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
								$total_premium+=$premium;
								
			 if(!empty($cnt_additionl_customer)){
			 for($j=0; $j < $cnt_additionl_customer; $j++){
				 $additionl_customer_full_name          = trim($_REQUEST['additionl_customer_full_name'][$j]);
				 $additionl_gender                      = trim($_REQUEST['additionl_gender'][$j]);
				 $additionl_marital_status              = trim($_REQUEST['additionl_marital_status'][$j]);
				 $dob_secondary  = date('Y',strtotime($_REQUEST['additionl_dob'][$j]));
				 $age_secondary  = (date('Y') - $dob_secondary);
				 $sqlQryB             = mysqli_query($conn,"SELECT `price` FROM `tbl_cmt_ins_price_master` WHERE `company_id`='$company_id' AND `gender`='$additionl_gender' AND `relation_type`='D' AND `marital_status`='$additionl_marital_status' AND `emirate_id`='$residency' AND `price_batch_id`='$price_batch_id' AND '$start_date' BETWEEN `start_date` AND `end_date` AND '$age_secondary' BETWEEN `start_age` AND `end_age` ORDER BY price_id DESC LIMIT 1");
				$rowQryB             = mysqli_fetch_row($sqlQryB);
				$premiumB            = empty($rowQryB[0])?0:$rowQryB[0];
				
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
				$total_premium+=$premiumB;
			 }
			 }
			 $total_premium_vat = 0;
			 $vat_percentage    = .05;
			 $total_premium_vat = ($total_premium + ($total_premium * $vat_percentage) );
			?>
          <td><button style="    
	height: 36px;
    width: 150px;
    font-size: 16px;
    font-weight: 500;
    background: #005AAB;
    border: none;
    color: white;" disabled="disabled">
            <?=Checkvalid_number_or_not($total_premium_vat,2);?>
            </button></td>
          <?php
			}
		  }
		 ?>
        </tr>
        <tr>
          <td style="font-size:13px  ; color:#333333 ; font-weight:600">Comments</td>
          <?php for($i=0; $i < $cnt_company; $i++){
			   list($company_id,$unique_id_n) = explode("|",$_REQUEST['company_id'][$i]);
			if(in_array($unique_id_n, $select_plan)){   
			  
			  ?>
          <td></td>
          <?php
			}
		  }
		 ?>
        </tr>
      </tbody>
    </table>
    <?php
   }//valid true	 	
?>
  </div>
</div>
<script type="text/javascript">
function click_annual_limit(itr){
	var val  = $("#annual_limit_modal_"+itr).val();
	$("#btn_annual_limit_"+itr).html(val);
	$("#annual_limit_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_geographical_coverage(itr){
	var val  = $("#geographical_coverage_modal_"+itr).val();
	$("#btn_geographical_coverage_"+itr).html(val);
	$("#geographical_coverage_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_inpatient(itr){
	var val  = $("#inpatient_modal_"+itr).val();
	$("#btn_inpatient_"+itr).html(val);
	$("#inpatient_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_out_patient(itr){
	var val  = $("#out_patient_modal_"+itr).val();
	$("#btn_out_patient_"+itr).html(val);
	$("#out_patient_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_physiotherapy(itr){
	var val  = $("#physiotherapy_modal_"+itr).val();
	$("#btn_physiotherapy_"+itr).html(val);
	$("#physiotherapy_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_emergency_evacuation(itr){
	var val  = $("#emergency_evacuation_modal_"+itr).val();
	$("#btn_emergency_evacuation_"+itr).html(val);
	$("#emergency_evacuation_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_chronic_conditions(itr){
	var val  = $("#chronic_conditions_modal_"+itr).val();
	$("#btn_chronic_conditions_"+itr).html(val);
	$("#chronic_conditions_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_pre_existing_cover(itr){
	var val  = $("#pre_existing_cover_modal_"+itr).val();
	$("#btn_pre_existing_cover_"+itr).html(val);
	$("#pre_existing_cover_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_routine_maternity(itr){
	var val  = $("#routine_maternity_modal_"+itr).val();
	$("#btn_routine_maternity_"+itr).html(val);
	$("#routine_maternity_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_maternity_waiting_period(itr){
	var val  = $("#maternity_waiting_period_modal_"+itr).val();
	$("#btn_maternity_waiting_period_"+itr).html(val);
	$("#maternity_waiting_period_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_dental(itr){
	var val  = $("#dental_modal_"+itr).val();
	$("#btn_dental_"+itr).html(val);
	$("#dental_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_optical_benefits(itr){
	var val  = $("#optical_benefits_modal_"+itr).val();
	$("#btn_optical_benefits_"+itr).html(val);
	$("#optical_benefits_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_wellness(itr){
	var val  = $("#wellness_modal_"+itr).val();
	$("#btn_wellness_"+itr).html(val);
	$("#wellness_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_semi_annual_surcharge(itr){
	var val  = $("#semi_annual_surcharge_modal_"+itr).val();
	$("#btn_semi_annual_surcharge_"+itr).html(val);
	$("#semi_annual_surcharge_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_quarterly_surcharge(itr){
	var val  = $("#quarterly_surcharge_modal_"+itr).val();
	$("#btn_quarterly_surcharge_"+itr).html(val);
	$("#quarterly_surcharge_"+itr).val(val);
	$('.modal').modal('hide');
}
function click_monthly_surcharge(itr){
	var val  = $("#monthly_surcharge_modal_"+itr).val();
	$("#btn_monthly_surcharge_"+itr).html(val);
	$("#monthly_surcharge_"+itr).val(val);
	$('.modal').modal('hide');
}
</script>
