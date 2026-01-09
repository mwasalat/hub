<?php 
require_once("../header_footer/header.php");
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$supplier_name          = killstring($_REQUEST['supplier_name']);
	$supplier_code          = killstring($_REQUEST['supplier_code']);
	$fax_no                 = killstring($_REQUEST['fax_no']);
	$phone_no               = killstring($_REQUEST['phone_no']);
	$email                  = killstring($_REQUEST['email']);
	$address                = trim($_REQUEST['address']);
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
		  $sql              = "INSERT INTO `tbl_inv_customer_master`(`supplier_name`, `supplier_code`, `address`, `fax_no`, `phone_no`,`email`,`tr_no`,`entered_by`) VALUES ('$supplier_name','$supplier_code','$address','$fax_no','$phone_no','$email','$tr_no','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  $last_id          = mysqli_insert_id($conn);
		  if($result){
		  /************ITEMS DETAILS**************/
		  $cntC                = 0;
		  $person_name     = $_REQUEST["person_name"];
		  $cntC                = count($person_name);
		  for($i=0; $i < $cntC; $i++){
		   $person_name      = killstring($_REQUEST['person_name'][$i]);
		   $person_position  = killstring($_REQUEST['person_position'][$i]);
		   $person_phone     = killstring($_REQUEST['person_phone'][$i]);
		   $person_status    = killstring($_REQUEST['person_status'][$i]);
		    if(!empty($person_name)){
			$sql             = mysqli_query($conn,"INSERT INTO `tbl_inv_concerned_person_master`(`supplier_id`, `person_name`, `person_position`, `person_phone`, `is_active`, `entered_by`) VALUES ('$last_id','$person_name','$person_position','$person_phone','$person_status','$userno')");
			}
		 }
		 /************ITEMS DETAILS**************/		  
		  $msg = "Customer added successfully";   
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
                <input type="text" class="validate[required] form-control" name="supplier_name" placeholder="Customer Name">
              </div>
              <div class="form-group">
                <label class="">Fax:</label>
                <input type="text" class="form-control" name="fax_no" placeholder="Fax">
              </div>
              <div class="form-group">
                <label class="required">Email:</label>
                <input type="text" class="validate[required,custom[email]] form-control" name="email" placeholder="Email">
              </div>
                  <div class="form-group">
                <label class="required">Phone No:</label>
                <input type="text" class="validate[required] form-control" name="phone_no" placeholder="Phone No">
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Customer Code:</label>
                <input type="text" class="form-control" name="supplier_code" placeholder="Customer Code">
              </div>
             
                <div class="form-group">
                <label class="required">Address:</label>
                <textarea type="text" class="validate[required] form-control" name="address" placeholder="Address" rows=5></textarea>
              </div>
              <div class="form-group">
                <label class="required">TR No:</label>
                <input type="text" class="validate[required] form-control" name="tr_no" placeholder="TR No">
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
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="cbm_tr_features">
                    <?php $unique_id = uniqid();?>
                    <tr id="person_added_service_<?=$unique_id?>">
                      <td><input type="text" name="person_name[]" value="" class="validate[required] form-control" placeholder="Name * "/></td>
                      <td><input type="text" name="person_position[]" value="" class="validate[required] form-control" placeholder="Position *"/></td>
                      <td><input type="text" name="person_phone[]" value="" class="validate[required,custom[phone]] form-control" placeholder="Phone *"/></td>  
                      <td><select name="person_status[]" class="validate[required] form-control">
                            <option value="0">Not Active</option>
                            <option value="1" selected="selected">Active</option>
                            </select>
                            </td>
                      <td width="10%"><a href="javascript:void(0);" onClick="person_remove_item_added('<?=$unique_id?>');"><i class="fa fa-trash fa-lg"></i></a></td>
                    </tr>
                  </tbody>
                </table>
                <hr/>
              </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        
        <div class="box-footer">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
        </div>
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Customer List</h3> 
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Cust. Name</th>
                      <th >Cust. Code</th>
                      <th >Phone No</th>
                      <th >Fax</th>
                      <th >Email</th>
                      <th >Status</th>
                      <th >Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sql  = "SELECT * FROM `tbl_inv_customer_master`  ORDER BY `entered_date` DESC";
				                           $data = mysqli_query($conn,$sql);
										   $itr = 0;
                                            while($build = mysqli_fetch_array($data)){ 
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
                      <td><?=$build['supplier_name']?></td>
                      <td><?=$build['supplier_code']?></td>
                      <td><?=$build['phone_no']?></td>
                      <td><?=$build['fax_no']?></td>
                      <td><?=$build['email']?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
                       <td>
                              <a href="edit_customer_master.php?flag=<?=$build['supplier_id']?>&gtoken=<?=$token?>" style="margin-left:10px;" title="edit"/><i class="fa fa-edit"></i>
                              </td>   
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
$("#supplier-form").validationEngine();
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ 
		  autowidth: false,					 
        columns: [
                    { "width": "10%" },
                    { "width": "25%" },
                    { "width": "10%" },
                    { "width": "20%" },
                    { "width": "10%" },
					{ "width": "10%" },
					{ "width": "10%" },
					{ "width": "5%" }
                ],
     "aaSorting": [[ 0, "desc" ]]});
  })
  
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