<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$category_name          = killstring($_REQUEST['category_name']);
	$category_status        = killstring($_REQUEST['category_status']);
	$IsPageValid = true;	
	if(empty($category_name)){
	$msg   = "Please enter category name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT IGNORE INTO `tbl_bms_category_master`(`category_name`,`is_active`,`entered_by`) VALUES ('$category_name','$category_status','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Category added successfully";   
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
    <h1>Category List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Category List</li>
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
          <h3 class="box-title">Category</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="required">Category Name:</label>
                <input type="text" class="validate[required] form-control" name="category_name" placeholder="Category Name"/>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="category_status" id="category_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
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
          <h3 class="box-title">Category List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Category</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_bms_category_master` ORDER BY `category_name` ASC");
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
                       <td><?=$build['category_name']?></td>
                       <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                       <td><a href="edit_category_master.php?flag=<?=$build['category_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
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
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]]})
  })
</script>