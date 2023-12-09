<?php
header("Access-Control-Allow-Origin: http://www.contactlist.com");
header("Access-Control-Allow-Methods: POST, OPTIONS");

function updateContact($contactId, $updatedContact) {
    $xmlFile = 'contacts.xml';

    $xml = simplexml_load_file($xmlFile);

    $contact = $xml->xpath("/contacts/contact[@id='$contactId']")[0];

    $contact->last_name = $updatedContact['lastName'];
    $contact->address = $updatedContact['address'];
    $contact->civil_status = $updatedContact['civilStatus'];
    $contact->phone_number = $updatedContact['phoneNumber'];
    $contact->email = $updatedContact['email'];

    $xml->asXML($xmlFile);
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

    // Update the contact information
    updateContact($contactId, $updatedContact);

    // Send a response (you can customize the response as needed)
    echo json_encode(['success' => true, 'message' => 'Contact updated successfully']);
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
