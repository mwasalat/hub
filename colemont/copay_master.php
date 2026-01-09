<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$company               = killstring($_REQUEST['company']);
	$copay_name            = killstring($_REQUEST['copay_name']);
	$copay_description     = killstring($_REQUEST['copay_description']);
	$IsPageValid = true;	
	if(empty($copay_name)){
	$msg   = "Please enter copay name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT INTO `tbl_cmt_copay_master`(`company_id`, `copay_name`, `copay_description`, `entered_by`) VALUES ('$company','$copay_name','$copay_description','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Copay added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-error";    
		  }
   }//valid rue	 	
 }
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>New Copay</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Copay</li>
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
      <?php }?>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Copay</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Insurance Company:</label>
                <select class="validate[required] form-control select" name="company" id="company" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `company_name`, `company_code`, `company_id` FROM `tbl_cmt_ins_company_master` WHERE `is_active`=1 order by `company_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['company_id']?>"><?=$b1['company_name']?></option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Copay Description:</label>
                <textarea class="validate[required] form-control" name="copay_description" placeholder="Copay Description"/></textarea>
              </div>
               
            </div>
            <div class="col-md-6">
            
             <div class="form-group">
                <label class="required">Copay Name:</label>
                <input type="text" class="validate[required] form-control" name="copay_name" placeholder="Copay Name"/>
              </div>
             
               
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
        </div>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Copay List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid" width="100%;">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Company</th>
                      <th >Name</th>
                      <th >Description</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb2.`company_name` FROM `tbl_cmt_copay_master` tb1 LEFT  JOIN `tbl_cmt_ins_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` ORDER BY tb1.`entered_date` DESC");
										   $itr = 0;
                                            while($build = mysqli_fetch_array($sqlQ)){
										       // Status
											   if($build['status'] == "1") {
													$class = 'bg-success';
													$label = "label-success";
													$type = 'Acive';
												} else{
													$class = 'bg-danger';
													$type  = 'Inactive';
													$label = "label-danger";
												}
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['company_name']?></td>
                      <td><?=$build['copay_name']?></td>
                      <td><?=$build['copay_description']?></td> 
                    <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
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
$("#company-form").validationEngine();
$('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
}); 
});
</script>
<!-- DataTables -->
<!--<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ 
		scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
         columns: [
                    { "width": "10%" },
                    { "width": "25%" },
                    { "width": "10%" },
                    { "width": "20%" },
					{ "width": "10%" },
					{ "width": "10%" },
					{ "width": "5%" },
					{ "width": "5%" }
                ],
        fixedColumns: true,"aaSorting": [[ 0, "desc" ]]})
  })
</script>-->
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
<script type="text/javascript">
$(document).ready(function() {
   $('#example2').DataTable({
      "aaSorting": [[ 0, "desc" ]]
   });
});     
</script>
