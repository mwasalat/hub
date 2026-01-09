<?php
require_once("../header_footer/header.php");
$msg="";
if ($refreshflag==3){
  if(isset($_POST['submit'])){
  $new_password           = trim($_REQUEST['new_password']);
  $new_password_n         = md5($new_password);
  $retype_password        = trim($_REQUEST['retype_password']);
  $IspageValid = true;
  if(empty($new_password)){
  $msg       = "Please enter new password";
  $label_msg = "warning";
  $IspageValid = false;
  }else if(empty($retype_password)){
  $msg       = "Please enter retype password";
  $label_msg = "warning";
  $IspageValid = false;
  }else{
  //nothing
  }
  if($IspageValid==true){
      $sqlQry = "UPDATE `tbl_login` SET  `password`='$new_password_n',`updated_by`='$userno',`updated_date`=NOW()  WHERE `user_id`='$userno'";
	  $resQry = mysqli_query($conn, $sqlQry);
	  if($resQry){
		  $msg       = "Password changed successfully!";
		  $alert_label = "alert-success";
		  }
	 }else{
	  $msg       = "Error!";
	  $alert_label = "alert-warning";
	 }	  
  }
}

//Upload profile photo
if($refreshflag==3){  
if(isset($_REQUEST['submit_photo'])){
  $profile_photo           = $_FILES["profile_photo"]["name"];
  $IspageValid = true;
  if(empty($profile_photo)){
  $msg       = "Please upload profile photo!";
  $alert_label = "alert-warning";
  $IspageValid = false;
  }else{
  //nothing
  }
  if($IspageValid==true){
	$thumb              = "";  
	$filename         = $_FILES["profile_photo"]["name"];
	if($filename!='' && $filename!=NULL){  
	$file_extn        = end_extention($filename);
	$extension_pos    = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
    $thumb            = substr($filename, 0, $extension_pos) .'_'.time().  substr($filename, $extension_pos);
		   if ($_FILES["profile_photo"]["error"] > 0 ){ 
		   }      
		   else{    
		   if(in_array($file_extn,$valid_ext)){ 
		   /*compressImage($_FILES["profile_photo"]["tmp_name"], "../uploads/profile/".$thumb);
		   Thumbnail("../uploads/profile/".$thumb, "../uploads/profile/thumbs/".$thumb);*/
		   move_uploaded_file($_FILES["profile_photo"]["tmp_name"],"../uploads/profile_photo/".$thumb); 
		   }else{
		   /*move_uploaded_file($_FILES["profile_photo"]["tmp_name"],"../uploads/profile/".$thumb); */
		   move_uploaded_file($_FILES["profile_photo"]["tmp_name"],"../uploads/profile_photo/".$thumb); 
		   }
	   }     
     }
      $sqlQry = "UPDATE `tbl_login` SET `profile_photo`='$thumb',`updated_by`='$userno',`updated_date`=NOW() WHERE `user_id`='$userno'";
	  $resQry = mysqli_query($conn, $sqlQry);
	  if($resQry){
		  $msg       = "Profile photo updated successfully! <font color='yellow'>Your next login profile photo will reflect!!!</font>";
		  $alert_label = "alert-success";
		  }
	 }else{
	  $msg       = "Error!";
	  $alert_label = "alert-warning";
	 }	  
  }
}
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Change Password</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Change Password</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <form role="form" name="edit-profile" id="edit-profile" method="post" action="#" enctype="multipart/form-data">
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
          <h3 class="box-title">Change Password</h3>
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
                <label class="required">Name:</label>
                <label class="form-control" style="background:none; border:none; color:#900;">
            <?=$_SESSION['full_name_user']?>
          </label>
              </div>
              <div class="form-group">
                <label class="required">Employee ID:</label>
                 <label class="form-control" style="background:none; border:none; color:#900;">
            <?=$empid?>
          </label>
              </div>
              
              <div class="form-group">
                <label class="required">Current Password:</label>
                <input type="text" name="current_password"  id="current_password" class="validate[required] form-control" value="" autocomplete="off" onChange="check_pwd(this.value)" placeholder="Current  Password">
                <span id="error_old_password" class="errors" style="color: #bf0000; float:left;"></span>
              </div>
              
              <div class="form-group">
                <label class="required">New Password:</label>
                <input type="password" name="new_password" id="new_password" value="" class="validate[required,minSize[9]] form-control" autocomplete="off" placeholder="New  Password">
              </div>
              
                <div class="form-group">
                <label class="required">Re-Type Password:</label>
                <input type="password" name="retype_password" id="retype_password" value="" class="validate[required,equals[new_password] form-control" autocomplete="off"  placeholder="Re-Type Password">
              </div>
              
              
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="../home/home.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Dashboard</a>
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <button type="submit" class="btn btn-primary pull-right" name="submit">CHANGE PASSWORD</button>
        </div>
      </div>
      <!-- /.box -->
      <!-- /.row -->
    </form>
    
    
     <!------------------------------------Upload Profile Photo------------------->
            <form class="form-horizontal" action="#" method="POST" name="upload-profile" id="upload-profile" enctype="multipart/form-data">
              <div class="panel panel-default">
                <div class="row">
                  <div class="col-md-12">
                    <div class="panel-heading ui-draggable-handle">
                      <h3 class="panel-title"><b>UPLOAD PROFILE PHOTO</b></h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 5px;">
                      <!--<p>Search with Item Codes and add it to the list.</p>-->
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="col-md-3 control-label">Name:</label>
                          <div class="col-md-9">
                            <div class="input-group control-label">
                            <?=$_SESSION['full_name_user']?>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 col-xs-12 control-label">Employee ID:</label>
                          <div class="col-md-9">
                            <div class="input-group control-label">
                              <?=$empid?>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 control-label">Profile Photo:</label>
                          <div class="col-md-9">
                            <input type="file" name="profile_photo"  id="profile_photo" class="validate[required,checkFileType[jpg|jpeg|png],checkFileSize[2]] form-control" title=".jpg | .jpeg | .png file only"  accept=".jpg, .jpeg, .png" value="" placeholder="Browse">
                          </div>
                        </div>
                        
               <!--         <div class="form-group">
                          <label class="col-md-3 col-xs-12 control-label">Email:</label>
                          <div class="col-md-9">
                            <input type="text" name="email_profile" class="validate[custom[email]] form-control"  placeholder="eg:abc@xyz.com" value="<?=!empty($EmailId_p)?$EmailId_p:NULL;?>"/>
                          </div>
                        </div>
                        
                         <div class="form-group">
                          <label class="col-md-3 col-xs-12 control-label">Contact No:</label>
                          <div class="col-md-9">
                           <input type="text" name="mobile_no_profile" class="validate[minSize[11],maxSize[12]] form-control"  placeholder="Mobile no like 971xxxxxxxxx,968xxxxxxxxx,973xxxxxxxxx format." maxlength="12" value="<?=!empty($contact_no_p)?$contact_no_p:NULL;?>"/>
                          </div>
                        </div>-->
                        
                        
                      </div>
                    </div>
                    <p style="margin-left:10px;">Note: <span class="clear"></span>
                      <div style="color:#990000"> 1. Maximum uploaded image file size will be 2 MB.<span class="clear"></span>
                      2. Image formats should be .jpg, .jpeg or png.<span class="clear"></span>
                      </div></p>
                    <div class="panel-footer">
                     <a href="../home/home.php?gtoken=<?=$token?>" class="btn btn-warning pull-left" title="Back">Dashboard</a>
                      <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
                      <button class="btn btn-primary pull-right" type="submit" name="submit_photo">UPDATE PROFILE</button>
                      <div class="clear"></div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
<!------------------------------------Upload Profile Photo------------------->
            
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once("../header_footer/footer.php");?>
<script type="text/javascript">
$(document).ready(function(){
$("#edit-profile").validationEngine();
$("#upload-profile").validationEngine();
});
</script>
<script type="text/javascript">
<!---------------------Old password check----------------->
var xmlHttp;
function check_pwd(old_pwd){ 
      if(old_pwd!=''){ 
      xmlHttp = new XMLHttpRequest();
      if (xmlHttp==null){
        alert ("Browser does not support HTTP Request");
        return false;
      }
      url="../ajax/ajax_check_old_password.php?flag="+old_pwd; 
	  xmlHttp.onreadystatechange=fill_pwd;
      xmlHttp.open("GET",url,true);
      xmlHttp.send(null);
	 }
}

function fill_pwd(){
	  if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
        {
		  if($.trim(xmlHttp.responseText)==0)
		  {
		  document.getElementById('error_old_password').innerHTML = "Current Password is incorrect!\n";  
		  document.getElementById('current_password').value = "";  
		  document.getElementById('current_password').focus();  
		  }  
		  else{
		  document.getElementById('error_old_password').innerHTML = "";  
		  }
		}
 }
</script>
 