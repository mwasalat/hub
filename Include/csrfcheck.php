<?php
session_start();
$tockenID=$_GET['id'];
/*$cur_url=$_SERVER['REQUEST_URI'];
$url=explode('?',$cur_url);
$cur_url=$url[0];
$crl=split("/",$cur_url);
$n=count($crl);
$curl1=$crl[$n-1];
if($curl1!="home.php")
{*/
/*echo "TockenID1 ".$tockenID."<br>";
echo "TockenID2 ".$_SESSION['tokencsrf']."<br>";*/

   if(strcmp($tockenID,$_SESSION['tokencsrf'])!=0)
	{
	  session_unset();
	/* echo "<script>location.href='../admin/login_error2.php'</script>";*/
	  echo "<script>location.href='../login_error.php'</script>";
	  exit;
	}
/*}*/  
?>