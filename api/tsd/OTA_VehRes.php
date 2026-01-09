<?php
/**
 * TSD XML API - Vehicle Reservation (New Booking)
 * OTA_VehResRQ / OTA_VehResRS
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
$broker_name = $broker['broker_name'];
$broker_vat_percentage = !empty($broker['broker_vat_percentage']) ? $broker['broker_vat_percentage'] : 0;

// Extract reservation details from XML
$pickUpDateTime = '';
$returnDateTime = '';
$pickUpLocation = '';
$returnLocation = '';
$vehicle_id = '';
$customerName = '';
$customerPhone = '';
$customerEmail = '';
$flightNumber = '';
$externalReference = '';
$totalValue = 0;
$accessories = '';

// Parse VehResRQCore
if (isset($xml->VehResRQCore)) {
    $core = $xml->VehResRQCore;
    
    // Rental details
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
    
    // Customer info
    if (isset($core->Customer)) {
        $customer = $core->Customer;
        if (isset($customer->Primary)) {
            $primary = $customer->Primary;
            if (isset($primary->PersonName)) {
                $givenName = (string) $primary->PersonName->GivenName;
                $surname = (string) $primary->PersonName->Surname;
                $customerName = trim($givenName . ' ' . $surname);
            }
            if (isset($primary->Telephone)) {
                $customerPhone = (string) $primary->Telephone['PhoneNumber'];
            }
            if (isset($primary->Email)) {
                $customerEmail = (string) $primary->Email;
            }
        }
    }
    
    // Vehicle selection
    if (isset($core->VehPref)) {
        $vehicle_id = (string) $core->VehPref['Code'];
        if (empty($vehicle_id) && isset($core->VehPref->VehMakeModel)) {
            $vehicle_id = (string) $core->VehPref->VehMakeModel['Code'];
        }
    }
    
    // Rate/pricing
    if (isset($core->RateQualifier)) {
        $externalReference = (string) $core->RateQualifier['RateQualifier'];
    }
    
    // Total from rate
    if (isset($core->TotalCharge)) {
        $totalValue = (float) $core->TotalCharge['RateTotalAmount'];
    }
}

// Parse VehResRQInfo for additional info
if (isset($xml->VehResRQInfo)) {
    $info = $xml->VehResRQInfo;
    
    // Flight info
    if (isset($info->ArrivalDetails)) {
        $flightNumber = (string) $info->ArrivalDetails['TransportationCode'];
        if (empty($flightNumber) && isset($info->ArrivalDetails->OperatingCompany)) {
            $flightNumber = (string) $info->ArrivalDetails->OperatingCompany . (string) $info->ArrivalDetails['Number'];
        }
    }
    
    // Reference
    if (isset($info->Reference)) {
        if (empty($externalReference)) {
            $externalReference = (string) $info->Reference['ID'];
        }
    }
    
    // Special equipment/accessories
    if (isset($info->SpecialEquipPrefs)) {
        $accList = [];
        foreach ($info->SpecialEquipPrefs->SpecialEquipPref as $equip) {
            $equipType = (string) $equip['EquipType'];
            $quantity = (int) $equip['Quantity'];
            $quantity = $quantity > 0 ? $quantity : 1;
            
            // Map equipment types
            $equipMap = [
                'SCDW' => 'scdw',
                'CDW' => 'cdw',
                'PAI' => 'pai',
                'GPS' => 'gps',
                'CST' => 'baby_seat',
                'CSI' => 'baby_seat',
                'ADR' => 'driver'
            ];
            
            if (isset($equipMap[$equipType])) {
                for ($i = 0; $i < $quantity; $i++) {
                    $accList[] = $equipMap[$equipType];
                }
            }
        }
        if (!empty($accList)) {
            $accessories = implode(',', $accList);
        }
    }
}

// Validate required fields
if (empty($pickUpDateTime) || empty($returnDateTime) || empty($pickUpLocation) || empty($vehicle_id)) {
    outputError("ERR_PARAMS", "Missing required parameters");
    exit;
}

if (empty($customerName) || empty($externalReference)) {
    outputError("ERR_PARAMS", "Missing customer name or external reference");
    exit;
}

// Use pickup as return if not specified
if (empty($returnLocation)) {
    $returnLocation = $pickUpLocation;
}

// Format dates
$pickUpDate = date('Y-m-d H:i:s', strtotime($pickUpDateTime));
$pickUpDate_Date = date('Y-m-d', strtotime($pickUpDateTime));
$returnDate = date('Y-m-d H:i:s', strtotime($returnDateTime));
$returnDate_Date = date('Y-m-d', strtotime($returnDateTime));

// Calculate days
$broker_gracetime = !empty($broker['broker_gracetime']) ? $broker['broker_gracetime'] : "00:00:00";
$broker_graceminutes = minutes($broker_gracetime);
$journey = journey_days($pickUpDate, $returnDate, $broker_graceminutes);
$noOfDays = $journey['total_days'];

// Calculate totals if not provided
if ($totalValue <= 0) {
    // Use availability query to get pricing (simplified)
    outputError("ERR_PRICE", "Total value must be provided");
    exit;
}

// Calculate tax
$totalValueWithoutAccssories = $totalValue;

// Add accessories value
if (!empty($accessories)) {
    $acc = explode(',', $accessories);
    // Would need to lookup prices - for now using provided total
}

$taxValue = round(($broker_vat_percentage * ($totalValue / 100)), 2);
$valueWithoutTax = round(($totalValue - $taxValue), 2);

// Generate reservation number
$QueryCounter = mysqli_query($conn, "SELECT `auto_id`+1 as `id` FROM `tbl_bms_reservation_no`");
$rowQryA = mysqli_fetch_row($QueryCounter);
$ordervalue = $rowQryA[0];
mysqli_query($conn, "UPDATE `tbl_bms_reservation_no` SET `auto_id`='$ordervalue'");

// Clean inputs
$customerName = killstring($customerName);
$customerPhone = killstring($customerPhone);
$customerEmail = killstring($customerEmail);
$flightNumber = killstring($flightNumber);
$externalReference = killstring($externalReference);
$accessories = killstring($accessories);

// Insert booking
$insertSql = "INSERT INTO `tbl_bms_booking` (
    `reservation_no`, `pickUpDate`, `returnDate`, `pickUpLocation`, `returnLocation`,
    `customerName`, `customerPhone`, `customerEmail`, `vehicle_id`, `flightNumber`,
    `externalReference`, `valueWithoutTax`, `taxValue`, `totalValue`, `totalValueWithoutAccssories`,
    `noOfDays`, `accessories`, `broker_id`, `entered_date`
) VALUES (
    '$ordervalue', '$pickUpDate', '$returnDate', '$pickUpLocation', '$returnLocation',
    '$customerName', '$customerPhone', '$customerEmail', '$vehicle_id', '$flightNumber',
    '$externalReference', '$valueWithoutTax', '$taxValue', '$totalValue', '$totalValueWithoutAccssories',
    '$noOfDays', '$accessories', '$broker_id', NOW()
)";

$query = mysqli_query($conn, $insertSql);

if (!$query) {
    outputError("ERR_INSERT", "Failed to create reservation");
    exit;
}

// Update inventory
$start_day = $pickUpDate_Date;
$end_day = $returnDate_Date;
while (strtotime($start_day) <= strtotime($end_day)) {
    mysqli_query($conn, "UPDATE `tbl_bms_inventory_master` SET `inventory`=(`inventory` - 1) 
                         WHERE `location_id`='$pickUpLocation' AND `vehicle_id`='$vehicle_id' 
                         AND `broker_id`='$broker_id' AND `inventory_date`='$start_day'");
    $start_day = date("Y-m-d", strtotime("+1 days", strtotime($start_day)));
}

// Get vehicle and location details for response
$vehSql = "SELECT tb1.`vehicle_name`, tb1.`sipp_code`, tb2.`group_name` 
           FROM `tbl_bms_vehicle_master` tb1 
           INNER JOIN `tbl_bms_group_master` tb2 ON tb1.`group_id` = tb2.`group_id` 
           WHERE tb1.`vehicle_id` = '$vehicle_id'";
$vehResult = mysqli_query($conn, $vehSql);
$vehicleInfo = mysqli_fetch_assoc($vehResult);

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

echo '<OTA_VehResRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TransactionIdentifier="' . $transactionID . '" TimeStamp="' . $timestamp . '">';
echo '<Success/>';
echo '<VehResRSCore>';

// Reservation details
echo '<VehReservation ReservationStatus="Reserved">';

// Confirmation ID
echo '<VehSegmentCore>';
echo '<ConfID Type="Supplier" ID="' . htmlspecialchars($ordervalue) . '"/>';
echo '<Vendor CompanyShortName="Autostrad" Code="AUTOSTRAD">Autostrad Rent a Car</Vendor>';

// Rental core
echo '<VehRentalCore PickUpDateTime="' . formatDateTimeXML($pickUpDate) . '" ReturnDateTime="' . formatDateTimeXML($returnDate) . '">';
echo '<PickUpLocation LocationCode="' . htmlspecialchars($pickUpLocation) . '">' . htmlspecialchars($locations[$pickUpLocation] ?? '') . '</PickUpLocation>';
echo '<ReturnLocation LocationCode="' . htmlspecialchars($returnLocation) . '">' . htmlspecialchars($locations[$returnLocation] ?? '') . '</ReturnLocation>';
echo '</VehRentalCore>';

// Vehicle
echo '<Vehicle Code="' . htmlspecialchars($vehicle_id) . '">';
echo '<VehMakeModel Name="' . htmlspecialchars($vehicleInfo['vehicle_name'] ?? '') . '" Code="' . htmlspecialchars($vehicleInfo['sipp_code'] ?? '') . '"/>';
echo '<VehClass Size="' . htmlspecialchars($vehicleInfo['group_name'] ?? '') . '"/>';
echo '</Vehicle>';

// Rental rate
echo '<RentalRate>';
echo '<VehicleCharges>';
echo '<VehicleCharge Amount="' . number_format($valueWithoutTax, 2, '.', '') . '" CurrencyCode="AED" Purpose="Base Rate" TaxInclusive="false"/>';
echo '</VehicleCharges>';
echo '</RentalRate>';

// Total charge
echo '<TotalCharge RateTotalAmount="' . number_format($totalValue, 2, '.', '') . '" CurrencyCode="AED"/>';

echo '</VehSegmentCore>';

// Customer info
echo '<Customer>';
echo '<Primary>';
echo '<PersonName>';
echo '<GivenName>' . htmlspecialchars($customerName) . '</GivenName>';
echo '</PersonName>';
if (!empty($customerPhone)) {
    echo '<Telephone PhoneNumber="' . htmlspecialchars($customerPhone) . '"/>';
}
if (!empty($customerEmail)) {
    echo '<Email>' . htmlspecialchars($customerEmail) . '</Email>';
}
echo '</Primary>';
echo '</Customer>';

// Arrival details
if (!empty($flightNumber)) {
    echo '<ArrivalDetails TransportationCode="' . htmlspecialchars($flightNumber) . '"/>';
}

// Reference
echo '<VehResRSInfo>';
echo '<ConfIDs>';
echo '<ConfID Type="Supplier" ID="' . htmlspecialchars($ordervalue) . '"/>';
if (!empty($externalReference)) {
    echo '<ConfID Type="Client" ID="' . htmlspecialchars($externalReference) . '"/>';
}
echo '</ConfIDs>';
echo '<RentalDays>' . $noOfDays . '</RentalDays>';
echo '</VehResRSInfo>';

echo '</VehReservation>';
echo '</VehResRSCore>';
echo '</OTA_VehResRS>';

// Send email notification (include existing email functionality)
// Note: Email sending code would go here - keeping it simple for now

$conn->close();
?>

