<?php 
require_once("../header_footer/header.php");
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$company          = killstring($_REQUEST['company']);
	$supplier         = killstring($_REQUEST['supplier']);
	$entry_date       = killstring($_REQUEST['entry_date']);
    $entry_date             = !empty($entry_date) ? date('Y-m-d',strtotime($entry_date)) : NULL;
    $entry_date             = !empty($entry_date) ? "'$entry_date'" : "NULL";
	$products_total_value      = killstring($_REQUEST['products_total_value_hidden']);
	$products_total_value       = !empty($products_total_value) ? $products_total_value : 0;
	
	$products_vat_value      = killstring($_REQUEST['products_vat_hidden']);
	$products_vat_value      = !empty($products_vat_value) ? $products_vat_value : 0;
	
	$special_terms           = killstring($_REQUEST['special_terms']);
	$bank_details            = killstring($_REQUEST['bank_details']);
	$currency                = killstring($_REQUEST['currency']);
	$duration                = killstring($_REQUEST['duration']);
	
    $concerned_person         = implode(',',$_REQUEST["concerned_person"]);
	$concerned_person         = !empty($concerned_person) ? "'$concerned_person'" : "NULL";
	$IsPageValid = true;	
	if(empty($company)){
	$msg   = "Please select company!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true
	              /******************ompany Code Find**********/
				 /* $qry1         = mysqli_query($conn,"SELECT `company_code` FROM `tbl_inv_company_master` WHERE `company_id`='$company'");
				  $row          = mysqli_fetch_row($qry1);
				  $company_code= $row['0'];*/
				 /******************ompany Code Find**********/
				 
				 /******************supplier Code Find**********/
				/*  $qry2          = mysqli_query($conn,"SELECT `supplier_code` FROM `tbl_inv_customer_master` WHERE `supplier_id`='$supplier'");
				  $row2          = mysqli_fetch_row($qry2);
				  $supplier_code = $row2['0'];*/
				 /******************supplier Code Find**********/
				  
	             /******************Auto Transaction no***********************/
			        $QueryCounter  =  mysqli_query($conn,"SELECT `auto_id`+1 as `id` from `tbl_inv_transaction_no`");
					$rowQryA       = mysqli_fetch_row($QueryCounter);
					$value2A       = $rowQryA[0];
					$QueryCounterA =  mysqli_query($conn,"UPDATE `tbl_inv_transaction_no` set `auto_id`='$value2A'");
					//$ordervalue    = $company_code."-".$supplier_code."-".$value2A;
					$ordervalue    = "INV/".date('y')."/".$value2A;
				/*********************Auto Transaction no*********************/
		 $sql              = "INSERT INTO `tbl_inv_transactions`(`transaction_no`, `company_id`, `supplier_id`, `date_of_entry`, `total_value`,`vat_value`,`concerned_person`, `special_terms`, `bank_details`, `currency_id`, `duration_id`, `entered_by`) VALUES ('$ordervalue','$company','$supplier',$entry_date,'$products_total_value','$products_vat_value',$concerned_person,'$special_terms','$bank_details','$currency','$duration','$userno')"; 
		  $result           = mysqli_query($conn,$sql);
		  $last_id          = mysqli_insert_id($conn);
		  if($result){
		 /************ITEMS DETAILS**************/
		  $cntC                = 0;
		  $products_description     = $_REQUEST["products_description"];
		  $cntC                = count($products_description);
		  for($i=0; $i < $cntC; $i++){
		   $products_quantity      = killstring($_REQUEST['products_quantity'][$i]);
		   $products_quantity      = !empty($products_quantity) ? $products_quantity : 0;
		   $products_description   = killstring($_REQUEST['products_description'][$i]);
		   $products_unit_price    = killstring($_REQUEST['products_unit_price'][$i]);
		   $products_unit_price    = !empty($products_unit_price) ? $products_unit_price : 0;
		   $products_total_price   = killstring($_REQUEST['products_total_price'][$i]);
		   $products_unit_price    = !empty($products_unit_price) ? $products_unit_price : 0;
		    if(!empty($products_description)){
			$sqlQry           = mysqli_query($conn,"INSERT INTO `tbl_inv_transactions_items`(`transaction_id`, `item_description`, `item_quantity`, `item_unit_value`, `item_total_value`,`entered_by`) VALUES ('$last_id','$products_description','$products_quantity','$products_unit_price','$products_total_price','$userno')");
			}
		 }
		 /************ITEMS DETAILS**************/	  
		  $msg = "Transaction added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> New Invoice</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Invoice</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="trn-form" id="trn-form" method="post" action="#">
      <?php if ($msg) { ?>
      <hr>
      <div class="alert <?=$alert_label?>">
        <?=$msg?>
      </div>
      <hr>
      <?php }?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default" data-select2-id="16">
        <div class="box-header with-border">
          <h3 class="box-title">Invoice</h3>
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
                <select class="validate[required] form-control select2" name="company" id="company">
                  <option selected="selected" value="">---Select a Company---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_name`, `company_id` FROM `tbl_inv_company_master` WHERE `is_active`=1 order by `company_name`");
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
                <label class="required">Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="validate[required] form-control" id="datepicker"  data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" placeholder="dd-mm-yyyy format" value="<?=date('d-m-Y')?>" name="entry_date">
                </div>
              </div>
              
              <div class="form-group">
                <label class="required">Currency:</label>
                <select class="validate[required] form-control select2" name="currency" id="currency">
                  <option selected="selected" value="">---Select a currency---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `currency_id`,`currency_name` FROM `tbl_currency_master`  WHERE `is_active`=1 ORDER BY `currency_name` ASC");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['currency_id']?>">
                  <?=$b1['currency_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Customer:</label>
                <select class="validate[required] form-control select2" name="supplier" id="supplier" onchange="change_concerned_person(this.value)">
                  <option selected="selected" value="">---Select a customer---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `supplier_name`, `supplier_id` FROM `tbl_inv_customer_master` WHERE `is_active`=1 order by `supplier_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['supplier_id']?>">
                  <?=$b1['supplier_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Concerned Person:</label>
                <div id="concerned_person_div">
                <select class="validate[required] form-control select2" name="concerned_person" id="concerned_person">
                <option selected="selected" value="">---Select Concerned Persons---</option>
                </select>
                </div>
              </div>
              
                <div class="form-group">
                <label class="required">Duration:</label>
                <select class="validate[required] form-control select2" name="duration" id="duration">
                  <option selected="selected" value="">---Select a duration---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `duration_id`,`duration_name` FROM `tbl_duration_master`  WHERE `is_active`=1 ORDER BY `duration_name` ASC");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['duration_id']?>">
                  <?=$b1['duration_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
            </div>
          </div>
          <!-- /.row -->
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <hr/>
              <div class="input-group"> <span class="input-group-btn">
                <button class="btn btn-default" type="button" name="feature_btn" onClick="add_items();">+ Add Invoice Item</button>
                </span> </div>
            </div>
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-sriped" role="grid">
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Quantity</th>
                      <th>Unit Value</th>
                      <th>Total Value</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="cbm_tr_features">
                    <?php $unique_id = uniqid();?>
                    <tr id="products_added_service_<?=$unique_id?>">
                      <td width="30%"><input type="text" name="products_description[]" value="" class="validate[required] form-control" id="products_description<?=$unique_id?>" placeholder="Description * "/></td>
                      <td width="20%"><input type="number" name="products_quantity[]" value="1" class="validate[required] form-control" id="products_quantity_<?=$unique_id?>" onChange="products_change_price('<?=$unique_id?>')" placeholder="Quantity *"/></td>
                      <td width="20%"><input type="text" name="products_unit_price[]" value="" class="validate[required] form-control" id="products_unit_price_<?=$unique_id?>" onChange="products_change_price('<?=$unique_id?>')" placeholder="Unit Value *"/></td>
                      <td width="20%"><input type="text" name="products_total_price[]" value="" class="products_total_price form-control" id="products_total_price_<?=$unique_id?>" readonly/></td>
                      <td width="10%"><a href="javascript:void(0);" onClick="products_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash fa-lg"></i></a></td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td><input type="hidden" name="products_sub_total_value_hidden" value="0" id="products_sub_total_value_hidden"/>
                        <b>Sub Total Value</b></td>
                      <td></td>
                      <td></td>
                      <td><span id="products_sub_total_value" style="font-weight:bold; color:#990000;"></span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="5"><label>Apply 5% VAT:&nbsp;&nbsp;
                          <input type="checkbox" id="vat_flag" value="1" onchange="TotalFill();">
                        </label></td>
                    </tr>
                    <tr>
                      <td colspan="5"><label>VAT:&nbsp;&nbsp;
                          <input type="hidden" name="products_vat_hidden" value="0" id="products_vat_hidden"/>
                          <span id="products_vat_value" style="font-weight:bold; color:#990000;">0</span></label></td>
                    </tr>
                    <tr>
                      <td colspan="5"><label>Total Value:&nbsp;&nbsp;
                          <input type="hidden" name="products_total_value_hidden" value="0" id="products_total_value_hidden"/>
                          <span id="products_total_value" style="font-weight:bold; color:#990000;">0</span></label></td>
                    </tr>
                  </tfoot>
                </table>
                <hr/>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Special Terms & Conditions:</label>
                <textarea rows="4" name="special_terms" placeholder="Special Terms & Conditions" class="validate[required] form-control"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Bank Details:</label>
                <textarea rows="4" name="bank_details" placeholder="Bank Details" class="validate[required] form-control"></textarea>
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
//Date picker
$('#datepicker').datepicker({
autoclose: true,
format: 'dd-mm-yyyy'
});
$('#datepicker').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });

function change_concerned_person(val){
	var scntDiv = $('#concerned_person_div');
	$('#concerned_person_div').html("");
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_fill_concerned_person_selectbox.php?flag="+val,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
	$('.select3').select2({
    placeholder: 'Select Options',
    allowClear: true
    });
    }
}
//vat calculation/
/*$(document).ready(function(){
$("#vat_flag").on('change', function() {
  if ($(this).is(':checked')) {
	var total_before_vat = 0;
	var total_after_vat  = 0;
	var vat_amount       = 0;
    total_before_vat = $('#products_total_value_hidden').val();
	vat_amount       = ( total_before_vat * 5 / 100 ).toFixed(2);
	total_after_vat  = parseFloat(total_before_vat) + parseFloat(vat_amount);
	alert(total_after_vat);
	$('#products_total_value_hidden').val(total_after_vat.toFixed(2));
	$('#products_total_value').html(total_after_vat.toFixed(2));
    //$('#products_total_value').html(total_after_vat.toFixed(2));
  } else {
    alert("not checked");
  }
});  
});*/
$(document).ready(function(){
$("#trn-form").validationEngine();
});
/************************ADD Items*******************/
function add_items(){
	var count = $('.features').length;
    if(count<10){
	var scntDiv = $('#cbm_tr_features');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_transaction_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    }
	}else{
	noty({ text: 'Maximum 10 limit reached!', layout: 'topCenter', type: 'error', timeout: 2000 });	
	}
}
//Remove new item
function products_remove_item_added(tr_id){
     var r = confirm("Are you want to remove this item from the list?");
			if (r == true) {
			$('#products_added_service_'+tr_id).remove();
			products_change_price(tr_id);  
          } 
}
/************************End Items*******************/

