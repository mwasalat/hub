<?php
require_once("../header_footer/header.php");
$msg="";
$transaction_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT tb1.*,tb2.`company_name` FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` WHERE tb1.`transaction_id`='$transaction_id'");
while($rowA = mysqli_fetch_array($qry)){
	$transaction_id_now = $rowA['transaction_id'];
	$transaction_no     = $rowA['transaction_no'];
	$company_name       = $rowA['company_name'];
	$reference_no       = $rowA['reference_no'];
	$payment_type       = $rowA['payment_type'];
	$debit_iban_account_no   = $rowA['debit_iban_account_no'];
	$payment_amount     = $rowA['payment_amount'];
	$payment_currency   = $rowA['payment_currency'];
	$cheque_print_date  = $rowA['cheque_print_date'];
	$beneficiary_name   = $rowA['beneficiary_name'];
	$beneficiary_address= $rowA['beneficiary_address'];
	$payment_details    = $rowA['payment_details'];
	$cheque_date        = $rowA['cheque_date'];
	$remarks            = $rowA['remarks'];
	$additional_details = $rowA['additional_details'];
											   // Status
											   if($build['status'] == "1") {
													$class = 'bg-danger';
													$label = "label-danger";
													$type = 'Open';
												} else if($build['status'] == "2") {
													$class = 'bg-warning';
													$type  = 'CEO Approved';
													$label = "label-warning";
												}else if($build['status'] == "3") {
													$class = 'bg-success';
													$type  = 'Accountant Approved';
													$label = "label-success";
												}else{
												//do nothing
												}
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Cheque List - View</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Cheque List - View</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Cheque List - View</h3>
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
                <label class="">Transaction No/Company:</label>
                <div class="input-group"><strong>
                  <?=$transaction_no?>
                  </strong>/
                  <?=$company_name?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Payment Type:</label>
                <div class="input-group">
                  <?=$payment_type?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Payment Amount:</label>
                <div class="input-group">
                  <?=Checkvalid_number_or_not($payment_amount,2)?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Cheque Print Dt.:</label>
                <div class="input-group">
                  <?=date('d-m-Y',strtotime($cheque_print_date))?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Beneficiary Address:</label>
                <div class="input-group">
                  <?=$beneficiary_address?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Cheque Dt.:</label>
                <div class="input-group">
                  <?=date('d-m-Y',strtotime($cheque_date))?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Additional Details:</label>
                <div class="input-group">
                  <?=$additional_details?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="">Reference No:</label>
                <div class="input-group">
                  <?=$reference_no?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Debit Account Number / IBAN:</label>
                <div class="input-group">
                  <?=$debit_iban_account_no?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Payment Currency:</label>
                <div class="input-group">
                  <?=$payment_currency?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Beneficiary Name:</label>
                <div class="input-group">
                  <?=$beneficiary_name?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Payment Details:</label>
                <div class="input-group">
                  <?=$payment_details?>
                </div>
              </div>
              <div class="form-group">
                <label class="">Remarks:</label>
                <div class="input-group">
                  <?=$remarks?>
                </div>
              </div>
              <?php /*?><div class="form-group">
                <label class="">Invoice:</label>
                <div class="input-group"> <a href="booking_pdf.php?flag=<?=$booking_id_now?>&gtoken=<?=$token?>" title="pdf" target="_blank"/><i class="fa fa-file-pdf-o fa-lg" aria-hidden="true"></i></a></div>
              </div><?php */?>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
       <?php /*?> <div class="box-footer"> <a href="list_booking.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a> </div><?php */?>
      </div>
      <!-- /.box -->
      
      
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Additional Documents List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >Document</th>
                      <th >Description</th>
                  </thead>
                  <tbody>
                    <?php 
					
					                       $Query = "SELECT * FROM `tbl_dms_transactions_items` WHERE  `transaction_id` ='$transaction_id_now'  ORDER BY `entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
                                           while($build = mysqli_fetch_array($sqlQ)){
										   $u_pathn = '../uploads/dms/transactions_items/'; 	
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                     <td><?php if ($build['item_document'] != '' && file_exists($u_pathn . $build['item_document'] )) {?>
                            <a href="<?=$u_pathn . $build['item_document']?>" target="_blank"><i class="fa fa-file-o fa-lg"></i></a>
                            <?php }?></td>
                      <td><?=$build['item_description']?></td>  
                    </tr>
                    <?php 
                                            }
                                        ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
      <!-- /.row -->
    </form>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>