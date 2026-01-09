<?php
/**
 * TSD XML API - Main Router
 * Autostrad Rent a Car - Mwasalat
 * 
 * This is the main entry point for TSD XML API requests.
 * It routes requests to the appropriate handler based on the XML message type.
 * 
 * Endpoint: http://api.autostrad.com/hub/api/tsd/
 * 
 * Supported Operations:
 * - OTA_VehLocSearchRQ  - Get locations
 * - OTA_VehAvailRateRQ  - Get vehicle availability and rates
 * - OTA_VehResRQ        - Create new reservation
 * - OTA_VehCancelRQ     - Cancel reservation
 * - OTA_VehModifyRQ     - Modify reservation (contact details only)
 */

header('Content-Type: application/xml; charset=utf-8');

// Get raw XML input
$xmlInput = file_get_contents('php://input');

if (empty($xmlInput)) {
    outputErrorMain("ERR_EMPTY", "No XML request received");
    exit;
}

// Parse XML to determine message type
libxml_use_internal_errors(true);
$xml = simplexml_load_string($xmlInput);

if ($xml === false) {
    $errors = libxml_get_errors();
    $errorMsg = !empty($errors) ? $errors[0]->message : "Invalid XML format";
    outputErrorMain("ERR_PARSE", "XML Parse Error: " . trim($errorMsg));
    exit;
}

// Get the root element name to determine the operation
$rootName = $xml->getName();

// Route to appropriate handler
switch ($rootName) {
    case 'OTA_VehLocSearchRQ':
        include 'OTA_VehLocSearch.php';
        break;
        
    case 'OTA_VehAvailRateRQ':
        include 'OTA_VehAvailRate.php';
        break;
        
    case 'OTA_VehResRQ':
        include 'OTA_VehRes.php';
        break;
        
    case 'OTA_VehCancelRQ':
        include 'OTA_VehCancel.php';
        break;
        
    case 'OTA_VehModifyRQ':
        include 'OTA_VehModify.php';
        break;
        
    case 'OTA_VehListRQ':
        include 'OTA_VehList.php';
        break;
        
    default:
        outputErrorMain("ERR_UNSUPPORTED", "Unsupported operation: " . $rootName);
        exit;
}

/**
 * Output error for main router
 */
function outputErrorMain($code, $message) {
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<OTA_VehErrorRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">';
    echo '<Errors>';
    echo '<Error Type="Error" Code="' . htmlspecialchars($code) . '">';
    echo htmlspecialchars($message);
    echo '</Error>';
    echo '</Errors>';
    echo '</OTA_VehErrorRS>';
}
?>

