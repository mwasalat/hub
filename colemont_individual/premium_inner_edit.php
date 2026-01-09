<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
ini_set('max_execution_time', 0); //0=NOLIMIT
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$price_id        = killstring($_REQUEST['price_id_now']);
	$premium           = killstring($_REQUEST['premium']);
	$status            = killstring($_REQUEST['status']);
	$IsPageValid = true;
	if(empty($price_id)){
	$alert_label = "alert-danger"; 
	$msg   = "Error on price";
	$IsPageValid = false;
	}else{
	//do nothing
	}
    
	if($IsPageValid==true){//valid - true
	                echo $sql = "UPDATE `tbl_cmt_ins_price_master`  SET `price`='$premium',`is_active`='$status' WHERE `price_id`='$price_id'";
					$Query         = mysqli_query($conn,$sql); 
	                if ($Query) {
                        $alert_label = "alert-success"; 
                        $msg = "Premium updated successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Error!";
                   }
	   /**********************************/
   }//valid rue	 	
 }
}

$price_id = killstring($_REQUEST['flag']);
if(!empty($price_id)){
	 $sql = mysqli_query($conn,"SELECT tb1.*,tb2.`company_name`,tb3.`emirates_name` FROM `tbl_cmt_ins_price_master` tb1 LEFT JOIN `tbl_cmt_ins_company_master` tb2 ON tb1.`company_id`=tb2.`company_id` LEFT JOIN `tbl_emirates_master` tb3 ON tb1.`emirate_id`=tb3.`emirates_id` WHERE tb1.`price_id`='$price_id'");
	 while($build = mysqli_fetch_array($sql)){
		$price_batch_id= $build['price_batch_id'];
		$price_id      = $build['price_id'];
	 	$emirates_name = $build['emirates_name'];  
		$relation_type = $build['relation_type'];  
		$gender        = $build['gender'];  
		$marital_status= $build['marital_status'];  
		$start_age     = $build['start_age'];  
		$end_age       = $build['end_age'];  
		$price         = $build['price'];  
		$is_active     = $build['is_active'];  
		
	 }
}
?>
<!-- Content Wrapper. Contains page content -->
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Premium Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Premium Edit</li>
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
         <div style="height: 10px;"></div>
          <h3 class="box-title">Premium Edit</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div style="height: 10px;"></div>
          <div style="height: 10px;"></div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Emirate:</label>
                <div class="input-group"><?=$emirates_name?></div>
              </div>
              <div class="form-group">
                <label class="required">Relation Type:</label>
                <div class="input-group"><?=$relation_type?></div>
              </div>
              <div class="form-group">
                <label class="required">Start Age:</label>
                <div class="input-group"><?=$start_age?></div>
              </div>
              <div class="form-group">
                <label class="required">Premium:</label>
                <input type="text" name="premium" class="validate[required,custom[number]] form-control" value="<?=$price?>" placeholder="Premium">
              </div>
    
               
            </div>
            <div class="col-md-6">
            
             <div class="form-group">
                <label class="required">Gender:</label>
                <div class="input-group"><?=$gender?></div>

              </div>
              <div class="form-group">
                <label class="required">Marital Status:</label>
                <div class="input-group"><?=$marital_status?></div>
              </div>
              <div class="form-group">
                <label class="required">End Age:</label>
                <div class="input-group"><?=$end_age?></div>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                  <select class="validate[required] form-control" name="status" id="status">
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
          <input type="hidden" name='price_id_now' value= "<?=$price_id?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <a href="list_premium_inner.php?flag=<?=$price_batch_id?>&gtoken=<?=$token?>" class="btn btn-warning">BACK</a>
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
$('.select').select2({
    placeholder: 'Select an option',
	width: 'resolve' // need to override the changed default
}); 
$('.datepickerA').datepicker({
    format: 'dd-mm-yyyy',
  /*  startDate: '-0d',*/
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
</script>
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
