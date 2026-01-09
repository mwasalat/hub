<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
?>
<style>
 table#example2 {
  margin: 0 auto;
  width: 100%;
  clear: both;
 /* border-collapse: collapse;*/
  table-layout: fixed;         
  word-wrap:break-word;     
 /* white-space: pre-line;*/
}
 /* td a {
    margin: 5px;
  }*/
</style>
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="../bower_components/select2/select2.min.css">
<link href="../plugins/datatable_1_10_16/select.dataTables.min.css" rel="stylesheet"/>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>My Tickets</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">My Tickets</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="company-form" id="company-form" method="post" action="#" enctype="multipart/form-data">
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Ticket List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
      <div class="box box-default">
         <hr/> 
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
               <table id="example2" class="table table-bordered table-hover table-striped stripe row-border order-column" role="grid" style="font-size:13px;">
                   <thead>
                  <tr>
                    <th >Dt.</th>
                    <th >Ticket</th>
                    <th >Module</th>
                    <th >Subject</th>
                   <?php /*?> <th >Description</th><?php */?>
                    <th >Assignee</th>
                   <?php /*?> <th style="display:none;">Estimated <br/> Resolve Dt.</th>
                    <th  style="display:none;">Last <br/>Updated Dt.</th><?php */?>
                    <th >Last remark</th>
                    <th >Status</th>
                    <th >Priority</th>
                    <th >Action</th>
                  </tr>
				</thead>
                  <tbody>
                    <?php 
					
					                        $sql  = "SELECT tb1.`ticket_id`, tb1.`ticket_no`, tb1.`subject`, tb1.`description`, tb1.`priority`, tb1.`assigned_to`,tb1.`estimated_date`, tb1.`entered_by`,tb1.`entered_date`,tb1.`updated_date`,tb1.status,tb2.`status_name`,tb3.`full_name` AS `requestor`,tb4.`full_name` AS `assignee`,tb5.`priority_name` AS `priority_name`,tb6.`name` AS `module_name`,tb7.`sub_name` AS `sub_module_name`,tb1.`last_remark`  FROM `tbl_tms_ticket` tb1 LEFT JOIN `tbl_tms_status_master` tb2 ON tb1.`status`=tb2.`status_id` LEFT JOIN `tbl_login` tb3 ON tb1.`entered_by`=tb3.`user_id` LEFT JOIN `tbl_login` tb4 ON tb1.`assigned_to`=tb4.`empid` LEFT JOIN `tbl_tms_priority_master` tb5 ON tb1.`priority`=tb5.`priority_id` LEFT JOIN `tbl_tms_module` tb6 ON tb1.`module_id`=tb6.`module_id`  LEFT JOIN `tbl_tms_sub_module` tb7 ON tb1.`sub_module_id`=tb7.`sub_module_id` WHERE tb1.`entered_by`='$userno'  ORDER BY tb1.`estimated_date` ASC";
				                           $sqlQ  = mysqli_query($conn,$sql);
										   $itr = 1;
                                            while($build = mysqli_fetch_array($sqlQ)){
											$status       = "";
											$status = $build['status'];
											 // Status
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
												$task_description = $build['description'];
												$task_description = strlen($task_description) > 150 ? substr($task_description,0,150)."..." : $task_description;
                                                $task_subject = $build['subject'];
												$task_subject = strlen($task_subject) > 150 ? substr($task_desctask_subjectription,0,150)."..." : $task_subject;
												$last_remark = $build['last_remark'];
												$last_remark = strlen($last_remark) > 150 ? substr($last_remark,0,150)."..." : $last_remark;
											?>
                  <tr>
                    <td data-order="<?=date('Y-m-d H:i:s',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                    <td><?=$build['ticket_no']?></td>
                    <td><?=$build['module_name']?></td>
                    <td><a href="javascript:void(0)" data-toggle="tooltip" title="<?=$build['subject']?>"><?=$task_subject?></a></td>
				<?php /*?>	<td><a href="javascript:void(0)" data-toggle="tooltip" title="<?=$build['description']?>"><?=$task_description?></a></td><?php */?>
                    <td ><?=$build['assignee']?></td> 
                    <td><a href="javascript:void(0)" data-toggle="tooltip" title="<?=$build['last_remark']?>"><?=$last_remark?></a></td>
                   <?php /*?> <td data-order="<?=!empty($build['estimated_date'])?date('d-m-Y',strtotime($build['estimated_date'])):NULL;?>"  style="display:none;"><?=!empty($build['estimated_date'])?date('d-m-Y',strtotime($build['estimated_date'])):NULL;?></td>
                    <td data-order="<?=date('Y-m-d',strtotime($updated_date))?>"  style="display:none;"><?=!empty($build['updated_date'])?date('d-m-Y',strtotime($build['updated_date'])):NULL;?></td><?php */?>
                    <td><div class="status label <?=$label?>"><b><?=$type?></b></div></td>
                    <td><div class="status label <?=$feed_label?>"><b><?=$feed_type?></b></div></td> 
                    <td style=" display:inline-flex;">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#show_preview<?=$itr?>" title="QUICK VIEW" class="btn btn-default btn-xs"><i class="fa fa-eye fa-sm"></i></a> 
                    <a href="view_ticket.php?flag=<?=$build['ticket_id']?>&gtoken=<?=$token?>" title="VIEW" class="btn btn-default btn-xs"><i class="fa fa-folder-open-o fa-sm"></i></a>
                    </td>
                  </tr>
                  <!---------------Preview show------------>
                <div class="modal fade" id="show_preview<?=$itr?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h3>Subject -
                          <?=$build['subject']?>/<?=$build['ticket_no']?>
                        </h3>
                      </div>
                      <div id="orderDetails" class="modal-body">
                        <ul class="list-group">
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Module:</div>
                            <div class="col-md-9">
                              <?=$build['module_name']?>
                            </div>
                          </li>
                          <?php if(!empty($build['sub_module_name'])){?>
                           <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Sub Module:</div>
                            <div class="col-md-9">
                              <?=$build['sub_module_name']?>
                            </div>
                          </li>
                          <?php }?>
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Task Name:</div>
                            <div class="col-md-9">
                              <?=$build['subject']?>
                            </div>
                          </li>
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Task Description:</div>
                            <div class="col-md-9">
                              <?=nl2br($build['description'])?>
                            </div>
                          </li>
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Assigned To:</div>
                            <div class="col-md-9">
                              <?=$build['assignee']?>
                            </div>
                          </li>
                           <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Last Remark:</div>
                            <div class="col-md-9">
                              <?=$build['last_remark']?>
                            </div>
                          </li>
                      <?php /*?>    <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Estimated Date:</div>
                            <div class="col-md-9">
                            <?=!empty($build['estimated_date'])?date('d-m-Y',strtotime($build['estimated_date'])):NULL;?>
                            </div>
                          </li><?php */?>
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Last Updated Dt:</div>
                            <div class="col-md-9">
                             <?=!empty($build['updated_date'])?date('d-m-Y',strtotime($build['updated_date'])):NULL;?>
                            </div>
                          </li>
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Status:</div>
                            <div class="col-md-9">
                              <div class="status label <?=$label?>"><b>
                                <?=$type?>
                                </b></div>
                            </div>
                          </li>
                          <li class="list-group-item row">
                            <div class="col-md-3" style="font-weight:bold;">Priority:</div>
                            <div class="col-md-9">
                              <div class="status label <?=$feed_label?>"><b>
                                <?=$feed_type?>
                                </b></div>
                            </div>
                          </li>
                        </ul>
                      </div>
                      <div id="orderItems" class="modal-body"></div>
                      <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!---------------Preview show------------>
                  <!---------------Preview show------------>
                    <?php 
					$itr++;
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
$('.select').select2({
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
<!-- DataTables -->
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
						   $('.select').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
});
 var oTable = $('#example2').dataTable({
  "select": {
            toggleable: false
        },
  "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 1, 3] }
    ],
		 "aaSorting": [[ 0, "desc" ]]
   });
   
    $('#myPriority').on('change',function(){
        var selectedValue = $(this).val();
		if(selectedValue==''){
		oTable.fnFilter('', 10, true);
		}else{
        oTable.fnFilter("^"+selectedValue+"$", 10, true); //Exact value, column, reg
		}
    });
	$('#myStatus').on('change',function(){
        var selectedValue = $(this).val();
		if(selectedValue==''){
		oTable.fnFilter('', 9, true);
		}else{
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		}
    });
	
	$('#funHighOpen').on('click',function(){
        var selectedValue = "Open";
		var selectedValueA = "High";
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		oTable.fnFilter("^"+selectedValueA+"$", 10, true); //Exact value, column, reg
    });
	
	
	$('#funMediumOpen').on('click',function(){
        var selectedValue = "Open";
		var selectedValueA = "Medium";
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		oTable.fnFilter("^"+selectedValueA+"$", 10, true); //Exact value, column, reg
    });
	
	$('#funLowOpen').on('click',function(){
        var selectedValue = "Open";
		var selectedValueA = "Low";
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		oTable.fnFilter("^"+selectedValueA+"$", 10, true); //Exact value, column, reg
    });
	
	$('#funHighPending').on('click',function(){
        var selectedValue = "In-Progress";
		var selectedValueA = "High";
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		oTable.fnFilter("^"+selectedValueA+"$", 10, true); //Exact value, column, reg
    });
	
	$('#funMediumPending').on('click',function(){
        var selectedValue = "In-Progress";
		var selectedValueA = "Medium";
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		oTable.fnFilter("^"+selectedValueA+"$", 10, true); //Exact value, column, reg
    });
	
	$('#funLowPending').on('click',function(){
        var selectedValue = "In-Progress";
		var selectedValueA = "Low";
        oTable.fnFilter("^"+selectedValue+"$", 9, true); //Exact value, column, reg
		oTable.fnFilter("^"+selectedValueA+"$", 10, true); //Exact value, column, reg
    });
	
	
	$('#table_reset').on('click',function(){
       oTable.fnFilter('', 9, true);
	   oTable.fnFilter('', 10, true);
    });
	
	
});     

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
