<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$category_id_now        = killstring($_REQUEST['category_id_now']);
	$category_name          = killstring($_REQUEST['category_name']);
	$category_status        = killstring($_REQUEST['category_status']);
	$IsPageValid = true;	
	if(empty($category_id_now)){
	$msg   = "Error on ID!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	      $sql              = "UPDATE `tbl_bms_category_master` SET `category_name`='$category_name',`is_active`='$category_status',`updated_by`='$userno',`updated_date`=NOW() WHERE `category_id`='$category_id_now' ";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Category updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

$category_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_category_master` WHERE `category_id`='$category_id'");
while($rowA = mysqli_fetch_array($qry)){
	$category_id_now   = $rowA['category_id'];
	$category_name     = $rowA['category_name'];
	$is_active         = $rowA['is_active'];
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Category - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Category - Edit</li>
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
          <h3 class="box-title">Category - Edit</h3>
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
                <input type="text" class="validate[required] form-control" name="category_name" placeholder="Category Name" value="<?=$category_name?>"/>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                 <select class="validate[required] form-control" name="category_status" id="category_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1" <?php if($is_active==1){echo "selected";}?>>Active</option>
                  <option value="0" <?php if($is_active==0){echo "selected";}?>>Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="category_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='category_id_now' value= '<?=$category_id_now?>' >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Update</button>
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