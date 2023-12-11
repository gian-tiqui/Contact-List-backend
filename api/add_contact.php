<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cFirstName = $_POST['cFirstName'];
    $cMiddleName = $_POST['cMiddleName'];
    $cLastName = $_POST['cLastName'];
    $cSex = $_POST['cSex'];
    $cCivilStatus = $_POST['cCivilStatus'];
    $cBirthYear = $_POST['cBirthYear'];
    $cBirthMonth = $_POST['cBirthMonth'];
    $cBirthDay = $_POST['cBirthDay'];
    $cAddress = $_POST['cAddress'];
    $cPhone = $_POST['cPhone'];
    $cEmail = $_POST['cEmail'];
    $contactOf = $_POST['contactOf'];

    

    $xml = simplexml_load_file('contacts.xml');

    $maxId = 0;
    foreach ($xml->contact as $contact) {
        $currentId = (int)$contact['id'];
        $maxId = max($maxId, $currentId);
    }

    $newId = $maxId + 1;

    $query = "INSERT INTO contacts VALUES ('$newId', '$cFirstName', '$cMiddleName', '$cLastName', '$cSex', '$cCivilStatus', $cBirthMonth, $cBirthDay, $cBirthYear, '$cAddress', '$cPhone', '$cEmail', $contactOf)";

    if ($connection->query($query) === TRUE) {
        $newContact = $xml->addChild('contact');
        $newContact->addAttribute('id', $newId);
        $newContact->addChild('first_name', $cFirstName);
        $newContact->addChild('middle_name', $cMiddleName);
        $newContact->addChild('last_name', $cLastName);
        $newContact->addChild('sex', $cSex);
        $newContact->addChild('civil_status', $cCivilStatus);
        $newContact->addChild('birthmonth', $cBirthMonth);
        $newContact->addChild('birthday', $cBirthDay);
        $newContact->addChild('birthyear', $cBirthYear);
        $newContact->addChild('address', $cAddress);
        $newContact->addChild('phone_number', $cPhone);
        $newContact->addChild('email', $cEmail);
        $newContact->addChild('contact_of', $contactOf);
    }

    $xml->asXML('contacts.xml');

    echo json_encode(array('message' => 'Contact added successfully'));
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}

?>
