<?Php
if (!isset($_SESSION['userno'])) { 
header('location:../index.php');     
exit();
} 
if($token != $_SESSION['stoken'])  
 { 
        $_SESSION['userno']='';
        //session_unregister('userno');  
         header('location:../index.php'); 
        exit();
 }
?>