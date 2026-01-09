<?php 
require_once("../header_footer/header.php");
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$supplier_id_now        = killstring($_REQUEST['supplier_id_now']);
	$supplier_name          = killstring($_REQUEST['supplier_name']);
	$supplier_code          = killstring($_REQUEST['supplier_code']);
	$fax_no                 = killstring($_REQUEST['fax_no']);
	$phone_no               = killstring($_REQUEST['phone_no']);
	$email                  = killstring($_REQUEST['email']);
	$address                = killstring($_REQUEST['address']);
	$tr_no                  = killstring($_REQUEST['tr_no']);
	$IsPageValid = true;	
	if(empty($supplier_name)){
	$msg   = "Please enter Customer name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true
		  $sql              = "UPDATE `tbl_inv_customer_master` SET `supplier_code`='$supplier_code', `address`='$address', `fax_no`='$fax_no', `phone_no`='$phone_no',`email`='$email',`tr_no`='$tr_no',`updated_by`='$userno',`updated_date`=NOW() WHERE `supplier_id`='$supplier_id_now'";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		 	/***Service Updation*********************************/
			$array_count          = 0;
			$person_id_now       = $_REQUEST['person_id'];
			$array_count         = count($person_id_now);
			for($i=0;$i<$array_count;$i++){
			$person_id_latest   = 0;	
			$person_id_latest   = trim($_REQUEST['person_id'][$i]);
			$person_name        = trim($_REQUEST['person_name'][$i]);
			$person_position    = trim($_REQUEST['person_position'][$i]);
			$person_phone       = trim($_REQUEST['person_phone'][$i]);
			$status_now         = trim($_REQUEST['person_status'][$i]);		    
					if(!empty($person_id_latest)){
					 $sqlnewquery = mysqli_query($conn,"UPDATE `tbl_inv_concerned_person_master` SET `person_name`='$person_name',`person_position`='$person_position',`person_phone`='$person_phone',`is_active`='$status_now' WHERE `person_id`='$person_id_latest'");
					}else{
					$sqlnewquery              = mysqli_query($conn,"INSERT INTO `tbl_inv_concerned_person_master`(`supplier_id`, `person_name`, `person_position`, `person_phone`, `is_active`, `entered_by`) VALUES ('$supplier_id_now','$person_name','$person_position','$person_phone','$status_now','$userno')");	
					}
			   if(empty($sqlnewquery)) { 
			   $error_cnt++;
               }
			 }
			/***************Service Updation********************/ 	  
		  $msg = "Customer updated successfully";   
		  $alert_label ="alert-success";  
		  }else{
		  $msg = "Error!"; 
		  $alert_label ="alert-warning";    
		  }
   }//valid rue	 	
 }
}


$tr_id = killstring($_REQUEST['flag']);
$sql  = "SELECT tb1.* FROM `tbl_inv_customer_master` tb1 WHERE tb1.`supplier_id`='$tr_id' ORDER BY tb1.`entered_date` DESC";
$data = mysqli_query($conn,$sql);
if(mysqli_num_rows($data)>0){
while($build = mysqli_fetch_array($data)){ 
  $supplier_id   = $build['supplier_id'];
  $supplier_name = $build['supplier_name'];
  $supplier_code = $build['supplier_code'];
  $address       = $build['address'];
  $phone_no      = $build['phone_no'];
  $fax_no        = $build['fax_no'];
  $email         = $build['email'];
  $tr_no         = $build['tr_no'];
  $is_active     = $build['is_active'];
 }
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> New Customer</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Customer</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <form role="form" name="supplier-form" id="supplier-form" method="post" action="#">
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
          <h3 class="box-title">Customer</h3>
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
                <label class="required">Customer Name:</label>
                <input type="text" class="validate[required] form-control" name="supplier_name" placeholder="Customer Name" value="<?=$supplier_name?>" readonly>
              </div>
              <div class="form-group">
                <label class="">Fax:</label>
                <input type="text" class="form-control" name="fax_no" placeholder="Fax"  value="<?=$fax_no?>">
              </div>
              <div class="form-group">
                <label class="required">Email:</label>
                <input type="text" class="validate[required,custom[email]] form-control" name="email" placeholder="Email"  value="<?=$email?>">
              </div>
                  <div class="form-group">
                <label class="required">Phone No:</label>
                <input type="text" class="validate[required] form-control" name="phone_no" placeholder="Phone No"  value="<?=$phone_no?>">
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Code:</label>
                <input type="text" class="form-control" name="supplier_code" placeholder="Customer Code"  value="<?=$supplier_code?>">
              </div>
             
                <div class="form-group">
                <label class="required">Address:</label>
                <textarea type="text" class="validate[required] form-control" name="address" placeholder="Address" rows=5><?=nl2br($address)?></textarea>
              </div>
              <div class="form-group">
                <label>TR No:</label>
                <input type="text" class="form-control" name="tr_no" placeholder="TR No"  value="<?=$tr_no?>">
              </div>
             </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <hr/>
              <div class="input-group"> <span class="input-group-btn">
                <button class="btn btn-default" type="button" name="feature_btn" onClick="add_items();">+ Add Concerned Person</button>
                </span> </div>
            </div>
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-sriped" role="grid">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Phone</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="cbm_tr_features">
                         <?php	
						 $itr                  = 0;
						 $sqlA           = mysqli_query($conn,"SELECT * FROM `tbl_inv_concerned_person_master` WHERE `supplier_id`='$supplier_id' ORDER BY `entered_date` DESC");
						 $NumA           = (mysqli_num_rows($sqlA))?mysqli_num_rows($sqlA):0; 
						 if($NumA>0){
						 while($buildA = mysqli_fetch_array($sqlA)){ 
						 $unique_id = uniqid();
						 $itr       = $unique_id;
						 $total_value_hidden  = $total_value_hidden  + $buildA['total_price'];
						 $total_qty_hidden    = $total_qty_hidden  + $buildA['quantity'];
						 ?>
                          <tr id="added_service_<?=$itr?>">
                            <td><input type="hidden" name="person_id[]" value="<?=$buildA['person_id']?>" /><input type="text" name="person_name[]" value="<?=$buildA['person_name']?>" class="validate[required] form-control"/></td>
                            <td><input type="text" name="person_position[]" value="<?=$buildA['person_position']?>" class="validate[required] form-control"/></td>
                            <td><input type="text" name="person_phone[]" value="<?=$buildA['person_phone']?>" class="validate[required,custom[phone]] form-control"/></td>
                            <td>
                            <td><select name="person_status[]" class="validate[required] form-control">
                            <option value="1"<?php if($buildA['is_active']==1){echo "selected";}?>>Active</option>
                            <option value="0" <?php if(empty($buildA['is_active'])){echo "selected";}?>>Not Active</option>
                            </select>
                            </td>
							</td>
                          </tr>
                          <?php
			      $itr++;
				 }
			 }
			?>
                  </tbody>
                </table>
                <hr/>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        
        <div class="box-footer">
          <input type="hidden" name="supplier_id_now" value= "<?=$supplier_id?>" >
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">UPDATE</button>
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
$("#supplier-form").validationEngine();
});
  
//Add new item  
function add_items(){
	var scntDiv = $('#cbm_tr_features');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_add_bms_concerned_person_row.php",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=''){
    scntDiv.append(xmlhttp.responseText);
    }
}    
//Remove new item
function person_remove_item_added(tr_id){
	$('#person_added_service_'+tr_id).remove();
	products_change_price(tr_id);  
     /*var r = confirm("Are you want to remove this item from the list?");
			if (r == true) {
          } */
}
</script>