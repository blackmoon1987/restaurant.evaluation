<?php
header('Content-Type: application/json');

require_once 'vendor/autoload.php';

function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');

    return $client;
}

function appendValues($spreadsheetId, $range, $values)
{
    $client = getClient();
    $service = new Google_Service_Sheets($client);

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $params = [
        'valueInputOption' => 'RAW'
    ];
    $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    return $result->getUpdates()->getUpdatedCells();
}

// Function to handle data processing
function processData($data) {
    if (is_array($data) && !empty($data)) {
        $spreadsheetId = 'xxxxxxxxxxxxxxxxxxxxb0bXOz4'; // Replace with your actual spreadsheet ID
        $range = 'Evaluation'; // Replace with your desired sheet name or range

        // Format the data as a single row
        $formattedData = [
            [
                $data['fullName'] ?? '',
                $data['mobile'] ?? '',
                $data['visitDate'] ?? '',
                $data['hearAbout'] ?? '',
                $data['visitFrequency'] ?? '',
                $data['qualityRating'] ?? '',
                $data['serviceQuality'] ?? '',
                $data['overallExperience'] ?? '',
                $data['suggestions'] ?? ''
            ]
        ];

        try {
            $updatedCells = appendValues($spreadsheetId, $range, $formattedData);
            return ['success' => true, 'message' => "Data recorded successfully. $updatedCells cells updated."];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    } else {
        return ['success' => false, 'message' => 'Invalid input data.'];
    }
}

// Handle different request methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = processData($data);
        break;
    case 'GET':
        $result = ['success' => true, 'message' => 'This API accepts POST requests to add data to a Google Sheet.'];
        break;
    default:
        $result = ['success' => false, 'message' => 'Unsupported request method. Please use POST to submit data.'];
}

echo json_encode($result);
?>
