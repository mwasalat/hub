<?php
/**
 * TSD XML API - Vehicle Reservation Modification
 * OTA_VehModifyRQ / OTA_VehModifyRS
 * Autostrad Rent a Car - Mwasalat
 * 
 * Note: This API only supports contact details modification
 * (Customer Name, Phone, Flight Number) as per Autostrad policy
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

// Extract modification details
$reservationNumber = '';
$customerName = '';
$customerPhone = '';
$customerEmail = '';
$flightNumber = '';

// Parse VehModifyRQCore
if (isset($xml->VehModifyRQCore)) {
    $core = $xml->VehModifyRQCore;
    
    // UniqueID / ConfID for reservation number
    if (isset($core->UniqueID)) {
        $reservationNumber = (string) $core->UniqueID['ID'];
    }
    if (empty($reservationNumber) && isset($core->ConfID)) {
        $reservationNumber = (string) $core->ConfID['ID'];
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
                if (empty($customerName)) {
                    $customerName = (string) $primary->PersonName;
                }
            }
            if (isset($primary->Telephone)) {
                $customerPhone = (string) $primary->Telephone['PhoneNumber'];
            }
            if (isset($primary->Email)) {
                $customerEmail = (string) $primary->Email;
            }
        }
    }
    
    // Arrival/Flight details
    if (isset($core->ArrivalDetails)) {
        $flightNumber = (string) $core->ArrivalDetails['TransportationCode'];
    }
}

// Alternative: VehModify direct children
if (empty($reservationNumber) && isset($xml->UniqueID)) {
    $reservationNumber = (string) $xml->UniqueID['ID'];
}

// VehModifyRQInfo for additional details
if (isset($xml->VehModifyRQInfo)) {
    $info = $xml->VehModifyRQInfo;
    
    if (empty($flightNumber) && isset($info->ArrivalDetails)) {
        $flightNumber = (string) $info->ArrivalDetails['TransportationCode'];
    }
}

// Validate
if (empty($reservationNumber)) {
    outputError("ERR_PARAMS", "Missing reservation number");
    exit;
}

$reservationNumber = killstring($reservationNumber);

// Get current booking
$bookingSql = "SELECT * FROM `tbl_bms_booking` 
               WHERE `reservation_no` = '$reservationNumber' AND `broker_id` = '$broker_id'";
$bookingResult = mysqli_query($conn, $bookingSql);

if (mysqli_num_rows($bookingResult) == 0) {
    outputError("ERR_NOTFOUND", "Reservation not found");
    exit;
}

$booking = mysqli_fetch_assoc($bookingResult);

// Check if cancelled
if ($booking['status'] == '2') {
    outputError("ERR_CANCELLED", "Cannot modify a cancelled reservation");
    exit;
}

// Build update query for allowed fields only
$updates = [];

if (!empty($customerName)) {
    $customerName = killstring($customerName);
    $updates[] = "`customerName` = '$customerName'";
} else {
    $customerName = $booking['customerName'];
}

if (!empty($customerPhone)) {
    $customerPhone = killstring($customerPhone);
    $updates[] = "`customerPhone` = '$customerPhone'";
} else {
    $customerPhone = $booking['customerPhone'];
}

if (!empty($customerEmail)) {
    $customerEmail = killstring($customerEmail);
    $updates[] = "`customerEmail` = '$customerEmail'";
} else {
    $customerEmail = $booking['customerEmail'];
}

if (!empty($flightNumber)) {
    $flightNumber = killstring($flightNumber);
    $updates[] = "`flightNumber` = '$flightNumber'";
} else {
    $flightNumber = $booking['flightNumber'];
}

if (empty($updates)) {
    outputError("ERR_NO_CHANGES", "No modifiable fields provided");
    exit;
}

$updates[] = "`updated_date` = NOW()";
$updateSql = "UPDATE `tbl_bms_booking` SET " . implode(', ', $updates) . " 
              WHERE `reservation_no` = '$reservationNumber' AND `broker_id` = '$broker_id'";

$query = mysqli_query($conn, $updateSql);

if (!$query) {
    outputError("ERR_UPDATE", "Failed to modify reservation");
    exit;
}

// Get updated booking with full details
$fullBookingSql = "SELECT tb1.*, tb2.`broker_name`, 
                   tb3.`location_name` AS `pickup_location`,
                   tb4.`location_name` AS `return_location`,
                   tb5.`vehicle_name`, tb5.`sipp_code`, tb6.`group_name`
                   FROM `tbl_bms_booking` tb1 
                   LEFT JOIN `tbl_bms_broker_master` tb2 ON tb1.`broker_id` = tb2.`broker_id`
                   LEFT JOIN `tbl_bms_location_master` tb3 ON tb1.`pickUpLocation` = tb3.`location_id`
                   LEFT JOIN `tbl_bms_location_master` tb4 ON tb1.`returnLocation` = tb4.`location_id`
                   LEFT JOIN `tbl_bms_vehicle_master` tb5 ON tb1.`vehicle_id` = tb5.`vehicle_id`
                   LEFT JOIN `tbl_bms_group_master` tb6 ON tb5.`group_id` = tb6.`group_id`
                   WHERE tb1.`reservation_no` = '$reservationNumber'";

$fullResult = mysqli_query($conn, $fullBookingSql);
$updatedBooking = mysqli_fetch_assoc($fullResult);

// Output XML response
outputXMLHeader();
$transactionID = generateTransactionID();
$timestamp = getTimestampXML();

echo '<OTA_VehModifyRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TransactionIdentifier="' . $transactionID . '" TimeStamp="' . $timestamp . '">';
echo '<Success/>';
echo '<VehModifyRSCore ModifyStatus="Modified">';

echo '<VehReservation ReservationStatus="Reserved">';

echo '<VehSegmentCore>';
echo '<ConfID Type="Supplier" ID="' . htmlspecialchars($reservationNumber) . '"/>';
echo '<Vendor CompanyShortName="Autostrad" Code="AUTOSTRAD">Autostrad Rent a Car</Vendor>';

// Rental core
echo '<VehRentalCore PickUpDateTime="' . formatDateTimeXML($updatedBooking['pickUpDate']) . '" ReturnDateTime="' . formatDateTimeXML($updatedBooking['returnDate']) . '">';
echo '<PickUpLocation LocationCode="' . htmlspecialchars($updatedBooking['pickUpLocation']) . '">' . htmlspecialchars($updatedBooking['pickup_location']) . '</PickUpLocation>';
echo '<ReturnLocation LocationCode="' . htmlspecialchars($updatedBooking['returnLocation']) . '">' . htmlspecialchars($updatedBooking['return_location']) . '</ReturnLocation>';
echo '</VehRentalCore>';

// Vehicle
echo '<Vehicle Code="' . htmlspecialchars($updatedBooking['vehicle_id']) . '">';
echo '<VehMakeModel Name="' . htmlspecialchars($updatedBooking['vehicle_name']) . '" Code="' . htmlspecialchars($updatedBooking['sipp_code']) . '"/>';
echo '<VehClass Size="' . htmlspecialchars($updatedBooking['group_name']) . '"/>';
echo '</Vehicle>';

// Total charge
echo '<TotalCharge RateTotalAmount="' . number_format($updatedBooking['totalValue'], 2, '.', '') . '" CurrencyCode="AED"/>';

echo '</VehSegmentCore>';

// Updated customer info
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

// Flight info
if (!empty($flightNumber)) {
    echo '<ArrivalDetails TransportationCode="' . htmlspecialchars($flightNumber) . '"/>';
}

echo '<VehResRSInfo>';
echo '<ConfIDs>';
echo '<ConfID Type="Supplier" ID="' . htmlspecialchars($reservationNumber) . '"/>';
if (!empty($updatedBooking['externalReference'])) {
    echo '<ConfID Type="Client" ID="' . htmlspecialchars($updatedBooking['externalReference']) . '"/>';
}
echo '</ConfIDs>';
echo '<ModifyDate>' . $timestamp . '</ModifyDate>';
echo '</VehResRSInfo>';

echo '</VehReservation>';
echo '</VehModifyRSCore>';
echo '</OTA_VehModifyRS>';

$conn->close();
?>

