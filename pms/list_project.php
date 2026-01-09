<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
?>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Project List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Project List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Projecct List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >Code</th>
                      <th >Name</th>
                      <th >Manager</th>
                      <th >Client</th>
                      <th >Start Dt.</th>
                      <th >Expected <br/> End Dt.</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                    	<tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th> 
                          <th></th> 
                          <th></th>
                          <th style="display:none;"></th>
                        </tr>
                  </thead>
                  <tbody>
                    <?php 
					
					                      $Query = "SELECT tb1.*,tb2.`emp_name` AS `mgr_name`,tb3.`client_name` AS `client_name`,tb4.`status_name` AS `status` FROM `tbl_pms_projects` tb1 LEFT JOIN `tbl_pms_employees` tb2 ON tb1.`mgr_id`=tb2.`emp_code`  LEFT JOIN `tbl_pms_clients` tb3 ON tb1.`client_id`=tb3.`client_id` LEFT JOIN `tbl_pms_status_master` tb4 ON tb1.`status_id`=tb4.`status_id` WHERE tb1.`is_active`=1 ORDER BY tb1.`entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
										   $itr = 0;
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
												 // Status
											   if($build['status_id'] == "1") {
													$class = 'bg-success';
													$label = "label-success";
													$type  = $build['status'];
												} else{
													$class = 'bg-danger';
													$type  = $build['status'];
													$label = "label-danger";
												}
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                         <td><?=$build['project_code']?></td>
                         <td><?=$build['project_name']?></td>
                         <td><?=$build['mgr_name']?></td>
                         <td><?=$build['client_name']?></td>
                         <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                         <td data-order="<?=date('Y-m-d',strtotime($build['expected_end_date']))?>"><?=date('d-m-Y',strtotime($build['expected_end_date']))?></td>
                      <?php /*?><td><div class="status label <?=$label?>"><b><?=$type?></b></div></td><?php */?>
                      <td><?=$build['status']?></td>
                      <td>
                      <a href="view_booking.php?flag=<?=$build['booking_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-eye"></i></a>
                     <?php /*?> <?php if($build['price_date']>=date('Y-m-d')){?><a href="edit_price_daily_master.php?flag=<?=$build['booking_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></a><?php }?><?php */?>
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
<script src="../plugins/datatable_1_10_16/dataTables.select.min.js"></script>
<script>
$(document).ready( function () {
  var table = $('#example2').DataTable({
  "select": {
            toggleable: false
        },
  "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0, 1, 2, 3,4, 5, 6, 7 ,8] }
    ],
	"aaSorting": [[ 0, "desc" ]], 
            initComplete: function () {
            count = 0;
            this.api().columns([0,1,2,3,4,5,6,7,8]).every( function () {
                var title = this.header();
                //replace spaces with dashes
                title = $(title).html().replace(/[\W]/g, '-');
                var column = this;
                var select = $('<select id="' + title + '" class="select2 form-control" ></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                      //Get the "text" property from each selected data 
                      //regex escape the value and store in array
                      var data = $.map( $(this).select2('data'), function( value, key ) {
                        return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                                 });
                      
                      //if no data selected use ""
                      if (data.length === 0) {
                        data = [""];
                      }
                      
                      //join array into string with regex or (|)
                      var val = data.join('|');
                      
                      //search for the option(s) selected
                      column
                            .search( val ? val : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
              
              //use column title as selector and placeholder
              $('.select2').select2({
                multiple: true,
                closeOnSelect: false,
                placeholder: "Select options"
              });
              
              //initially clear select otherwise first option is selected
              $('.select2').val(null).trigger('change');
            } );
        }
  });
})
</script>
