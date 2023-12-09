<?php

header('Access-Control-Allow-Origin: http://www.contactlist.com');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json'); 

$host = "localhost";
$user = "root";
$password = "hakdog2001";
$database = "contacts"; 

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $connection->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["usname"];
    $password = $_POST["pass"];
    $email = $_POST["email"];
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $birthYear = $_POST["birthYear"];
    $birthMonth = $_POST["birthMonth"];
    $birthDay = $_POST["birthDay"];
    $sex = $_POST["sex"];
    $address = $_POST["address"];
    $civilStatus = $_POST["civilStatus"];
    $phone = $_POST["phone"];

    $checkSql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $res = $connection->query($checkSql);

    if ($res->num_rows > 0) {
        echo json_encode(["error" => "Username or email already exists"]);
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, email, first_name, middle_name, last_name, birth_year, birth_month, birth_day, sex, phone_number, address, civil_status) 
                VALUES ('$username', '$hashedPassword', '$email', '$firstName', '$middleName', '$lastName', '$birthYear', '$birthMonth', '$birthDay', '$sex', '$phone', '$address', '$civilStatus')";

        if ($connection->query($sql) === TRUE) {
            echo json_encode(["message" => "Registration successful"]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $connection->error]);
        }
    }
}

$connection->close();
?>
