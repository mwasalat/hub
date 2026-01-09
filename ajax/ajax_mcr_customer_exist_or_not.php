<?php
//require_once('../Include/DbConnector.php');  
require_once('../Include/SqlInjection.php');  
require_once('../Include/phpscripts.php'); 
$flag = killstring($_REQUEST['flag']);
$servername   = "130.61.83.59";
$database     = "fraclive";
$username     = "root";
$password     = "F@st{rent}17";
$connFt       = new mysqli($servername, $username, $password, $database);
$search_arr = array();
if(!empty($flag)){
        $sql=  mysqli_query($connFt, "SELECT tb1.`pri_cust_id`,tb2.`fname`,tb1.`glcod`,tb1.`pscode`,tb1.`phone1`,tb3.`ename` AS `salmn`,tb1.`salmn` AS `salmnid` FROM `pri_cust` tb1 JOIN `pri_cust_lang` tb2 ON tb1.`pri_cust_id`=tb2.`pri_cust_id` LEFT JOIN `emp_file` tb3 ON tb1.`salmn`=tb3.`empno` WHERE (tb2.`fname` LIKE  '%".$flag."%' OR tb1.`glcod` LIKE  '%".$flag."%' )  and tb2.`fname`!='' AND tb2.`language`='en' ORDER BY tb2.`fname` ASC");
	if(mysqli_num_rows($sql) > 0){ ?>
    <ul id="customer-list">
      <?php while($fetch = mysqli_fetch_array($sql)){ ?>
      <li onClick="selectCustomer('<?=$fetch["pri_cust_id"]?>','<?=$fetch["pscode"]?>','<?=$fetch["fname"]?>','<?=$fetch["glcod"]?>','<?=$fetch["phone1"]?>','<?=$fetch["salmn"]?>','<?=$fetch["salmnid"]?>');"> <?php echo $fetch["fname"]; ?> - <?php echo $fetch["glcod"]; ?></li> 
      <?php }?>
    </ul>
    <?php
    }
}
?>
