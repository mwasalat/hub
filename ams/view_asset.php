<?php 
require_once("../header_footer/header.php");
$tr_id = killstring($_REQUEST['flag']);
$sql  = "SELECT tb1.`comments`,tb1.`ip_address`,tb1.`status`,tb1.`entered_date`,tb1.`asset_no`,tb1.`asset_id`,tb1.`asset_name`,tb1.`purchase_date`,tb1.`warranty_end_date`,tb1.`support_end_date`,tb2.`company_name`,tb3.`location_name`,tb4.`type_name`,tb5.`os_name`,tb6.`full_name`
FROM `tbl_ams_assets` tb1
LEFT JOIN `tbl_emp_company_master` tb2 ON tb1.`company_id`=tb2.`company_id`
LEFT JOIN `tbl_emp_location_master` tb3 ON tb1.`location_id`=tb3.`location_id`
LEFT JOIN `tbl_ams_type_master` tb4 ON tb1.`asset_type_id`=tb4.`type_id`
LEFT JOIN `tbl_ams_os_master` tb5 ON tb1.`os_id`=tb5.`os_id`
LEFT JOIN `tbl_emp_master` tb6 ON tb1.`emp_id`=tb6.`empid` WHERE tb1.`asset_id`='$tr_id'";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 
 $company_name      = $build['company_name'];
  $full_name        = $build['full_name'];
   $os_name          = $build['os_name'];
    $type_name        = $build['type_name'];
	$location_name        = $build['location_name'];

  $asset_id_now      = $build['asset_id'];
  $status            = $build['status'];
  $asset_no          = $build['asset_no'];
  $purchase_date     = $build['purchase_date'];
  $warranty_end_date = $build['warranty_end_date'];
  $support_end_date  = $build['support_end_date'];
  $company_id        = $build['company_id'];
  $location_id       = $build['location_id'];
  $asset_type_id     = $build['asset_type_id'];
  $os_id             = $build['os_id'];
  $emp_id            = $build['emp_id'];
  $comments          = $build['comments'];
  $ip_address        = $build['ip_address'];
  $asset_name        = $build['asset_name'];
  $ip_address        = $build['ip_address'];
 }
}
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>View Asset</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Asset</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <form role="form" name="trn-form" id="trn-form" method="post" action="#">
       <?php if ($msg) { ?>
      <hr>
      <div class="alert <?=$alert_label?>">
        <?=$msg?>
      </div>
      <hr>
      <?php }?>
        <!-- SELECT2 EXAMPLE -->
        <div class="box box-default" data-select2-id="16">
          <div class="box-header with-border">
            <h3 class="box-title">View Asset - <?=$asset_no?></h3>
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
                  <label class="required">Company:</label>
                  <div class="input-group"><?=$company_name?></div>
                </div>
                <div class="form-group">
                  <label class="required">Employee:</label>
                   <div class="input-group"><?=$full_name?></div>
                </div>
                
                <div class="form-group">
                  <label class="required">Operating System(OS):</label>
                  <div class="input-group"><?=$os_name?></div>
                </div>
                
                <div class="form-group">
                  <label class="required">Purchase Date:</label>
                   <div class="input-group"><?=date('d-m-Y',strtotime($purchase_date))?></div>
                </div>
                
                    <div class="form-group">
                  <label class="required">Support End Date:</label>
                 <div class="input-group"><?=date('d-m-Y',strtotime($support_end_date))?></div>
                </div>
                
             
                 <div class="form-group">
                  <label class="">Comments:</label>
                  <div class="input-group"><?=nl2br($comments)?></div>
                </div>
                
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Location:</label>
                   <div class="input-group"><?=$location_name?></div>
                </div>
                
                <div class="form-group">
                  <label class="required">Asset Type:</label>
                 <div class="input-group"><?=$type_name?></div>
                </div>
                
                <div class="form-group">
                  <label class="required">Asset Name:</label>
                  <div class="input-group"><?=$asset_name?></div>
                </div>
                
                
                <div class="form-group">
                  <label class="required">Warranty End Date:</label>
                  <div class="input-group"><?=date('d-m-Y',strtotime($warranty_end_date))?></div>
                </div>
                      <div class="form-group">
                  <label class="">IP Address:</label>
                   <div class="input-group"><?=$ip_address?></div>
                </div>
                
                
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <!-- /.row -->
        
        <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Warranty List</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entered Dt.</th>
                      <th >Start Dt.</th>
                      <th >End Dt.</th>
                      <th >Remarks</th>
                  </thead>
                  <tbody>
                    <?php 
					
					                       $Query = "SELECT * FROM `tbl_ams_asset_warranty_history` WHERE  `asset_id` ='$asset_id_now'  ORDER BY `entered_date` DESC";
				                           $sqlQ  = mysqli_query($conn,$Query);
                                            while($build = mysqli_fetch_array($sqlQ)){
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['start_date']))?>"><?=date('d-m-Y',strtotime($build['start_date']))?></td>
                      <td data-order="<?=date('Y-m-d',strtotime($build['end_date']))?>"><?=date('d-m-Y',strtotime($build['end_date']))?></td>
                      <td><?=$build['remarks']?></td>  
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
      </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php require_once("../header_footer/footer.php");?>