function products_change_price(input_id){
	var quantity  = 0;
	quantity  = parseFloat($("#products_quantity_"+input_id).val());
    if(quantity!=""){
	var row_total_price = 0;	
	var total_value = 0;
	var total_qty   = 0;
	var unit_price  = 0;
	unit_price  = parseFloat($("#products_unit_price_"+input_id).val());
	row_total_price = unit_price * quantity;
	if(row_total_price=='' || row_total_price==null || isNaN(row_total_price)){
	$('#products_total_price_'+input_id).val(0);
	}else{
    $('#products_total_price_'+input_id).val(row_total_price.toFixed(2));
	}
    }else{
    $('#products_total_price_'+input_id).val(0);
	}
	TotalFill();
}


function TotalFill(){
	  var total = 0;
      var $changeInputs = $('input.products_total_price');
      $changeInputs.each(function(idx, el) {
      total += Number($(el).val());
      });
	  $('#products_total_value_hidden').val(total.toFixed(2));
	  $('#products_total_value').html(total.toFixed(2));
	  $('#products_sub_total_value_hidden').val(total.toFixed(2));
	  $('#products_sub_total_value').html(total.toFixed(2));
	  var vat_flag = document.getElementById("vat_flag").checked;
	  if (vat_flag==1) {
		var total_before_vat = 0;
		var total_after_vat  = 0;
		var vat_amount       = 0;
		total_before_vat = $('#products_total_value_hidden').val();
		vat_amount       = ( total_before_vat * 5 / 100 ).toFixed(2);
		total_after_vat  = parseFloat(total_before_vat) + parseFloat(vat_amount);
		$('#products_vat_hidden').val(vat_amount);
		$('#products_vat_value').html(vat_amount);
		$('#products_total_value_hidden').val(total_after_vat.toFixed(2));
		$('#products_total_value').html(total_after_vat.toFixed(2));
	  }else {
		$('#products_vat_hidden').val(0);
		$('#products_vat_value').html(0);
	  }
}
</script>