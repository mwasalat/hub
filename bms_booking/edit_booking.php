<?php
require_once("../header_footer/header.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$rac_no                      = "";	
	$booking_id_now_latest       = killstring($_REQUEST['booking_id_now_latest']);
	$fleet_no                    = killstring($_REQUEST['fleet_no']);
	$fleet_type                  = killstring($_REQUEST['fleet_type']);
	$fleet_remarks               = killstring($_REQUEST['fleet_remarks']);
	$IsPageValid = true;	
	if(empty($fleet_no)){
	$msg   = "Please select fleet no!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true
		  $sql        = "UPDATE `tbl_bms_booking` SET `fleet_no`='$fleet_no',`fleet_type`='$fleet_type',`fleet_remarks`='$fleet_remarks',`fleet_no_entered_by`='$userno',`fleet_no_entered_date`=NOW() WHERE `booking_id`='$booking_id_now_latest'"; 
		  $result     = mysqli_query($conn,$sql);
		  if($result){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS fleet details updation for ID:$booking_id_now_latest','1','$ip_address','$userno')");  
		  $msg = "Fleet details updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}


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
	$status             = $rowA['status'];
	$entered_date       = $rowA['entered_date'];
	$fleet_no           = $rowA['fleet_no'];
	$fleet_type         = $rowA['fleet_type'];
	$fleet_remarks      = $rowA['fleet_remarks'];
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
    <h1>Booking  - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Booking - Edit</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="booking-form" id="booking-form" method="post" action="#" enctype="multipart/form-data">
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
          <h3 class="box-title">Booking  - Edit</h3>
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
              
              
               <div class="form-group">
                  <label class="required">Agreement Type:</label>
                  <?php if(empty($fleet_no) || $userno == 4){?>

                    <select class="validate[required] form-control" name="fleet_type" id="fleet_type">
                      <option selected="selected" value="">---Select an option---</option>
                      <option value="RAC" <?=($fleet_type == 'RAC') ? 'selected': '' ?>>RAC</option>
                      <option value="MRA"  <?=($fleet_type == 'MRA') ? 'selected': '' ?> >MRA</option>
                      <option value="NO SHOW"  <?=($fleet_type == 'NO SHOW') ? 'selected': '' ?> >NO SHOW</option>
                      <option value="DENIED"  <?=($fleet_type == 'DENIED') ? 'selected': '' ?> >DENIED</option>
                  </select>
                  <?php }else{?>
                    <div class="input-group"><?=$fleet_type?></div><?php 
                    }?>
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
              
               
               <div class="form-group">
                  <label class="required">Agreement No:</label>
                  <?php if(empty($fleet_no) || $userno == 4){?>
                    <input type="text" class="validate[required,custom[onlyNumber]] form-control" placeholder="Fleet No" value="<?=$fleet_no?>" name="fleet_no" id="fleet_no">
                  <?php }else{?>
                    <div class="input-group"><?=$fleet_no?></div>
                  <?php }?>
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
              
               
               <div class="form-group">
                  <label class="">Fleet Remarks:</label>
                  <?php if(empty($fleet_no) || $userno == 4){?>
                    <textarea class="form-control" value="<?=nl2br($fleet_remarks)?>" placeholder="Fleet Remarks" name="fleet_remarks" rows="4"></textarea>
                  <?php }else{?>
                    <div class="input-group"><?=nl2br($fleet_remarks)?></div>
                  <?php }?>
              </div>
              
            </div>
            
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="list_booking.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <input type="hidden" name='booking_id_now_latest' value= '<?=$booking_id_now?>' >
          <?php if(empty($fleet_no) || $userno == 4){?>
            <button type="submit" class="btn btn-primary pull-right" name="submit" id="submit">Submit</button>
          <?php }?>
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


$.validationEngineLanguage.allRules['customEquals'] = {
    "regex": /^-1$/,
    "alertText": "* Value must be -1"
};

$("#fleet_type").change(function(){
    var fleetTypeValue = $(this).val();
    var $fleetNo = $("#fleet_no");
    debugger
    if(fleetTypeValue === "NO SHOW" || fleetTypeValue === "DENIED") {
        // Remove the required rule for fleet_no
        $fleetNo.removeClass("validate[required,custom[onlyNumber]]").addClass("validate[required,custom[customEquals]]");
    } else {
        // Add the required rule for fleet_no
        $fleetNo.removeClass("validate[required,custom[customEquals]]").addClass("validate[required,custom[onlyNumber]]");
    }
});

$("#booking-form").validationEngine();


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
