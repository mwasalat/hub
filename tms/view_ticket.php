<?php
require_once("../header_footer/header.php"); 
if(isset($_REQUEST['flag'])){
	$task = $_REQUEST["flag"];
	$sql  = mysqli_query($conn,"SELECT tb1.`ticket_id`, tb1.`ticket_no`, tb1.`subject`, tb1.`description`, tb1.`priority`, tb1.`module_id`,tb1.`estimated_date`, tb1.`entered_by`,tb1.`entered_date`,tb1.`updated_date`,tb1.status,tb2.`status_name`,tb3.`full_name` AS `requestor`,tb4.`full_name` AS `assignee`,tb5.`priority_name` AS `priority_name`,tb6.`name` AS `module_name`,tb7.`sub_name` AS `sub_module_name` FROM `tbl_tms_ticket` tb1 INNER JOIN `tbl_tms_status_master` tb2 ON tb1.`status`=tb2.`status_id` LEFT JOIN `tbl_login` tb3 ON tb1.`entered_by`=tb3.`user_id` LEFT JOIN `tbl_login` tb4 ON tb1.`assigned_to`=tb4.`empid` INNER JOIN `tbl_tms_priority_master` tb5 ON tb1.`priority`=tb5.`priority_id` INNER JOIN `tbl_tms_module` tb6 ON tb1.`module_id`=tb6.`module_id` LEFT JOIN `tbl_tms_sub_module` tb7 ON tb1.`sub_module_id`=tb7.`sub_module_id`  WHERE tb1.`ticket_id` ='$task'");
	while($build = mysqli_fetch_array($sql)){ 
	 $ticket_id =  $build['ticket_id'];
	  $ticket_no =  $build['ticket_no'];
	   $subject   =  $build['subject'];
	    $description =  $build['description'];
		 $module_id =  $build['module_id'];
		  $priority =  $build['priority'];
		   $status =  $build['status'];
		    $module_name      =  $build['module_name'];
			$sub_module_name  =  $build['sub_module_name'];
			 $assignee         =  $build['assignee'];
			$priority_name    =  $build['priority_name'];
			$status_name      =  $build['status_name'];
			                                    if($status == "1") {
													$class = 'bg-danger';
													$type  = $build['status_name'];
													$label = "label-danger";
												}else if($status == "4") {
													$class = 'bg-success';
													$type  = $build['status_name'];
													$label = "label-success";
												}else if($status == "2") {
													$class = 'bg-info';
													$type  = $build['status_name'];
													$label = "label-info";
												}else if($status == "3") {
													$class = 'bg-warning';
													$type  = $build['status_name'];
													$label = "label-warning";
												}else if($status == "5") {
													$class = 'bg-maroon';
													$type  = $build['status_name'];
													$label = "label-maroon";
												}else{
												//do nothing
												}
													// priority
											   if($build['priority'] == "1") {
													$feed_class = 'bg-success';
													$feed_type  = 'Low';
													$feed_label = "label-success";
												}else if($build['priority'] == "2") {
													$feed_class = 'bg-warning';
													$feed_type  = 'Medium';
													$feed_label = "label-warning";
												}
												else if($build['priority'] == "3") {
													$feed_class = 'bg-danger';
													$feed_label = "label-danger";
													$feed_type = 'High';
												}else{
												//do nothing
												}
	}
}
					                       
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>TMS Ticket</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">TMS Ticket :
        <?=$ticket_no?>
      </li>
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
          <h3 class="box-title">TMS Ticket :
            <?=$ticket_no?>
          </h3>
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
                <label class="">Ticket Module:</label>
                <div class="input-group"><?=$module_name?></div>
              </div>
              <div class="form-group">
                <label class="">Ticket Subject:</label>
                <div class="input-group"><?=$subject?></div>
              </div>
              
                <div class="form-group">
                <label class="">Ticket Description:</label>
                <div class="input-group"><?=nl2br($description)?></div>
              </div>
              
            
            </div>
            <div class="col-md-6">
            <?php if(!empty($sub_module_name)){?>
            <div class="form-group">
                <label class="">Ticket Sub Module:</label>
                <div class="input-group"><?=$sub_module_name?></div>
              </div>
              <?php }?>
              <div class="form-group">
                <label class="">Assigned To:</label>
                 <div class="input-group"><?=$assignee?></div>
              </div>
                <div class="form-group">
                <label class="">Priority:</label>
                 <div class="input-group"><div class="status label <?=$feed_label?>"><b><?=$feed_type?></b></div></div>
              </div>
                 
               <div class="form-group">
                <label class="">Status:</label>
                 <div class="input-group"><div class="status label <?=$label?>"><b><?=$type?></b></div></div>
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!-- /.box-body -->
          <!---------------Documents Section--------------->
        <?php 
        $sqlA           = mysqli_query($conn,"SELECT tb1.`document_name`, tb1.`document`, tb1.`entered_date`,tb2.`full_name` FROM `tbl_tms_documents` tb1 LEFT JOIN `tbl_login` tb2 ON tb1.`entered_by`=tb2.`user_id` WHERE tb1.`ticket_id`='$ticket_id' AND tb1.`is_active`=1 ORDER BY tb1.`entered_date` DESC");
		$NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
		if($NumA>0){
		?>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Documents History</h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Dt.</th>
                          <th>Name</th>
                          <th>Document</th>
                          <th>By</th>
                        </tr>
                      </thead>
                      <tbody >
                        <?php		  
						 while($buildA = mysqli_fetch_array($sqlA)){ 
						 $u_pathn = '../uploads/tms/documents/'; 
						 ?>
                        <tr>
                          <td><?=date('d-m-Y',strtotime($buildA['entered_date']))?></td>
                          <td><?=$buildA['document_name']?></td>
                          <td><?php if ($buildA['document'] != '' && file_exists($u_pathn . $buildA['document'] )) {?>
                            <a href="<?=$u_pathn . $buildA['document']?>" target="_blank"><i class="fa fa-file-o fa-lg"></i></a>
                            <?php }?></td>
                          <td><?=$buildA['full_name']?></td>
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
        </div>
        <?php }?>
        <!---------------Documents Section--------------->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                  <h3 class="panel-title">Remarks  History</h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-actions">
                      <thead>
                        <tr>
                          <th>Dt.</th>
                          <th>Remark</th>
                          <th>Status</th>
                          <th>By</th>
                        </tr>
                      </thead>
                      <tbody >
                        <?php	
						$sqlA           = mysqli_query($conn,"SELECT tb1.`remark`, tb1.`entered_date`,tb2.`full_name`,tb3.`status_name` FROM `tbl_tms_remarks` tb1 LEFT JOIN `tbl_login` tb2 ON tb1.`entered_by`=tb2.`user_id` LEFT JOIN `tbl_tms_status_master` tb3 ON tb1.`status`=tb3.`status_id` WHERE tb1.`ticket_id`='$ticket_id' ORDER BY tb1.`entered_date` DESC");
						 $NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
						 if($NumA>0){
						 while($buildA = mysqli_fetch_array($sqlA)){ 
						 ?>
                        <tr>
                          <td><?=date('d-m-Y',strtotime($buildA['entered_date']))?></td>
                          <td><?=$buildA['remark']?></td>
                          <td><?=$buildA['status_name']?></td>
                          <td><?=$buildA['full_name']?></td>
                        </tr>
                        <?php
							 }
						 }else{
						 echo "<tr><td colspan='4'>No remarks found!</td></tr>";
						 }
						?>
                      </tbody>
                    </table>
                  </div>
                </div>
                
              </div>
            </div>
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