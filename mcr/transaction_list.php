<?php 
require_once("../header_footer/header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Transaction List </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Transaction List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="supplier-form" id="supplier-form" method="post" action="#">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Transaction List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Transaction No.</th>
                      <th >Customer Name</th>
                      <th >Trans<sup>n</sup> Type</th>
                      <th >Amount</th>
                      <!--<th >Status</th>-->
                      <th >Invoice</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					                       if($empid==911855){
										   $sql  = "SELECT * FROM `tbl_mcr_transactions` WHERE `status`=1 ORDER BY `date_of_reg` DESC";   
										   }else{
				                           $sql  = "SELECT * FROM `tbl_mcr_transactions` WHERE `entered_by`='$userno' AND `status`=1 ORDER BY `date_of_reg` DESC";
										   }
				                           $data = mysqli_query($conn,$sql);
										   $itr = 0;
                                            while($build = mysqli_fetch_array($data)){ 
											$invoice_file ="";
											$pdf_save     = "";
											$pdfnamenew = "MCR-".$build['trans_sl_no'].".pdf";  
											$pdf_save = '../uploads/mcr/'.$pdfnamenew;
											if ($build['trans_sl_no'] != '' && file_exists($pdf_save)) {
											$invoice_file = $pdf_save;
											}
													?>
							<tr>
							  <td data-order="<?=date('Y-m-d',strtotime($build['date_of_reg']))?>"><?=date('d-m-Y',strtotime($build['date_of_reg']))?></td>
							  <td><?=$build['trans_sl_no']?></td>
							  <td><?=$build['customername']?></td>
							  <td><?=$build['trans_type']?></td>
							  <td><?=Checkvalid_number_or_not($build['amount'],2)?></td>
                              <td><?php if ($invoice_file) {?>
							   <a href="<?=$invoice_file?>" target="_blank" title="Invoice"><i class="fa fa-file-o fa-lg"></i></a>
							   <?php }?>
							  </td>
                             <?php /*?> <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td><?php */?>
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
	"aaSorting": [[ 0, "desc" ]],
	 dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Transactions',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Transactions',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Transactions',
			    className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
  })
</script>