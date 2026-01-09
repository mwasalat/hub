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
   <a href="ins_ind_members_premium.php?gtoken=<?=$token?>" class="btn btn-warning" style="float:right"><i class="fa fa-plus"></i> Create Proposal</a>
    <h1>Proposals</h1>
  <!--  <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Price Insurance Premium List</li>
    </ol>-->
  </section>
  <!-- Main content -->
  <section class="content">
  
  <div class="clear"></div>
   <div id="tabs">
    <div id="tabs-ui">
    <ul>
          <li><a href="#tabs-1">Created</a></li>
          <li><a href="#tabs-2">Requests</a></li>
          <li><a href="#tabs-3">Drafts</a></li>
        </ul>
    </div>
        
        <!---------------------Start Tab 1----------------------->
        <div id="tabs-1">
    <form role="form" name="company-form1" id="company-form1" method="post" action="#" enctype="multipart/form-data">
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid" width="100%">
                  <thead>
                    <tr>
                      <th>Dt:</th> 
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Advisor</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					$i = 1;
					$Query = "SELECT tb1.*,tb2.`full_name` AS `advisor` FROM `tbl_cmt_ind_premium_transactions` tb1 LEFT JOIN `tbl_login` tb2 ON tb1.`entered_by`=tb2.`user_id` WHERE tb1.`is_active`=1 GROUP BY tb1.`tr_no` ORDER BY tb1.`tr_no` DESC";
				    $sqlQ  = mysqli_query($conn,$Query);
                    while($build = mysqli_fetch_array($sqlQ)){
					?>
                    <tr id="tr_row_<?=$i?>">
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['customer_first_name']?> <?=$build['customer_last_name']?></td>
                      <td><?=$build['customer_email']?></td>
                      <td><?=$build['customer_phone']?></td>
                      <td><?=$build['advisor']?></td>
                      <!--<td><a href="ins_members_premium_group.php?flag=<?=$build['customer_id']?>&gtoken=<?=$token?>" title="excel" class="btn btn-default btn-sm"/>New Quotation</td>-->
                      <td>
                      <a href="ins_premium_pdf.php?flag=<?=$build['ind_premium_id']?>&gtoken=<?=$token?>" title="pdf" target="_blank" class="btn btn-default btn-sm"/><i class="fa fa-file-pdf-o"></i></a>
                      <a href="ins_ind_members_premium.php?Newflag=<?=$build['tr_no']?>&gtoken=<?=$token?>" title="New Quotation" class="btn btn-default btn-sm"/>New Quotation</a>
                      <a href="#" title="Delete Quotation" class="btn btn-default btn-sm" onclick="delete_quotation('<?=$build['tr_no']?>','<?=$i?>')"/>Delete Quotation</a></td>
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
    </div>
    
     <div id="tabs-2">
     <form role="form" name="company-form1" id="company-form1" method="post" action="#" enctype="multipart/form-data">
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
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					$j = 1;
					$Query = "SELECT tb1.* FROM `tbl_cmt_ind_premium_transactions` tb1 WHERE tb1.`is_active`=2 GROUP BY tb1.`tr_no` ORDER BY tb1.`tr_no` DESC";
				    $sqlQ  = mysqli_query($conn,$Query);
                    while($build = mysqli_fetch_array($sqlQ)){
					?>
                    <tr id="tr_row_request<?=$j?>">
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['tr_no']?></td>
                      <td><?=$build['customer_first_name']?> <?=$build['customer_last_name']?></td>
                      <td><a href="ins_ind_members_premium.php?Newflag=<?=$build['tr_no']?>&gtoken=<?=$token?>" title="New Quotation" class="btn btn-default btn-sm"/>New Quotation</a><a href="#" title="Delete Quotation" class="btn btn-default btn-sm" onclick="delete_quotation_request('<?=$build['tr_no']?>','<?=$j?>')"/>Delete Quotation</a></td>
                    </tr>
                    <?php 
					$j++;
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
     </div>
     <div id="tabs-3">No data found!</div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
<script type="text/javascript">
$(document).ready(function(){
$("#company-form1").validationEngine();
$("#company-form2").validationEngine();

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
<link rel="stylesheet" href="../bower_components/jquery-ui/themes/base/jquery-ui.css">
<link rel="stylesheet" href="ins_premium.css">
<script src="../bower_components/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript">
  $( function() {
    $( "#tabs" ).tabs({
	"active": 0
	});
  });
  

function delete_quotation(tr_no,tr_id){
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_delete_quotation.php?tr_no="+tr_no,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
	$('#tr_row_'+tr_id).remove();
	alert("Quotation removed!");
    }else{
    alert("No item found!");
    }
}


function delete_quotation_request(tr_no,tr_id){
    var xmlhttp     = new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_cmt_delete_quotation.php?tr_no="+tr_no,false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
	$('#tr_row_request'+tr_id).remove();
	alert("Quotation removed!");
    }else{
    alert("No item found!");
    }
}
</script>