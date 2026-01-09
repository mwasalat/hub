<?php
/**
 * TSD XML API - Vehicle Location Search
 * OTA_VehLocSearchRQ / OTA_VehLocSearchRS
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

// Fetch locations
$sql = "SELECT 
            tb1.`location_id`,
            tb1.`location_name`,
            tb2.`city_name` AS `location_city`,
            tb3.`nicename` AS `location_country`,
            tb3.`iso` AS `country_code`,
            tb1.`location_latitude`,
            tb1.`location_longitude`,
            tb1.`location_landline`,
            tb1.`location_email`,
            tb1.`location_iata`,
            tb1.`location_address`,
            tb1.`is_active`,
            tb1.`updated_date`,
            tb1.`entered_date`
        FROM `tbl_bms_location_master` tb1 
        LEFT JOIN `tbl_bms_city_master` tb2 ON tb1.`location_city` = tb2.`city_id` 
        LEFT JOIN `tbl_country` tb3 ON tb1.`location_country` = tb3.`id` 
        WHERE tb1.`is_active` = 1 
        ORDER BY tb1.`location_name` ASC";

$result = mysqli_query($conn, $sql);
$locations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $locations[] = $row;
}

// Output XML response
outputXMLHeader();
$transactionID = generateTransactionID();
$timestamp = getTimestampXML();

echo '<OTA_VehLocSearchRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TransactionIdentifier="' . $transactionID . '" TimeStamp="' . $timestamp . '">';
echo '<Success/>';
echo '<VehMatchedLocs>';

foreach ($locations as $loc) {
    $latitude = !empty($loc['location_latitude']) ? $loc['location_latitude'] : '0';
    $longitude = !empty($loc['location_longitude']) ? $loc['location_longitude'] : '0';
    $countryCode = !empty($loc['country_code']) ? $loc['country_code'] : 'AE';
    $iata = !empty($loc['location_iata']) ? $loc['location_iata'] : '';
    
    echo '<VehMatchedLoc>';
    echo '<LocationDetail AtAirport="' . (!empty($iata) ? 'true' : 'false') . '" Code="' . htmlspecialchars($loc['location_id']) . '" Name="' . htmlspecialchars($loc['location_name']) . '">';
    
    // Address
    echo '<Address>';
    if (!empty($loc['location_address'])) {
        echo '<AddressLine>' . htmlspecialchars($loc['location_address']) . '</AddressLine>';
    }
    echo '<CityName>' . htmlspecialchars($loc['location_city']) . '</CityName>';
    echo '<CountryName Code="' . htmlspecialchars($countryCode) . '">' . htmlspecialchars($loc['location_country']) . '</CountryName>';
    echo '</Address>';
    
    // Telephone
    if (!empty($loc['location_landline'])) {
        echo '<Telephone PhoneNumber="' . htmlspecialchars($loc['location_landline']) . '"/>';
    }
    
    // Email
    if (!empty($loc['location_email'])) {
        echo '<Email>' . htmlspecialchars($loc['location_email']) . '</Email>';
    }
    
    // IATA Code
    if (!empty($iata)) {
        echo '<AdditionalInfo>';
        echo '<ParkLocation Location="' . htmlspecialchars($iata) . '"/>';
        echo '</AdditionalInfo>';
    }
    
    // Position (GPS)
    if ($latitude != '0' || $longitude != '0') {
        echo '<Position Latitude="' . $latitude . '" Longitude="' . $longitude . '"/>';
    }
    
    echo '</LocationDetail>';
    echo '</VehMatchedLoc>';
}

echo '</VehMatchedLocs>';
echo '</OTA_VehLocSearchRS>';

$conn->close();
?>

