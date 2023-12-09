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
    
    $query = "SELECT * FROM users";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $responseData['data'][] = $row;
        }
    }

    $connection->close();

    header('Content-Type: application/json');
    echo json_encode($responseData);
} else {
    http_response_code(405); 
    echo json_encode(array('status' => 'error', 'message' => 'Method not allowed'));
}

?>
