<?php
ob_start();                            
session_start(); 
error_reporting(0);
////////////////////////////////////////////////////////////////////////////////////////

// Class: DbConnector                                                                 //

// Purpose: Connect to a database, mySQL version                                  //

////////////////////////////////////////////////////////////////////////////////////////
$db_host     = "fleetdb.enguae.com";
$db_username = "root";
$db_password = "F@st{rent}17";  
$db_name     = "db_lpo"; 
$conn        = new mysqli("$db_host","$db_username","$db_password","$db_name") or die ("Could not connect to MYSQL");
date_default_timezone_set('Asia/Dubai');
?>