<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
$msg = "";
?>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Employees List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Employees List</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Employees List</h3>
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
                    <th width="5%">Sl No.</th>
                    <th width="5%" >EMP ID</th>
                    <th width="10%" >Name</th>
                    <th width="10%" >Gender</th>
                    <th width="10%" >Designation</th>
                    <th width="10%" >Email</th>
                    <th width="10%" >Mobile No</th>
                    <th width="10%" >Work Location</th>
                    <th width="10%" >Company</th>
                    <th width="10%" >Department</th>
                    <th width="10%" >Status</th>
                  </tr>
             
				 <tr>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th> 
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                  </tr>
				</thead>
                  <tbody>
                    <?php 
					
					                       $sql  = "SELECT * FROM tbl_emp_master ORDER BY `full_name` ASC";
				                           $sqlQ  = mysqli_query($conn,$sql);
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
											$status       = "";
											$status = $build['status'];
											?>
                  <tr>
                    <td><?=$itr?></td>
                    <td><?=$build['empid']?></td>
                    <td><?=$build['full_name']?></td>
                    <td><?=$build['gender']?></td>
                     <td><?=$build['designation']?></td>
                      <td><?=$build['email']?></td>
                       <td><?=$build['mobile_no']?></td>
                        <td><?=$build['location']?></td>
                        <td><?=$build['company']?></td>
                        <td><?=$build['department']?></td>
                        <td><?=$build['status']?></td>
					<!--<td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>-->
                    
                  </tr>
                    <?php 
					$itr++;
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
});
</script>
<!-- DataTables -->
<script src="../bower_components/select2/select2.js" type="text/javascript"></script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/dataTables.select.min.js"></script>
<?php /*?><script>
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

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script><?php */?>

<script type="text/javascript">
$(document).ready(function() {
 $('.select').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
 var oTable = $('#example2').dataTable({
		  "select": {
					toggleable: false
				},
		  /*"aoColumnDefs": [
				{ "bSortable": false, "aTargets": [  1, 2, 3, 5, 6, 7 ,8, 9, 10] }
			],*/
		 "aaSorting": [[ 0, "asc" ]], 
            initComplete: function () {
            count = 0;
            this.api().columns([1,2,3,5,6,7,8,9,10]).every( function () {
                var title = this.header();
                //replace spaces with dashes
                title = $(title).html().replace(/[\W]/g, '-');
                var column = this;
                var select = $('<select  class="select2 form-control" ></select>')
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
});     

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
