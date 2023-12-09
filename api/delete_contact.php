<?php

header("Access-Control-Allow-Origin: http://www.contactlist.com");
header("Content-Type: application/json");

function deleteContact($id) {
    $xml = simplexml_load_file('contacts.xml');

    $contactToDelete = null;
    foreach ($xml->contact as $contact) {
        if ((int)$contact['id'] === (int)$id) {
            $contactToDelete = $contact;
            break;
        }
    }

    if ($contactToDelete !== null) {
        $dom = dom_import_simplexml($contactToDelete);
        $dom->parentNode->removeChild($dom);

        $xml->asXML('contacts.xml');
        echo json_encode(["message" => "Contact with ID $id deleted successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["error" => "Contact with ID $id not found"]);
    }
}

$contactIdToDelete = $_POST['contactId'] ?? null;

if ($contactIdToDelete !== null) {
    deleteContact($contactIdToDelete);
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Invalid contact ID provided"]);
}
?>
