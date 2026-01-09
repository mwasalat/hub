<?php 
require_once("../header_footer/header.php");
$tr_id = killstring($_REQUEST['flag']);
$sql  = "SELECT tb1.*,tb2.*,tb3.*,tb4.`currency_name`,tb5.`duration_name` FROM `tbl_inv_transactions` tb1 LEFT JOIN `tbl_inv_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`  LEFT JOIN `tbl_inv_customer_master` tb3 ON tb1.`supplier_id`=tb3.`supplier_id` LEFT JOIN `tbl_currency_master` tb4 ON tb1.`currency_id`=tb4.`currency_id` LEFT JOIN `tbl_duration_master` tb5 ON tb1.`duration_id`=tb5.`duration_id` WHERE tb1.`transaction_id`='$tr_id' ORDER BY tb1.`entered_date` DESC";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 
  $transaction_id = $build['transaction_id'];
  $transaction_no = $build['transaction_no'];
  $company_name   = $build['company_name'];
  $company_tr_no  = $build['company_tr_no'];
  $company_address= $build['company_address'];
  $company_phone  = $build['company_phone'];
  $company_fax    = $build['company_fax'];
  
  $date_of_entry = date('d-M-Y',strtotime($build['date_of_entry']));
  $supplier_name = $build['supplier_name'];
  $trn_type      = $build['trn_type'];
  $status        = $build['status'];
											   if($build['status'] == "1") {
													$class = 'bg-success';
													$label = "label-success";
													$type = 'Issued';
												} else if($build['status'] == "2") {
													$class = 'bg-danger';
													$type  = 'Cancelled';
													$label = "label-danger";
												}else{
												//do nothing
												}
  $cancel_reason         = $build['cancel_reason'];
  $concerned_person      = $build['concerned_person'];
  $special_terms         = $build['special_terms'];
  $bank_details          = $build['bank_details'];
  $currency_name         = $build['currency_name'];
  $duration_name         = $build['duration_name'];
  $vat_value             = 0;
  $total_value           = 0;
  $vat_value             = $build['vat_value'];
  $total_value           = $build['total_value'];
  $phone_no = $build['phone_no'];
  $fax_no   = $build['fax_no'];
  $email    = $build['email'];
 }
}
//This if function to convert numbers to Indian Currency
function getIndianCurrency(float $number){
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    //$paise = ($decimal > 0) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Fils' : '';
	$paise = ($decimal > 0) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Fils' : '';
	return ($Rupees ? $Rupees . 'Dirhams ' : '') . $paise . ' Only';
    //return ($Rupees ? $Rupees . 'Dirhams Only ' : '');
}
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>View Invoice</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Invoice</li>
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
            <h3 class="box-title">Invoice / <span style="color:red; font-weight:bold;"><?=$transaction_no?></span></h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
              <div class="table-responsive">
            <table class="table">
              <tbody><tr>
                <th>Company:</th>
                <td><?=$company_name?></td>
              </tr>
              <tr>
                <th>Date:</th>
                <td><?=$date_of_entry?></td>
              </tr>
             <tr>
                <th>Duration:</th>
                <td><?=$duration_name?></td>
              </tr>
            </tbody></table>
          </div>
              </div>
              <div class="col-md-6">
              <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <th>Supplier:</th>
                <td><?=$supplier_name?></td>
              </tr>
                <tr>
                <th>Currency:</th>
                <td><?=$currency_name?></td>
              </tr>
            </tbody></table>
          </div>
              </div>
            </div>
            <!-- /.row -->
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                 <hr/>
                <table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody><tr><th colspan="5">Concerned Person</th></tr>
                                           <tr><td width="10%"><b>Sl.No.</b></td><td width="30%"><b>Name</b></td><td width="20%"><b>Position</b></td><td  width="20%"><b>Phone</b></td></tr>
                                           <?php
										   $concerned_person_cnt = explode(",",$concerned_person);
										   $cntC                 = count($concerned_person_cnt);   
										   $itr = 1;
										   for($i=0; $i < $cntC; $i++){
				                           $sql  = "SELECT * FROM `tbl_inv_concerned_person_master` WHERE `person_id`='$concerned_person_cnt[$i]' ORDER BY `entered_date` ASC";
				                           $dataA = mysqli_query($conn,$sql);
										    if(mysqli_num_rows($dataA)>0){
                                            while($build = mysqli_fetch_array($dataA)){ 
											$person_name        = $build['person_name'];
											$person_position    = $build['person_position'];
											$person_phone       = $build['person_phone'];
											?>
                                            <tr><td><?=$itr?></td><td><?=$person_name?></td><td><?=$person_position?></td><td><?=$person_phone?></td></tr>
                                            <?php
											}
											}
											$itr++;
											}
                                            ?>
                 </tbody>
                  </table>
                <hr/>
                <table class="table table-bordered"  width="100%" height="auto" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;border:solid 1px #DDD"><tbody><tr><th colspan="5">Invoice Items</th></tr>
                                           <?php
				                           $sql  = "SELECT `item_id`, `transaction_id`, `item_description`, `item_quantity`, `item_unit_value`, `item_total_value` FROM `tbl_inv_transactions_items` WHERE `transaction_id`='$tr_id' and `is_active`=1 ORDER BY `entered_date` ASC";
				                           $dataA = mysqli_query($conn,$sql);
										   $itr = 1;
										    if(mysqli_num_rows($dataA)>0){
                                            ?>
											<tr><td width="10%"><b>Sl.No.</b></td><td width="30%"><b>Description</b></td><td width="20%"><b>Quantity</b></td><td  width="20%"><b>Rate</b></td><td><b  width="20%">Amount</b></td></tr>
                                            <?php
											$total = 0;
                                            while($build = mysqli_fetch_array($dataA)){ 
											$item_description        = $build['item_description'];
											$item_quantity           = $build['item_quantity'];
											$item_unit_value         = $build['item_unit_value'];
											$item_total_value        = $build['item_total_value'];
											$total+=$build['item_total_value'];
											?>
                                            <tr><td><?=$itr?></td><td><?=$item_description?></td><td><?=$item_quantity?></td><td><?=Checkvalid_number_or_not($item_unit_value,2)?></td><td><?=Checkvalid_number_or_not($item_total_value,2)?></td></tr>
                                            <?php
					                        $itr++;
											}
											}
											$sum_total_vat = $total+$vat_value;
                                            ?>
                 <tr><td colspan="4">Sub Total</td><td><?=Checkvalid_number_or_not($total,2)?></td></tr>
				 <tr><td colspan="4">VAT 5%</td><td><?=Checkvalid_number_or_not($vat_value,2)?></td></tr>
				 <tr><td colspan="4">Total</td><td><?=Checkvalid_number_or_not(($sum_total_vat),2)?></td></tr>
                 </tbody>
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
              <div class="table-responsive">
            <table class="table">
              <tbody><tr>
                <th>Place Of Delivery:</th>
                <td><?=$place_of_delivery?></td>
              </tr>
              <tr>
                <th>Delivery Schedule:</th>
                <td><?=$delivery_schedule?></td>
              </tr>
              <tr>
                <th>Special Terms & Conditions:</th>
                <td><?=$special_terms?></td>
              </tr>
              <tr>
                <th>Status:</th>
                <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
              </tr>
            
            </tbody></table>
          </div>
              </div>
              <div class="col-md-6">
              <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <th>Place Of Registration:</th>
                <td><?=$place_of_registration?></td>
              </tr>
              <tr>
                <th>Terms Of Payment:</th>
                <td><?=$terms_of_payment?></td>
              </tr>
                 <tr>
                <th>Comments:</th>
                <td><?=$comments?></td>
              </tr>
              <?php if($status==2){?>
              <tr>
                <th>Cancel Reason:</th>
                <td><?=$cancel_reason?></td>
              </tr>
              <?php }?>
            </tbody></table>
          </div>
              </div>
            </div>
            <!-- /.row --> 
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- /.row -->
      </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php require_once("../header_footer/footer.php");?>