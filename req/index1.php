<?php

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
    printf("%d cells appended.", $result->getUpdates()->getUpdatedCells());
}

// Usage
$spreadsheetId = '19RYXXhP57YJlxPYjVodI0g40Kdlm41OWoVGxb0bXOz4';
$range = 'Evaluation';
$values = [
    ['12312', 'dataaa', 'other data', '43565474']
];

appendValues($spreadsheetId, $range, $values);