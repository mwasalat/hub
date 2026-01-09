<?php
require_once("../header_footer/header.php"); 
/*phpinfo();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$module_name          = killstring($_REQUEST['module_name']);
	$module_description   = killstring($_REQUEST['module_description']);
	$module_code          = killstring($_REQUEST['module_code']);
	$IsPageValid = true;	
	if(empty($module_name)){
	$msg   = "Please enter module name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql     = "INSERT IGNORE INTO `tbl_tms_module`(`name`,`code`,`description`,`entered_by`,`entered_date`) VALUES ('$module_name','$module_code','$module_description','$userno',NOW())";
		  $result  = mysqli_query($conn,$sql); 
		  $last_id = mysqli_insert_id($conn);
		  if($result){
		  /************Purchases DETAILS**************/
		  $cntC                = 0;
		  $member_empid        = $_REQUEST["member_empid"];
		  $cntC                = count($member_empid);
		  for($i=0; $i < $cntC; $i++){
		   $member_empid_no     = killstring($_REQUEST['member_empid'][$i]);
		    if(!empty($member_empid_no)){
			$sqlQryA           = mysqli_query($conn,"INSERT IGNORE INTO `tbl_tms_module_members`(`module_id`, `empid`, `entered_by` , `entered_date`) VALUES ('".$last_id."','".$member_empid_no."','".$userno."',NOW())");
			}
		 }
		 /************Purchases DETAILS**************/		  
		  $msg = "Module added successfully";   
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
    <h1>TMS Module Master</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">TMS Module Master</li>
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
          <h3 class="box-title">TMS Module</h3>
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
                <label class="required">Module Name:</label>
                <input type="text" class="validate[required] form-control" name="module_name" placeholder="Module Name"/>
              </div>
               <div class="form-group">
                <label class="required">Module Code:</label>
                <input type="text" class="validate[required,minSize[3],maxSize[3],custom[onlyLetterNumber]] form-control" name="module_code" placeholder="Module Code"/>
              </div>
               
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label class="">Module Description:</label>
                <textarea type="text" class="form-control" name="module_description" placeholder="Module Description" rows="5"></textarea>
              </div>
            </div>
            
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
          <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Add Ticketing Members</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 5px;">
                  <div class="col-md-6">
                    <div class="input-group"> <span class="input-group-btn">
                      <button class="btn btn-default" type="button" name="submit" onClick="add_member()">+ Add Member</button>
                      </span> </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Member</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="cbm_tr_member">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="box-footer">
            <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
            <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
          </div>
        </div>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">TMS Module List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Module</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_tms_module` ORDER BY `name` ASC");
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
                      <td><?=$build['name']?></td>
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
	 //Timepicker
    $('#grace_timepicker').timepicker({
      showInputs: false,
	  showMeridian: false 
    });
  })
  
 //Add new Purchase
function add_member(){
    var scntDiv = $('#cbm_tr_member');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_tms_member.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=0){
    scntDiv.append(xmlhttp.responseText);
	$('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
    });
    }
}  
//Remove new Purchase
function remove_member_added(tr_id){
			$('#added_row_'+tr_id).remove();
} 
</script>