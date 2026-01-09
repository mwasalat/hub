<?php
require_once("../header_footer/header.php");
require_once("../plugins/classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg = "";
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Range Pricing List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Range Pricing List</li>
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
      <?php }?><br/><br/>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Price Range List</h3><br/>
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
                <select class="validate[required] form-control select2" name="broker" id="broker" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `broker_name`, `broker_id` FROM `tbl_bms_broker_master` WHERE `is_active`=1 order by `broker_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['broker_id']?>" <?php if($_REQUEST['broker']==$b1['broker_id']){echo "selected";}?>>
                  <?=$b1['broker_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
              <div class="form-group">
                <label class="">Group:</label>
                <select class="form-control select2" name="group" id="group" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `group_name`, `group_id` FROM `tbl_bms_group_master` WHERE `is_active`=1 order by `group_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['group_id']?>" <?php if($_REQUEST['group']==$b1['group_id']){echo "selected";}?>>
                  <?=$b1['group_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
              <div class="form-group">
                <label class="required">End Date:</label>
                <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="end_date" class="validate[required] datepickerA form-control" value="<?=!empty($_REQUEST['end_date'])?date('d-m-Y',strtotime($_REQUEST['end_date'])):NULL;?>"/></div>               
              </div>
              
              
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Location:</label>
                <select class="validate[required] form-control select2" name="location" id="location" style="width:100%;">
                  <option selected="selected" value="">---Select an option---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `location_name`, `location_id` FROM `tbl_bms_location_master` WHERE `is_active`=1 order by `location_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['location_id']?>" <?php if($_REQUEST['location']==$b1['location_id']){echo "selected";}?>>
                  <?=$b1['location_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
              <div class="form-group">
                <label class="required">Start Date:</label>
                 <div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div> <input type="text" name="start_date" class="validate[required] datepickerA form-control" value="<?=!empty($_REQUEST['start_date'])?date('d-m-Y',strtotime($_REQUEST['start_date'])):NULL;?>"/></div>      
              </div>
              
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer"> 
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Search</button>
        </div>
        <br/>
      </div>
      <!-- /.box -->
     <?php 
    if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$start_date          = $_REQUEST['start_date'];
	$start_date          = date('Y-m-d',strtotime($start_date));
	$end_date            = $_REQUEST['end_date'];
	$end_date            = date('Y-m-d',strtotime($end_date));
	$group               = killstring($_REQUEST['group']);
	$broker              = killstring($_REQUEST['broker']);
	$location            = killstring($_REQUEST['location']);
	?>
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th>First Dt.</th>
                      <th>Last Dt.</th>
                      <th>Broker</th>
                      <th>Location</th>
                      <th>Parameter</th>
                      <th>Group</th>
                      <th>From Dt.</th>
                      <th>To Dt.</th>
                      <th>From Day</th>
                      <th>To Day</th>
                      <th>Amount</th>
                      <th>Status</th>
                     <?php /*?> <th>Action</th><?php */?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
					
					
					
										   $Query = "SELECT tb1.`range_id`,tb1.`parameter_id`,tb1.`start_date`,tb1.`end_date`,tb1.`range_from`,tb1.`range_to`,tb1.`range_price`,tb1.`is_active`,tb1.`entered_date`,tb1.`updated_date`,tb2.`broker_name`,tb3.`display_name`,tb4.`location_name`,tb5.`group_name` FROM `tbl_bms_range_pricing_master` tb1 LEFT JOIN `tbl_bms_broker_master` tb2 ON tb1.`broker_id`=tb2.`broker_id` LEFT JOIN `tbl_bms_pricing_parameter_master` tb3 ON tb1.`parameter_id`=tb3.`parameter_id`  LEFT JOIN `tbl_bms_location_master` tb4 ON tb1.`location_id`=tb4.`location_id` LEFT JOIN `tbl_bms_group_master` tb5 ON tb1.`group_id`=tb5.`group_id` WHERE tb1.`broker_id`='$broker' AND tb1.`location_id`='$location' AND '$start_date' BETWEEN tb1.`start_date` AND tb1.`end_date` AND '$end_date' BETWEEN tb1.`start_date` AND tb1.`end_date` ";
										   if(!empty($_REQUEST['group'])){
										   $Query.=" AND tb1.`group_id`='$_REQUEST[group]'";	 
										   }
										   $Query.=" ORDER BY tb1.`range_id` DESC";
										  // echo $Query;
				                           $sqlQ  = mysqli_query($conn,$Query);
										   $itr = 0;
										   $itr = 1;
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
												$monthName = date('F', mktime(0, 0, 0, $build['price_month'], 10));
												
												$updated_date = !empty($build['updated_date'])?date('d-m-Y',strtotime($build['updated_date'])):NULL;
											?>
                    <tr>
                    
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td data-order="<?=$updated_date?>"><?=$updated_date?></td>
                      <td><?=$build['broker_name']?></td>
                      <td><?=$build['location_name']?></td>
                      <td><?=$build['display_name']?></td>
                      <td><?=$build['group_name']?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['end_date']))?>"><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                      <td><?=$build['range_from']?></td>
                      <td><?=$build['range_to']?></td>
                      <td><?=Checkvalid_number_or_not($build['range_price'],2)?></td>
                      <td><div class="status label <?=$label?>"><b><?=$type?> </b></div></td>
                      <?php /*?><td>
                      <?php if($build['end_date']>=date('Y-m-d')){?>
                      <a href="edit_range_pricing_master.php?flag=<?=$build['range_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i>
                      <?php }?>
                      </td><?php */?>
                    </tr>
                    <?php 
					$i++;
                                            }
                                        ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php }}?>
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
    //startDate: '-0d',
	autoclose: true
});
$('.datepickerA').inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' });
});
</script>
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<!--<script src="../plugins/datatable_1_10_16/datatables.button.js"></script>-->
<script type="text/javascript" src="../plugins/datatable_1_10_16/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/jszip.min.js"></script>
<script type="text/javascript" src="../plugins/datatable_1_10_16/buttons.html5.min.js"></script>
<script>
$(document).ready( function () {
  $('#example2').DataTable({ 
     "aaSorting": [[ 0, "desc" ]],
	 dom: 'Bfrtip',
       buttons: [
            {
               extend: 'csv',
               title: 'Range Price Master',
			   className: 'btn btn-primary',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
            {
               extend: 'excel',
               title: 'Range Price Master',
			    className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            },
			 {
               extend: 'pdf',
               title: 'Range Price Master',
			    className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)',
				}
            }
			]					   
		})
})
</script>
