<?php 
require_once("../header_footer/header.php");
?>
<style type="text/css">
table#example1,table#example2,table#example3 {
  margin: 0 auto;
  width: 100%;
  clear: both;
  table-layout: fixed;         
  word-wrap:break-word;     
}
 table  td a {
    margin: 5px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Batchwise Summary List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Batchwise Summary List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="supplier-form" id="supplier-form" method="post" action="#">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Batchwise Summary List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
            <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">All</a></li>
              <li><a href="#tab_2" data-toggle="tab">Pending</a></li>
              <li><a href="#tab_3" data-toggle="tab">Authorized</a></li>
            </ul>
             <div class="tab-content">
             <!-------------------Start Tab1 All------------------------>
              <div class="tab-pane active" id="tab_1"> 
              <div class="table-responsive" >
                  <table id="example1" class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid">
                  <thead>
                    <tr>
                     <?php /*?> <th>Sl No.</th><?php */?>
                      <th>Batch No.</th>
                      <th>Reference No.s (Total Unique Reference no.s)</th>
                      <th>Total Amount</th>
                      <th>Support. Docs (Total)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					            if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100 || $empid==912642  || $empid==912642){//Sanil, Abood, Hashim, Karunesh, Dinesh, Adnan
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.`is_active`=1 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";   
										   }else if($empid==300421 || $empid==300418){//Sanish & Sultan Ecab
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.`is_active`=1 AND tb1.`company_id`=8 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";    
										   }else if($empid==100205){//RAJESH(ETAXI)
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.`is_active`=1 AND tb1.`company_id`=10 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";    
										   }else{
				                           $sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no`,count(tb2.`item_id`) AS `no_item_id`  FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_transactions_items` tb2 ON tb1.`transaction_id`=tb2.`transaction_id` WHERE tb1.`is_active`=1  AND tb1.`entered_by`='$userno' GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";
										   }
				                           $data = mysqli_query($conn,$sql);
										   $itr = 1;
                                            while($build = mysqli_fetch_array($data)){ 
											$no_supportd_docs = 0;
											$no_reference     = 0;
											$no_reference     = $build['no_reference_no'];
							?>
							<tr>
							<?php /*?>  <td><?=$itr?></td><?php */?>
							  <td><?=$build['transaction_no']?></td>
                              <td><?=$build['reference_nos']?> (<?=$no_reference?>) </td>
                              <td><?=Checkvalid_number_or_not($build['total_amount'],2)?> AED</td>
                              <td>
                              <?php 
                                  $Query = "SELECT tb1.`item_document` FROM `tbl_dms_transactions_items` tb1 INNER JOIN `tbl_dms_transactions` tb2 ON tb1.`transaction_id`=tb2.`transaction_id` WHERE  tb2.`transaction_no` ='$build[transaction_no]'  ORDER BY tb1.`entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
                                           while($buildA = mysqli_fetch_array($sqlQ)){
										   $u_pathn = '../uploads/dms/transactions_items/';
										   $no_supportd_docs++;
							?>			   
                            <a href="<?=$u_pathn . $buildA['item_document']?>" target="_blank"><i class="fa fa-file-o fa-lg"></i></a>
                            <?php }?> (<?=$no_supportd_docs?>)
                              </td>
							</tr>
							<?php 
							$itr++;
                                            }
                                        ?>
                  </tbody>
                </table>
                  </div>
                </div>
                <!-------------------End Tabl all----------------------->
                
                  <!-------------------Start Tab2 Pending------------------------>
              <div class="tab-pane" id="tab_2"> 
              <div class="table-responsive" >
                  <table id="example2" class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid">
                  <thead>
                    <tr>
                     <?php /*?> <th>Sl No.</th><?php */?>
                      <th>Batch No.</th>
                      <th>Reference No.s (Total Unique Reference no.s)</th>
                      <th>Total Amount</th>
                      <th>Support. Docs (Total)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					                        if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100  || $empid==912642){//Sanil, Abood, Hashim, Karunesh, Dinesh
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.status!=3 AND tb1.`is_active`=1 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";   
										   }else if($empid==300421 || $empid==300418){//Sanish ECab
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.status!=3 AND tb1.`is_active`=1 AND tb1.`company_id`=8 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";    
										   }else if($empid==100205){//RAJESH(ETAXI)
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.status!=3 AND tb1.`is_active`=1 AND tb1.`company_id`=10 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";    
										   }else{
				                           $sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no`,count(tb2.`item_id`) AS `no_item_id`  FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_transactions_items` tb2 ON tb1.`transaction_id`=tb2.`transaction_id` WHERE tb1.status!=3 AND tb1.`is_active`=1  AND tb1.`entered_by`='$userno' GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";
										   }
				                           $data = mysqli_query($conn,$sql);
										   $itr = 1;
                                            while($build = mysqli_fetch_array($data)){ 
											$no_supportd_docs = 0;
											$no_reference     = 0;
											$no_reference     = $build['no_reference_no'];
							?>
							<tr>
							<?php /*?>  <td><?=$itr?></td><?php */?>
							  <td><?=$build['transaction_no']?></td>
                              <td><?=$build['reference_nos']?> (<?=$no_reference?>) </td>
                              <td><?=Checkvalid_number_or_not($build['total_amount'],2)?> AED</td>
                              <td>
                              <?php 
                                  $Query = "SELECT tb1.`item_document` FROM `tbl_dms_transactions_items` tb1 INNER JOIN `tbl_dms_transactions` tb2 ON tb1.`transaction_id`=tb2.`transaction_id` WHERE  tb2.`transaction_no` ='$build[transaction_no]'  ORDER BY tb1.`entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
                                           while($buildA = mysqli_fetch_array($sqlQ)){
										   $u_pathn = '../uploads/dms/transactions_items/';
										   $no_supportd_docs++;
							?>			   
                            <a href="<?=$u_pathn . $buildA['item_document']?>" target="_blank"><i class="fa fa-file-o fa-lg"></i></a>
                            <?php }?> (<?=$no_supportd_docs?>)
                              </td>
							</tr>
							<?php 
							$itr++;
                                            }
                                        ?>
                  </tbody>
                </table>
                  </div>
                </div>
                <!-------------------End Tab2 Pending----------------------->
                
                
                  <!-------------------Start Tab3 Authorized------------------------>
              <div class="tab-pane" id="tab_3"> 
              <div class="table-responsive" >
                  <table id="example3" class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid">
                  <thead>
                    <tr>
                     <?php /*?> <th>Sl No.</th><?php */?>
                      <th>Batch No.</th>
                      <th>Reference No.s (Total Unique Reference no.s)</th>
                      <th>Total Amount</th>
                      <th>Support. Docs (Total)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					                        if($empid==911855 || $empid==911001 || $empid==911102 || $empid==911300 || $empid==911100  || $empid==912642){//Sanil, Abood, Hashim, Karunesh, Dinesh
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.status=3 AND tb1.`is_active`=1 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";   
										   }else if($empid==300421 || $empid==300418){//Sanish ECAB
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.status=3 AND tb1.`is_active`=1 AND tb1.`company_id`=8 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";    
										   }else if($empid==100205){//RAJESH(ETAXI)
											$sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no` FROM `tbl_dms_transactions` tb1 WHERE tb1.status=3 AND tb1.`is_active`=1 AND tb1.`company_id`=10 GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";    
										   }else{
				                           $sql  = "SELECT tb1.`transaction_no`,SUM(tb1.`payment_amount`) AS `total_amount`,GROUP_CONCAT(tb1.`reference_no`) AS `reference_nos`,count(DISTINCT tb1.`reference_no`) AS `no_reference_no`,count(tb2.`item_id`) AS `no_item_id`  FROM `tbl_dms_transactions` tb1 LEFT JOIN `tbl_dms_transactions_items` tb2 ON tb1.`transaction_id`=tb2.`transaction_id` WHERE tb1.status=3 AND tb1.`is_active`=1  AND tb1.`entered_by`='$userno' GROUP BY tb1.`transaction_no` ORDER BY tb1.`entered_date` DESC";
										   }
				                           $data = mysqli_query($conn,$sql);
										   $itr = 1;
                                            while($build = mysqli_fetch_array($data)){ 
											$no_supportd_docs = 0;
											$no_reference     = 0;
											$no_reference     = $build['no_reference_no'];
							?>
							<tr>
							<?php /*?>  <td><?=$itr?></td><?php */?>
							  <td><?=$build['transaction_no']?></td>
                              <td><?=$build['reference_nos']?> (<?=$no_reference?>) </td>
                              <td><?=Checkvalid_number_or_not($build['total_amount'],2)?> AED</td>
                              <td>
                              <?php 
                                  $Query = "SELECT tb1.`item_document` FROM `tbl_dms_transactions_items` tb1 INNER JOIN `tbl_dms_transactions` tb2 ON tb1.`transaction_id`=tb2.`transaction_id` WHERE  tb2.`transaction_no` ='$build[transaction_no]'  ORDER BY tb1.`entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
                                           while($buildA = mysqli_fetch_array($sqlQ)){
										   $u_pathn = '../uploads/dms/transactions_items/';
										   $no_supportd_docs++;
							?>			   
                            <a href="<?=$u_pathn . $buildA['item_document']?>" target="_blank"><i class="fa fa-file-o fa-lg"></i></a>
                            <?php }?> (<?=$no_supportd_docs?>)
                              </td>
							</tr>
							<?php 
							$itr++;
                                            }
                                        ?>
                  </tbody>
                </table>
                  </div>
                </div>
                <!-------------------End Tab3 Authorized----------------------->
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
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<!--<script src="../plugins/datatable_1_10_16/datatables.button.js"></script>-->
<script type="text/javascript" src="../plugins/datatable_1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/jszip.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/buttons.html5.min.js"></script>
<script>
$(function () {
    $('#example1').DataTable({ 
	"aaSorting": [[ 0, "asc" ]],
	 "autowidth": false,
	   "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 1,2,3] }
    ],
	  dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Batchwise Summary - All',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Batchwise Summary - All',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Batchwise Summary - All',
			    className: 'btn btn-info',
				orientation: 'landscape',
                pageSize: 'LEGAL',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
 //Pending
    $('#example2').DataTable({ 
	"aaSorting": [[ 0, "asc" ]],
	 "autowidth": false,
	   "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 1,2,3] }
    ],
	  dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Batchwise Summary - Pending',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Batchwise Summary - Pending',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Batchwise Summary - Pending',
			    className: 'btn btn-info',
				orientation: 'landscape',
                pageSize: 'LEGAL',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
 //Authorized
    $('#example3').DataTable({ 
	"aaSorting": [[ 0, "asc" ]],
	 "autowidth": false,
	   "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 1,2,3] }
    ],
	  dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Batchwise Summary - Authorized',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Batchwise Summary - Authorized',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Batchwise Summary - Authorized',
			    className: 'btn btn-info',
				orientation: 'landscape',
                pageSize: 'LEGAL',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
  })
</script>