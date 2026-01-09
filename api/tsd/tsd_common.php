<?php
/**
 * TSD XML API - Common Functions
 * Autostrad Rent a Car - Mwasalat
 * OTA (OpenTravel Alliance) XML Standard Implementation
 */

ob_start();
error_reporting(0);
date_default_timezone_set('Asia/Dubai');

// Database connection
$db_host     = "130.61.83.59";
$db_username = "root";
$db_password = "F@st{rent}17";
$db_name     = "db_lpo";
$conn        = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    outputError("ERR_DB", "Database connection failed");
    exit;
}
mysqli_query($conn, 'SET CHARACTER SET utf8');

/**
 * Parse incoming XML request
 */
function parseXMLRequest() {
    $xmlInput = file_get_contents('php://input');
    if (empty($xmlInput)) {
        return null;
    }
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xmlInput);
    if ($xml === false) {
        return null;
    }
    return $xml;
}

/**
 * Authenticate broker from XML credentials
 */
function authenticateBroker($conn, $username, $password) {
    $username = killstring($username);
    $password = trim($password);
    
    if (empty($username) || empty($password)) {
        return false;
    }
    
    $stmt = $conn->prepare("SELECT `broker_id`, `broker_name`, `broker_gracetime`, `broker_vat_percentage` 
                            FROM `tbl_bms_broker_master` 
                            WHERE `broker_api_username` = ? AND `broker_api_password` = ? AND `is_active` = 1");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return false;
}

/**
 * Output XML error response
 */
function outputError($code, $message, $type = "Error") {
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<OTA_VehErrorRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">';
    echo '<Errors>';
    echo '<Error Type="' . htmlspecialchars($type) . '" Code="' . htmlspecialchars($code) . '">';
    echo htmlspecialchars($message);
    echo '</Error>';
    echo '</Errors>';
    echo '</OTA_VehErrorRS>';
}

/**
 * Output XML success header
 */
function outputXMLHeader() {
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
}

/**
 * Format date for XML output
 */
function formatDateTimeXML($datetime) {
    return date('Y-m-d\TH:i:s', strtotime($datetime));
}

/**
 * Format date only for XML output
 */
function formatDateXML($date) {
    return date('Y-m-d', strtotime($date));
}

/**
 * Generate unique transaction ID
 */
function generateTransactionID() {
    return strtoupper(bin2hex(random_bytes(8)));
}

/**
 * Get current timestamp for XML
 */
function getTimestampXML() {
    return date('Y-m-d\TH:i:s');
}

/**
 * Clean input string (security)
 */
function killstring($mainstring) {
    $mainstring = trim($mainstring);
    $badstrings = "script/</>/drop/--/delete/xp_/'/(/)/%/$/!/=/#/^/*/?/~/`/|/\\/";
    $badarray = explode("/", $badstrings);
    foreach ($badarray as $badsubstring) {
        $mainstring = str_replace($badsubstring, " ", $mainstring);
    }
    $mainstring = str_replace('"', " ", $mainstring);
    $mainstring = preg_replace('!\s+!', ' ', $mainstring);
    return trim($mainstring);
}

/**
 * Convert minutes to time format
 */
function minutes($time) {
    $time = explode(':', $time);
    return ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
}

/**
 * Calculate journey days
 */
function journey_days($pickup_date_time, $dropoff_date_time, $broker_graceminutes = 0) {
    $broker_graceminutes = !empty($broker_graceminutes) ? ($broker_graceminutes * 60) : 0;
    $pickup_date_time = strtotime($pickup_date_time);
    $dropoff_date_time = strtotime($dropoff_date_time) - $broker_graceminutes;
    $diff = ($dropoff_date_time - $pickup_date_time) / 86400;
    $ceil = ceil($diff) - 1;
    $total_days = ceil($diff);
    $pickup_date = date('Y-m-d', $pickup_date_time);
    $dropoff_date = date('Y-m-d', strtotime($pickup_date . " +" . $ceil . " days"));
    return array('total_days' => $total_days, 'dropoff_date' => $dropoff_date);
}

/**
 * Extract POS credentials from XML
 */
function extractPOSCredentials($xml) {
    $namespaces = $xml->getNamespaces(true);
    $ns = isset($namespaces['']) ? $namespaces[''] : null;
    
    // Try to find credentials in POS/Source/RequestorID
    $username = '';
    $password = '';
    
    if (isset($xml->POS->Source->RequestorID)) {
        $requestorID = $xml->POS->Source->RequestorID;
        $username = (string) $requestorID['ID'];
        $password = (string) $requestorID['MessagePassword'];
    }
    
    // Alternative: check attributes directly on POS
    if (empty($username) && isset($xml->POS['Username'])) {
        $username = (string) $xml->POS['Username'];
        $password = (string) $xml->POS['Password'];
    }
    
    // Alternative: check Source attributes
    if (empty($username) && isset($xml->POS->Source)) {
        $source = $xml->POS->Source;
        if (isset($source['Username'])) {
            $username = (string) $source['Username'];
            $password = (string) $source['Password'];
        }
    }
    
    return ['username' => $username, 'password' => $password];
}

/**
 * Map SIPP code to vehicle characteristics
 */
function parseSIPPCode($sipp) {
    $category = substr($sipp, 0, 1);
    $type = substr($sipp, 1, 1);
    $transmission = substr($sipp, 2, 1);
    $fuelAC = substr($sipp, 3, 1);
    
    return [
        'category' => $category,
        'type' => $type,
        'transmission' => $transmission,
        'fuelAC' => $fuelAC
    ];
}

/**
 * Log API request for debugging
 */
function logAPIRequest($broker_id, $action, $request_data, $response_data = '') {
    global $conn;
    $request_json = is_string($request_data) ? $request_data : json_encode($request_data);
    $response_json = is_string($response_data) ? $response_data : json_encode($response_data);
    
    $stmt = $conn->prepare("INSERT INTO `tbl_bms_api_requests_log` 
                            (`broker_id`, `action`, `request_data`, `response_data`, `entered_date`) 
                            VALUES (?, ?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("isss", $broker_id, $action, $request_json, $response_json);
        $stmt->execute();
    }
}
?>

