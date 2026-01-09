<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php');  
/*--------NEWLY ADDED ENDS HERE----------*/
 // for refreshing...
    $refreshflag=0;
    if (!isset($_POST['refresh1']))
    {
		   $r_random = rand(10,1000);
		   $_SESSION['r_random']=$r_random;
    }
    else
	   if($_POST['refresh1']==$_SESSION['r_random'])
		 {  
		    $r_random = rand(10,1000);
		    $_SESSION['r_random']=$r_random;
		    $refreshflag=3;
		 }  
    $userno           = $_SESSION['userno'];   
	$empid            = $_SESSION['empid'];
	$today            = date('Y-m-d H:i:s');
	$to_date          = date('Y-m-d'); 

/*$sqlPic      = mysqli_query($conn,"SELECT `profile_photo` FROM `tbl_login` WHERE `user_id`='$userno'"); 
$rowPic      = mysqli_fetch_row($sqlPic);  
$userPic     = $rowPic['0']; 
$userPic_N   = ""; 
if(!empty($userPic) && file_exists("../uploads/profile_photo/".$userPic)){
$userPic_N    = "../uploads/profile_photo/".$userPic;
}else{
$userPic_N    = "../images/profile/user2-160x160.jpg";	
}	*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>HUB | ENG</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- Select 2 -->
<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
<!-- jQuery-Validation-Engine-master -->
<link rel="stylesheet" href="../plugins/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" type="text/css"/>
<!-- DataTables -->
<link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="shortcut icon" href="../images/favicon.ico" type="image/vnd.microsoft.icon" />
<style type="text/css">
#quote b,br,i{
	display:none;
}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<!-- =============================================== -->
<!-- Left side column. contains the sidebar -->
<!-- =============================================== -->
<?php
$today        = date('Y-m-d');
$msg          = "";
$flag         = !empty($_REQUEST['flag'])?killstring($_REQUEST['flag']):NULL;
if ($refreshflag==3){
	if(isset($_POST['submit'])){
    $customer_full_name  = killstring($_REQUEST['customer_full_name']);	
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
	$dependent             = $_REQUEST['additionl_customer_full_name'];
	$cnt_dependent         = count($dependent);
	/************Dependent*********/
	
	/*************Quotation details******************/
	$flagNow             = killstring($_REQUEST['flagNow']);
	list($unique,$quotation_id) = explode("|",$flagNow);
    $quotation_link      =  "http://mcr.enguae.com/hub/qtn_entry?flag=".$flagNow;
	//$quotation_id        = killstring($_REQUEST['quotation_id']);
	$quotation_currency  = killstring($_REQUEST['quotation_currency']);
	//$quotation_link      = killstring($_REQUEST['quotation_link']);
	$advisor_name        = killstring($_REQUEST['advisor_name']);
	$advisor_email       = killstring($_REQUEST['advisor_email']);
	$quotation_timestamp = date('Y-m-d h:i:s');
	/*************Quotation details******************/
	$select_plan          = $_REQUEST['select_plan'];
	$IsPageValid = true;
	if(empty($customer_full_name)){
	$msg   = "Please enter customer name!";
	$alert_label = "alert-danger"; 
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
				$MyQuery  = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_premium_transactions` (`tr_no`,`customer_name`, `customer_phone`,`customer_email`, `customer_nationality`, `customer_gender`, `customer_emirate_id`, `customer_dob`, `customer_age`, `customer_marital_status`,`customer_relation`,`ins_start_date`, `quotation_id`,`quotation_currency`,`quotation_link`,`advisor_name`,`advisor_email`,`quotation_timestamp`,`is_active`) VALUES ('".$ordervalueA."','".$customer_full_name."','".$customer_phone."','".$customer_email."','".$nationality."','".$gender."','".$location_active."','".$dob."','".$age_primary."','".$marital_status."','".$relation."','".$start_date."','".$quotation_id."','".$quotation_currency."','".$quotation_link."','".$advisor_name."','".$advisor_email."','".$quotation_timestamp."',2)"); 
				if(empty($MyQuery)){$error_cnt++;}//error query - tbl_cmt_ind_premium_transactions
				$last_id  = mysqli_insert_id($conn);
				If($MyQuery){
					/***********premium amounts*******/
					/***********premium amounts*******/
					/**********Dependent Addition****************/
					 if(!empty($cnt_dependent)){
					 for($j=0; $j < $cnt_dependent; $j++){
						 $dependent_name             = killstring($_REQUEST['additionl_customer_full_name'][$j]);
						 $dependent_gender           = killstring($_REQUEST['additionl_gender'][$j]);
						 $dependent_marital_status   = killstring($_REQUEST['additionl_marital_status'][$j]);
						 $dependent_dob              = date('Y-m-d',strtotime($_REQUEST['additionl_dob'][$j]));
						 $dependent_dob_year         = date('Y',strtotime($dependent_dob));
						 $year                       = date('Y');
						 $age_dependent              = ($year - $dependent_dob_year);
						 $MyQueryA                   = mysqli_query($conn,"INSERT INTO `tbl_cmt_ind_dependants` (`ind_premium_id`,`tr_no`,`dependent_name`, `dependent_gender`,`dependent_marital_status`, `dependent_dob`, `dependent_age`, `is_active`) VALUES ('".$last_id."','".$ordervalueA."','".$dependent_name."','".$dependent_gender."','".$dependent_marital_status."','".$dependent_dob."','".$age_dependent."',1)"); 
						 if(empty($MyQueryA)){$error_cnt++;}//error query - tbl_cmt_ind_dependants
						 $dependent_last_id  = mysqli_insert_id($conn);
						 /***********premium amounts*******/
							/***********premium amounts*******/
					 }
					 }
					/**********Dependent Addition****************/ 
					/**********Insurance Premium Comapny Selection****************/
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


 /* $QueryCounter =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` FROM `tbl_cmt_quotation_no`");
  $rowQry       =  mysqli_fetch_row($QueryCounter);
  $value        =  $rowQry[0];
  $QueryCounter =  mysqli_query($conn,"UPDATE `tbl_cmt_quotation_no` SET `auto_id`='$valueC'");
  $ordervalue   =  uniqid()."|".$value;
  $qtn_link     =  "http://mcr.enguae.com/hub/qtn_entry?flag=".$ordervalue;*/
?>
<!-- Content Wrapper. Contains page content -->
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper" style="margin-left:0px;">
  <!-- Content Header (Page header) -->
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
          <div class="row">
            <div class="box box-default">
              <div class="box-body">
                <!------------------------New Customer--------->
                <div class="row col-md-12 new_customer">
                  <h4>Primary Person</h4>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Full Name:</label>
                      <input type="text" class="validate[required] form-control" name="customer_full_name" value="" placeholder="Full Name"/>
                    </div>
                    <div class="form-group">
                      <label class="">Nationality:</label>
                      <select class="validate[required] form-control select" name="nationality" id="nationality" style="width:100%;">
                        <?php  
                      $d1 = mysqli_query($conn,"SELECT `name`, `id` FROM `tbl_country` WHERE `is_active`=1 order by `name`");
                      while($b1 = mysqli_fetch_array($d1)){
                      ?>
                        <option value="<?=$b1['id']?>">
                        <?=$b1['name']?>
                        </option>
                        <?php		
                      } 
                     ?>
                      </select>
                    </div>
                    <div class="form-group"> 
                      <label class="">DOB:</label>
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="dob" class="validate[required] datepickerA form-control" value=""  placeholder="DOB"/>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="">Relation :</label>
                      <select class="validate[required] form-control select" name="relation" style="width:100%;">
                        <option value="P">Primary</option>
                        <option value="F">Dependent</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Email Address:</label>
                      <input type="text" class="validate[required,custom[email]] form-control" name="customer_email" placeholder="Email Address"/>
                    </div>
                    <div class="form-group">
                      <label class="">Gender:</label>
                      <select class="validate[required] form-control select" name="gender" id="gender" style="width:100%;">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="">Marital Status:</label>
                      <select class="validate[required] form-control select" name="marital_status" id="marital_status" style="width:100%;">
                        <option value="M">Married</option>
                        <option value="S">Single</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="required">Phone Number:</label>
                      <input type="text" class="validate[required,custom[phone]] form-control" name="customer_phone" placeholder="Phone Number" maxlength="20"/>
                    </div>
                    <div class="form-group">
                      <label class="">Residency:</label>
                      <select class="validate[required] form-control select" name="residency" id="residency" style="width:100%;">
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
                    <div class="form-group">
                      <label class="">Start Date:</label>
                      <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="start_date" class="validate[required] datepickerB form-control" value="" placeholder="Start Date"/>
                      </div>
                    </div>
                  </div>
                  
                </div>
                <!------------------------New Customer--------->
                <!----------------Additional Customer------------------------------------->
                <h4>Additional Members</h4>
                <div id="additional_customer_section"></div>
                <button type="button" class="btn btn-default pull-left" name="additional_customer" id="additional_customer" onclick="add_additional_members()">Add Another</button>
                <!----------------Additional Customer------------------------------------->
                
              </div>
              <div class="box-footer">
                 <input type="hidden" name="flagNow" value="<?=$flag?>"/>
                 <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
                <button type="submit" class="btn btn-primary pull-right" name="submit" id="submit">Submit</button>
              </div>
              <hr/>
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
<footer class="main-footer" style="margin-left:0px;">
  <div class="pull-left hidden-xs"> <b>Version</b> 1.5.1 </div>
  <strong>Copyright &copy; 2023 <a href="https://enguae.com/" target="_blank">ENG GROUP</a>.</strong> All rights
  reserved. </footer>
</div>
<!-- ./wrapper -->
<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
  })
