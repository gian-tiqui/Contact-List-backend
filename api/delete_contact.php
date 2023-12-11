<?php

header("Access-Control-Allow-Origin: http://www.contactlist.com");
header("Content-Type: application/json");

require("connection.php");

function deleteContact($id, $connection) {
    $xml = simplexml_load_file('contacts.xml');

    $contactToDelete = null;
    foreach ($xml->contact as $contact) {
        if ((int)$contact['id'] === (int)$id) {
            $contactToDelete = $contact;
            break;
        }
    }

    $query = "DELETE FROM contacts WHERE id = '$id'";


    if ($contactToDelete !== null && $connection->query($query) === TRUE) {
        $dom = dom_import_simplexml($contactToDelete);
        $dom->parentNode->removeChild($dom);

        $xml->asXML('contacts.xml');

        $jsonFile = 'contacts.json';
        $json = json_decode(file_get_contents($jsonFile), true);

        foreach ($json['contacts'] as $key => $jsonContact) {
            if ($jsonContact['id'] == $id) {
                unset($json['contacts'][$key]);
                break;
            }
        }

        file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));
        
        echo json_encode(["message" => "Contact with ID $id deleted successfully"]);
    } else {
        http_response_code(404); 
        echo json_encode(["error" => "Contact with ID $id not found"]);
    }
}

$contactIdToDelete = $_POST['contactId'] ?? null;

if ($contactIdToDelete !== null) {
    deleteContact($contactIdToDelete, $connection);
} else {
    http_response_code(400); 
    echo json_encode(["error" => "Invalid contact ID provided"]);
}
?>
