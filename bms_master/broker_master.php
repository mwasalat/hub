<?php
require_once("../header_footer/header.php"); 
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$broker_name          = killstring($_REQUEST['broker_name']);
	$broker_mobile        = killstring($_REQUEST['broker_mobile']);
	$broker_landline      = killstring($_REQUEST['broker_landline']);
	$broker_email         = killstring($_REQUEST['broker_email']);
	$broker_city          = killstring($_REQUEST['broker_city']);
	$broker_country       = killstring($_REQUEST['broker_country']);
	$broker_address       = killstring($_REQUEST['broker_address']);
	$broker_status        = killstring($_REQUEST['broker_status']);
	$broker_gracetime     = killstring($_REQUEST['broker_gracetime']);
	$IsPageValid = true;	
	if(empty($broker_name)){
	$msg   = "Please enter broker name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql     = "INSERT IGNORE INTO `tbl_bms_broker_master`(`broker_name`,`broker_mobile`,`broker_landline`,`broker_email`, `broker_city`, `broker_address`,`broker_country`, `broker_gracetime`,`is_active`,`entered_by`) VALUES ('$broker_name','$broker_mobile','$broker_landline','$broker_email','$broker_city', '$broker_address','$broker_country','$broker_gracetime','$broker_status','$userno')";
		  $result  = mysqli_query($conn,$sql); 
		  $last_id = mysqli_insert_id($conn);
		  if($result){
		  $ip_address       = getIP();	   
		  $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS broker master insertion for ID:$last_id','6','$ip_address','$userno')");
		  $msg = "Broker added successfully! <b>Please add excess fee for location through excess master page!</b>";   
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
    <h1>Broker List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Broker List</li>
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
        <h3 class="box-title">Broker</h3>
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
              <label class="required">Broker Name:</label>
              <input type="text" class="validate[required] form-control" name="broker_name" placeholder="Broker Name"/>
            </div>
            <div class="form-group">
              <label class="required">Broker Landline:</label>
              <input type="text" class="validate[required,custom[phone]] form-control" name="broker_landline" placeholder="Broker Landline"/>
            </div>
            <div class="form-group">
              <label class="required">Broker City:</label>
              <input type="text" class="validate[required] form-control" name="broker_city" placeholder="Broker City"/>
            </div>
            <div class="form-group">
              <label class="required">Broker Country:</label>
              <select class="validate[required] form-control select2" name="broker_country" id="broker_country">
                <option selected="selected" value="">---Select an option---</option>
                <?php  
				  $d1 = mysqli_query($conn,"SELECT `name`, `id` FROM `tbl_country` order by `name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                <option value="<?=$b1['id']?>">
                <?=$b1['name']?>
                </option>
                <?php		
                  } 
                 ?>
              </select>
            </div>
           
            <div class="form-group">
              <label>API Grace Period Time:</label>
              <div class="input-group">
                <input type="text" id="grace_timepicker" class="validate[required] form-control" name="broker_gracetime" placeholder="API Grace Period Time" value="0:30">
                <div class="input-group-addon"><i class="fa fa-clock-o"></i> </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="required">Broker Mobile:</label>
              <input type="text" class="validate[required,custom[phone]] form-control" name="broker_mobile" placeholder="Broker Mobile"/>
            </div>
            <div class="form-group">
              <label class="required">Broker Email:</label>
              <input type="text" class="validate[required,custom[email]] form-control" name="broker_email" placeholder="Broker Email"/>
            </div>
            <div class="form-group">
              <label class="required">Broker Address:</label>
              <textarea class="validate[required] form-control" name="broker_address" placeholder="Broker Address" rows="5"/></textarea>
            </div>
            <div class="form-group">
              <label class="required">Status:</label>
              <select class="validate[required] form-control" name="broker_status" id="broker_status">
                <option selected="selected" value="">---Select an option---</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
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
          <h3 class="box-title">Group List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Broker</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_bms_broker_master` ORDER BY `broker_name` ASC");
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
                      <td><?=$build['broker_name']?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                      <td><a href="edit_broker_master.php?flag=<?=$build['broker_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
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
    $('#example2').DataTable({ "aaSorting": [[ 0, "desc" ]]})
	 //Timepicker
    $('#grace_timepicker').timepicker({
      showInputs: false,
	  showMeridian: false 
    });
  })
  
  
</script>