<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$company_name          = killstring($_REQUEST['company_name']);
	$company_code          = killstring($_REQUEST['company_code']);
	$company_address       = killstring($_REQUEST['company_address']);
	$company_phone         = killstring($_REQUEST['company_phone']);
	$company_fax           = killstring($_REQUEST['company_fax']);
	$company_tr_no         = killstring($_REQUEST['company_tr_no']);
	$IsPageValid = true;	
	if(empty($company_name)){
	$msg   = "Please enter company name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
	      
	        //Company Logo
			$thumbk                     = ""; 
			$document_signature = $_FILES["company_logo"]["name"];
			$document_signature = killstring($document_signature);
			if($document_signature!='' && $document_signature!=NULL){  
			$file_extn                  = end_extention($document_signature);
			$extension_pos              = strrpos($document_signature, '.'); // find position of the last dot, so where the extension starts
			$thumbk                     = "ENG_".substr($document_signature, 0, $extension_pos) .'_'.time(). substr($document_signature, $extension_pos);
			 if ($_FILES["company_logo"]["error"] > 0 ){}      
			else{ 
				   /*if(in_array($file_extn,$valid_ext)){ 
				   //compressImage($_FILES["company_logo"]["tmp_name"], "../uploads/company_logo/".$thumbk);
				   Thumbnail("../uploads/company_logo/".$thumbk, "../uploads/company_logo/thumbs/".$thumbk);
				   }else{
				   move_uploaded_file($_FILES["company_logo"]["tmp_name"],"../uploads/company_logo/".$thumbk); 
				   }*/
				    move_uploaded_file($_FILES["company_logo"]["tmp_name"],"../uploads/company_logo/".$thumbk); 
			   }     
			 }
			 
		  $sql              = "INSERT INTO `tbl_company_master`(`company_name`, `company_code`, `company_tr_no`, `company_logo`, `company_address`, `company_fax`, `company_phone`, `entered_by`) VALUES ('$company_name','$company_code','$company_tr_no','$thumbk','$company_address','$company_fax','$company_phone','$userno')";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Company added successfully";   
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
    <h1> New Company </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">New Company</li>
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
          <h3 class="box-title">Company</h3>
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
                <label class="required">Company Name:</label>
                <input type="text" class="validate[required] form-control" name="company_name" placeholder="Company Name"/>
              </div>
              <div class="form-group">
                <label class="required">Company Logo:</label>
                 <input type="file" name="company_logo" placeholder="Company Logo" class="validate[required,checkFileType[jpg|jpeg|png],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png file only"  accept=".jpg, .jpeg, .png"/>
              </div>
              <div class="form-group">
                <label class="required">Company Address:</label>
                <input type="text" class="validate[required] form-control" name="company_address" placeholder="Company Address"/>
              </div>
               <div class="form-group">
                <label class="required">Company Fax:</label>
                <input type="text" class="validate[required] form-control" name="company_fax" placeholder="Company Fax"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Company Code:</label>
                <input type="text" class="validate[required] form-control" name="company_code" placeholder="Company Code"/>
              </div>
              <div class="form-group">
                <label class="required">Company TR No:</label>
                <input type="text" class="validate[required] form-control" name="company_tr_no" placeholder="Company TR No"/>
              </div>
              <div class="form-group">
                <label class="required">Company Phone:</label>
                <input type="text" class="validate[required] form-control" name="company_phone" placeholder="Company Phone"/>
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
          <h3 class="box-title">Company List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid" width="100%;">
                  <thead>
                    <tr>
                      <th >Dt.</th>
                      <th >Name</th>
                      <th >Code</th>
                      <th >TR No</th>
                      <th >Address</th>
                      <th >Phone</th>
                      <th >Fax</th>
                      <th >Logo</th>
                      <th >Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_company_master` ORDER BY `entered_date` DESC");
										   $itr = 0;
                                            while($build = mysqli_fetch_array($sqlQ)){
											$company_logo           = $build['company_logo'];
											$u_pathnF = '../uploads/company_logo/'; 
											if ($build['company_logo'] != '' && file_exists($u_pathnF . $build['company_logo'] )) {
											$company_logo = $u_pathnF . $build['company_logo'];
											}
											
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
                      <td><?=$build['company_name']?></td>
                      <td><?=$build['company_code']?></td> 
                      <td><?=$build['company_tr_no']?></td> 
                      <td><?=$build['company_address']?></td>
                      <td><?=$build['company_phone']?></td>
                      <td><?=$build['company_fax']?></td>
                      <td><?php if ($company_logo != '' && file_exists($company_logo)) {
					   $info_B  = pathinfo($company_logo);
					   $extn2   = file_type_img_now($info_B['extension']);	
						?>
					<a href="<?=$company_logo?>" target="_blank" title="Logo"><?php /*?><img src="../images/<?=$extn2?>"  class="attachment-img" /><?php */?><i class="fa fa-image fa-lg"></i></a>
					<?php }
					?>
                    </td>
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
});
</script>
<!-- DataTables -->
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({ scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
         columns: [
                    { "width": "10%" },
                    { "width": "25%" },
                    { "width": "5%" },
                    { "width": "10%" },
                    { "width": "20%" },
					{ "width": "10%" },
					{ "width": "10%" },
					{ "width": "5%" },
					{ "width": "5%" }
                ],
        fixedColumns: true,"aaSorting": [[ 0, "desc" ]]})
  })
</script>
<!--<script src="../plugins/datatable_1_10_16/pdfmake.min.js"></script>
<script src="../plugins/datatable_1_10_16/vfs_fonts.js"></script>
<script src="../plugins/datatable_1_10_16/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $('#example2').DataTable({
      "aaSorting": [[ 0, "desc" ]]
   });
});     
</script>
-->