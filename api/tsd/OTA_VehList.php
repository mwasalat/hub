<?php
/**
 * TSD XML API - Vehicle List (Fleet Master)
 * Custom endpoint to get all vehicles/fleet information
 * Autostrad Rent a Car - Mwasalat
 */

require_once 'tsd_common.php';

// Parse XML request
$xml = parseXMLRequest();
if ($xml === null) {
    outputError("ERR_PARSE", "Invalid or missing XML request");
    exit;
}

// Extract credentials
$credentials = extractPOSCredentials($xml);
$broker = authenticateBroker($conn, $credentials['username'], $credentials['password']);

if (!$broker) {
    outputError("ERR_AUTH", "Invalid credentials or broker not found");
    exit;
}

// Fetch vehicles
$sql = "SELECT 
            tb1.`vehicle_id`,
            tb1.`vehicle_name` AS `description`,
            tb1.`sipp_code`,
            tb2.`group_name` AS `groupCode`,
            tb1.`doors`,
            tb1.`passengers` AS `seats`,
            tb1.`suitcases` AS `bags`,
            CASE WHEN tb1.`air_conditionar` = 1 THEN 'true' ELSE 'false' END AS `airConditioning`,
            CASE WHEN tb1.`transmission_id` = 2 THEN 'true' ELSE 'false' END AS `manualTransmission`,
            tb3.`transmission_name`,
            tb4.`fuel_type_name`,
            tb5.`category_name`,
            tb1.`is_active`,
            tb1.`updated_date`,
            tb1.`entered_date`
        FROM `tbl_bms_vehicle_master` tb1 
        INNER JOIN `tbl_bms_group_master` tb2 ON tb1.`group_id` = tb2.`group_id`
        LEFT JOIN `tbl_bms_transmission_master` tb3 ON tb1.`transmission_id` = tb3.`transmission_id`
        LEFT JOIN `tbl_bms_fuel_type_master` tb4 ON tb1.`fuel_type_id` = tb4.`fuel_type_id`
        LEFT JOIN `tbl_bms_category_master` tb5 ON tb1.`category_id` = tb5.`category_id`
        WHERE tb1.`is_active` = 1 
        ORDER BY tb1.`vehicle_name` ASC";

$result = mysqli_query($conn, $sql);
$vehicles = [];
while ($row = mysqli_fetch_assoc($result)) {
    $vehicles[] = $row;
}

// Output XML response
outputXMLHeader();
$transactionID = generateTransactionID();
$timestamp = getTimestampXML();

echo '<OTA_VehListRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TransactionIdentifier="' . $transactionID . '" TimeStamp="' . $timestamp . '">';
echo '<Success/>';
echo '<Vehicles>';

foreach ($vehicles as $veh) {
    $transmissionType = ($veh['manualTransmission'] == 'true') ? 'Manual' : 'Automatic';
    
    echo '<Vehicle Code="' . htmlspecialchars($veh['vehicle_id']) . '" ';
    echo 'AirConditionInd="' . $veh['airConditioning'] . '" ';
    echo 'TransmissionType="' . $transmissionType . '" ';
    echo 'PassengerQuantity="' . $veh['seats'] . '" ';
    echo 'BaggageQuantity="' . $veh['bags'] . '" ';
    echo 'DoorCount="' . $veh['doors'] . '">';
    
    echo '<VehMakeModel Name="' . htmlspecialchars($veh['description']) . '" Code="' . htmlspecialchars($veh['sipp_code']) . '"/>';
    echo '<VehType VehicleCategory="' . htmlspecialchars($veh['groupCode']) . '"/>';
    echo '<VehClass Size="' . htmlspecialchars($veh['groupCode']) . '"/>';
    
    if (!empty($veh['fuel_type_name'])) {
        echo '<FuelType FuelType="' . htmlspecialchars($veh['fuel_type_name']) . '"/>';
    }
    
    if (!empty($veh['category_name'])) {
        echo '<Category>' . htmlspecialchars($veh['category_name']) . '</Category>';
    }
    
    echo '</Vehicle>';
}

echo '</Vehicles>';
echo '</OTA_VehListRS>';

$conn->close();
?>

