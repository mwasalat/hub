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
	$premium_id          = killstring($_REQUEST['premium_id']);
	$company             = killstring($_REQUEST['company']);
	
	$copay_id            = killstring($_REQUEST['copay_id']);
	$ac_id               = killstring($_REQUEST['ac_id']);
	$np_id               = killstring($_REQUEST['np_id']);
	
	$enhanced_maternity  = killstring($_REQUEST['enhanced_maternity']);
	$enhanced_maternity_amount  = killstring($_REQUEST['enhanced_maternity_amount']);
	$enhanced_maternity_amount  = !empty($enhanced_maternity_amount)?$enhanced_maternity_amount:0;
	$wellness_covered    = killstring($_REQUEST['wellness_covered']);
	$wellness_covered_amount    = killstring($_REQUEST['wellness_covered_amount']);
	$wellness_covered_amount    = !empty($wellness_covered_amount)?$wellness_covered_amount:0;
	$dental_covered      = killstring($_REQUEST['dental_covered']);
	$dental_covered_amount    = killstring($_REQUEST['dental_covered_amount']);
	$dental_covered_amount    = !empty($dental_covered_amount)?$dental_covered_amount:0;
	$optical_covered     = killstring($_REQUEST['optical_covered']);
	$optical_covered_amount    = killstring($_REQUEST['optical_covered_amount']);
	$optical_covered_amount    = !empty($optical_covered_amount)?$optical_covered_amount:0;
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
	if(empty($premium_id)){
	$alert_label = "alert-danger"; 
	$msg   = "Please select premium!";
	$IsPageValid = false;
	}else{
	//do nothing
	}
   
	if($IsPageValid==true){//valid - true
				//$Query         = mysqli_query($conn,"INSERT INTO `tbl_cmt_ins_price_batch_master` (`tr_no`,`company_id`, `premium_id`, `emirate_id`, `copay_id`, `np_id`,`ac_id`,`start_date`, `end_date`,`batch_name`, `enhanced_maternity`,`enhanced_maternity_amount`,`wellness_covered`,`wellness_covered_amount`,`dental_covered`,`dental_covered_amount`,`optical_covered`,`optical_covered_amount`,`customer_type`, `is_active`,`entered_by`) VALUES ('".$ordervalueA."','".$company_id."','".$premium_id."','".$location_comma_value."','".$copay_id."','".$np_id."','".$ac_id."','".$start_date."','".$end_date."','".$batch_name."','".$enhanced_maternity."','".$enhanced_maternity_amount."','".$wellness_covered."','".$wellness_covered_amount."','".$dental_covered."','".$dental_covered_amount."','".$optical_covered."','".$optical_covered_amount."',0,1,'".$userno."')"); 
				$last_id  = mysqli_insert_id($conn);
		/*******ERROR CHECK****************/
	                if ($Query) {
                        $alert_label = "alert-success"; 
                        $msg = "Updated successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Updated not successfully!";
                   }
	   /**********************************/
   }//valid rue	 	
 }
}
$price_id = killstring($_REQUEST['flag']);
if(!empty($price_id)){
    $query = mysqli_query($conn,"SELECT * FROM `tbl_cmt_ins_price_batch_master` WHERE `price_batch_id`='$price_id'");
	while($build = mysqli_fetch_array($query)){ 
	$price_batch_id    = $build['price_batch_id'];
	$tr_no             = $build['tr_no'];
	$company_id        = $build['company_id'];
	$premium_id        = $build['premium_id'];
	$emirate_id        = explode(',',$build['emirate_id']);
	$copay_id          = $build['copay_id'];
	$np_id             = $build['np_id'];
	$ac_id             = $build['ac_id'];
	$enhanced_maternity         = $build['enhanced_maternity'];
	$enhanced_maternity_amount  = $build['enhanced_maternity_amount'];
	$wellness_covered           = $build['wellness_covered'];
	$wellness_covered_amount    = $build['wellness_covered_amount'];
	$dental_covered             = $build['dental_covered'];
	$dental_covered_amount      = $build['dental_covered_amount'];
	$optical_covered            = $build['optical_covered'];
	$optical_covered_amount     = $build['optical_covered_amount'];
	$is_active                  = $build['is_active'];
	$start_date = date('d-m-Y',strtotime($build['start_date'])); 
	$end_date   = date('d-m-Y H:i:s',strtotime($build['end_date'])); 
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
                <select class="validate[required] form-control select" name="company" id="company" style="width:100%;"  disabled="disabled">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_name`, `company_code`, `company_id` FROM `tbl_cmt_ins_company_master` WHERE `company_id`='$company_id'");
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
                  <input type="text" name="start_date" class="validate[required] datepickerA form-control" id="start_date" placeholder="Start Date" value="<?=$start_date?>"  readonly/>
                </div>
              </div>
            
            <div class="form-group">
                <label class="required">Emirates:</label>
                <select class="validate[required] form-control select" name="location[]" id="location" style="width:100%;" multiple="multiple" disabled="disabled">
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `emirates_name`, `emirates_id` FROM `tbl_emirates_master` WHERE `is_active`=1 order by `emirates_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['emirates_id']?>" <?php if(in_array($b1['emirates_id'],$emirate_id)){echo "selected";}?>><?=$b1['emirates_name']?></option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
                <div class="form-group">
                <label class="required">Area Of Cover:</label>
                <div id="ac_select_div">
                  <select class="validate[required] form-control select" name="ac_id" id="ac_id" style="width:100%;" disabled="disabled">
                  <?php
				   $rres4=  mysqli_query($conn, "SELECT `ac_id`,`ac_name` FROM `tbl_cmt_area_of_cover` WHERE `ac_id`='$ac_id'");
					if(mysqli_num_rows($rres4)>0){
						while ($rrow4=  mysqli_fetch_array($rres4)){
							echo "<option value='$rrow4[ac_id]'><strong>$rrow4[ac_name]</strong></ul>";
						}
					}
				  ?>
                  </select>
               </div>   
              </div>
              
               <div class="form-group" >
                <label class="required">Enhanced Maternity:</label>
                  <input type="radio" name="enhanced_maternity" value="1"/>&nbsp;Yes &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="enhanced_maternity" value="0" checked="checked"/>&nbsp;No &nbsp;&nbsp;&nbsp;
                  <input type="text" name="enhanced_maternity_amount" id="enhanced_maternity_amount" class="validate[required,custom[number]] form-control" style="width:50%;display:none;" placeholder="Enhanced Maternity Amount *"/>
              </div>
              
              <div class="form-group" >
                <label class="required">Wellness Covered:</label>
                  <input type="radio" name="wellness_covered" value="1" />&nbsp;Yes &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="wellness_covered" value="0" checked="checked"/>&nbsp;No &nbsp;&nbsp;&nbsp;
                  <input type="text" name="wellness_covered_amount" id="wellness_covered_amount" class="validate[required,custom[number]] form-control" style="width:50%;display:none;" placeholder="Wellness Covered Amount *" />
              </div>       
                            
            </div>
            <div class="col-md-6">
               <div class="form-group">
                <label class="required">Premium:</label>
                <div id="premium_select_div">
                  <select class="validate[required] form-control select" name="premium_id" id="premium_id" style="width:100%;"  disabled="disabled">
                  <?php
                   $rres4=  mysqli_query($conn, "SELECT `premium_id`,`premium_name` FROM `tbl_cmt_premium_master` WHERE `premium_id`='$premium_id'");
					if(mysqli_num_rows($rres4)>0){
						while ($rrow4=  mysqli_fetch_array($rres4)){
							echo "<option value='$rrow4[premium_id]'><strong>$rrow4[premium_name]</strong></ul>";
						}
					}
					?>
                  </select>
               </div>   
              </div>
              <div class="form-group">
                <label class="required">End Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" name="end_date" class="validate[required] datepickerA form-control" id="end_date"  placeholder="End Date" value="<?=$end_date?>" readonly="readonly"/>
                </div>
              </div>
              
              <div class="form-group">
                <label class="required">Network Provider:</label>
                <div id="np_select_div">
                  <select class="validate[required] form-control select" name="np_id" id="np_id" style="width:100%;" disabled="disabled">
                  <?php
				    $rres4=  mysqli_query($conn, "SELECT `np_id`,`np_name` FROM `tbl_cmt_network_providers` WHERE `np_id`='$np_id'");
					if(mysqli_num_rows($rres4)>0){
					
						while ($rrow4=  mysqli_fetch_array($rres4)){
							echo "<option value='$rrow4[np_id]'><strong>$rrow4[np_name]</strong></ul>";
						}
					}
					?>
                  </select>
               </div>   
              </div>
              
              
              <div class="form-group">
                <label class="required">Co Pay:</label>
                <div id="copay_select_div">
                  <select class="validate[required] form-control select" name="copay_id" id="copay_id" style="width:100%;" disabled="disabled">
                   <?php
				    $rres4=  mysqli_query($conn, "SELECT `copay_id`,`copay_name` FROM `tbl_cmt_copay_master` WHERE `copay_id`='$copay_id'");
					if(mysqli_num_rows($rres4)>0){
						while ($rrow4=  mysqli_fetch_array($rres4)){
							echo "<option value='$rrow4[copay_id]'><strong>$rrow4[copay_name]</strong></ul>";
						}
					}
					?>
                  </select>
               </div>   
              </div>
              
              <div class="form-group">
                <label class="required">Dental Covered:</label>
                  <input type="radio" name="dental_covered" value="1"/>&nbsp;Yes &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="dental_covered" value="0" checked="checked"/>&nbsp;No&nbsp;&nbsp;&nbsp;
                  <input type="text" name="dental_covered_amount" id="dental_covered_amount" class="validate[required,custom[number]] form-control" style="width:50%;display:none;" placeholder="Dental Covered Amount *" />
              </div>
              
              <div class="form-group">
                <label class="required">Optical Covered:</label>
                  <input type="radio" name="optical_covered" value="1"/>&nbsp;Yes &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="optical_covered" value="0" checked="checked"/>&nbsp;No &nbsp;&nbsp;&nbsp;
                  <input type="text" name="optical_covered_amount" id="optical_covered_amount" class="validate[required,custom[number]] form-control" style="width:50%;display:none;" placeholder="Optical Covered Amount *" />
              </div>
              
             <?php /*?><div class="form-group">
                <label class="required">Batch Name:</label>
                <input type="text" name="batch_name"class="validate[required] form-control" placeholder="Batch Name">
              </div><?php */?>
              
                 
              
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="hidden" name="price_batch_id" value="<?=$price_batch_id?>">
          <input type="hidden" name='refresh1' value= "<?php  echo $_SESSION['r_random']; ?>" >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
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

function change_premium(company_id){
	var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_fill_premium.php?company_id="+company_id,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=''){
	$("#premium_select_div").html("");	
    $("#premium_select_div").html($.trim(xmlhttp.responseText));
    $('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
    }); 
    }
}
function change_copay(company_id){
	var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_fill_copay.php?company_id="+company_id,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=''){
	$("#copay_select_div").html("");	
    $("#copay_select_div").html($.trim(xmlhttp.responseText));
    $('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
    }); 
    }
}

