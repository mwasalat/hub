<?php
/**
 * TSD XML API - Vehicle Reservation Cancellation
 * OTA_VehCancelRQ / OTA_VehCancelRS
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

// Extract reservation number
$reservationNumber = '';

// Try multiple XML structures for reservation ID
if (isset($xml->VehCancelRQCore)) {
    $core = $xml->VehCancelRQCore;
    
    // UniqueID
    if (isset($core->UniqueID)) {
        $reservationNumber = (string) $core->UniqueID['ID'];
    }
    
    // ConfID
    if (empty($reservationNumber) && isset($core->ConfID)) {
        $reservationNumber = (string) $core->ConfID['ID'];
    }
}

// Alternative: direct UniqueID
if (empty($reservationNumber) && isset($xml->UniqueID)) {
    $reservationNumber = (string) $xml->UniqueID['ID'];
}

// Alternative: VehCancelCore
if (empty($reservationNumber) && isset($xml->VehCancelCore)) {
    if (isset($xml->VehCancelCore->UniqueID)) {
        $reservationNumber = (string) $xml->VehCancelCore->UniqueID['ID'];
    }
}

// Validate
if (empty($reservationNumber)) {
    outputError("ERR_PARAMS", "Missing reservation number");
    exit;
}

$reservationNumber = killstring($reservationNumber);

// Get booking details before cancellation
$bookingSql = "SELECT tb1.*, tb2.`broker_name`, 
               tb3.`location_name` AS `pickup_location`, tb3.`location_email`,
               tb4.`location_name` AS `return_location`, tb4.`location_email` AS `return_location_email`,
               tb5.`vehicle_name`, tb5.`sipp_code`, tb6.`group_name`
               FROM `tbl_bms_booking` tb1 
               LEFT JOIN `tbl_bms_broker_master` tb2 ON tb1.`broker_id` = tb2.`broker_id`
               LEFT JOIN `tbl_bms_location_master` tb3 ON tb1.`pickUpLocation` = tb3.`location_id`
               LEFT JOIN `tbl_bms_location_master` tb4 ON tb1.`returnLocation` = tb4.`location_id`
               LEFT JOIN `tbl_bms_vehicle_master` tb5 ON tb1.`vehicle_id` = tb5.`vehicle_id`
               LEFT JOIN `tbl_bms_group_master` tb6 ON tb5.`group_id` = tb6.`group_id`
               WHERE tb1.`reservation_no` = '$reservationNumber' AND tb1.`broker_id` = '$broker_id'";

$bookingResult = mysqli_query($conn, $bookingSql);

if (mysqli_num_rows($bookingResult) == 0) {
    outputError("ERR_NOTFOUND", "Reservation not found");
    exit;
}

$booking = mysqli_fetch_assoc($bookingResult);

// Check if already cancelled
if ($booking['status'] == '2') {
    outputError("ERR_ALREADY_CANCELLED", "Reservation is already cancelled");
    exit;
}

// Update booking status to cancelled (status = 2)
$updateSql = "UPDATE `tbl_bms_booking` SET `status` = '2', `updated_date` = NOW() 
              WHERE `reservation_no` = '$reservationNumber' AND `broker_id` = '$broker_id'";
$query = mysqli_query($conn, $updateSql);

if (!$query) {
    outputError("ERR_UPDATE", "Failed to cancel reservation");
    exit;
}

// Restore inventory
$start_day = date('Y-m-d', strtotime($booking['pickUpDate']));
$end_day = date('Y-m-d', strtotime($booking['returnDate']));
$vehicle_id = $booking['vehicle_id'];
$pickUpLocation = $booking['pickUpLocation'];

while (strtotime($start_day) <= strtotime($end_day)) {
    mysqli_query($conn, "UPDATE `tbl_bms_inventory_master` SET `inventory` = (`inventory` + 1) 
                         WHERE `location_id` = '$pickUpLocation' AND `vehicle_id` = '$vehicle_id' 
                         AND `broker_id` = '$broker_id' AND `inventory_date` = '$start_day'");
    $start_day = date("Y-m-d", strtotime("+1 days", strtotime($start_day)));
}

// Output XML response
outputXMLHeader();
$transactionID = generateTransactionID();
$timestamp = getTimestampXML();

echo '<OTA_VehCancelRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TransactionIdentifier="' . $transactionID . '" TimeStamp="' . $timestamp . '">';
echo '<Success/>';
echo '<VehCancelRSCore CancelStatus="Cancelled">';

// Confirmation
echo '<VehReservation ReservationStatus="Cancelled">';

echo '<VehSegmentCore>';
echo '<ConfID Type="Supplier" ID="' . htmlspecialchars($reservationNumber) . '"/>';
echo '<Vendor CompanyShortName="Autostrad" Code="AUTOSTRAD">Autostrad Rent a Car</Vendor>';

// Rental core
echo '<VehRentalCore PickUpDateTime="' . formatDateTimeXML($booking['pickUpDate']) . '" ReturnDateTime="' . formatDateTimeXML($booking['returnDate']) . '">';
echo '<PickUpLocation LocationCode="' . htmlspecialchars($booking['pickUpLocation']) . '">' . htmlspecialchars($booking['pickup_location']) . '</PickUpLocation>';
echo '<ReturnLocation LocationCode="' . htmlspecialchars($booking['returnLocation']) . '">' . htmlspecialchars($booking['return_location']) . '</ReturnLocation>';
echo '</VehRentalCore>';

// Vehicle
echo '<Vehicle Code="' . htmlspecialchars($booking['vehicle_id']) . '">';
echo '<VehMakeModel Name="' . htmlspecialchars($booking['vehicle_name']) . '" Code="' . htmlspecialchars($booking['sipp_code']) . '"/>';
echo '<VehClass Size="' . htmlspecialchars($booking['group_name']) . '"/>';
echo '</Vehicle>';

echo '</VehSegmentCore>';

// Customer info
echo '<Customer>';
echo '<Primary>';
echo '<PersonName>';
echo '<GivenName>' . htmlspecialchars($booking['customerName']) . '</GivenName>';
echo '</PersonName>';
if (!empty($booking['customerPhone'])) {
    echo '<Telephone PhoneNumber="' . htmlspecialchars($booking['customerPhone']) . '"/>';
}
if (!empty($booking['customerEmail'])) {
    echo '<Email>' . htmlspecialchars($booking['customerEmail']) . '</Email>';
}
echo '</Primary>';
echo '</Customer>';

echo '<VehResRSInfo>';
echo '<ConfIDs>';
echo '<ConfID Type="Supplier" ID="' . htmlspecialchars($reservationNumber) . '"/>';
if (!empty($booking['externalReference'])) {
    echo '<ConfID Type="Client" ID="' . htmlspecialchars($booking['externalReference']) . '"/>';
}
echo '</ConfIDs>';
echo '<CancelDate>' . $timestamp . '</CancelDate>';
echo '</VehResRSInfo>';

echo '</VehReservation>';
echo '</VehCancelRSCore>';
echo '</OTA_VehCancelRS>';

$conn->close();
?>

