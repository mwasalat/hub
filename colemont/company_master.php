<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$company_name          = killstring($_REQUEST['company_name']);
	$company_code          = killstring($_REQUEST['company_code']);
	$company_address       = killstring($_REQUEST['company_address']);
	$company_phone         = killstring($_REQUEST['company_phone']);
	$company_fax           = killstring($_REQUEST['company_fax']);
	$company_person        = killstring($_REQUEST['company_person']);
	$company_email         = killstring($_REQUEST['company_email']);
	$IsPageValid = true;	
	if(empty($company_name)){
	$msg   = "Please enter company name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT INTO `tbl_cmt_ins_company_master`(`company_name`, `company_code`, `company_person`, `company_address`, `company_fax`, `company_phone`, `company_email`,`entered_by`) VALUES ('$company_name','$company_code','$company_person','$company_address','$company_fax','$company_phone','$company_email','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Insurance Company added successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>New Insurance Company</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Insurance Company</li>
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
          <h3 class="box-title">Company</h3>
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
                <label class="required">Company Name:</label>
                <input type="text" class="validate[required] form-control" name="company_name" placeholder="Company Name"/>
              </div>
              <div class="form-group">
                <label class="required">Company Address:</label>
                <input type="text" class="validate[required] form-control" name="company_address" placeholder="Company Address"/>
              </div>
               <div class="form-group">
                <label class="">Company Fax:</label>
                <input type="text" class="form-control" name="company_fax" placeholder="Company Fax"/>
              </div>
              <div class="form-group">
                <label class="required">Company Email:</label>
                <input type="text" class="validate[required,custom[email]] form-control" name="company_email" placeholder="Company Email"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Company Code:</label>
                <input type="text" class="validate[required] form-control" name="company_code" placeholder="Company Code"/>
              </div>
             
              <div class="form-group">
                <label class="required">Company Phone:</label>
                <input type="text" class="validate[required] form-control" name="company_phone" placeholder="Company Phone"/>
              </div>
               <div class="form-group">
                <label class="">Company Concerned Person:</label>
                <input type="text" class=" form-control" name="company_person" placeholder="Company Concerned Person"/>
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
          <h3 class="box-title">Company List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid" width="100%;">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Name</th>
                      <th >Code</th>
                      <th >Address</th>
                      <th >Phone</th>
                      <th >Fax</th>
                      <th >Email</th>
                      <th >Person</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_cmt_ins_company_master` ORDER BY `entered_date` DESC");
										   $itr = 0;
                                            while($build = mysqli_fetch_array($sqlQ)){
										       // Status
											   if($build['is_active'] == "1") {
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
                      <td><?=$build['company_code']?></td> 
                      <td><?=$build['company_address']?></td>
                      <td><?=$build['company_phone']?></td>
                      <td><?=$build['company_fax']?></td>
                      <td><?=$build['company_email']?></td>
                      <td><?=$build['company_person']?></td>
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
