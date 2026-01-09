<?php 
// INCLUDE THIS PAGE IN EVERY PAGE ---
// THIS PAGE WILL HANDLE SESSION TIME OUT WITH THE SPECIFIED TIMEOUT VALUE
//session_start();
/*if(!isset($_SESSION['isLoggedIn']) || !($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == false)
{
	//code for authentication comes here
	//ASSUME USER IS VALID
	$_SESSION['isLoggedIn'] = true;
	/////////////////////////////////////////
	$_SESSION['timeOut'] = 1200;  // IN SEC . . . . . 20 Minutes . . . . .   
	$logged = time();
	$_SESSION['loggedAt']= $logged;	
	//showLoggedIn();
}
else
{
	$hasSessionExpired = checkIfTimedOut();
	if($hasSessionExpired)
	{
		session_unset();
		$_SESSION['isLoggedIn']=false;
		//header("Location:admin/index.html");		
		echo "<script language='javascript'>alert('Session Expired!');</script>";
		echo "<script language='javascript'>window.parent.location='../index.php'</script>";
		exit;
	}
	else
	{
		$_SESSION['loggedAt']= time();// update last accessed time
		//showLoggedIn();
	}

}
	function checkIfTimedOut()  
	{
		$current = time();// take the current time
		$diff = $current - $_SESSION['loggedAt'];
		if($diff > $_SESSION['timeOut'])
		{
			return true;
		}
		else
		{
			return false;
			$_SESSION['loggedAt']= time();// update last accessed time
		}
	}*/
	
	/*function showLoggedIn()
	{
		echo'<html>';
		echo'<head>';
		echo'</head>';
		echo'<body>';
			echo'<p>';
				echo'Page 1. User is logged in currently.Timeout has been set to 5 seconds. If you stay inactive for more then 5 seconds, and then click the link below or refresh the page you will be logged out and redirected to home page.';
			echo'</p>';
			echo'<br/>';
			echo'<p><a href="second.php">Go to second page</a></p>';
			echo'<br/><br/><br/><p><a href="">Back to article</a></p>';
		echo'</body>';
		echo'</html>';
	}*/
	?>