<?php

require("connection.php");

header('Access-Control-Allow-Origin: http://www.contactlist.com');
header('Access-Control-Allow-Methods: GET');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $responseData = array(
        'status' => 'success',
        'message' => 'mah data',
        'data' => array()
    );

    $xmlFile = "contacts.xml";

    if (file_exists($xmlFile)) {
        $xmlContent = file_get_contents($xmlFile);

        header('Content-Type: application/xml');
        echo $xmlContent;
    } else {
        $responseData['status'] = 'error';
        $responseData['message'] = 'XML file not found.';
        echo array_to_xml($responseData);
    }

    $connection->close();
} else {
    http_response_code(405);
    echo json_encode(array('status' => 'error', 'message' => 'Method not allowed'));
}

function array_to_xml($array, $rootElement = null, $xml = null)
{
    $xml = new SimpleXMLElement($rootElement ? '<' . $rootElement . '/>' : '<root/>');
    array_walk_recursive($array, function ($value, $key) use ($xml) {
        $xml->addChild($key, htmlspecialchars($value));
    });
    return $xml->asXML();
}
?>
