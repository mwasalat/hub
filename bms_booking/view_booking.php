<?php
require_once("../header_footer/header.php");
$msg="";
$booking_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT tb1.*,tb2.`location_name` AS `pickup_location`,tb3.`location_name` AS `dropoff_location`,tb4.`vehicle_name` AS `vehicle_name`,tb5.`broker_name` AS `broker_name` FROM `tbl_bms_booking` tb1 LEFT JOIN `tbl_bms_location_master` tb2 ON tb1.`pickUpLocation`=tb2.`location_id`  LEFT JOIN `tbl_bms_location_master` tb3 ON tb1.`returnLocation`=tb3.`location_id` LEFT JOIN `tbl_bms_vehicle_master` tb4 ON tb1.`vehicle_id`=tb4.`vehicle_id` LEFT JOIN `tbl_bms_broker_master` tb5 ON tb1.`broker_id`=tb5.`broker_id` WHERE tb1.`booking_id`='$booking_id'");
while($rowA = mysqli_fetch_array($qry)){
	$booking_id_now     = $rowA['booking_id'];
	$reservation_no     = $rowA['reservation_no'];
	$pickUpDate         = $rowA['pickUpDate'];
	$returnDate         = $rowA['returnDate'];
	$pickup_location    = $rowA['pickup_location'];
	$dropoff_location   = $rowA['dropoff_location'];
	$customerName       = $rowA['customerName'];
	$customerPhone      = $rowA['customerPhone'];
	$customerEmail      = $rowA['customerEmail'];
	$vehicle_name       = $rowA['vehicle_name'];
	$totalValue         = $rowA['totalValue'];
	$noOfDays           = $rowA['noOfDays'];
	$accessories        = $rowA['accessories'];
	$broker_name        = $rowA['broker_name'];
	$externalReference  = $rowA['externalReference'];
	$flightNumber       = $rowA['flightNumber'];
	$entered_date       = $rowA['entered_date'];
	$status             = $rowA['status'];
		 // Status
											   if($status == "1") {
													$class = 'bg-success';
													$label = "label-success";
													$type = 'Confirmed';
												} else if($status == "2"){
													$class = 'bg-danger';
													$type  = 'Cancelled';
													$label = "label-danger";
												}else{
													//do nothing
												}
}
?>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Booking  - View</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Booking - View</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Booking  - View</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="">Booking ID/Booking Dt/Broker:</label>
                 <div class="input-group"><strong><?=$reservation_no?></strong>/<?=date('d-m-Y',strtotime($entered_date))?>/<?=$broker_name?></div>
              </div>
             
         <div class="form-group">
                <label class="">Pickup Location:</label>
                 <div class="input-group"><?=$pickup_location?></div>
              </div>
              
                  
         <div class="form-group">
                <label class="">Customer Name:</label>
                 <div class="input-group"><?=$customerName?></div>
              </div>
              
                           
         <div class="form-group">
                <label class="">Total Value:</label>
                 <div class="input-group"><?=Checkvalid_number_or_not($totalValue,2)?></div>
              </div>
              
               <div class="form-group">
                <label class="">Flight No:</label>
                 <div class="input-group"><?=$flightNumber?></div>
              </div>
              
            </div>
            <div class="col-md-4">
             
              <div class="form-group">
                <label class="">Pickup Dt:</label>
                <div class="input-group"><?=date('d-m-Y H:i:s',strtotime($pickUpDate))?></div>
              </div>
              
              <div class="form-group">
                <label class="">Dropoff Location:</label>
                 <div class="input-group"><?=$dropoff_location?></div>
              </div>
              
               <div class="form-group">
                <label class="">Customer Name:</label>
                 <div class="input-group"><?=$customerPhone?></div>
              </div>
              
              <div class="form-group">
                <label class="">Accessories/price:</label>
                 <div class="input-group"><?=$accessories?>&nbsp;</div>
              </div>
              
                <div class="form-group">
                <label class="">Status:</label>
                 <div class="input-group"><div class="status label <?=$label?>"><b><?=$type?></b></div></div>
              </div>
              
              
              
            </div>
            <div class="col-md-4">
            
              <div class="form-group">
                <label class="">Return Dt:</label>
                <div class="input-group"><?=date('d-m-Y H:i:s',strtotime($returnDate))?></div>
              </div>
              
              <div class="form-group">
                <label class="">Vehicle:</label>
                 <div class="input-group"><?=$vehicle_name?></div>
              </div>
              
              <div class="form-group">
                <label class="">Customer Email:</label>
                 <div class="input-group"><?=$customerEmail?></div>
              </div>
              
                  <div class="form-group">
                <label class="">External Reference No:</label>
                 <div class="input-group"><?=$externalReference?></div>
              </div>
              
              
                  <div class="form-group">
                <label class="">Invoice:</label>
                 <div class="input-group"> <a href="booking_pdf.php?flag=<?=$booking_id_now?>&gtoken=<?=$token?>" title="pdf" target="_blank"/><i class="fa fa-file-pdf-o fa-lg" aria-hidden="true"></i></a></div>
              </div>
              
            </div>
            
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="list_booking.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
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
<!-- CK Editor -->
<script src="../plugins/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    CKEDITOR.replace('editor1')
  })
</script>
