<?php
require_once("../header_footer/header.php"); 
/*phpinfo();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$os_name          = killstring($_REQUEST['os_name']);
	$os_description   = killstring($_REQUEST['os_description']);
	$IsPageValid = true;	
	if(empty($os_name)){
	$msg   = "Please enter name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql     = "INSERT IGNORE INTO `tbl_ams_os_master`(`os_name`,`os_description`,`entered_by`,`entered_date`) VALUES ('$os_name','$os_description','$userno',NOW())";
		  $result  = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Asset OS added successfully";   
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
    <h1>AMS OS Master</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">AMS OS Master</li>
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
          <h3 class="box-title">AMS OS</h3>
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
                <label class="required">OS Name:</label>
                <input type="text" class="validate[required] form-control" name="os_name" placeholder="OS Name"/>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label class="">OS Description:</label>
                <textarea type="text" class="form-control" name="os_description" placeholder="OS Description" rows="5"></textarea>
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
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">AMS OS List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >OS Type</th>
                      <th >OS Description</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_ams_os_master` ORDER BY `os_name` ASC");
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
                      <td><?=$build['os_name']?></td>
                      <td><?=$build['os_description']?></td>
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
$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]]})
  })
</script>