<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$broker          = killstring($_REQUEST['broker']);
	$status          = killstring($_REQUEST['status']);
	$IsPageValid = true;	
	if(empty($broker)){
	$msg   = "Please select broker!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		 ///***Products********************************/
			$array_countC         = 0;
			$from_now             = $_REQUEST['from'];
			$array_countC         = count($from_now);
			for($i=0;$i<$array_countC;$i++){
			$from_latest        = trim($_REQUEST['from'][$i]);	
			$to_latest          = trim($_REQUEST['to'][$i]);
			$discount_latest    = trim($_REQUEST['discount'][$i]);
				  if(!empty($from_latest) && !empty($to_latest) ){
				  $sqlnewquery  = mysqli_query($conn,"INSERT IGNORE INTO `tbl_bms_advance_booking_pricing_master`(`broker_id`, `advance_from`, `advance_to`, `advance_discount`, `is_active`, `entered_by`) VALUES ('$broker','$from_latest','$to_latest','$discount_latest','$status','$userno')");
				  $last_id          = mysqli_insert_id($conn);
				  $ip_address       = getIP();	   
		          $log_query        = mysqli_query($conn,"INSERT INTO `tbl_bms_logs`(`log_remarks`,`log_module`,`ip_address`,`entered_by`)VALUES('BMS advance pricing master insertion for ID:$last_id','11','$ip_address','$userno')"); 
				}
			 }
			/***************Products********************/ 
		  if($sqlnewquery){
		  $msg = "Advance booking price added successfully";   
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
<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Advance Booking Pricing List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Advance Booking Pricing List</li>
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
          <h3 class="box-title">Advance Booking Pricing</h3>
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
                  <option value="<?=$b1['broker_id']?>">
                  <?=$b1['broker_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
           </div>
            <div class="col-md-6">
            <div class="form-group">
                <label class="required">Status:</label>
                  <select class="validate[required] form-control" name="status" id="status">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
           </div>
           
            <div class="col-md-12">  
            <!--------------Range Pricing------------->
            <div class="table-responsive">
              
                  <table class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                  <tr><th >From</th><th >To</th><th >Discount %</th><th >Action</th></tr>
                  </thead>
                  <tbody id="row_val">                      
                    <tr>
                      <td><select class="validate[required] form-control select2" name="from[]"> 
                          <option selected="selected" value="">---Select an option---</option>
                          <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?>
                          </select>
                      </td>
                      <td><select class="validate[required] form-control select2" name="to[]">
                          <option selected="selected" value="">---Select an option---</option>
                          <?php for($i=1;$i<=365;$i++){?><option value="<?=$i?>"><?=$i?></option><?php }?>
                          </select>
                      </td>
                     <td><input type="text"  name="discount[]" value="0.00" class="validate[required,custom[number],min[0],max[75]] form-control" placeholder="Discount"/></td>
                     <td><a href="javascript:void(0);" title="Add" onclick="add_row('<?=$build['parameter_id']?>')"  style="cursor:pointer"/><i class="fa fa-plus fa-lg"></i></td>
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
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
        </div>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Advance Booking Pricing List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" role="grid" id="example2">
                  <thead>
                    <tr>
                      <th>Dt.</th>
                      <th>Broker</th>
                      <th>From</th>
                      <th>To</th>
                      <th>Discount</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.`advance_id`,tb1.`advance_from`,tb1.`advance_to`,tb1.`advance_discount`,tb1.`is_active`,tb1.`entered_date`,tb2.`broker_name` FROM `tbl_bms_advance_booking_pricing_master` tb1 LEFT JOIN `tbl_bms_broker_master` tb2 ON tb1.`broker_id`=tb2.`broker_id` ORDER BY tb1.`entered_date` DESC");
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
                      <td><?=$build['advance_from']?></td>
                      <td><?=$build['advance_to']?></td>
                      <td><?=Checkvalid_number_or_not($build['advance_discount'],2)?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                      <td><a href="edit_advance_pricing_master.php?flag=<?=$build['advance_id']?>&gtoken=<?=$token?>" title="edit"/><i class="fa fa-edit"></i></td>
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
function add_row(){
	var scntDiv = $('#row_val');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_bms_advance_booking_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!='0'){
    scntDiv.append(xmlhttp.responseText);
    $('.select2').select2({
    placeholder: 'Select an option',
	  width: 'resolve' // need to override the changed default
    });
    }
}
function remove_row(tr_id){
     var r = confirm("Are you want to remove this products from the list?");
			if (r == true) {
			$('#added_row_'+tr_id).remove();
          } 
}
</script>