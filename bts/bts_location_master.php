<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$location_name          = killstring($_REQUEST['location_name']);
	$location_code          = killstring($_REQUEST['location_code']);
	$location_address       = killstring($_REQUEST['location_address']);
	$location_latitude      = killstring($_REQUEST['location_latitude']);
	$location_longitude     = killstring($_REQUEST['location_longitude']);
	$emirates_id            = killstring($_REQUEST['emirates_id']);
	$location_status        = killstring($_REQUEST['location_status']);
	$IsPageValid = true;	
	if(empty($location_name)){
	$msg   = "Please enter location name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 $sql     = "INSERT IGNORE INTO `tbl_bts_location_master`(`location_name`,`location_code`,`location_address`,`location_latitude`, `location_longitude`, `emirates_id`, `status`,`entered_by`) VALUES ('$location_name','$location_code','$location_address','$location_latitude','$location_longitude', '$emirates_id','$location_status','$userno')";
		  $result  = mysqli_query($conn,$sql);
		  if($result){
		  $msg = "Location added successfully";    
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
    <h1>Location List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Location List</li>
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
          <h3 class="box-title">Location</h3>
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
                <label class="required">Location Name:</label>
                <input type="text" class="validate[required] form-control" name="location_name" placeholder="Location Name"/>
              </div>
              <div class="form-group">
                <label class="required">Emirate:</label>
                <select class="validate[required] form-control select2" name="emirates_id" id="emirates_id">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php   
				  $d1 = mysqli_query($conn,"SELECT `emirates_id`, `emirates_name` FROM `tbl_emirates_master` WHERE `is_active`=1 order by `emirates_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['emirates_id']?>">
                  <?=$b1['emirates_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
          
              <div class="form-group">
                <label class="required">Location Latitude:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="location_latitude" placeholder="Location Latitude"/>
              </div>
              <div class="form-group">
                <label class="required">Location Longitude:</label>
                <input type="text" class="validate[required,custom[number]] form-control" name="location_longitude" placeholder="Location Longitude"/>
              </div>

              
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label class="required">Location Code:</label>
                <input type="text" class="validate[required] form-control" name="location_code" placeholder="Location Code"/>
              </div>
              <div class="form-group">
                <label class="required">Location Address:</label>
                <textarea class="validate[required] form-control" name="location_address" placeholder="Location Address" rows="5"/></textarea>
              </div>
              <div class="form-group">
                <label class="required">Status:</label>
                <select class="validate[required] form-control" name="location_status" id="location_status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
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
          <h3 class="box-title">Location List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Location</th>
                      <th >Code</th>
                       <th >Latitude</th>
                        <th >Longitude</th>
                      <th >Emirate</th>
                      <th >Status</th>
                      <!--<th >Action</th>-->
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb2.`emirates_name` FROM `tbl_bts_location_master` tb1 LEFT JOIN `tbl_emirates_master` tb2 ON tb1.`emirates_id`=tb2.`emirates_id` ORDER BY tb1.`location_name` ASC");
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
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['location_code']?></td>
                       <td><?=$build['location_latitude']?></td>
                        <td><?=$build['location_longitude']?></td>
                      <td><?=$build['emirates_name']?></td>
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
