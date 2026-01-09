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
	
	$premium_name        = killstring($_REQUEST['premium_name']);
	$annual_limit        = killstring($_REQUEST['annual_limit']);
	$geographical_coverage        = killstring($_REQUEST['geographical_coverage']);
	$inpatient        = killstring($_REQUEST['inpatient']);
	$out_patient      = killstring($_REQUEST['out_patient']);
	$physiotherapy    = killstring($_REQUEST['physiotherapy']);
	$emergency_evacuation        = killstring($_REQUEST['emergency_evacuation']);
	$chronic_conditions          = killstring($_REQUEST['chronic_conditions']);
	$pre_existing_cover          = killstring($_REQUEST['pre_existing_cover']);
	$routine_maternity           = killstring($_REQUEST['routine_maternity']);
	$maternity_waiting_period    = killstring($_REQUEST['maternity_waiting_period']);
	$dental                = killstring($_REQUEST['dental']);
	$dental_waiting_period = killstring($_REQUEST['dental_waiting_period']);
	$optical_benefits      = killstring($_REQUEST['optical_benefits']);
	$wellness              = killstring($_REQUEST['wellness']);
	$semi_annual_surcharge      = killstring($_REQUEST['semi_annual_surcharge']);
	$quarterly_surcharge        = killstring($_REQUEST['quarterly_surcharge']);
	$monthly_surcharge          = killstring($_REQUEST['monthly_surcharge']);
	
	$company                         = killstring($_REQUEST['company']);
	list($company_id,$company_code)  = explode('|',$company);
	$IsPageValid = true;
	if(empty($company_code)){
	$alert_label = "alert-danger"; 
	$msg   = "Please select company";
	$IsPageValid = false;
	}else{
	//do nothing
	}
   
	if($IsPageValid==true){//valid - true
	                 $sql = "INSERT INTO `tbl_cmt_premium_master` (`company_id`,`premium_name`, `annual_limit`, `geographical_coverage`, `inpatient`,`out_patient`, `physiotherapy`,`emergency_evacuation`,`chronic_conditions`,`pre_existing_cover`,`routine_maternity`,`maternity_waiting_period`,`dental`,`dental_waiting_period`,`optical_benefits`,`wellness`, `semi_annual_surcharge`, `quarterly_surcharge`, `monthly_surcharge`,`is_active`,`entered_by`) VALUES ('".$company_id."','".$premium_name."','".$annual_limit."','".$geographical_coverage."','".$inpatient."','".$out_patient."','".$physiotherapy."','".$emergency_evacuation."','".$chronic_conditions."','".$pre_existing_cover."','".$routine_maternity."','".$maternity_waiting_period."','".$dental."','".$dental_waiting_period."','".$optical_benefits."','".$wellness."','".$semi_annual_surcharge."','".$quarterly_surcharge."','".$monthly_surcharge."',1,'".$userno."')";
					$Query         = mysqli_query($conn,$sql); 
	                if ($Query) {
                        $alert_label = "alert-success"; 
                        $msg = "Premium added successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Error!";
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
                <label class="required">Annual Limit:</label>
                <input type="text" name="annual_limit" class="validate[required,custom[number]] form-control" value="0" placeholder="Annual Limit">
              </div>
              <div class="form-group">
                <label class="required">Inpatient(Hospitalization):</label>
                <input type="text" name="inpatient" class="validate[required] form-control" value="" placeholder="Inpatient (Hospitalization)">
              </div>
              <div class="form-group">
                <label class="required">Physiotherapy:</label>
                <input type="text" name="physiotherapy" class="validate[required] form-control" value="" placeholder="Physiotherapy">
              </div>
              <div class="form-group">
                <label class="required">Chronic Conditions:</label>
                <input type="text" name="chronic_conditions" class="validate[required] form-control" value="" placeholder="Chronic Conditions">
              </div>
              <div class="form-group">
                <label class="required">Routine Maternity:</label>
                <input type="text" name="routine_maternity" class="validate[required] form-control" value="" placeholder="Routine Maternity">
              </div>
              <div class="form-group">
                <label class="required">Dental:</label>
                <input type="text" name="dental" class="validate[required] form-control" value="" placeholder="Dental">
              </div>
              <div class="form-group">
                <label class="required">Optical Benefits:</label>
                <input type="text" name="optical_benefits" class="validate[required] form-control" value="" placeholder="Optical Benefits">
              </div>
               <div class="form-group">
                <label class="required">Semi Annual Surcharge:</label>
                <input type="text" name="semi_annual_surcharge" class="validate[required] form-control" value="" placeholder="Semi Annual Surcharge">
              </div>
              <div class="form-group">
                <label class="required">Monthly Surcharge:</label>
                <input type="text" name="monthly_surcharge" class="validate[required] form-control" value="" placeholder="Monthly Surcharge">
              </div>
               
            </div>
            <div class="col-md-6">
            
             <div class="form-group">
                <label class="required">Premium Name:</label>
                <input type="text" name="premium_name"class="validate[required] form-control" placeholder="Premium Name">
              </div>
              <div class="form-group">
                <label class="required">Geographical Coverage:</label>
                <input type="text" name="geographical_coverage" class="validate[required] form-control" value="" placeholder="Geographical Coverage">
              </div>
              <div class="form-group">
                <label class="required">Out Patient:</label>
                <input type="text" name="out_patient" class="validate[required] form-control" value="" placeholder="Out Patient">
              </div>
              <div class="form-group">
                <label class="required">Emergency Evacuation:</label>
                <input type="text" name="emergency_evacuation" class="validate[required] form-control" value="" placeholder="Emergency Evacuation">
              </div>
              <div class="form-group">
                <label class="required">Pre-existing Cover:</label>
                <input type="text" name="pre_existing_cover" class="validate[required] form-control" value="" placeholder="Pre-existing Cover">
              </div>
              <div class="form-group">
                <label class="required">Maternity Waiting Period:</label>
                <input type="text" name="maternity_waiting_period" class="validate[required] form-control" value="" placeholder="Maternity Waiting Period">
              </div>
               <div class="form-group">
                <label class="required">Dental Waiting Period:</label>
                <input type="text" name="dental_waiting_period" class="validate[required] form-control" value="" placeholder="Dental Waiting Period">
              </div>
              <div class="form-group">
                <label class="required">Wellness:</label>
                <input type="text" name="wellness" class="validate[required] form-control" value="" placeholder="Wellness">
              </div>
                <div class="form-group">
                <label class="required">Quarterly Surcharge:</label>
                <input type="text" name="quarterly_surcharge" class="validate[required] form-control" value="" placeholder="Quarterly Surcharge">
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
