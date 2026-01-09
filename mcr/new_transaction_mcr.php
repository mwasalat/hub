<?php
require_once("../header_footer/header.php"); 
require_once('../plugins/tcpdf/config/lang/eng.php');
require_once('../plugins/tcpdf/tcpdf.php');
require('../plugins/tcpdf/htmlcolors.php');
$msg="";
function numberTowords($num){
$ones = array(
0 =>"ZERO",
1 => "ONE",
2 => "TWO",
3 => "THREE",
4 => "FOUR",
5 => "FIVE",
6 => "SIX",
7 => "SEVEN",
8 => "EIGHT",
9 => "NINE",
10 => "TEN",
11 => "ELEVEN",
12 => "TWELVE",
13 => "THIRTEEN",
14 => "FOURTEEN",
15 => "FIFTEEN",
16 => "SIXTEEN",
17 => "SEVENTEEN",
18 => "EIGHTEEN",
19 => "NINETEEN",
"014" => "FOURTEEN"
);
$tens = array( 
0 => "ZERO",
1 => "TEN",
2 => "TWENTY",
3 => "THIRTY", 
4 => "FORTY", 
5 => "FIFTY", 
6 => "SIXTY", 
7 => "SEVENTY", 
8 => "EIGHTY", 
9 => "NINETY" 
); 
$hundreds = array( 
"HUNDRED", 
"THOUSAND", 
"MILLION", 
"BILLION", 
"TRILLION", 
"QUARDRILLION" 
); /*limit t quadrillion */
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr,1); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){
while(substr($i,0,1)=="0")
$i=substr($i,1,5);
if($i < 20){ 
/* echo "getting:".$i; */
$rettxt .= $ones[$i]; 
}elseif($i < 100){ 
if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
}else{ 
if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
} 
if($key > 0){ 
$rettxt .= " ".$hundreds[$key]." "; 
}
} 
if($decnum > 0){
$rettxt .= " AND ";
if($decnum < 20){
$rettxt .= $ones[$decnum];
}elseif($decnum < 100){
$rettxt .= $tens[substr($decnum,0,1)];
$rettxt .= " ".$ones[substr($decnum,1,1)];
}
}
return $rettxt;
}
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$mcr_region         = killstring($_REQUEST['mcr_region']);
	$customer_type      = killstring($_REQUEST['customer_type']);
	$phone              = killstring($_REQUEST['phone']);
	$invoice            = killstring($_REQUEST['invoice']);
	$cq_mode            = killstring($_REQUEST['cq_mode']);
	$cq_bank            = killstring($_REQUEST['cq_bank']);
	$amount             = killstring($_REQUEST['amount']);
	$amount             = !empty($amount) ? $amount : 0;
	$mcr_company        = killstring($_REQUEST['mcr_company']);
	$customer           = killstring($_REQUEST['customer']);
	$email              = killstring($_REQUEST['email']);
	$payment_mode       = killstring($_REQUEST['payment_mode']);
	$cq_cheque_no       = killstring($_REQUEST['cq_cheque_no']);
	
	$cq_cheque_date     = killstring($_REQUEST['cq_cheque_date']);
	$cq_cheque_date_chk = !empty($cq_cheque_date) ? date('d-m-Y',strtotime($cq_cheque_date)) : NULL;
	$cq_cheque_date     = !empty($cq_cheque_date) ? date('Y-m-d',strtotime($cq_cheque_date)) : NULL;
    $cq_cheque_date     = !empty($cq_cheque_date) ? "'$cq_cheque_date'" : "NULL";
	
	$cc_type            = killstring($_REQUEST['cc_type']);
	
	$bank_tarnsfer_date = killstring($_REQUEST['bank_tarnsfer_date']);
	$bank_tarnsfer_date = !empty($bank_tarnsfer_date) ? date('Y-m-d',strtotime($bank_tarnsfer_date)) : NULL;
	$bank_tarnsfer_date_chk = !empty($bank_tarnsfer_date) ? date('d-m-Y',strtotime($bank_tarnsfer_date)) : NULL;
    $bank_tarnsfer_date = !empty($bank_tarnsfer_date) ? "'$bank_tarnsfer_date'" : "NULL";
	
	$pri_cust_id        = killstring($_REQUEST['pri_cust_id']);
	$pscode             = killstring($_REQUEST['pscode']);
	$glcod              = killstring($_REQUEST['glcod']);
	$salmn              = killstring($_REQUEST['salmn']);
	$salmnid            = killstring($_REQUEST['salmnid']);
	$remarks            = killstring($_REQUEST['remarks']);
	$pm = "";
	if($payment_mode == "CHEQUE"){
    $pm = $payment_mode."-".$cq_mode;
    $flagchq = true;
    }else{
	$pm = $payment_mode;	
	}

	$IsPageValid = true;	
	if(empty($mcr_region)){
	$msg   = "Please select mcr region!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	/***********Serial No***********/
        $query = mysqli_query($conn,"SELECT `auh_pdc`,`auh_rest`,`dxb_pdc`,`dxb_rest`,`company_logo`,`company_name`,`auh_ccmails`,`other_ccmails` FROM `tbl_mcr_company_master` where `company_id`='$mcr_company'");
        $row   = mysqli_fetch_row($query);
        $ordervalue=0;
		$bcc="";
        $auh_pdc            =   $row['0'];
        $auh_rest           =   $row['1'];
		$dxb_pdc            =   $row['2'];
		$dxb_rest           =   $row['3'];
		$company_logo       =   $row['4'];
		$company_name_active=   $row['5'];
		$auh_ccmails        =   $row['6'];
		$other_ccmails      =   $row['7'];
		if($mcr_region=='AUH'){
		$bcc = $auh_ccmails;	
		}else{
		$bcc = $other_ccmails;		
		}
		if($mcr_region=='AUH' && $cq_mode=='PDC'){//AUH & PDC
			    /******************Auto MCR no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `auh_pdc`+1 as `id`,`company_code` FROM `tbl_mcr_company_master` WHERE `company_id`='$mcr_company'");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$company_code  = $rowQryA[1];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_mcr_company_master` SET `auh_pdc`='$value2A' WHERE `company_id`='$mcr_company'");
					$ordervalue    = $company_code."-".$value2A;
				/**************************************************/
		}else if($mcr_region=='AUH' && $cq_mode!='PDC'){//AUH & !PDC
			    /******************Auto MCR no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `auh_rest`+1 as `id`,`company_code` FROM `tbl_mcr_company_master` WHERE `company_id`='$mcr_company'");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$company_code  = $rowQryA[1];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_mcr_company_master` SET `auh_rest`='$value2A' WHERE `company_id`='$mcr_company'");
					$ordervalue    = $company_code."-".$value2A;
				/**************************************************/
		}else if($mcr_region=='DXB' && $cq_mode=='PDC'){//DXB & PDC
			    /******************Auto MCR no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `dxb_pdc`+1 as `id`,`company_code` FROM `tbl_mcr_company_master` WHERE `company_id`='$mcr_company'");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$company_code  = $rowQryA[1];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_mcr_company_master` SET `dxb_pdc`='$value2A' WHERE `company_id`='$mcr_company'");
					$ordervalue    = $company_code."-".$value2A;
				/**************************************************/
		}else if($mcr_region=='DXB' && $cq_mode!='PDC'){//DXB & !PDC
			    /******************Auto MCR no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `dxb_rest`+1 as `id`,`company_code` FROM `tbl_mcr_company_master` WHERE `company_id`='$mcr_company'");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$company_code  = $rowQryA[1];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_mcr_company_master` SET `dxb_rest`='$value2A' WHERE `company_id`='$mcr_company'");
					$ordervalue    = $company_code."-".$value2A;
				/**************************************************/
		}else{
			//do nothing
			exit();
		}
	/***********Serial No***********/	
	if(!empty($cq_mode) && $payment_mode == 'CQ'){
        $trans_type = $cq_mode;
    }else{
        $trans_type = $pm;
    }
	/******user email*********/
	$QuA         = mysqli_query($conn,"SELECT `email` FROM `tbl_login` WHERE `user_id`='$_SESSION[userno]'");
	$rowQ        = mysqli_fetch_row($QuA);
	$email_user  = $rowQ[0];
					
	      $sql = "INSERT INTO `tbl_mcr_transactions`( `trans_sl_no`,`location`,`customername`,`customer_phone`, `trans_type`, `emailid`, `chqnumber`,`chqdate`, `bankname`, `invoicenumber`, `amount`,`messenger`,`salesmanname`,salesmancode,pscode,glcode,`company`,`mcrstatus`,`remarks`, `entered_by`,`entered_date`) VALUES ('$ordervalue','$mcr_region','$customer','$phone','$trans_type','$email','$cq_cheque_no',$cq_cheque_date,'$cq_bank','$invoice','$amount','$email_user','$salmn','$salmnid','$pscode','$glcod','$mcr_company',1,'$remarks','$userno',NOW())";
		  $result  = mysqli_query($conn,$sql); 
		  if($result){
		  include_once("mcr_pdf.php");	  
		  $msg = "MCR Transaction added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

?>
<style type="text/css">
#customer-list {
    float: left;
    list-style: none;
    margin-top: -3px;
    padding: 0;
   /* width: 250px;*/
    position: absolute;
	height:250px;
	overflow:scroll;
}

#customer-list li {
    padding: 10px;
    background: #f0f0f0;
    border-bottom: #bbb9b9 1px solid;
}

