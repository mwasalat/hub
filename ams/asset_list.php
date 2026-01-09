<?php 
require_once("../header_footer/header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Asset List </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Asset List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="supplier-form" id="supplier-form" method="post" action="#">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Asset List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Asset No.</th>
                      <th >Company</th>
                      <th >Location</th>
                      <th >Employee</th>
                      <th >Asset Type</th>
                      <th >OS</th>
                      <th >Asset Name</th>
                      <th >Purchase Dt.</th>
                      <th >Warranty End Dt</th>
                      <th >Support End Dt</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
										   $sql  = "SELECT tb1.`status`,tb1.`entered_date`,tb1.`asset_no`,tb1.`asset_id`,tb1.`asset_name`,tb1.`purchase_date`,tb1.`warranty_end_date`,tb1.`support_end_date`,tb2.`company_name`,tb3.`location_name`,tb4.`type_name`,tb5.`os_name`,tb6.`full_name`
FROM `tbl_ams_assets` tb1
LEFT JOIN `tbl_emp_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`
LEFT JOIN `tbl_emp_location_master` tb3 ON tb1.`location_id`=tb3.`location_id`
LEFT JOIN `tbl_ams_type_master` tb4 ON tb1.`asset_type_id`=tb4.`type_id`
LEFT JOIN `tbl_ams_os_master` tb5 ON tb1.`os_id`=tb5.`os_id`
LEFT JOIN `tbl_emp_master` tb6 ON tb1.`emp_id`=tb6.`empid`
ORDER BY tb1.`entered_date` DESC";   
				                           $data = mysqli_query($conn,$sql);
										   $itr = 0;
                                            while($build = mysqli_fetch_array($data)){ 
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
													?>
							<tr>
							  <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
							  <td><?=$build['asset_no']?></td>
							  <td><?=$build['company_name']?></td>
							  <td><?=$build['location_name']?></td>
							  <td><?=$build['full_name']?></td>
                              <td><?=$build['type_name']?></td>
                              <td><?=$build['os_name']?></td>
                               <td><?=$build['asset_name']?></td>
                              <td><?=$build['purchase_date']?></td>
                              <td><?=$build['warranty_end_date']?></td>
                              <td><?=$build['support_end_date']?></td>
                              <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                              <td style="display:inline-flex;">
                              <?php if($build['status'] == "1"){?>
                              <a href="edit_asset.php?flag=<?=$build['asset_id']?>&gtoken=<?=$token?>" title="invoice" class="btn btn-default btn-sm"/><i class="fa fa-edit"></i></a>
							  <?php }?>
                              <a href="view_asset.php?flag=<?=$build['asset_id']?>&gtoken=<?=$token?>" title="invoice" class="btn btn-default btn-sm" title="view"/><i class="fa fa-eye"></i></a>
                              </td>
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
      <!-- /.row -->
    </form>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
<script type="text/javascript">
$(document).ready(function(){
$("#supplier-form").validationEngine();
});
</script>
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
    $('#example2').DataTable({ 
							 /* "select": {
            toggleable: false
        },*/
	"aaSorting": [[ 0, "desc" ]],
	 dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Assets',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Assets',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Assets',
			    className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
  })
</script>