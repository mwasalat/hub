<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
	if(isset($_POST['submit'])){
	$user          = killstring($_REQUEST['user']);
	$main_menu     = killstring($_REQUEST['main_menu']);
	$sub_menu      = killstring($_REQUEST['sub_menu']);
	$IsPageValid = true;	
	if(empty($main_menu)){
	$msg   = "Please enter menu name!";
	$IsPageValid = false;
	$alert_label ="alert-warning";
	}else{ 
	//do nothing
	} 
	if($IsPageValid==true){//valid - true
		  $sql              = "INSERT IGNORE INTO `tbl_user_menu`(`main_menu_id`, `sub_menu_id`, `user_id`, `entered_by`, `entered_date`) VALUES ('$main_menu','$sub_menu','$user','$userno',NOW())";
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
    <h1>Assign Menu</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Assign Menu</li>
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
          <h3 class="box-title">Assign Menu</h3>
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
                <label class="required">User:</label>
                <select class="validate[required] form-control select2" name="user" id="user" onchange="show_user_assigned(this.value)">
                  <option value="">---Select an option---</option>
                  <?php $d1 = mysqli_query($conn,"SELECT `user_id`,`full_name`,`empid` FROM `tbl_login` WHERE `status`=1 order by `full_name`");
                                                            while($b1 = mysqli_fetch_array($d1))
                                                            {
                                                                echo "<option value='$b1[user_id]'>$b1[full_name] - $b1[empid]</option>";
                                                            } 
                                                        ?>
                </select>
              </div>
              <div class="form-group">
                <label class="required">Main Menu:</label>
                <select class="validate[required] form-control" name="main_menu" id="main_menu" onchange="change_sub_menu(this.value)">
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
                <label class="required">Sub Menu:</label>
                <div id="sub_menu_div"><select class="validate[required] form-control" name="sub_menu" id="sub_menu"><option value="">---Select an option---</option></select></div>
              </div>
              <div class="box-footer">
                <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
                <button type="submit" class="btn btn-primary pull-right" name="submit">Submit</button>
              </div>
            </div>
            <div class="col-md-6" id="show_user_assigned_div"></div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
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
});
</script>
<script type="text/javascript">
/************************ADD Items*******************/
function show_user_assigned(uid){
	$('#show_user_assigned_div').html('');	
	$('#main_menu').val('');	
	$('#sub_menu').find('option').not(':first').remove();
	$('#sub_menu').val('');	
    if(uid!=''){
	var scntDiv = $('#show_user_assigned_div');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_show_user_assigned_menus.php?flag="+uid+"&gtoken=<?=$token?>",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=''){
    scntDiv.append(xmlhttp.responseText);
    }
	}else{
	//noty({ text: 'Maximum 10 limit reached!', layout: 'topCenter', type: 'error', timeout: 2000 });	
	}
}
/****************************************************/

/************************ADD Submenu*******************/
function change_sub_menu(main_menu){
	$('#sub_menu').find('option').not(':first').remove();
	$('#sub_menu').val('');	
	$('#sub_menu_div').html('');
	var scntDiv = $('#sub_menu_div');
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","../ajax/ajax_fill_submenu.php?flag="+main_menu+"&gtoken=<?=$token?>",false);
    xmlhttp.send(null);
    if($.trim(xmlhttp.responseText)!=''){
    scntDiv.append(xmlhttp.responseText);
    }
}
/****************************************************/
</script>
