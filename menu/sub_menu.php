<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$main_menu          = killstring($_REQUEST['main_menu']);
	$menu_name          = killstring($_REQUEST['menu_name']);
	$sub_menu_url       = killstring($_REQUEST['sub_menu_url']);
	$menu_icon          = killstring($_REQUEST['menu_icon']);
	$menu_icon          = !empty($menu_icon)?$menu_icon:"fa-circle";
	$IsPageValid = true;	
	if(empty($menu_name)){
	$msg   = "Please enter menu name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	}
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT INTO `tbl_sub_menu`(`main_menu_id`, `sub_menu_name`, `sub_menu_icon`, `sub_menu_url`, `entered_by`, `entered_date`) VALUES ('$main_menu','$menu_name','$menu_icon','$sub_menu_url','$userno',NOW())";
		  $result           = mysqli_query($conn,$sql); 
		  if($result){
		  $msg = "Sub Menu added successfully";   
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
    <h1>Sub Menu</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Sub Menu</li>
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
          <h3 class="box-title">Sub Menu</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
             <div class="form-group">
                <label class="required">Main Menu:</label>
                  <select class="validate[required] form-control" name="main_menu" id="main_menu">
                  <option value="">---Select an option---</option>
                                                        <?php $d1 = mysqli_query($conn,"SELECT * FROM tbl_main_menu WHERE `is_active`=1 order by `main_menu_name`");
                                                            while($b1 = mysqli_fetch_array($d1))
                                                            {
                                                                echo "<option value='$b1[main_menu_id]'>$b1[main_menu_name]</option>";
                                                            } 
                                                        ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Sub Menu Name:</label>
                <input type="text" class="validate[required] form-control pull-right" name="menu_name" placeholder="Menu Name">
              </div>
              <div class="form-group">
                <label class="required">Sub Menu URL:</label>
                <input type="text" class="validate[required] form-control pull-right" name="sub_menu_url" placeholder="Sub Menu URL">
              </div>
              <div class="form-group">
                <label class="">Sub Menu Icon:</label>
                    <input type="text" class="form-control" name="menu_icon" id="menu_icon" placeholder="Menu Icon"/>
                    <!--<a href="javascript:Popup('https://adminlte.io/themes/AdminLTE/pages/UI/icons.html')">Icons List</a>-->
                    <a href="https://adminlte.io/themes/AdminLTE/pages/UI/icons.html" target="_blank">Icons List</a>
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
          <h3 class="box-title">Sub Menu List</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped" role="grid">
                  <thead>
                    <tr>
                      <th >Entry Dt.</th>
                      <th >Main Menu Name</th>
                      <th >Sub Menu Name</th>
                      <th >Sub Menu URL</th>
                      <th >Sub Menu Icon</th>
                      <th >Active</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
				                           $sqlQ  = mysqli_query($conn,"SELECT tb1.*,tb2.`main_menu_name` FROM `tbl_sub_menu` tb1 LEFT JOIN `tbl_main_menu` tb2 ON tb1.`main_menu_id`=tb2.`main_menu_id` ORDER BY tb1.`entered_date` DESC");
										   $itr   = 0;
                                            while($build = mysqli_fetch_array($sqlQ)){
											?>
                    <tr>
                      <td data-order="<?=date('Y-m-d',strtotime($build['entered_date']))?>"><?=date('d-m-Y',strtotime($build['entered_date']))?></td>
                      <td><?=$build['main_menu_name']?></td>
                      <td><?=$build['sub_menu_name']?></td>
                      <td><?=$build['sub_menu_url']?></td>
                      <td><?=$build['sub_menu_icon']?></td> 
                      <td><?=$build['is_active']?></td> 
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
    $('#example2').DataTable({ "aaSorting": [[ 0, "desc" ]]})
  })
</script>