</script>
<!---------------------------- jquery.validation Engine -------------->
<script src="../plugins/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="../plugins/jQuery-Validation-Engine-master/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!----------------------End of jquery.validation engine--------------->
<!-- bootstrap datepicker -->
<script type="text/javascript" src="../plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- noty -->
<script type="text/javascript" src="../plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="../plugins/noty/layouts/topCenter.js"></script>
<script type="text/javascript" src="../plugins/noty/layouts/topLeft.js"></script>
<script type="text/javascript" src="../plugins/noty/layouts/topRight.js"></script>
<script type="text/javascript" src="../plugins/noty/themes/default.js"></script>
<!-- noty -->
<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!--<script src="../dist/js/demo.js"></script>-->
<script type="text/javascript">
// Top Notificator
function ShowNotificator(add_class, the_text) {
    $('div#notificator').text(the_text).addClass(add_class).slideDown('slow').delay(2000).slideUp('slow', function () {
        $(this).removeClass(add_class).empty();
    }); 
}
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    $('[data-mask]').inputmask();

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  })
</script>
<style type="text/css">
#notificator { display : none; left : 50%; margin-left : -100px; padding : 15px 25px; position : fixed; text-align : center; top : 20px; width : 200px; z-index : 5000; }
</style>
</body>
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
</script>
</html>