<?php
function callAPINow($method, $url, $data){
     $curl = curl_init($url);
     curl_setopt($curl, CURLOPT_CUSTOMREQUEST,$method);                                                                     
     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                                                                  
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
     curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data))                                                                       
    );                                                                                                                   
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
}

//Availability Search
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_getavailability.php";  
$array = array(
    'username' => 'QEEKAdmin',
    'password' => 'qeekadmin123??',
	'pickUpDate' => '2023-08-21 08:00:00',
	'returnDate' => '2023-08-30 08:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();

$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_newbooking.php";  
$array = array(
	"username"=>"QEEKAdmin",
    "password"=>"qeekadmin123??",
    "pickUpDate"=>"2023-11-03 12:00:00",
    "returnDate"=>"2023-11-05 12:00:00",
    "pickUpLocation"=>"19",
    "returnLocation"=>"19",
    "customerName"=>"tangyangming",
    "customerPhone"=>"008617774530116",
    "customerEmail"=>"tangyangming@zuzuche.com",
    "vehicle_id"=>"3",
    "flightNumber"=>"123456",
    "externalReference"=>"1081231232", 
    "valueWithoutTax"=>"342.00",
    "taxValue"=>"18.00",
    "noOfDays"=>"3",
    "totalValue"=> "360.00"
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();


//View Booking
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_viewbooking.php";  
$array = array(
    'username' => 'QEEKAdmin',
    'password' => 'qeekadmin123??',
	'reservationNumber' => '100306'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();


//Modify Booking
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_modifybooking.php";  
$array = array(
    'username' => 'QEEKAdmin',
    'password' => 'qeekadmin123??',
	 'customerName'=> 'Sanil',
     'customerPhone'=> '9715454545',
     'flightNumber'=> 'AJ12345',
     'reservationNumber'=> '100307'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();

$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_newbooking.php";  
$array = array(
    'username' => 'QEEKAdmin',
    'password' => 'qeekadmin123??',
	'pickUpDate' => '2023-02-16 10:00:00',
	'returnDate' => '2023-02-17 10:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1',
	'customerName' => 'Sanil-Test',
	'customerPhone' => '3123233232',
	'customerEmail' => 'abcsdsd@gmail.com',
	'vehicle_id' => '16',
	'flightNumber' => 'X-92575',
	'externalReference' => 'ABC1234567898',
	'valueWithoutTax' => '335.82',
	'taxValue' => '17.68',
	'totalValue' => '353.50',
	'noOfDays' => '1', 
	'accessories' => 'baby_seat:30:00,cdw:40:00,scdw:70:00' 
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();


//Availability Search
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_getavailability.php";  
$array = array(
    'username' => 'QEEKAdmin',
    'password' => 'qeekadmin123??',
	'pickUpDate' => '2023-07-01 00:00:00',
	'returnDate' => '2023-07-02 01:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();

//View Booking
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_cancelbooking.php";  
$array = array(
    'username' => 'CJAdmin',
    'password' => 'cjadmin123??',
	'reservationNumber' => '100297'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();

//View Booking
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_viewbooking.php";  
$array = array(
    'username' => 'CJAdmin',
    'password' => 'cjadmin123??',
	'reservationNumber' => '100297'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();




//Cancellation
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_getavailability.php";  
$array = array(
    'username' => 'CJAdmin',
    'password' => 'cjadmin123??',
	'pickUpDate' => '2023-08-09 08:00:00',
	'returnDate' => '2023-08-12 07:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1'
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();


$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_newbooking.php";  
$array = array(
    'username' => 'CJAdmin',
    'password' => 'cjadmin123??',
	'pickUpDate' => '2023-02-16 10:00:00',
	'returnDate' => '2023-02-17 10:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1',
	'customerName' => 'Sanil-Test',
	'customerPhone' => '3123233232',
	'customerEmail' => 'abcsdsd@gmail.com',
	'vehicle_id' => '16',
	'flightNumber' => 'X-92575',
	'externalReference' => 'ABC123',
	'valueWithoutTax' => '335.82',
	'taxValue' => '17.68',
	'totalValue' => '353.50',
	'noOfDays' => '1', 
	'accessories' => 'baby_seat:30:00,cdw:40:00,scdw:70:00' 
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();

//Availability Search
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_getavailability.php";  
$array = array(
    'username' => 'CJAdmin',
    'password' => 'cjadmin123??',
	'pickUpDate' => '2023-08-09 08:00:00',
	'returnDate' => '2023-08-12 07:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1'
);
/*$array = array(
    'username' => 'QEEKAdmin',
    'password' => 'qeekadmin123??',
	'pickUpDate' => '2023-04-06 12:15:00',
	'returnDate' => '2023-04-09 12:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1'
);*/
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
exit();
//New Booking
$method      = "POST";
$url         = "http://api.autostrad.com/hub/api/test/bms_newbooking.php";  
$array = array(
    'username' => 'CJAdmin',
    'password' => 'cjadmin123??',
	'pickUpDate' => '2023-02-16 10:00:00',
	'returnDate' => '2023-02-17 10:00:00',
	'pickUpLocation' => '1',
	'returnLocation' => '1',
	'customerName' => 'Sam',
	'customerPhone' => '3123233232',
	'customerEmail' => 'abcsdsd@gmail.com',
	'vehicle_id' => '16',
	'flightNumber' => 'sdsdAS',
	'externalReference' => '12',
	'valueWithoutTax' => '335.82',
	'taxValue' => '17.68',
	'totalValue' => '353.50',
	'noOfDays' => '1', 
	'accessories' => 'baby_seat:30:00,cdw:40:00,scdw:70:00' 
);
$update_plan = callAPINow($method,$url, json_encode($array)); 
$response    = json_decode($update_plan, true);
echo "<pre>";
var_dump($response);
echo "</pre>";
?>
