<?php 
    ob_start();
    session_start();
    /*****************************************************************/
	Logoutfnc();	
function Logoutfnc()
{
    session_destroy();      
	session_unset();   
	//print_r($_SESSION); // prints Array ( )    
	header("Location:index.php");   
	exit();                  
}	
?>