<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$extra_fee_id_now        = killstring($_REQUEST['extra_fee_id_now']);
	$broker                  = killstring($_REQUEST['broker']);
	$location                = killstring($_REQUEST['location']);
	$status                  = killstring($_REQUEST['status']);
	
	$airport_fee   = killstring($_REQUEST['airport_fee']);
	$airport_fee   = !empty($airport_fee) ? "'$airport_fee'" : "NULL";
	$vmd_fee   = killstring($_REQUEST['vmd_fee']);
	$vmd_fee   = !empty($vmd_fee) ? "'$vmd_fee'" : "NULL";
	
	$IsPageValid = true;	
	if(empty($extra_fee_id_now)){  
	$msg   = "Error on ID!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql     = "UPDATE `tbl_bms_extra_fee` SET `airport_fee`=$airport_fee,`vmd_fee`=$vmd_fee,`is_active`='$status',`updated_by`='$userno',`updated_date`=NOW() WHERE `extra_fee_id`='$extra_fee_id_now' ";
		  $result  = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Extra Fee updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}


$extra_fee_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT * FROM `tbl_bms_extra_fee` WHERE `extra_fee_id`='$extra_fee_id'");
while($rowA = mysqli_fetch_array($qry)){
	$extra_fee_id_now     = $rowA['extra_fee_id'];
	$broker_id            = $rowA['broker_id'];
	$location_id          = $rowA['location_id'];
	$airport_fee          = !empty($rowA['airport_fee'])?$rowA['airport_fee']:0;
	$vmd_fee              = !empty($rowA['vmd_fee'])?$rowA['vmd_fee']:0;
	$is_active            = $rowA['is_active'];
	
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Extra Fee - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Extra Fee - Edit</li>
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
        <h3 class="box-title">Extra Fee - Edit</h3>
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
              <label class="required">Broker:</label>
              <select class="validate[required] form-control select2" name="broker" id="broker" disabled="disabled">
                <?php   
				  $d1 = mysqli_query($conn,"SELECT `broker_name`, `broker_id` FROM `tbl_bms_broker_master` WHERE `broker_id`='$broker_id'");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                <option value="<?=$b1['broker_id']?>" ><?=$b1['broker_name']?></option>
                <?php		
                  } 
                 ?>
              </select>
            </div>
             <div class="form-group">
                <label class="">Airport Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="<?=$airport_fee?>" name="airport_fee" placeholder="Airport Fee(If Applicable)"/>
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
          <div class="col-md-6">
          
          
             <div class="form-group">
                 <label class="required">Location:</label>
                 <select class="validate[required] form-control select2" name="location" id="location" disabled="disabled">
                  <?php   
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_bms_location_master` WHERE `location_id`='$location_id' ");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['location_id']?>">
                  <?=$b1['location_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
               <div class="form-group">
                <label class="">VMD Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="<?=$vmd_fee?>" name="vmd_fee" placeholder="VMD Fee(If Applicable)"/>
              </div>
            
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer"> <a href="extra_fee_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">BACK</a>
          <input type="hidden" name="extra_fee_id_now" value= "<?=$extra_fee_id_now?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">UPDATE</button>
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