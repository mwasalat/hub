<?php 
require_once("../header_footer/header.php");
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
                      <th >Company</th>
                      <th >Supplier</th>
                      <th >Total</th>
                      <th >Status</th>
                      <th >Invoice</th>
                    </tr>
                  </thead>
                  <thead>
                  <tr>
                    <th><input type="text" name="lpo_date" class="datepickerA " id="lpo_date" /></th>
                    <th><input type="text" name="trans_no" class="" id="trans_no" /></th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
				  </tr>
				 </thead>
                  <tbody>
                    <?php 
					                       if($empid==911855 || $empid==911092){
											$sql  = "SELECT tb1.*,tb2.`company_name`,tb3.`supplier_name`  FROM `tbl_transactions` tb1 LEFT JOIN `tbl_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`  LEFT JOIN `tbl_supplier_master` tb3 ON tb1.`supplier_id`=tb3.`supplier_id` ORDER BY tb1.`entered_date` DESC";   
										   }else{
				                           $sql  = "SELECT tb1.*,tb2.`company_name`,tb3.`supplier_name`  FROM `tbl_transactions` tb1 LEFT JOIN `tbl_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`  LEFT JOIN `tbl_supplier_master` tb3 ON tb1.`supplier_id`=tb3.`supplier_id`  WHERE tb1.`entered_by`='$userno' ORDER BY tb1.`entered_date` DESC";
										   }
				                           $data = mysqli_query($conn,$sql);
										   $itr = 0;
                                            while($build = mysqli_fetch_array($data)){ 
											 // Status
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
							  <td data-order="<?=date('Y-m-d',strtotime($build['date_of_entry']))?>"><?=date('d-m-Y',strtotime($build['date_of_entry']))?></td>
							  <td><?=$build['transaction_no']?></td>
							  <td><?=$build['company_name']?></td>
							  <td><?=$build['supplier_name']?></td>
							  <td><?=Checkvalid_number_or_not($build['total_value'],2)?></td>
                              <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
							 <!-- <td><a href="invoice_pdf.php?flag=<?=$build['transaction_id']?>&gtoken=<?=$token?>" target="_blank"/><img src="../images/002-pdf.png"  class="attachment-img" /></td></td>-->
                              <td style="display:inline-flex; padding:10px;">
                               <?php if($build['status'] == "1"){?>
                              <a href="invoice_pdf.php?flag=<?=$build['transaction_id']?>&gtoken=<?=$token?>" target="_blank" title="invoice" class="btn btn-block btn-default btn-sm" style="margin-top: 5px;"/><i class="fa fa-file-pdf-o fa-sm"></i></a>
                              <a href="edit_transaction.php?flag=<?=$build['transaction_id']?>&gtoken=<?=$token?>" title="edit" class="btn btn-block btn-default btn-sm"/><i class="fa fa-edit fa-sm"></i></a>
							  <?php }?>
                              <a href="view_transaction.php?flag=<?=$build['transaction_id']?>&gtoken=<?=$token?>" title="view" class="btn btn-block btn-default btn-sm"/><i class="fa fa-eye fa-sm"></i></a>
                              
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
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
   /* startDate: '-0d',*/
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
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
     var oTable = $('#example2').DataTable({ 
	"aaSorting": [[ 0, "desc" ]],
	 dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'LPO - Transactions',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'LPO - Transactions',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'LPO - Transactions',
			    className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]
	});
	 $("#lpo_date").change(function(){         
    oTable
        .columns(0)
        .search(this.value)
        .draw();
     });
	$("#trans_no").keyup(function(){         
    oTable
        .columns(1)
        .search(this.value)
        .draw();
     });
  })
</script>