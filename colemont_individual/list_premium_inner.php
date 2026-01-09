<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
$price_batch_id = killstring($_REQUEST['flag']);
$msg = "";
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Premium List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Premium List</li>
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
      <?php }?><br/><br/>
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >Emirate</th>
                      <th >Relation Type</th>
                      <th >Gender</th>
                      <th >Marital Status</th>
                      <th >Start Age</th>
                      <th >End Age</th>
                      <th >Type</th>
                      <th >Premium</th>
                      <th >Status</th>
                       <th ></th>
                    </tr>
                  </thead>
                  <tbody> 
                    <?php 
					
					                        if (!empty($price_batch_id )){
											$Query = "SELECT tb1.*,tb2.`company_name`,tb3.`emirates_name`, CASE WHEN tb1.`customer_type`=1 THEN 'Group' ELSE 'Individual' END AS `customer_type_now`  FROM `tbl_cmt_ins_price_master` tb1 LEFT JOIN `tbl_cmt_ins_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_emirates_master` tb3 ON tb1.`emirate_id`=tb3.`emirates_id` WHERE tb1.`price_batch_id`='$price_batch_id ' ORDER BY tb1.`price_id` DESC";	
				                           $sqlQ  = mysqli_query($conn,$Query);
										   $itr = 0;
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
											$class = ($build['is_active'] == "1")? 'bg-success' : 'bg-danger';
											$label = ($build['is_active'] == "1")? 'label-success' : 'label-danger';
											$type  = ($build['is_active'] == "1")? 'Acive' : 'Inactive';
										?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['emirates_name']?></td>
                      <td><?=$build['relation_type']?></td>
                      <td><?=$build['gender']?></td>
                      <td><?=$build['marital_status']?></td>
                      <td><?=$build['start_age']?></td>
                      <td><?=$build['end_age']?></td>
                      <td><?=$build['customer_type_now']?></td>
                      <td><?=Checkvalid_number_or_not($build['price'],2)?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                         <td><?php if($build['end_date']>=date('Y-m-d')){?><a href="premium_inner_edit.php?flag=<?=$build['price_id']?>&gtoken=<?=$token?>" title="Edit" class="btn btn-default btn-sm"/><i class="fa fa-edit"></i></a><?php }?></td>
                    </tr>
                    <?php 
					$i++;
                            }
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
  "aaSorting": [[ 0, "desc" ]],
  "select": {
            toggleable: false
        },
	   dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Price Master List - ColeMont',
			   className: 'btn btn-primary'/*,
				exportOptions: {
					columns: ':not(:first-child)',
				}*/
            },
            {
               extend: 'excel',
               title: 'Price Master List - ColeMont',
			    className: 'btn btn-success'/*,
				exportOptions: {
					columns: ':not(:first-child)',
				}*/
            },
			 {
                extend: 'pdf',
                title: 'Price Master List - ColeMont',
			    className: 'btn btn-info'/*,
				exportOptions: {
					columns: ':not(:first-child)',
				}*/
            }
			]
  });
})
</script>