function change_np(company_id){
	var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_fill_np.php?company_id="+company_id,false);
    xmlhttp.send(null); 
    if($.trim(xmlhttp.responseText)!=''){
	$("#np_select_div").html("");	
    $("#np_select_div").html($.trim(xmlhttp.responseText));
    $('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
    }); 
    }
}

function change_ac(company_id){
	var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_fill_areacover.php?company_id="+company_id,false);
    xmlhttp.send(null); 
    if($.trim(xmlhttp.responseText)!=''){
	$("#ac_select_div").html("");	
    $("#ac_select_div").html($.trim(xmlhttp.responseText));
    $('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
    }); 
    }
}

$(document).ready(function(){
//dental_covered						   
$('input[name="dental_covered"]').click(function(){ 
	if($('input[name="dental_covered"]:checked').val()==1){
	$('input[name="dental_covered_amount"]').show();	
	}else{
	$('input[name="dental_covered_amount"]').hide();		
	}
 });
//enhanced_maternity						   
$('input[name="enhanced_maternity"]').click(function(){ 
	if($('input[name="enhanced_maternity"]:checked').val()==1){
	$('input[name="enhanced_maternity_amount"]').show();	
	}else{
	$('input[name="enhanced_maternity_amount"]').hide();		
	}
 });
//wellness_covered						   
$('input[name="wellness_covered"]').click(function(){ 
	if($('input[name="wellness_covered"]:checked').val()==1){
	$('input[name="wellness_covered_amount"]').show();	
	}else{
	$('input[name="wellness_covered_amount"]').hide();		
	}
 });
//optical_covered						   
$('input[name="optical_covered"]').click(function(){ 
	if($('input[name="optical_covered"]:checked').val()==1){
	$('input[name="optical_covered_amount"]').show();	
	}else{
	$('input[name="optical_covered_amount"]').hide();		
	}
 });
});
</script>
