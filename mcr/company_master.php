<?php
require_once("../header_footer/header.php"); 
/*phpinfo();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$company_name         = killstring($_REQUEST['company_name']);
	$company_code         = killstring($_REQUEST['company_code']);
	$auh_pdc              = killstring($_REQUEST['auh_pdc']);
	$auh_rest             = killstring($_REQUEST['auh_rest']);
	$dxb_pdc              = killstring($_REQUEST['dxb_pdc']);
	$dxb_rest             = killstring($_REQUEST['dxb_rest']);
	$is_active            = killstring($_REQUEST['is_active']);
	$auh_ccmails          = killstring($_REQUEST['auh_ccmails']);
	$auh_carsales_ccmails = killstring($_REQUEST['auh_carsales_ccmails']);
	$other_ccmails        = killstring($_REQUEST['other_ccmails']);
	$other_carsales_ccmails= killstring($_REQUEST['other_carsales_ccmails']);
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
				   compressImage($_FILES["company_logo"]["tmp_name"], "../uploads/mcr/company_logo/".$thumbk);
				   Thumbnail("../uploads/mcr/company_logo/".$thumbk, "../uploads/mcr/company_logo/thumbs/".$thumbk);
				   }else{
				   move_uploaded_file($_FILES["company_logo"]["tmp_name"],"../uploads/mcr/company_logo/".$thumbk); 
				   }*/
				   move_uploaded_file($_FILES["company_logo"]["tmp_name"],"../uploads/mcr/company_logo/".$thumbk); 
			   }     
			 }
		  $sql     = "INSERT IGNORE INTO `tbl_mcr_company_master`(`company_name`,`company_code`,`auh_pdc`,`auh_rest`,`dxb_pdc`, `dxb_rest`, `company_logo`, `auh_ccmails`,`auh_carsales_ccmails`, `other_ccmails`,`other_carsales_ccmails`,`is_active`,`entered_by`,`entered_date`) VALUES ('$company_name','$company_code','$auh_pdc','$auh_rest','$dxb_pdc','$dxb_rest','$thumbk','$auh_ccmails','$auh_carsales_ccmails','$other_ccmails','$other_carsales_ccmails','$is_active','$userno',NOW())";
		  $result  = mysqli_query($conn,$sql); 
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
    <h1>MCR Company List</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">MCR Company List</li>
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
          <h3 class="box-title">MCR Company</h3>
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
                <label class="required">AUH Rest Start Sl No:</label>
                <input type="text" class="validate[required] form-control" name="auh_rest" placeholder="AUH Rest Starting Sl No."/>
              </div>
              <div class="form-group">
                <label class="required">DXB Rest Start Sl No:</label>
                <input type="text" class="validate[required] form-control" name="dxb_rest" placeholder="DXB Rest Starting Sl No."/>
              </div>
                <div class="form-group">
                <label class="">AUH CC Emails:</label>
                <textarea type="text" class="form-control" name="auh_ccmails" placeholder="AUH CC Emails(Add as comma(,) seperated values)"></textarea>
              </div>
              
                 <div class="form-group">
                <label class="">AUH Carsales CC Emails:</label>
                <textarea type="text" class="form-control" name="auh_carsales_ccmails" placeholder="AUH Carsales CC Emails(Add as comma(,) seperated values)"></textarea>
              </div>
             <div class="form-group">
                <label class="required">Status:</label>
                <select class="validate[required] form-control" name="is_active" id="is_active">
                  <option selected="selected" value="">---Select an option---</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Company Code:</label>
                <input type="text" class="validate[required,minSize[3],maxSize[3]] form-control" name="company_code" placeholder="Company Code"/>
              </div>
              <div class="form-group">
                <label class="required">AUH PDC Start Sl No:</label>
                <input type="text" class="validate[required] form-control" name="auh_pdc" placeholder="AUH PDC Starting Sl No"/>
              </div>
              <div class="form-group">
                <label class="required">DXB PDC Start Sl No:</label>
                <input type="text" class="validate[required] form-control" name="dxb_pdc" placeholder="DXB PDC Starting Sl No"/>
              </div>
             
              <div class="form-group">
                <label class="required">Company Logo:</label>
                 <input type="file" name="company_logo" placeholder="Company Logo" class="validate[required,checkFileType[jpg|jpeg|png],checkFileSize[5]] form-control" title=".jpg | .jpeg | .png file only"  accept=".jpg, .jpeg, .png"/>
              </div>
              
                <div class="form-group">
                <label class="">Other CC Emails:</label>
                <textarea type="text" class="form-control" name="other_ccmails" placeholder="Other CC Emails(Add as comma(,) seperated values)"></textarea>
              </div>
              
                  <div class="form-group">
                <label class="">Other Carsales CC Emails:</label>
                <textarea type="text" class="form-control" name="other_carsales_ccmails" placeholder="Other Carsales CC Emails(Add as comma(,) seperated values)"></textarea>
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
      </div>
      <!-- /.box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">MCR Company List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th>Dt.</th>
                      <th>Company</th>
                      <th>Code</th>
                      <th>AUH CC Emails</th>
                      <th>Other CC Emails</th>
                      <th>AUH PDC<br/> Start Sl No.</th>
                      <th>AUH Rest<br/> Start Sl No.</th>
                      <th>DXB PDC<br/> Start Sl No.</th>
                      <th>DXB Rest<br/> Start Sl No.</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT * FROM `tbl_mcr_company_master` ORDER BY `company_name` ASC");
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
                      <td><?=$build['company_name']?></td>
                      <td><?=$build['company_code']?></td>
                      <td><a href="javascript:void(0);" class="btn btn-warning btn-sm" data-toggle="tooltip" data-container="body" data-placement="auto" title="<?=$build['auh_ccmails']?>">Email</a></td>
                      <td><a href="javascript:void(0);" class="btn btn-warning btn-sm" data-toggle="tooltip" data-container="body" data-placement="auto" title="<?=$build['other_ccmails']?>">Email</a></td>
                      <td><?=$build['auh_pdc']?></td>
                      <td><?=$build['auh_rest']?></td>
                      <td><?=$build['dxb_pdc']?></td>
                      <td><?=$build['dxb_rest']?></td>
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
    $('#example2').DataTable({ "aaSorting": [[ 1, "asc" ]],"autoWidth":false})
	 //Timepicker
    $('#grace_timepicker').timepicker({
      showInputs: false,
	  showMeridian: false 
    });
  })
  
 $('[data-toggle="tooltip"]').tooltip({
    container : 'body'
  });
</script>