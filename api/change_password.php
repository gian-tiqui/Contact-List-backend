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
    $username = $_POST["username"];
    $oldPass = $_POST["oldPass"];
    $newPass = $_POST["newPass"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if (password_verify($oldPass, $hashedPassword)) {

            $newHashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET password = '$newHashedPassword' WHERE username = '$username'";

            if ($connection->query($updateSql) === TRUE) {
                echo json_encode(["message" => "Password change successful"]);
            } else {
                echo json_encode(["error" => "Error updating password"]);
            }
        } else {
            echo json_encode(["error" => "Incorrect old password"]);
        }
    } else {
        echo json_encode(["error" => "User not found"]);
    }
}

$connection->close();
?>