#customer-list li:hover {
    background: #ece3d2;
    cursor: pointer;
}

#customer{
    padding: 10px;
    border: #a8d4b1 1px solid;
    border-radius: 4px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>MCR - Transaction</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">MCR - Transaction</li>
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
          <h3 class="box-title">MCR - Transaction</h3>
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
                <label class="required">Region:</label>
                <select class="validate[required] form-control select2" name="mcr_region" id="mcr_region">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `emirates_id`, `emirates_name`, `emirates_code` FROM `tbl_emirates_master` WHERE `is_active`=1 AND `emirates_code` IN ('AUH','DXB') order by `emirates_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['emirates_code']?>">
                  <?=$b1['emirates_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Customer Type:</label>
                <div class="clear"></div>
                <input type="radio" name="customer_type" value="0" checked="checked"/>
                &nbsp;&nbsp;Existing&nbsp;&nbsp;
                <input type="radio" name="customer_type" value="1"/>
                &nbsp;&nbsp;Other Payments </div>
              <div class="form-group">
                <label class="required">Phone:</label>
                <input type="text" class="validate[required] form-control" name="phone" id="phone" placeholder="phone"/>
              </div>
              <div class="form-group">
                <label class="required">Invoice#:</label>
                <input type="text" class="validate[required] form-control" name="invoice" placeholder="Invoice"/>
              </div>
              <div class="form-group cq_div" style="display:none;">
                <label class="required">Cheque Mode:</label>
                <div class="clear"></div>
                <input type="radio" name="cq_mode" value="CDC" checked="checked"/>
                &nbsp;&nbsp;CDC&nbsp;&nbsp;
                <input type="radio" name="cq_mode" value="PDC"/>
                &nbsp;&nbsp;PDC </div>
              <div class="form-group cq_div bank_div"  style="display:none;">
                <label class="required">Bank:</label>
                <input type="text" class="validate[required] form-control" name="cq_bank" placeholder="Bank"/>
              </div>
              <div class="form-group">
                <label class="required">Amount:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="amount" placeholder="Amount"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Company:</label>
                <select class="validate[required] form-control select2" name="mcr_company" id="mcr_company">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_name`, `company_id` FROM `tbl_mcr_company_master` WHERE `is_active`=1 order by `company_name`");
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
              <div class="form-group frmSearch">
                <label class="required">Customer:</label>
                <input type="text" class="validate[required] form-control" name="customer" id="customer" placeholder="Customer" onkeyup="check_customer(this.value)" />
                <div id="suggesstion-box"></div>
              </div>
              <div class="form-group">
                <label class="required">Email:</label>
                <input type="text" class="validate[required,custom[email]] form-control" name="email" placeholder="Email"/>
              </div>
              <div class="form-group">
                <label class="required">Payment Mode:</label>
                <div class="clear"></div>
                <input type="radio" name="payment_mode" value="CASH" checked="checked"/>
                &nbsp;&nbsp;CASH&nbsp;&nbsp;
                <input type="radio" name="payment_mode" value="CHEQUE"/>
                &nbsp;&nbsp;CQ&nbsp;&nbsp;
                <input type="radio" name="payment_mode" value="CREDIT CARD"/>
                &nbsp;&nbsp;CC&nbsp;&nbsp;
                <input type="radio" name="payment_mode" value="BANK TRANSFER"/>
                &nbsp;&nbsp;BANK&nbsp;&nbsp; </div>
              <div class="form-group cq_div"  style="display:none;">
                <label class="required">Cheque Number:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="cq_cheque_no" placeholder="Cheque Number"/>
              </div>
              <div class="form-group cq_div" style="display:none;">
                <label class="required">Cheque Date:</label>
                <input type="text" class="validate[required] form-control datepickerA" name="cq_cheque_date" placeholder="Cheque Date"/>
              </div>
              <div class="form-group cc_div" style="display:none;">
                <label class="required">CC Type:</label>
                <div class="clear"></div>
                <input type="radio" name="cc_type" value="Amex" checked="checked"/>
                &nbsp;&nbsp;Amex&nbsp;&nbsp;
                <input type="radio" name="cc_type" value="Master Card"/>
                &nbsp;&nbsp;Master Card&nbsp;&nbsp;
                <input type="radio" name="cc_type" value="Visa"/>
                &nbsp;&nbsp;Visa&nbsp;&nbsp;
                <input type="radio" name="cc_type" value="Discover"/>
                &nbsp;&nbsp;Discover&nbsp;&nbsp; </div>
              <div class="form-group bank_div" style="display:none;">
                <label class="required">Transfer Date:</label>
                <input type="text" class="validate[required] form-control datepickerA" name="bank_tarnsfer_date" placeholder="Transfer Date"/>
              </div>
              
                <div class="form-group">
                <label class="">Remarks:</label>
                <textarea name="remarks" class="form-control" rows="4" placeholder="Remarks"></textarea>
              </div>
              
              
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
             <input type="hidden" name="pri_cust_id" id="pri_cust_id" value="" >
             <input type="hidden" name="pscode" id="pscode" value="" >
             <input type="hidden" name="glcod" id="glcod" value="" >
             <input type="hidden" name="salmn" id="salmn" value="" >
             <input type="hidden" name="salmnid" id="salmnid" value="" >
            <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
            <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
          </div>
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
});
</script>
<script>
  $(function () {
	$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
    startDate: '-0d',
	autoclose: true
	});
	$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
	/************Payment Mode Change******/
	$('input[type=radio][name=payment_mode]').change(function() {
	$(".cq_div").hide();  
	$(".cc_div").hide();  
	$(".bank_div").hide(); 														  
    if (this.value == 'CHEQUE') {
    $(".cq_div").show();
    }else if (this.value == 'CREDIT CARD') {
    $(".cc_div").show();
    }else if (this.value == 'BANK TRANSFER') {
    $(".bank_div").show();
    }else{
	$(".cq_div").hide();  
	$(".cc_div").hide();  
	$(".bank_div").hide(); 
	}
	});
	/************Payment Mode Change******/
  })
  	/**********Fleet Customer Exist Or Not**************/
	function check_customer(val){
	/*$("#customer").val('');
	$("#phone").val('');
	$('#pri_cust_id').val('');
    $('#pscode').val('');
    $('#glcod').val('');
	$("#suggesstion-box").hide();*/
		var customer_type = $('input[type=radio][name=customer_type]').val();
		var customer = val;
		if(customer.length > 3 && customer_type==0){
			  $.ajax({
				type: "POST",
				url: "../ajax/ajax_mcr_customer_exist_or_not.php",
				data: {flag: customer},
				beforeSend: function() {
					$("#customer").css("background", "#FFF url(../images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data) {
					$("#suggesstion-box").show();
					$("#suggesstion-box").html(data);
					$("#customer").css("background", "#FFF");
				}
			});
			/*  
			var xmlhttp=new XMLHttpRequest();
			xmlhttp.open("GET","../ajax/ajax_mcr_customer_exist_or_not.php?flag="+customer,false);
			xmlhttp.send(null);
			if($.trim(xmlhttp.responseText)!='' || $.trim(xmlhttp.responseText)!=null ){
				$("#suggesstion-box").show();
				$("#suggesstion-box").html($.trim(xmlhttp.responseText));
				$("#customer").css("background", "#FFF");
			}*/	
		}else{
		//do nothing	
		}
	}
	/**********Fleet Customer Exist Or Not**************/
function selectCustomer(pri_cust_id,pscode,fname,glcod,phone1,salmn,salmnid) {
    $("#customer").val(fname);
	$("#phone").val(phone1);
	$('#pri_cust_id').val(pri_cust_id);
    $('#pscode').val(pscode);
    $('#glcod').val(glcod);
	$('#salmn').val(salmn);
	$('#salmnid').val(salmnid);
    $("#suggesstion-box").hide();
}	
</script>