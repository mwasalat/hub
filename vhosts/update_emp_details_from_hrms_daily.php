<?php
phpinfo();
exit();
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'On');
ini_set('memory_limit','2048M');
date_default_timezone_set('Asia/Dubai');
$db_host     = "fleetdb.enguae.com";
$db_username = "root";
$db_password = "F@st{rent}17";  
$db_name     = "db_lpo"; 
$connA       = new mysqli("$db_host","$db_username","$db_password","$db_name") or die ("Could not connect to MYSQL");
$conn        = oci_pconnect("orbeng", "I$#nfoEng$$19", "//10.6.1.162:1521/orbdbprd_pdb21.frcdbsubnetdr.engoradbdrvcn.oraclevcn.com");
if ($conn) {
$queryA      = mysqli_query($connA,"DELETE FROM  `tbl_emp_master`");	
$stid = oci_parse($conn, "SELECT Decode(py.pycomcde,300,'ECAB',101,'ETAXI',501,'PIONEER',700,'AUTOSTRAD','') CompanyName,pyempcde AS EmployeeID ,pyempNam AS EmployeeName,dg.pycoddes Designation,lc.pycoddes WorkLocation,cm.pycoddes Company,py.pycpmail Email,py.pyempmob Mobile,'' Landline,'' Extention,'' ReportingManager,py.pyjoinDt DateOfJoin,py.pyempdob DateofBirth,py.pyempSex Gender,nt.pycoddes Nationality,st.pycoddes Status,dpt.pycoddes Department,gr.pycoddes Grade from pyempMas py Left join pycodmas dg On dg.PYHDRCDE = Substr(py.pydescde,1,2)  And dg.pysofcde = Substr(py.pydescde,3,5) And dg.PyHdrcde = 'DG' Left join pycodmas lc On lc.PYHDRCDE = Substr(py.pyloccde,1,2)  And lc.pysofcde = Substr(py.pyloccde,3,5) And lc.PyHdrcde = 'LC' Left join pycodmas cm On cm.PYHDRCDE = Substr(py.pysponsr,1,2)  And cm.pysofcde = Substr(py.pysponsr,3,5) And cm.PyHdrcde = 'SR' Left join pycodmas nt On nt.PYHDRCDE = Substr(py.pynatexp,1,2)  And nt.pysofcde = Substr(py.pynatexp,3,5) And nt.PyHdrcde = 'NT' Left join pycodmas st On st.PYHDRCDE = Substr(py.PYSTATUS,1,2)  And st.pysofcde = Substr(py.PYSTATUS,3,5) And st.PyHdrcde = 'ST' Left join pycodmas Dpt On dpt.PYHDRCDE = Substr(py.pydivcde,1,2)  And dpt.pysofcde = Substr(py.pydivcde,3,5) And dpt.PyHdrcde = 'DV' Left join pycodmas gr On gr.PYHDRCDE = Substr(py.pygrdcde,1,2)  And gr.pysofcde = Substr(py.pygrdcde,3,5) And gr.PyHdrcde = 'GR' where py.pycomcde In ('300','101','501','700') and py.pycatcde = 'CT001'");
oci_execute($stid);
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $COMPANYNAME  = $row['COMPANYNAME'];
	$EMPLOYEEID   = $row['EMPLOYEEID'];
	$EMPLOYEENAME = $row['EMPLOYEENAME'];
	$DESIGNATION  = $row['DESIGNATION'];
	$WORKLOCATION = $row['WORKLOCATION'];
	$COMPANY      = $row['COMPANY'];
	$EMAIL        = $row['EMAIL'];
	$MOBILE       = $row['MOBILE'];
	$LANDLINE     = $row['LANDLINE'];
	$EXTENTION    = $row['EXTENTION'];
	$REPORTINGMANAGER = $row['REPORTINGMANAGER'];
	$REPORTINGMANAGER = !empty($REPORTINGMANAGER)?'"$REPORTINGMANAGER"':"NULL";
	$DATEOFJOIN       = $row['DATEOFJOIN'];
	$DATEOFJOIN       = !empty($DATEOFJOIN)?date('Y-m-d',strtotime($DATEOFJOIN)):NULL;
	$DATEOFJOIN       = !empty($DATEOFJOIN) ? "'$DATEOFJOIN'" : "NULL";
	$DATEOFBIRTH      = $row['DATEOFBIRTH'];
	$DATEOFBIRTH      = !empty($DATEOFBIRTH)?date('Y-m-d',strtotime($DATEOFBIRTH)):NULL;
	$DATEOFBIRTH      = !empty($DATEOFBIRTH) ? "'$DATEOFBIRTH'" : "NULL";
	$GENDER           = $row['GENDER'];
	$NATIONALITY      = $row['NATIONALITY'];
	$STATUS           = $row['STATUS'];
	$DEPARTMENT       = $row['DEPARTMENT'];
	$GRADE            = $row['GRADE'];
	$query = mysqli_query($connA,"INSERT INTO `tbl_emp_master`(`full_name`,`empid`,`gender`,`designation`,`email`,`mobile_no`,`landline_no`,`landline_ext`,`reporting_manager`,`DOJ`,`DOB`,`grade`,`nationality`,`department`,`company`,`sub_company`,`location`,`status`)VALUES('".$EMPLOYEENAME."','".$EMPLOYEEID."','".$GENDER."','".$DESIGNATION."','".$EMAIL."','".$MOBILE."','".$LANDLINE."','".$EXTENTION."',$REPORTINGMANAGER,$DATEOFJOIN,$DATEOFBIRTH,'".$GRADE."','".$NATIONALITY."','".$DEPARTMENT."','".$COMPANYNAME."','".$COMPANY."','".$WORKLOCATION."','".$STATUS."')"); 
}
// Close the Oracle connection
oci_close($conn);
}

function killstring($mainstring){
	/*$db_host_kill     = "192.168.1.159"; 
	$db_username_kill = "db_user";
	$db_password_kill = "1234"; 
	$db_name_kill     = "danube_bi_db";
	$conn_kill        = new mysqli("$db_host_kill","$db_username_kill","$db_password_kill","$db_name_kill") or die ("Could not connect to MYSQL");*/
	$mainstring = trim($mainstring);
	//$mainstring=strtolower($mainstring); 
	//$badstrings="create%alter%script%<%>%select%drop%;%--%insert%update%delete%xp_%'%\%,%(%)%";
	//$badstrings="create/alter/script/select/drop/--/insert/update/delete/xp_/`/$/'/^/\/~/"; 
	//$badstrings="script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/|/\/";
	$badstrings="script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/|/“/Â/\/";
	//$badstrings="script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/|/\/";
	//$badarray=explode("%",$badstrings);
	$badarray=explode("/",$badstrings);
	foreach( $badarray as $key =>$badsubstring)  
	{
		$mainstring = str_replace($badsubstring," ",$mainstring);
	}
	$mainstring = str_replace('"', " ", $mainstring);//Remove double quotes
	$mainstring = preg_replace("/#\r*\n/", " ", $mainstring);//Remove html quotes 
	$mainstring = preg_replace('!\s+!', ' ', $mainstring);//
	//$mainstring = mysqli_real_escape_string($conn_kill,$mainstring);
	$mainstring = trim($mainstring);
	//$ln=strlen($mainstring);  
	return($mainstring);
}
?>