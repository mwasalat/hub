<?php
require_once("../header_footer/header.php");
$msg="";
//Submit Warranty
if ($refreshflag==3){
	if(isset($_POST['submit_additional'])){
	$transaction_id_now_d                = killstring($_REQUEST['transaction_id_now_d']);
	$description                         = killstring($_REQUEST['description']);
	$IsPageValid = true;	
	if(empty($transaction_id_now_d)){
	$msg   = "Please select document id!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true	
	        $doc_name = killstring($_REQUEST['document']);
		    $thumbk   = ""; 
			$document = $_FILES["document"]["name"];
			$document = killstring($document);
			if($document!='' && $document!=NULL){  
			$file_extn                  = end_extention($document);
			$extension_pos              = strrpos($document, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "DMS_A_".substr($document, 0, $extension_pos) .'_'.time(). substr($document, $extension_pos);
			 if ($_FILES["document"]["error"] > 0 ){}      
			else{ 
				   if(in_array($file_extn,$valid_ext)){ 
				   /*compressImage($_FILES["purchase_document"]["tmp_name"][$i], "../uploads/dms/transactions_items/".$thumbk);
				   Thumbnail("../uploads/dms/transactions_items/".$thumbk, "../uploads/dms/transactions_items/".$thumbk);*/
				   move_uploaded_file($_FILES["document"]["tmp_name"],"../uploads/dms/transactions_items/".$thumbk); 
				   }else{
				   move_uploaded_file($_FILES["document"]["tmp_name"],"../uploads/dms/transactions_items/".$thumbk); 
				   }
			   }     
			 }
		 $sqlQry           = mysqli_query($conn,"INSERT INTO `tbl_dms_transactions_items`(`transaction_id`, `item_document`, `item_description`,`entered_by`) VALUES ('$transaction_id_now_d','$thumbk','$description','$userno')");
		  if($sqlQry){
		  $msg = "Additional Document added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}


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
    <h1>Cheque List - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Cheque List - Edit</li>
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
          <h3 class="box-title">Cheque List - Edit</h3>
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
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
       <?php /*?> <div class="box-footer"> <a href="list_booking.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a> </div><?php */?>
      </div>
      <!-- /.box -->
      
      
      <div class="box box-default" data-select2-id="16">
          <div class="box-header with-border">
            <h3 class="box-title">Additional Documents Upload</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                    <div class="form-group">
                  <label class="required">Document:</label>
                       <input type="file" name="document" accept=".xlx, .xlsx, .pdf, .doc, .docx, .png, .jpg, .jpeg" class="validate[required,checkFileType[xlx|xlsx|pdf|doc|docx|png|jpg|jpeg],checkFileSize[10]] form-control" title=".xlx | .xlsx | .pdf | .doc | .docx | .png | .jpg | .jpeg file only">
                </div>
             
                 <div class="form-group">
                  <label class="">Description:</label>
                  <textarea rows="4" name="description" placeholder="Description" class="form-control"></textarea>
                </div>
                
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
          <input type="hidden" name="transaction_id_now_d" value="<?=$transaction_id_now?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit_additional">Submit</button>
          </div>
        </div>
        
        
        
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
<script>
$(document).ready(function(){
$("#company-form").validationEngine();
$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]]})
  })
</script>