<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$route_name          = killstring($_REQUEST['route_name']);
	$route_from          = killstring($_REQUEST['route_from']);
	$route_to            = killstring($_REQUEST['route_to']);
	$route_code          = killstring($_REQUEST['route_code']);
	$route_status        = killstring($_REQUEST['route_status']);
	
	$route_amount   = killstring($_REQUEST['route_amount']);
	$route_amount   = !empty($route_amount) ? "'$route_amount'" : "NULL";
	$IsPageValid = true;	
	if(empty($route_name)){
	$msg   = "Please enter route name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 $sql     = "INSERT IGNORE INTO `tbl_bts_route_master`(`route_name`,`route_code`,`route_from`,`route_to`,`amount`,`status`,`entered_by`) VALUES ('$route_name','$route_code','$route_from','$route_to',$route_amount,'$route_status','$userno')";
		  $result  = mysqli_query($conn,$sql);
		  if($result){
		  $msg = "Rote added successfully";    
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
    <h1>Bus-Driver Mapping  List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Bus-Driver Mapping List</li>
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
          <h3 class="box-title">Bus-Driver Mapping</h3>
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
                <label class="required">Driver:</label>
                <select class="validate[required] form-control select2" name="route_from" id="route_from">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php   
				  $d1 = mysqli_query($conn,"SELECT `location_id`, `location_name` FROM `tbl_bts_location_master` WHERE `status`=1 order by `location_name`");
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
                <label class="required">Status:</label>
                <select class="validate[required] form-control" name="route_status" id="route_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Bus:</label>
                <select class="validate[required] form-control select2" name="route_to" id="route_to">
                  <option selected="selected" value="">---Select an option---</option>
                </select>
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
          <h3 class="box-title">Bus-Driver Mapping List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >ROute</th>
                      <th >Code</th>
                      <th >Start Point</th>
                      <th >End Point</th>
                      <th >Amount</th>
                      <th >Status</th>
                      <!--<th >Action</th>-->
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb2.`location_name` AS `route_from_name`,tb3.`location_name` AS `route_to_name`  FROM `tbl_bts_route_master` tb1 LEFT JOIN `tbl_bts_location_master` tb2 ON tb1.`route_from`=tb2.`location_id` LEFT JOIN `tbl_bts_location_master` tb3 ON tb1.`route_to`=tb3.`location_id`  ORDER BY tb1.`route_name` ASC");
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
                      <td><?=$build['route_name']?></td>
                      <td><?=$build['route_code']?></td>
                      <td><?=$build['route_from_name']?></td>
                      <td><?=$build['route_to_name']?></td>
                      <td><?=Checkvalid_number_or_not($build['amount'],2)?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                     <!-- <td><a href="edit_location_master.php?flag=<?=$build['location_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>-->
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
