<?php 
require_once("../header_footer/header.php");
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$supplier_id          = killstring($_REQUEST['supplier']);
	$person_name          = killstring($_REQUEST['concerned_person']);
	$person_position      = killstring($_REQUEST['position']);
	$person_phone         = killstring($_REQUEST['phone_no']);
	$IsPageValid = true;	
	if(empty($supplier_id)){
	$msg   = "Please select customer!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT INTO `tbl_inv_concerned_person_master`(`supplier_id`, `person_name`, `person_position`, `person_phone`, `entered_by`) VALUES ('$supplier_id','$person_name','$person_position','$person_phone','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){	  
		  $msg = "Concerned person added successfully";   
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
    <h1> New Concerned Person</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Concerned Person</li>
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
          <h3 class="box-title">Concerned Person</h3>
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
                <label class="required">Customer:</label>
                 <select class="validate[required] form-control select2" name="supplier" id="supplier">
                  <option selected="selected" value="">---Select a customer---</option>
                  <?php  
				  $d1 = mysqli_query($conn,"SELECT `supplier_name`, `supplier_id` FROM `tbl_inv_customer_master` WHERE `is_active`=1 order by `supplier_name`");
                  while($b1 = mysqli_fetch_array($d1)){
				  ?>
                  <option value="<?=$b1['supplier_id']?>">
                  <?=$b1['supplier_name']?>
                  </option>
                  <?php		
                  } 
                 ?>
                </select>
              </div>
              
              <div class="form-group">
                <label class="">Position:</label>
                <input type="text" class="validate[required] form-control" name="position" placeholder="Position">
              </div>
              
                  
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Name:</label>
                <input type="text" class="validate[required] form-control" name="concerned_person" placeholder="Concerned Person">
              </div>
             
             <div class="form-group">
                <label class="required">Phone No:</label>
                <input type="text" class="validate[required] form-control" name="phone_no" placeholder="Phone No">
              </div>
                
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
          <h3 class="box-title">Concerned Person List</h3> 
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Supplier</th>
                      <th >Name</th>
                      <th >Position</th>
                      <th >Phone No</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sql  = "SELECT * FROM `tbl_inv_concerned_person_master`  ORDER BY `entered_date` DESC";
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
                      <td><?=$build['supplier_id']?></td>
                      <td><?=$build['person_name']?></td>
                      <td><?=$build['person_position']?></td>
                      <td><?=$build['person_phone']?></td>
                      <td><div class="status label <?=$label?>"><b>
                          <?=$type?>
                          </b></div></td>
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
</script>