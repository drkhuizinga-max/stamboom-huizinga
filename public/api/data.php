<?php
// Voorkom dat witruimte of eerdere fouten de JSON verpesten
ob_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost"; 
$user = "dirkh_stamboom"; 
$pass = "StamboomHuizinga1";     
$dbname = "dirkh_stamboom"; 

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

if (isset($_GET['slug'])) {
    $id = intval(explode('-', $_GET['slug'])[0]);
    if ($id > 0) {
        $result = $conn->query("SELECT * FROM personen WHERE id = $id");
        $row = $result->fetch_assoc();

        if ($row) {
            // SCHOONMAAK: Dit is de redding voor Cornelis (ID 64)
            foreach ($row as $key => $value) {
                if (is_string($value)) {
                    // Verwijder enters en tabs die JSON breken
                    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    $row[$key] = str_replace(array("\r", "\n", "\t"), ' ', $value);
                }
            }
            // Gebruik extra vlaggen om crashes te voorkomen
            echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        } else {
            echo json_encode(["error" => "ID niet gevonden"]);
        }
    }
    exit;
}

// Kinderen ophalen
if (isset($_GET['children_of'])) {
    $parent_id = intval($_GET['children_of']);
    $result = $conn->query("SELECT id, naam FROM personen WHERE vader_id = $parent_id OR moeder_id = $parent_id");
    $kids = [];
    while($r = $result->fetch_assoc()) { $kids[] = $r; }
    echo json_encode($kids);
    exit;
}
?>