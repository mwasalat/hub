<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$broker          = killstring($_REQUEST['broker']);
	$status          = killstring($_REQUEST['status']);
	$from            = killstring($_REQUEST['from']);
	$to              = killstring($_REQUEST['to']);
	$start_date      = killstring($_REQUEST['start_date']);
	$start_date      = date('Y-m-d',strtotime($start_date));
	$end_date        = killstring($_REQUEST['end_date']);
	$end_date        = date('Y-m-d',strtotime($end_date));
	$discount        = killstring($_REQUEST['discount']);
	$range_id_now    = killstring($_REQUEST['range_id_now']);
	$IsPageValid = true;	
	if(empty($range_id_now)){
	$msg   = "Error on range id!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	 $sqlnewquery = mysqli_query($conn,"UPDATE `tbl_bms_range_pricing_master` SET  `range_from`='$from',`range_to`='$to',`range_price`='$discount',`start_date`='$start_date',`end_date`='$end_date',`is_active`='$status', `updated_by`='$userno',`updated_date`=NOW() WHERE `range_id`='$range_id_now'");
		  if($sqlnewquery){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS range pricing  individual price edit for ID:$range_id_now','10','$ip_address','$userno')");  
		  $msg = "Range price updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}

$range_id = killstring($_REQUEST['flag']);
$qry    = mysqli_query($conn,"SELECT tb1.*,tb2.`display_name`,tb3.`broker_name`,tb4.`location_name`,tb5.`group_name` FROM `tbl_bms_range_pricing_master` tb1 LEFT JOIN `tbl_bms_pricing_parameter_master` tb2 ON tb1.`parameter_id`=tb2.`parameter_id` LEFT JOIN `tbl_bms_broker_master` tb3 ON tb1.`broker_id`=tb3.`broker_id` LEFT JOIN `tbl_bms_location_master` tb4 ON tb1.`location_id`=tb4.`location_id` LEFT JOIN `tbl_bms_group_master` tb5 ON tb1.`group_id`=tb5.`group_id` WHERE tb1.`range_id`='$range_id'");
while($rowA = mysqli_fetch_array($qry)){
	$range_id_now   = $rowA['range_id'];
	$broker_id      = $rowA['broker_id'];
	$location_id    = $rowA['location_id'];
	$location_name  = $rowA['location_name'];
	$group_id       = $rowA['group_id'];
	$group_name     = $rowA['group_name'];
	$range_id_now   = $rowA['range_id'];
	$range_from     = $rowA['range_from'];
	$range_to       = $rowA['range_to'];
	$range_price    = $rowA['range_price'];
	$start_date     = $rowA['start_date'];
	$end_date       = $rowA['end_date'];
	$display_name   = $rowA['display_name'];
	$broker_name    = $rowA['broker_name'];
	$is_active      = $rowA['is_active'];
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Range Pricing - Edit</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Range Pricing  - Edit</li>
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
          <h3 class="box-title">Range Pricing  - Edit</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
          
           <div class="col-md-12">
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Broker:</label>
                  <div class="input-group"><p class="text-muted"><?=$broker_name?></p></div>
              </div>
            </div>
             <div class="col-md-4">
              <div class="form-group">
                <label class="required">Location:</label>
                  <div class="input-group"><p class="text-muted"><?=$location_name?></p></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Group:</label>
                 <div class="input-group"><p class="text-muted"><?=$group_name?></p></div>
              </div>
            </div>
            </div>
            
             <div class="col-md-12">
              <div class="col-md-4">
              <div class="form-group">
                <label class="required">Start Date:</label>
                   <input type="text" name="start_date" class="validate[required] datepickerA form-control" value="<?=!empty($start_date)?date('d-m-Y',strtotime($start_date)):NULL;?>" readonly="readonly" style="background:#fff;">
              </div>
            </div>
              <div class="col-md-4">
              <div class="form-group">
                <label class="required">End Date:</label>
                <input type="text" name="end_date" class="validate[required] datepickerA form-control" value="<?=!empty($end_date)?date('d-m-Y',strtotime($end_date)):NULL;?>" readonly="readonly" style="background:#fff;">
              </div>
            </div>
            <div class="col-md-4">
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
            <div class="col-md-12">
              <!--------------Range Pricing------------->
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-centered" role="grid" >
                  <thead>
                    <tr>
                      <th><label class="required">Parameter</label></th>
                      <th><label class="required">From Day</label></th>
                      <th><label class="required">To Day</label></th>
                      <th><label class="required">Amount</label></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><center><?=$display_name?></center><?php /*?><input type="text" class="form-control"  value="<?=$display_name?>" name="parameter" readonly/><?php */?></td>
                      <td>
                      <select class="validate[required] form-control select2" name="from"> 
                      <option selected="selected" value="">---Select an option---</option>
                      <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>" <?php if($i==$range_from){echo "selected";}?>><?=$i?></option><?php }?>
                      </select>
                      </td>
                      <td>
                      <select class="validate[required] form-control select2" name="to"> 
                      <option selected="selected" value="">---Select an option---</option>
                      <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>" <?php if($i==$range_to){echo "selected";}?>><?=$i?></option><?php }?>
                      </select>
                      </td>
                      <td><input type="text"  name="discount" value="<?=$range_price?>" class="validate[custom[number],minSize[1],maxSize[6]] form-control" placeholder="Amount"/></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!----------------------Range Pricing-------------->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="range_pricing_master.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Back</a>
          <input type="hidden" name='range_id_now' value= '<?=$range_id_now?>' >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
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
$('.select2').select2({
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
