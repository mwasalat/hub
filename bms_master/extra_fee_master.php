<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$broker          = killstring($_REQUEST['broker']);
	$location        = $_REQUEST['location'];
	$cntA            = count($location);
	$airport_fee   = killstring($_REQUEST['airport_fee']);
	$airport_fee   = !empty($airport_fee) ? "'$airport_fee'" : "NULL";
	$vmd_fee   = killstring($_REQUEST['vmd_fee']);
	$vmd_fee   = !empty($vmd_fee) ? "'$vmd_fee'" : "NULL";
	$IsPageValid = true;	
	if(empty($broker)){
	$msg   = "Please select broker!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	$error_cnt = 0;
		   for($i=0; $i < $cntA; $i++){
		   $location = killstring($_REQUEST['location'][$i]);
		   $sql  = mysqli_query($conn,"INSERT IGNORE INTO `tbl_bms_extra_fee`(`broker_id`, `location_id`,`airport_fee`, `vmd_fee`,`entered_by`) VALUES ('$broker','$location',$airport_fee,$vmd_fee,'$userno') ON DUPLICATE KEY UPDATE `vmd_fee`= $vmd_fee, `airport_fee`=$airport_fee, `updated_date`=NOW(), `updated_by`='$userno' "); 
		  if(!$sql){
			$error_cnt = $error_cnt + 1;
		  }//sql
		 }//location end loop
		/*******ERROR CHECK****************/
	                if ($error_cnt==0) {
                        $alert_label = "alert-success"; 
                        $msg = "Excee fee Added successfully!";
                    } else {
                        $alert_label = "alert-danger"; 
                        $msg = "Excee fee not uploaded completely!";
                   }
	   /**********************************/
   }//valid rue	 	
 }
}

?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Extra Fee List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Extra Fee List</li>
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
          <h3 class="box-title">Extra Fee</h3>
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
                <select class="validate[required] form-control select2" name="broker" id="broker">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php   
				  $d1 = mysqli_query($conn,"SELECT `broker_name`, `broker_id` FROM `tbl_bms_broker_master` WHERE `is_active`=1 order by `broker_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['broker_id']?>">
                  <?=$b1['broker_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>

              <div class="form-group">
                <label class="">Airport Fee:</label>
                <input type="text" class="validate[custom[number]] form-control" value="0" name="airport_fee" placeholder="Airport Fee(If Applicable)" />
              </div>
              
                <?php /*?><div class="form-group">
                <label class="required">Status:</label>
                <select class="validate[required] form-control" name="status" id="status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div><?php */?>
             
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                 <label class="required">Location:</label>
                 <select class="validate[required] form-control select2" name="location[]" id="location" multiple>
                  <?php   
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_bms_location_master` WHERE `is_active`=1 order by `location_name`");
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
                <input type="text" class="validate[custom[number]] form-control" value="0" name="vmd_fee" placeholder="VMD Fee(If Applicable)"/>
              </div>
              
            </div>
            <!-- /.row -->
          </div>
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
          <h3 class="box-title">Extra Fee List</h3>
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
                      <th >Location</th>
                      <th >Airport Fee</th>
                      <th >VMD Fee</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT  tb1.`extra_fee_id`, tb1.`entered_date`,tb2.`location_name`,IFNULL(tb1.`airport_fee`,0) AS `airport_fee`,IFNULL(tb1.`vmd_fee`,0) AS `vmd_fee`, tb3.`broker_name`,tb1.`is_active` FROM `tbl_bms_extra_fee` tb1 LEFT JOIN `tbl_bms_location_master` tb2 ON tb1.`location_id`=tb2.`location_id`  LEFT JOIN `tbl_bms_broker_master` tb3 ON tb1.`broker_id`=tb3.`broker_id`  ORDER BY tb2.`location_name` ASC");
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
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['airport_fee']?></td>
                      <td><?=$build['vmd_fee']?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                      <td><a href="edit_extra_fee_master.php?flag=<?=$build['extra_fee_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
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
    $('#example2').DataTable({ "aaSorting": [[ 0, "desc" ]]})
  })
  $(function () {
	 //Timepicker
    $('.location_timepicker').timepicker({
        minuteStep: 1,
        showInputs: false,
        disableFocus: true,
        showMeridian: false,
		defaultTime: ''
		});
	
	
  })
</script>
