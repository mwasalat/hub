<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
?>
<style>
 table#example2 {
  margin: 0 auto;
  width: 100%;
  clear: both;
 /* border-collapse: collapse;*/
  table-layout: fixed;         
  word-wrap:break-word;     
 /* white-space: pre-line;*/
}
 /* td a {
    margin: 5px;
  }*/
</style>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Booking List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Booking List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Bookings List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <br/>
        <!-- /.box-header -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid" style="font-size:12px;">
                  <thead>
                    <tr>
                      <th >Action</th>
                      <th >Booking Dt.</th>
                      <th >Res. No</th>
                      <th >AG No</th>
                      <th >Broker</th>
                      <th >External Ref. No</th>
                      <th >Name/Phone</th>
                      <th >Vehicle/Group</th>
                      <th >Pickup Dt.</th>
                      <th >Dropoff Dt.</th>
                      <th >Pickup Location</th>
                      <th >Dropoff Location</th>
                      <th >Total</th>
                      <th >Accessories</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <thead>
                  <tr>
                    <th>&nbsp;</th>
                    <th><input type="text" name="booking_date" class="datepickerA " id="booking_date" /></th>
                    <th><input type="text" name="res_no" class="" id="res_no" /></th>
                    <th><input type="text" name="fleet_no" class="" id="fleet_no" /></th>
                     <th><select id="myBroker" class="" style="width:100%;">
                        <option value=""></option>
                        <?php  
						   $sqlAA  = "SELECT `broker_name` FROM `tbl_bms_broker_master` WHERE `is_active`=1 ORDER BY `broker_name` ASC";
						   $dataAA = mysqli_query($conn,$sqlAA);
						   while($buildA = mysqli_fetch_array($dataAA)){
						   ?>
										<option value="<?=$buildA['broker_name']?>">
										<?=$buildA['broker_name']?>
										</option>
										<?php 
						   }?>
                      </select></th>
                      <th><input type="text" name="extrenal_ref_no" class="" id="extrenal_ref_no" /></th>
                       <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th><input type="text" name="pickup_date" class="datepickerA " id="pickup_date" /></th>
                        <th><input type="text" name="dropoff_date" class="datepickerA " id="dropoff_date" /></th>
                        <th><select id="myPickup" class="" style="width:100%;">
                        <option value=""></option>
                        <?php  
						   $sqlAA  = "SELECT `location_name` FROM `tbl_bms_location_master` WHERE `is_active`=1 ORDER BY `location_name` ASC";
						   $dataAA = mysqli_query($conn,$sqlAA);
						   while($buildA = mysqli_fetch_array($dataAA)){
						   ?>
										<option value="<?=$buildA['location_name']?>">
										<?=$buildA['location_name']?>
										</option>
										<?php 
						   }?>
                      </select></th>
                         <th><select id="myDropff" class="" style="width:100%;">
                        <option value=""></option>
                        <?php  
						   $sqlAA  = "SELECT `location_name` FROM `tbl_bms_location_master` WHERE `is_active`=1 ORDER BY `location_name` ASC";
						   $dataAA = mysqli_query($conn,$sqlAA);
						   while($buildA = mysqli_fetch_array($dataAA)){
						   ?>
										<option value="<?=$buildA['location_name']?>">
										<?=$buildA['location_name']?>
										</option>
										<?php 
						   }?>
                      </select></th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
					  <?php /*?><th><select id="myStatus" class="form-control">
                        <option value=""></option>
                        <option value="Pending">Pending</option>
						<option value="Approved">Approved</option>
						<option value="Rejected">Rejected</option>
                        <option value="Mgr Pending">Mgr Pending</option>
                        <option value="Mgr Approved">Mgr Approved</option>
                        <option value="Mgr Rejected">Mgr Rejected</option>
                      </select></th>
					  <th><select id="myType" class="form-control">
                        <option value=""></option>
                       <option value="Addition">Addition</option>
					   <option value="Replacement">Replacement</option>
                       <option value="Temporary">Temporary</option>
                       <option value="Internal Transfer">Internal Transfer</option>
                      </select></th><?php */?>
					  </tr>
					  </thead>
                  <tbody>
                    <?php 
					
					                      $Query = "SELECT tb1.*,tb2.`location_name` AS `pickup_location`,tb3.`location_name` AS `dropoff_location`,tb4.`vehicle_name` AS `vehicle_name`,tb5.`broker_name` AS `broker_name`,tb6.`group_name` AS `group_name` FROM `tbl_bms_booking` tb1 LEFT JOIN `tbl_bms_location_master` tb2 ON tb1.`pickUpLocation`=tb2.`location_id`  LEFT JOIN `tbl_bms_location_master` tb3 ON tb1.`returnLocation`=tb3.`location_id` LEFT JOIN `tbl_bms_vehicle_master` tb4 ON tb1.`vehicle_id`=tb4.`vehicle_id` LEFT JOIN `tbl_bms_broker_master` tb5 ON tb1.`broker_id`=tb5.`broker_id` LEFT JOIN `tbl_bms_group_master` tb6 ON tb4.`group_id`=tb6.`group_id`  ORDER BY tb1.`entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
										   $itr = 0;
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
												 // Status
											   if($build['status'] == "1") {
													$class = 'bg-success';
													$label = "label-success";
													$type = 'Confirmed';
												} else{
													$class = 'bg-danger';
													$type  = 'Cancelled';
													$label = "label-danger";
												}
											?>
                    <tr>
                       <td style="display:inline-flex;">
                      <?php if((empty($build['fleet_no']) || $userno == 4 ) && $build['status']==1 ){?>
                        <a href="edit_booking.php?flag=<?=$build['booking_id']?>&gtoken=<?=$token?>" title="edit" class="btn btn-default btn-xs"/>
                          <i class="fa fa-edit fa-sm"></i>
                        </a>
                      <?php }?>
                      <a href="view_booking.php?flag=<?=$build['booking_id']?>&gtoken=<?=$token?>" title="View" class="btn btn-default btn-xs"/><i class="fa fa-eye fa-sm"></i></a>
                      <a href="booking_pdf.php?flag=<?=$build['booking_id']?>&gtoken=<?=$token?>" title="pdf" target="_blank" class="btn btn-default btn-xs"/><i class="fa fa-file-pdf-o fa-sm" aria-hidden="true">                      </i>
                      </a>
                      </td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y H:i:s',strtotime($build['entered_date']))?></td>
                         <td><?=$build['reservation_no']?></td>
                         <td><?=$build['fleet_type']?><?=$build['fleet_no']?></td>
                         <td><?=$build['broker_name']?></td>
                         <td><?=$build['externalReference']?></td>
                         <td><?=$build['customerName']?>/<?=$build['customerPhone']?></td>
                         <td><?=$build['vehicle_name']?>/<?=$build['group_name']?></td>
                         <td data-order="<?=date('Y-m-d H:i:s',strtotime($build['pickUpDate']))?>"><?=date('d-m-Y H:i:s',strtotime($build['pickUpDate']))?></td>
                         <td data-order="<?=date('Y-m-d H:i:s',strtotime($build['returnDate']))?>"><?=date('d-m-Y H:i:s',strtotime($build['returnDate']))?></td>
                      <td><?=$build['pickup_location']?></td>  
                      <td><?=$build['dropoff_location']?></td> 
                      <td><?=Checkvalid_number_or_not($build['totalValue'],2)?></td>
                      <td><?=$build['accessories']?></td> 
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                      
                    </tr>
                    <?php 
					$i++;
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
   /* startDate: '-0d',*/
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
</script>
<!-- DataTables -->
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
<script>
$(document).ready( function () {
  var oTable = $('#example2').DataTable({
  "aaSorting": [[ 1, "desc" ]],
/*   "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": true,*/
  "select": {
            toggleable: false
        },
  "autowidth":false,		
  "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0, 1, 2, 3,4, 5, 6, 7 ,8, 9, 10, 11,12,13] }
    ],
	    dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Booking List - BMS',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:first-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Booking List - BMS',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:first-child)',
				}
            },
			 {
                extend: 'pdf',
                title: 'Booking List - BMS',
			    className: 'btn btn-info',
				orientation: 'landscape',
                pageSize: 'LEGAL',
				exportOptions: {
					columns: ':not(:first-child)',
				}
            }
			]
  });
  
    /* $('#booking_date').on('change',function(){
        var selectedValue = $(this).val();
		if(selectedValue==''){
		oTable.fnFilter('', 1, true);
		}else{
        oTable.fnFilter("^"+selectedValue+"$", 1, true); //Exact value, column, reg
		}
    });*/
	
	$("#booking_date").change(function(){         
    oTable
        .columns(1)
        .search(this.value)
        .draw();
     });
	$("#res_no").keyup(function(){         
    oTable
        .columns(2)
        .search(this.value)
        .draw();
     });
	$("#fleet_no").keyup(function(){         
    oTable
        .columns(3)
        .search(this.value)
        .draw();
     });
	 $('#myBroker').on('change',function(){
		oTable
        .columns(4)
        .search(this.value)
        .draw();								 
    });
	$('#extrenal_ref_no').on('change',function(){
		oTable
        .columns(5)
        .search(this.value)
        .draw();								 
    });
	$('#pickup_date').on('change',function(){
		oTable
        .columns(8)
        .search(this.value)
        .draw();								 
    });
	$('#dropoff_date').on('change',function(){
		oTable
        .columns(9)
        .search(this.value)
        .draw();								 
    });
	$('#myPickup').on('change',function(){
		oTable
        .columns(10)
        .search(this.value)
        .draw();								 
    });
	$('#myDropff').on('change',function(){
		oTable
        .columns(11)
        .search(this.value)
        .draw();								 
    });
	 /* $('#res_no').on('change',function(){
        var selectedValue = $(this).val();
		if(selectedValue==''){
		oTable.fnFilter('', 2, true);
		}else{
	    oTable.fnFilter('^' + selectedValue, 2, true); //Issue 37
        //oTable.fnFilter("^"+selectedValue, 2, true); //Exact value, column, reg
		}
    });*/
	  
})
</script>
