<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
phpinfo();
/*ini_set('memory_limit','2048M');
date_default_timezone_set('Asia/Dubai');*/
$db_host     = "fleetdb.enguae.com";
$db_username = "root";
$db_password = "F@st{rent}17";  
$db_name     = "db_lpo"; 
$connA       = new mysqli("$db_host","$db_username","$db_password","$db_name") or die ("Could not connect to MYSQL");
$conn        = oci_pconnect("orbeng", "I$#nfoEng$$19", "//10.6.1.162:1521/orbdbprd_pdb21.frcdbsubnetdr.engoradbdrvcn.oraclevcn.com");
if (!$conn) {
   $m = oci_error();
   echo "Error on connecting Oracle DB".$m['message']."\n";
   exit;
}
else {
   echo "Connected to Oracle!";
   exit();
}
exit();
?>