<?php
/**
 * TSD XML API - Vehicle Availability and Rates
 * OTA_VehAvailRateRQ / OTA_VehAvailRateRS
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

$broker_id = $broker['broker_id'];
$broker_vat_percentage = $broker['broker_vat_percentage'];
$broker_gracetime = !empty($broker['broker_gracetime']) ? $broker['broker_gracetime'] : "00:00:00";
$broker_graceminutes = minutes($broker_gracetime);

// Extract search criteria from XML
$pickUpDateTime = '';
$returnDateTime = '';
$pickUpLocation = '';
$returnLocation = '';

// Parse VehAvailRQCore
if (isset($xml->VehAvailRQCore)) {
    $core = $xml->VehAvailRQCore;
    
    // Pick-up details
    if (isset($core->VehRentalCore)) {
        $rental = $core->VehRentalCore;
        $pickUpDateTime = (string) $rental['PickUpDateTime'];
        $returnDateTime = (string) $rental['ReturnDateTime'];
        
        if (isset($rental->PickUpLocation)) {
            $pickUpLocation = (string) $rental->PickUpLocation['LocationCode'];
        }
        if (isset($rental->ReturnLocation)) {
            $returnLocation = (string) $rental->ReturnLocation['LocationCode'];
        }
    }
}

// Alternative parsing for different XML structures
if (empty($pickUpDateTime) && isset($xml->VehAvailRQInfo)) {
    if (isset($xml->VehAvailRQInfo->PickUpLocation)) {
        $pickUpLocation = (string) $xml->VehAvailRQInfo->PickUpLocation['LocationCode'];
    }
    if (isset($xml->VehAvailRQInfo->ReturnLocation)) {
        $returnLocation = (string) $xml->VehAvailRQInfo->ReturnLocation['LocationCode'];
    }
}

// Validate required fields
if (empty($pickUpDateTime) || empty($returnDateTime) || empty($pickUpLocation)) {
    outputError("ERR_PARAMS", "Missing required parameters: pickUpDateTime, returnDateTime, pickUpLocation");
    exit;
}

// Use pickup as return if not specified
if (empty($returnLocation)) {
    $returnLocation = $pickUpLocation;
}

// Format dates
$pickUpDate = date('Y-m-d H:i:s', strtotime($pickUpDateTime));
$pickUpDate_Time = date('H:i', strtotime($pickUpDateTime));
$pickUpDate_date = date('Y-m-d', strtotime($pickUpDateTime));
$pickUpDate_wkno = date('N', strtotime($pickUpDateTime));

$returnDate = date('Y-m-d H:i:s', strtotime($returnDateTime));
$returnDate_date = date('Y-m-d', strtotime($returnDateTime));
$returnDate_Time = date('H:i', strtotime($returnDateTime));
$returnDate_wkno = date('N', strtotime($returnDateTime));

// Calculate journey days
$journey = journey_days($pickUpDate, $returnDate, $broker_graceminutes);
$no_days = $journey['total_days'];
$returnDate_calc = $journey['dropoff_date'];

// Calculate from_days (advance booking)
$difference = strtotime($pickUpDate) - strtotime(date('Y-m-d'));
$from_days = floor($difference / (3600 * 24)) + 1;
$from_days = ($from_days > 1) ? $from_days : 1;

// Buffer time check
$current_date_time = date('Y-m-d H:i:s');
$BQuery = mysqli_query($conn, "SELECT `location_buffer_time` FROM `tbl_bms_location_master` WHERE `location_id`='$pickUpLocation' AND `is_active`=1");
$row_buffer = mysqli_fetch_row($BQuery);
$buffer_time = !empty($row_buffer['0']) ? $row_buffer['0'] : "00:00:00";
$buffer_timeminutes = minutes($buffer_time) * 60;
$buffer_extra_time = strtotime($pickUpDate) - $buffer_timeminutes;
$buffer_time_to_check = date('Y-m-d H:i:s', $buffer_extra_time);

if (strtotime($buffer_time_to_check) < strtotime($current_date_time)) {
    outputError("ERR_LEADTIME", "Pickup time does not meet minimum lead time requirements");
    exit;
}

// Build availability query (same logic as existing API)
$Qry = "SELECT `vehicle_id`,
        IFNULL((SUM(`total`) + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`) - ROUND((SUM(`total`) + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`) * ('$broker_vat_percentage'/100), 2), 0) AS `valueWithoutTax`,
        IFNULL(ROUND((SUM(`total`) + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`) * ('$broker_vat_percentage'/100), 2), 0) AS `taxValue`,
        '$broker_vat_percentage' AS `taxRate`,
        IFNULL((SUM(`total`) + `airport_fee` + `return_airport_fee` + `inter_emirate_pricing`), 0) AS `totalValue`,
        IFNULL(SUM(`cdw`), 0) AS `cdw`,
        IFNULL(SUM(`scdw`), 0) AS `scdw`,
        IFNULL(SUM(`pai`), 0) AS `pai`,
        IFNULL(SUM(`gps`), 0) AS `gps`,
        IFNULL(SUM(`baby_seat`), 0) AS `baby_seat`,
        IFNULL(SUM(`driver`), 0) AS `driver`
        FROM (
            SELECT tb2.`vehicle_id` AS `vehicle_id`,
            IFNULL(ROUND(((CASE WHEN tb4.`range_price` > 0 THEN tb4.`range_price` ELSE tb1.`rate` END) + IFNULL((tb13.`vmd_fee`), 0) - IFNULL(ROUND(tb1.`rate` * (tb5.`advance_discount`/100), 2), 0)), 2), 0) AS `total`,
            IFNULL(tb13.`airport_fee`, 0) AS `airport_fee`,
            IFNULL(tb14.`airport_fee`, 0) AS `return_airport_fee`,
            IFNULL(tb4.`range_price`, 0) AS `range_price`,
            IFNULL(tb12.`inter_emirate_pricing`, 0) AS `inter_emirate_pricing`,
            IFNULL(ROUND(tb1.`rate` * (tb5.`advance_discount`/100), 2), 0) AS `advance_discount`,
            tb1.`rate` AS `rate`,
            tb1.`cdw` AS `cdw`,
            tb1.`scdw` AS `scdw`,
            tb1.`pai` AS `pai`,
            tb1.`gps` AS `gps`,
            tb1.`baby_seat` AS `baby_seat`,
            tb1.`driver` AS `driver`
            FROM `tbl_bms_daily_price_master` tb1
            INNER JOIN `tbl_bms_inventory_master` tbA ON tb1.`vehicle_id` = tbA.`vehicle_id`
            INNER JOIN `tbl_bms_vehicle_master` tb2 ON tb1.`vehicle_id` = tb2.`vehicle_id`
            INNER JOIN `tbl_bms_group_master` tb3 ON tb2.`group_id` = tb3.`group_id`
            INNER JOIN `tbl_bms_location_master` tb6 ON tb1.`price_location_id` = tb6.`location_id`
            LEFT JOIN `tbl_bms_range_pricing_master` tb4 ON tb1.`price_broker_id` = tb4.`broker_id` AND tb2.`group_id` = tb4.`group_id` AND tb6.`location_id` = tb4.`location_id` AND tb4.`parameter_id` = 1 AND tb4.`is_active` = 1 AND tb4.`range_id` = (
                SELECT MAX(`range_id`)
                FROM tbl_bms_range_pricing_master tb11
                WHERE tb1.`price_broker_id` = tb11.`broker_id` AND tb2.`group_id` = tb11.`group_id` AND tb6.`location_id` = tb11.`location_id` AND tb11.`parameter_id` = 1 AND '$no_days' BETWEEN tb11.`range_from` AND tb11.`range_to` AND tb1.`price_date` BETWEEN tb11.`start_date` AND tb11.`end_date` AND tb11.`is_active` = 1
            )
            LEFT JOIN `tbl_bms_advance_booking_pricing_master` tb5 ON tb1.`price_broker_id` = tb5.`broker_id` AND '$from_days' BETWEEN tb5.`advance_from` AND tb5.`advance_to` AND tb5.`is_active` = 1
            INNER JOIN `tbl_bms_location_timing_master` tb7 ON tb1.`price_location_id` = tb7.`location_id`
            INNER JOIN `tbl_bms_location_master` tb8 ON tb8.`location_id` = '$returnLocation'
            INNER JOIN `tbl_bms_location_timing_master` tb9 ON tb8.`location_id` = tb9.`location_id`
            LEFT JOIN `tbl_bms_inter_emirate_pricing` tb12 ON tb6.`location_city` = tb12.`pickup_emirate_id` AND tb8.`location_city` = tb12.`dropoff_emirate_id` AND tb12.`is_active` = 1
            LEFT JOIN `tbl_bms_extra_fee` tb13 ON tb7.`location_id` = tb13.`location_id` AND tb13.`broker_id` = '$broker_id'
            LEFT JOIN `tbl_bms_extra_fee` tb14 ON tb8.`location_id` = tb14.`location_id` AND tb7.`location_id` != tb8.`location_id` AND tb14.`broker_id` = '$broker_id'
            WHERE tb1.`is_active` = 1 AND tb2.`is_active` = 1 AND tb2.`car_start_date` < '$pickUpDate_date' AND tb1.`price_location_id` = '$pickUpLocation' AND tb6.`location_id` = '$pickUpLocation' AND tb8.`location_id` = '$returnLocation' AND tb7.`location_id` = '$pickUpLocation' AND tb9.`location_id` = '$returnLocation' AND tb1.`price_broker_id` = '$broker_id' AND tbA.`is_active` = 1 AND tbA.`location_id` = '$pickUpLocation' AND tbA.`broker_id` = '$broker_id' AND tbA.`inventory` > 0 AND tbA.`inventory_date` = '$pickUpDate_date'
            AND tb6.`is_active` = 1 AND tb8.`is_active` = 1 
            AND tb7.`week_day` = '$pickUpDate_wkno' AND tb7.`is_closed` = 0 AND tb7.`is_active` = 1 
            AND (TIME('$pickUpDate_Time') BETWEEN tb7.`start_time_first` AND tb7.`end_time_first` OR TIME('$pickUpDate_Time') BETWEEN tb7.`start_time_second` AND tb7.`end_time_second`)
            AND tb9.`week_day` = '$returnDate_wkno' AND tb9.`is_closed` = 0 AND tb9.`is_active` = 1 
            AND (TIME('$returnDate_Time') BETWEEN tb9.`start_time_first` AND tb9.`end_time_first` OR TIME('$returnDate_Time') BETWEEN tb9.`start_time_second` AND tb9.`end_time_second`)
            AND tb1.`price_date` BETWEEN DATE_FORMAT('$pickUpDate_date', '%Y-%m-%d') AND DATE_FORMAT('$returnDate_calc', '%Y-%m-%d')
            GROUP BY tb1.`price_location_id`, tb1.`vehicle_id`, tb1.`price_date`
        ) AS X 
        GROUP BY X.`vehicle_id` 
        ORDER BY X.`vehicle_id`";

$result = mysqli_query($conn, $Qry);
$availability = [];
while ($row = mysqli_fetch_assoc($result)) {
    $availability[$row['vehicle_id']] = $row;
}

// Get vehicle details
$vehicleSql = "SELECT tb1.`vehicle_id`, tb1.`vehicle_name`, tb1.`sipp_code`, tb2.`group_name`, 
               tb1.`doors`, tb1.`passengers` AS `seats`, tb1.`suitcases` AS `bags`,
               CASE WHEN tb1.`air_conditionar` = 1 THEN 'true' ELSE 'false' END AS `airConditioning`,
               CASE WHEN tb1.`transmission_id` = 2 THEN 'true' ELSE 'false' END AS `manualTransmission`,
               tb3.`fuel_type_name`
               FROM `tbl_bms_vehicle_master` tb1 
               INNER JOIN `tbl_bms_group_master` tb2 ON tb1.`group_id` = tb2.`group_id`
               LEFT JOIN `tbl_bms_fuel_type_master` tb3 ON tb1.`fuel_type_id` = tb3.`fuel_type_id`
               WHERE tb1.`is_active` = 1 
               ORDER BY tb1.`vehicle_name` ASC";

$vehicleResult = mysqli_query($conn, $vehicleSql);
$vehicles = [];
while ($row = mysqli_fetch_assoc($vehicleResult)) {
    $vehicles[$row['vehicle_id']] = $row;
}

// Get location names
$locSql = "SELECT `location_id`, `location_name` FROM `tbl_bms_location_master` WHERE `location_id` IN ('$pickUpLocation', '$returnLocation')";
$locResult = mysqli_query($conn, $locSql);
$locations = [];
while ($row = mysqli_fetch_assoc($locResult)) {
    $locations[$row['location_id']] = $row['location_name'];
}

// Output XML response
outputXMLHeader();
$transactionID = generateTransactionID();
$timestamp = getTimestampXML();

echo '<OTA_VehAvailRateRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TransactionIdentifier="' . $transactionID . '" TimeStamp="' . $timestamp . '">';
echo '<Success/>';
echo '<VehAvailRSCore>';

// Rental details
echo '<VehRentalCore PickUpDateTime="' . formatDateTimeXML($pickUpDate) . '" ReturnDateTime="' . formatDateTimeXML($returnDate) . '">';
echo '<PickUpLocation LocationCode="' . htmlspecialchars($pickUpLocation) . '" CodeContext="Autostrad">' . htmlspecialchars($locations[$pickUpLocation] ?? '') . '</PickUpLocation>';
echo '<ReturnLocation LocationCode="' . htmlspecialchars($returnLocation) . '" CodeContext="Autostrad">' . htmlspecialchars($locations[$returnLocation] ?? '') . '</ReturnLocation>';
echo '</VehRentalCore>';

echo '<VehVendorAvails>';
echo '<VehVendorAvail>';
echo '<Vendor CompanyShortName="Autostrad" TravelSector="Car Rental" Code="AUTOSTRAD">Autostrad Rent a Car</Vendor>';
echo '<VehAvails>';

foreach ($availability as $vehicle_id => $avail) {
    if (!isset($vehicles[$vehicle_id])) continue;
    $veh = $vehicles[$vehicle_id];
    
    echo '<VehAvail>';
    echo '<VehAvailCore Status="Available">';
    
    // Vehicle
    echo '<Vehicle AirConditionInd="' . $veh['airConditioning'] . '" TransmissionType="' . ($veh['manualTransmission'] == 'true' ? 'Manual' : 'Automatic') . '" PassengerQuantity="' . $veh['seats'] . '" BaggageQuantity="' . $veh['bags'] . '" Code="' . $vehicle_id . '">';
    echo '<VehType VehicleCategory="' . htmlspecialchars($veh['group_name']) . '" DoorCount="' . $veh['doors'] . '"/>';
    echo '<VehClass Size="' . htmlspecialchars($veh['group_name']) . '"/>';
    echo '<VehMakeModel Name="' . htmlspecialchars($veh['vehicle_name']) . '" Code="' . htmlspecialchars($veh['sipp_code']) . '"/>';
    if (!empty($veh['fuel_type_name'])) {
        echo '<FuelType FuelType="' . htmlspecialchars($veh['fuel_type_name']) . '"/>';
    }
    echo '</Vehicle>';
    
    // Rental Rate
    echo '<RentalRate>';
    echo '<RateDistance Unlimited="true" DistUnitName="Km"/>';
    echo '<VehicleCharges>';
    
    // Base rate
    echo '<VehicleCharge Amount="' . number_format($avail['valueWithoutTax'], 2, '.', '') . '" CurrencyCode="AED" Purpose="Base Rate" TaxInclusive="false">';
    echo '<TaxAmounts>';
    echo '<TaxAmount Total="' . number_format($avail['taxValue'], 2, '.', '') . '" CurrencyCode="AED" Percentage="' . $avail['taxRate'] . '" Description="VAT"/>';
    echo '</TaxAmounts>';
    echo '</VehicleCharge>';
    
    echo '</VehicleCharges>';
    echo '<RateQualifier RateCategory="Standard" RatePeriod="Daily"/>';
    echo '</RentalRate>';
    
    // Total Charge
    echo '<TotalCharge RateTotalAmount="' . number_format($avail['totalValue'], 2, '.', '') . '" EstimatedTotalAmount="' . number_format($avail['totalValue'], 2, '.', '') . '" CurrencyCode="AED"/>';
    
    // Equipment/Extras
    echo '<PricedEquips>';
    
    if ($avail['cdw'] > 0) {
        echo '<PricedEquip>';
        echo '<Equipment EquipType="CDW" Description="Collision Damage Waiver"/>';
        echo '<Charge Amount="' . number_format($avail['cdw'], 2, '.', '') . '" CurrencyCode="AED"/>';
        echo '</PricedEquip>';
    }
    
    if ($avail['scdw'] > 0) {
        echo '<PricedEquip>';
        echo '<Equipment EquipType="SCDW" Description="Super Collision Damage Waiver - Zero Excess"/>';
        echo '<Charge Amount="' . number_format($avail['scdw'], 2, '.', '') . '" CurrencyCode="AED"/>';
        echo '</PricedEquip>';
    }
    
    if ($avail['pai'] > 0) {
        echo '<PricedEquip>';
        echo '<Equipment EquipType="PAI" Description="Personal Accident Insurance"/>';
        echo '<Charge Amount="' . number_format($avail['pai'], 2, '.', '') . '" CurrencyCode="AED"/>';
        echo '</PricedEquip>';
    }
    
    if ($avail['gps'] > 0) {
        echo '<PricedEquip>';
        echo '<Equipment EquipType="GPS" Description="GPS Navigation System"/>';
        echo '<Charge Amount="' . number_format($avail['gps'], 2, '.', '') . '" CurrencyCode="AED"/>';
        echo '</PricedEquip>';
    }
    
    if ($avail['baby_seat'] > 0) {
        echo '<PricedEquip>';
        echo '<Equipment EquipType="CST" Description="Child Safety Seat"/>';
        echo '<Charge Amount="' . number_format($avail['baby_seat'], 2, '.', '') . '" CurrencyCode="AED"/>';
        echo '</PricedEquip>';
    }
    
    if ($avail['driver'] > 0) {
        echo '<PricedEquip>';
        echo '<Equipment EquipType="ADR" Description="Additional Driver"/>';
        echo '<Charge Amount="' . number_format($avail['driver'], 2, '.', '') . '" CurrencyCode="AED"/>';
        echo '</PricedEquip>';
    }
    
    echo '</PricedEquips>';
    
    echo '</VehAvailCore>';
    
    // Additional info
    echo '<VehAvailInfo>';
    echo '<PaymentRules>';
    echo '<PaymentRule PaymentType="Prepaid"/>';
    echo '</PaymentRules>';
    echo '</VehAvailInfo>';
    
    echo '</VehAvail>';
}

echo '</VehAvails>';
echo '</VehVendorAvail>';
echo '</VehVendorAvails>';

echo '</VehAvailRSCore>';
echo '</OTA_VehAvailRateRS>';

$conn->close();
?>

