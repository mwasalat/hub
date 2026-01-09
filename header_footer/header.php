<?php
require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php');  
if (!isset($_SESSION['userno'])) { 
header('location:../index.php');     
exit();
} 
    if(isset($_POST['htoken'])) 
    {//echo "1";exit();
        $token=$_POST['htoken'];
    }
    else if(isset($_GET['gtoken']))
    {//echo "2";exit();
        $token=$_GET['gtoken'];
    }
      $_SESSION['t'] = $token;
     if($token != $_SESSION['stoken'])  
    { 
        $_SESSION['userno']='';
        //session_unregister('userno');  
         header('location:../index.php'); 
        exit();
    }
/*--------NEWLY ADDED ENDS HERE----------*/
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
    $userno           = $_SESSION['userno'];   
	$empid            = $_SESSION['empid'];
	$today            = date('Y-m-d H:i:s');
	$to_date          = date('Y-m-d'); 

/*$sqlPic      = mysqli_query($conn,"SELECT `profile_photo` FROM `tbl_login` WHERE `user_id`='$userno'"); 
$rowPic      = mysqli_fetch_row($sqlPic);  
$userPic     = $rowPic['0']; 
$userPic_N   = ""; 
if(!empty($userPic) && file_exists("../uploads/profile_photo/".$userPic)){
$userPic_N    = "../uploads/profile_photo/".$userPic;
}else{
$userPic_N    = "../images/profile/user2-160x160.jpg";	
}	*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>HUB | ENG</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- Select 2 -->
<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
<!-- jQuery-Validation-Engine-master -->
<link rel="stylesheet" href="../plugins/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" type="text/css"/>
<!-- DataTables -->
<link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="shortcut icon" href="../images/favicon.ico" type="image/vnd.microsoft.icon" />
<style type="text/css">
#quote b,br,i{
	display:none;
}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<header class="main-header">
  <!-- Logo -->
  <a href="../home/home.php?gtoken=<?=$token?>" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>E</b>NG</span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>ENG</b></span> </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
    <marquee style="margin-left: 10px; font-size:14px; padding:10px; width:70%; color:#FFFFFF;" Scrollamount=4 id="quote"><script src="https://wordsmith.org/words/quote.js" type="text/javascript">
</script></marquee>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="<?=$_SESSION['profile_pic']?>" class="user-image" alt="User Image"> <span class="hidden-xs">
          <?=$_SESSION['full_name_user']?>
          </span> </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header"> <img src="<?=$_SESSION['profile_pic']?>" class="img-circle" alt="User Image">
              <p>
                <?=$_SESSION['full_name_user']?>
                <!--  <small>Member since Nov. 2012</small>-->
              </p>
            </li>
            <!-- Menu Body -->
            <!-- Menu Footer-->
            <li class="user-footer">
               <div class="pull-left">
                  <a href="../my_profile/change_password.php?gtoken=<?=$token?>" class="btn btn-default btn-flat">My Profile</a>
                </div>
              <div class="pull-right"> <a href="../logout.php" class="btn btn-default btn-flat">Sign out</a> </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
      </ul>
    </div>
  </nav>
</header>
<!-- =============================================== -->
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> <img src="<?=$_SESSION['profile_pic']?>" class="img-circle" alt="User Image"> </div>
      <div class="pull-left info">
        <p>
          <?=$_SESSION['full_name_user']?>
        </p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a> </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li><a href="../home/home.php?gtoken=<?=$token?>"><i class="fa fa-book"></i> <span>Dashboard</span></a></li>
      <?php
		if(!empty($userno)){
			$res4=  mysqli_query($conn, "select tb1.`main_menu_id`,tb2.`main_menu_name`,tb2.`main_menu_icon` from `tbl_user_menu` tb1 LEFT JOIN `tbl_main_menu` tb2 ON tb1.`main_menu_id`=tb2.`main_menu_id` where tb1.`user_id`='$userno' AND tb2.`is_active`=1 GROUP BY tb1.`main_menu_id` ORDER BY tb2.`main_menu_name` ASC");
			while ($row4=  mysqli_fetch_array($res4)){
				$main_menu_id    = $row4['main_menu_id'];
				$main_menu_name  = $row4['main_menu_name'];	
				$main_menu_icon  = $row4['main_menu_icon'];	
				    ?>
      <li class="treeview"> <a href="#"> <i class="fa <?=$main_menu_icon?>"></i> <span>
        <?=$main_menu_name?>
        </span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>
        <?php
					$rrres4=  mysqli_query($conn, "SELECT tb1.`sub_menu_name`,tb1.`sub_menu_icon`,tb1.`sub_menu_url` FROM `tbl_sub_menu` tb1 LEFT JOIN `tbl_user_menu` tb2 ON tb1.`sub_menu_id`=tb2.`sub_menu_id` WHERE tb1.`main_menu_id`='$main_menu_id' AND tb2.`user_id`='$userno' AND tb1.`is_active`='1' ORDER BY tb1.`sub_menu_name` ASC");
					if(mysqli_num_rows($rrres4)>0){
					echo "<ul class='treeview-menu'>";	
					while ($rrrow4=  mysqli_fetch_array($rrres4)){
					$sub_menu_name  = $rrrow4['sub_menu_name'];	
					$sub_menu_icon  = $rrrow4['sub_menu_icon'];	
					$sub_menu_url   = $rrrow4['sub_menu_url']."?gtoken=$token";
					?>
      <li><a href="<?=$sub_menu_url?>"><i class="fa <?=$sub_menu_icon?>"></i>
        <?=$sub_menu_name?>
        </a></li>
      <?php
					}
					echo "</ul>";
				}
				echo "</li>";
			}
		}
       ?>
      <?php /*?> <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li><?php */?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- =============================================== -->
