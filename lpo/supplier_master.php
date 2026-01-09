<?php 
require_once("../header_footer/header.php");
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$supplier_name          = killstring($_REQUEST['supplier_name']);
	$supplier_code          = killstring($_REQUEST['supplier_code']);
	$concerned_person       = killstring($_REQUEST['concerned_person']);
	$fax_no                 = killstring($_REQUEST['fax_no']);
	$phone_no               = killstring($_REQUEST['phone_no']);
	$email                  = killstring($_REQUEST['email']);
	
	$IsPageValid = true;	
	if(empty($supplier_name)){
	$msg   = "Please enter supplier name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT INTO `tbl_supplier_master`(`supplier_name`, `supplier_code`, `concerned_person`, `fax_no`, `phone_no`,`email`,`entered_by`) VALUES ('$supplier_name','$supplier_code','$concerned_person','$fax_no','$phone_no','$email','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Supplier added successfully";   
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
    <h1> New Supplier </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Suppler</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="supplier-form" id="supplier-form" method="post" action="#">
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
          <h3 class="box-title">Supplier</h3>
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
                <label class="required">Supplier Name:</label>
                <input type="text" class="validate[required] form-control" name="supplier_name" placeholder="Supplier Name">
              </div>
              <div class="form-group">
                <label class="required">Concerned Person:</label>
                <input type="text" class="validate[required] form-control" name="concerned_person" placeholder="Concerned Person">
              </div>
              <div class="form-group">
                <label class="">Fax:</label>
                <input type="text" class="form-control" name="fax_no" placeholder="Fax">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Supplier Code:</label>
                <input type="text" class="form-control" name="supplier_code" placeholder="Supplier Code">
              </div>
              <div class="form-group">
                <label class="required">Phone No:</label>
                <input type="text" class="validate[required] form-control" name="phone_no" placeholder="Phone No">
              </div>
              <div class="form-group">
                <label class="required">Email:</label>
                <input type="text" class="validate[required,custom[email]] form-control" name="email" placeholder="Email">
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
          <h3 class="box-title">Supplier List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Supplier Name</th>
                      <th >Supplier Code</th>
                      <th >Concerned Person</th>
                      <th >Phone No</th>
                      <th >Fax</th>
                      <th >Email</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sql  = "SELECT * FROM `tbl_supplier_master`  ORDER BY `entered_date` DESC";
				                           $data = mysqli_query($conn,$sql);
										   $itr = 0;
                                            while($build = mysqli_fetch_array($data)){ 
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
                      <td><?=$build['supplier_name']?></td>
                      <td><?=$build['supplier_code']?></td>
                      <td><?=$build['concerned_person']?></td>
                      <td><?=$build['phone_no']?></td>
                      <td><?=$build['fax_no']?></td>
                      <td><?=$build['email']?></td>
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
$("#supplier-form").validationEngine();
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ 
		  autowidth: false,					 
        columns: [
                    { "width": "10%" },
                    { "width": "25%" },
                    { "width": "10%" },
                    { "width": "20%" },
                    { "width": "10%" },
					{ "width": "10%" },
					{ "width": "10%" },
					{ "width": "5%" }
                ],
     "aaSorting": [[ 0, "desc" ]]});
  })
</script>
<!--<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<script src="../plugins/datatable_1_10_16/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $('#example2').DataTable({
      "aaSorting": [[ 0, "desc" ]]
   });
});     
</script>
-->