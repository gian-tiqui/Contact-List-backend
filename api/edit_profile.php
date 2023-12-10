<?php

require("connection.php");

header('Access-Control-Allow-Origin: http://www.contactlist.com');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $connection->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $lastName = $_POST["lastName"];
  $civilStatus = $_POST["civilStatus"];
  $address = $_POST["address"];
  $userId = $_POST["userId"];

  $query = "UPDATE users SET last_name = ?, address = ?, civil_status = ? WHERE uid = ?";
  $stmt = $connection->prepare($query);
  $stmt->bind_param("sssi", $lastName, $address, $civilStatus, $userId);

  if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

  $stmt->close();
}
?>
