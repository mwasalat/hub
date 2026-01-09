<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
   <a href="ins_members_premium_group.php?gtoken=<?=$token?>" class="btn btn-warning" style="float:right"><i class="fa fa-plus"></i> New Quotation</a>
    <h1>Quotations - Group</h1>
  <!--  <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Price Insurance Premium List</li>
    </ol>-->
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="clear"></div>
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entered Dt:</th> 
                      <th >Batch No</th>
                      <th >Customer</th>
                      <th >Batch Name</th>
                      <th ></th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					
					                        $Query = "SELECT tb1.`entered_date`,tb1.`tr_no`,tb2.`customer_name`,tb2.`customer_id`,tb1.`batch_name`,tb1.`insurance_master_id` FROM `tbl_cmt_ins_members_master` tb1 LEFT JOIN tbl_cmt_ins_customer_master tb2 ON tb1.`customer_id`=tb2.`customer_id` WHERE tb1.`customer_type`=1 GROUP BY tb1.`tr_no` ORDER BY tb1.`tr_no` DESC";
				                            $sqlQ  = mysqli_query($conn,$Query);
                                            while($build = mysqli_fetch_array($sqlQ)){
											/*$class = ($build['is_active'] == "1")? 'bg-success' : 'bg-danger';
											$label = ($build['is_active'] == "1")? 'label-success' : 'label-danger';
											$type  = ($build['is_active'] == "1")? 'Acive' : 'Inactive';*/
										?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['tr_no']?></td>
                      <td><?=$build['customer_name']?></td>
                      <td><?=$build['batch_name']?></td>
                      <td><a href="ins_members_premium_group.php?flag=<?=$build['customer_id']?>&gtoken=<?=$token?>" title="excel" class="btn btn-default btn-sm"/>New Quotation</td>
                      <td><a href="submit_member_batch_list_download_group.php?flag=<?=$build['tr_no']?>&gtoken=<?=$token?>" title="excel" class="btn btn-default btn-sm"/><i class="fa fa-file-excel-o"></i>
                      <a href="list_ins_premium_inner_group.php?flag=<?=$build['insurance_master_id']?>&gtoken=<?=$token?>" title="view"  class="btn btn-default btn-sm"/><i class="fa fa-eye"></i></a></td>
                     <?php /*?> <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td><?php */?>
                      <?php /*?><td>
                      <?php if($build['end_date']>=date('Y-m-d')){?><a href="edit_price_daily_master.php?flag=<?=$build['price_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></a><?php }?>
                      </td><?php */?>
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
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
$(document).ready( function () {
  $('#example2').DataTable({ "aaSorting": [[ 0, "desc" ]]})
})
</script>
