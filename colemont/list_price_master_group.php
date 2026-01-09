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
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
   <a href="price_master_group.php?gtoken=<?=$token?>" class="btn btn-warning" style="float:right"><i class="fa fa-plus"></i> New Premium - Group</a>
   <h1>Premium List - Group</h1>
   
    <!--<ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Price Insurance List</li>
    </ol>-->
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
      <div class="box box-default">
      <div class="clear"></div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2"  class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid" style="font-size:12px;">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >Company</th>
                      <th >Batch No.</th>
                      <th >Batch Name</th>
                      <th >Emirates</th>
                      <th >Start Dt:</th>
                      <th >End Dt:</th>
                      <th >Type</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php 
											$Query = "SELECT tb1.*,tb2.`company_name`, CASE WHEN tb1.`customer_type`=1 THEN 'Group' ELSE 'Individual' END AS `customer_type_now` FROM `tbl_cmt_ins_price_batch_master` tb1 LEFT JOIN `tbl_cmt_ins_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`  ORDER BY tb1.`price_batch_id` DESC";	
				                            $sqlQ  = mysqli_query($conn,$Query);
										    $itr = 0;
										    $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
											$class      = ($build['is_active'] == "1")? 'bg-success' : 'bg-danger';
											$label      = ($build['is_active'] == "1")? 'label-success' : 'label-danger';
											$type       = ($build['is_active'] == "1")? 'Acive' : 'Inactive';
											$emirate_id = explode(',',$build['emirate_id']);
					?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['company_name']?></td>
                      <td><?=$build['tr_no']?></td>
                      <td><?=$build['batch_name']?></td>
                       <td>
                      <?php
					  foreach($emirate_id as $emirate_ids){
					  $QueryCounterB =  mysqli_query($conn,"SELECT `emirates_name` FROM `tbl_emirates_master` WHERE `emirates_id`='$emirate_ids' ");
					  $rowQryB       = mysqli_fetch_row($QueryCounterB);
					  echo $rowQryB[0].",";  
					  }
					  ?>
                      </td>
                      <td><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                      <td><?=$build['customer_type_now']?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                      <td>
                      <?php if($build['end_date']>=date('Y-m-d')){?><a href="list_price_master_inner_group.php?flag=<?=$build['price_batch_id']?>&gtoken=<?=$token?>" title="View"/><i class="fa fa-eye"></i></a><?php }?>
                      </td>
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
    startDate: '-0d',
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
</script>
<!-- DataTables -->
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
  "select": {
            toggleable: false
        },
  "autowidth":false,		
  "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0, 1, 2, 3,4, 5, 6] }
    ],
	    dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Price Master List - Colemont',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Price Master List - Colemont',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
                extend: 'pdf',
                title: 'Price Master List - Colemont',
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
