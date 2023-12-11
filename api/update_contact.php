<?php
header("Access-Control-Allow-Origin: http://www.contactlist.com");
header("Access-Control-Allow-Methods: POST, OPTIONS");

require("connection.php");

function updateContact($contactId, $updatedContact, $connection) {
    
    $ln = $updatedContact['lastName'];
    $ad = $updatedContact['address'];
    $cs = $updatedContact['civilStatus'];
    $pn = $updatedContact['phoneNumber'];
    $em = $updatedContact['email'];

    $query = "UPDATE contacts SET last_name = '$ln', address = '$ad', civil_status = '$cs', phone_number = '$pn', email = '$em' WHERE id = '$contactId'";

    if ($connection->query($query) === TRUE) {
        $xmlFile = 'contacts.xml';

        $xml = simplexml_load_file($xmlFile);

        $contact = $xml->xpath("/contacts/contact[@id='$contactId']")[0];

        $contact->last_name = $ln;
        $contact->address = $ad;
        $contact->civil_status = $cs;
        $contact->phone_number = $pn;
        $contact->email = $em;

        $jsonFile = 'contacts.json';
        $json = json_decode(file_get_contents($jsonFile), true);

        foreach ($json['contacts'] as &$jsonContact) {
            if ($jsonContact['id'] == $contactId) {
                $jsonContact['last_name'] = $ln;
                $jsonContact['address'] = $ad;
                $jsonContact['civil_status'] = $cs;
                $jsonContact['phone_number'] = $pn;
                $jsonContact['email'] = $em;
                break;
            }
        }

        file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));

        if ($xml->asXML($xmlFile)) {
            echo json_encode(['success' => true, 'message' => 'Contact updated successfully']);
        } else {
            echo json_encode(['error' => 'Error saving XML file']);
        }
    }

    $connection->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $contactId = $_POST['id'];
    $updatedContact = [
        'lastName' => $_POST['lastName'],
        'address' => $_POST['address'],
        'civilStatus' => $_POST['civilStatus'],
        'phoneNumber' => $_POST['phoneNumber'],
        'email' => $_POST['email'],
    ];

    updateContact($contactId, $updatedContact, $connection);

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
