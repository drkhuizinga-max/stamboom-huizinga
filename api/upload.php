<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Map waar de foto's komen te staan (pas aan indien nodig)
$target_dir = "../images/";

if (isset($_FILES["foto"])) {
    $file = $_FILES["foto"];
    $fileName = time() . "_" . basename($file["name"]); // Unieke naam om overschrijven te voorkomen
    $target_file = $target_dir . $fileName;

    // Check of het een echte afbeelding is
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            echo json_encode(["success" => true, "fileName" => $fileName]);
        } else {
            echo json_encode(["success" => false, "error" => "Upload mislukt op de server."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Bestand is geen afbeelding."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Geen bestand ontvangen."]);
}
?>