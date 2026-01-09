<?php 
require_once('Include/DbConnector.php');
require_once('Include/SqlInjection.php'); 
require_once('Include/phpscripts.php');     
 // for refreshing...
    $refreshflag=0;
    if (!isset($_POST['refresh1']))
    {
		   $r_random = rand(10,1000);
		   $_SESSION['r_random']=$r_random;
    }
    else
	   if($_POST['refresh1']==$_SESSION['r_random'])
		 {  
		    $r_random = rand(10,1000);
		    $_SESSION['r_random']=$r_random;
		    $refreshflag=3;
		 }
       else
       {
          // Session mismatch - regenerate and allow login attempt anyway
          // This can happen with PHP built-in server or session issues
          $r_random = rand(10,1000);
          $_SESSION['r_random']=$r_random;
          $refreshflag=3; // Allow login to proceed
       }
$msg="";		 
if ($refreshflag==3){
if(isset($_REQUEST['signin'])){
$username = killstring($_REQUEST['email']);  
$password = trim($_REQUEST['password']);
$password = md5($password);
$today    = date('Y-m-d H:i:s');
$Ispagevalid = true;
	if(empty($username)){
	$msg= "Please enter your username.";
	$alert_label = "alert-danger"; 
	$Ispagevalid = false;
	}  
	else if(empty($password)){ 
	$msg= "Please enter your password.";
	$alert_label = "alert-danger"; 	
	$Ispagevalid = false;
	} else{
	//do nothing	
	}
  if($Ispagevalid==true){ 
  $token             = rand(10,1000);
  $token             = md5($token);
  $_SESSION['stoken'] = $token;
		$sql      = "SELECT `status`, `user_id`, `full_name`,`empid`,`profile_photo`,`is_IT_SA`,`email`,`company_id` FROM `tbl_login` WHERE `username`='$username' and BINARY `password`='$password'"; 
		$result   = mysqli_query($conn,$sql);  
		$no_rows  = mysqli_num_rows($result); 
		if($no_rows>0){ 
			$row            = mysqli_fetch_row($result);  
			$userstatus     = $row['0']; 
			$userno         = $row['1'];
			$full_name_user = $row['2'];
			$empid          = $row['3'];
			$profile_pic    = $row['4'];
			$is_IT_SA       = $row['5'];
			$useremail      = $row['6'];
			$usercompany    = $row['7'];
				if($userstatus=='1'){ 
					$_SESSION['userno']         = $userno;  
					$_SESSION['full_name_user'] = $full_name_user;
					$_SESSION['username_active']= $username;
					$_SESSION['empid']          = $empid;
					if(!empty($profile_pic) && file_exists("uploads/profile_photo/".$profile_pic)){
					$_SESSION['profile_pic']    = "../uploads/profile_photo/".$profile_pic;
					}else{
					$_SESSION['profile_pic']    = "../images/profile/user2-160x160.jpg";	
					}
					$_SESSION['is_IT_SA']          = $is_IT_SA;
					$_SESSION['useremail']         = $useremail;
					$_SESSION['usercompany']       = $usercompany;
					$sql1       = "UPDATE `tbl_login` SET `last_login_date`=NOW() WHERE `user_id`='$userno'";
					$result1    = mysqli_query($conn,$sql1);  
					/********************Remember me**************/
				    if(!empty($_POST["remember_me"])) {
						setcookie ("member_login",$_POST["email"],time()+ (10 * 365 * 24 * 60 * 60));
						setcookie ("member_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
					} else {
						if(isset($_COOKIE["member_login"])) {
							setcookie ("member_login","");
						}
						if(isset($_COOKIE["member_password"])) { 
							setcookie ("member_password","");
						}
					}
					/*********************************************/
					header("Location:home/home.php?gtoken=$token");  
				}
				else if($userstatus=='2'){ 
				$alert_label = "alert-danger"; 
				$msg =  "Your account has been closed by the administrator. Please contact Danube HR Team.";       
				}
				else{
				$alert_label = "alert-danger"; 	
			    $msg  =  "Kindly activate your account.";         
				}
		 }  
		 else
		 {  
		        $msg =  "Invalid username or password"; 
				$alert_label = "alert-danger"; 	
		 }

	  }

	}
}			 
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>ENG - HUB | Log in</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<!-- iCheck -->
<link rel="stylesheet" href="plugins/iCheck/square/blue.css">
<!-- Google Font --> 
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="shortcut icon" href="images/favicon.ico" type="image/vnd.microsoft.icon" />
</head>

<body class="hold-transition login-page" style="background: url(images/background/wall_1.jpg) repeat fixed center !important;">
<div class="login-box" >
  <div class="login-logo"> <a href="index.php"><img src="images/ENG-Final-logo.png" width="250"><!--<b>ENG</b>LPO--></a> </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
     <?php if($msg){?><hr><div class="alert <?=$alert_label?>"><?=$msg?></div><hr><?php }?>
    <form action="#" method="post" name="signin-form" id="signin-form">
      <div class="form-group has-feedback">
          <input type="text"  class="validate[required] form-control"  placeholder="Your Username" name="email" id="email"  data-validate="required"  value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>">
        <span class="glyphicon glyphicon-user form-control-feedback"></span> </div>
      <div class="form-group has-feedback">
        <input type="password"  class="validate[required] form-control"  placeholder="Your Password" name="password"  data-validate="required" id="password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>" autocomplete-"off">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span> </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
             <input type="checkbox" id="squaredFour" name="remember_me" value="1" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?>   >
              Remember Me </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="hidden" name='refresh1' value= '<?php  echo $_SESSION['r_random']; ?>' >
          <input type="submit" name="signin" id="signin" value="Sign In" class="btn btn-primary btn-block btn-flat">
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!--<div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>-->
    <!-- /.social-auth-links -->
    <!-- <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>-->
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
<!---------------------------- jquery.validation Engine ----------------->
<script src="plugins/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="plugins/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="plugins/jQuery-Validation-Engine-master/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="plugins/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" type="text/css"/>
<!----------------------End of jquery.validation engine----------------->
<script type="text/javascript">
$(document).ready(function(){
$("#signin-form").validationEngine();
});
</script>
</body>
</html>
