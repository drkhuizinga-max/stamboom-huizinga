<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Pak de data uit $_POST (omdat we FormData gebruiken in Vue)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. HONEYPOT CHECK
    if (!empty($_POST['website'])) {
        echo json_encode(["status" => "success", "message" => "Bot detected"]);
        exit;
    }

    // 2. DATA SCHOONMAKEN
    $naam      = strip_tags(trim($_POST['naam'] ?? ''));
    $email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $onderwerp = strip_tags(trim($_POST['onderwerp'] ?? ''));
    $bericht   = strip_tags(trim($_POST['bericht'] ?? ''));

    // 3. VALIDATIE
    if (empty($naam) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Ongeldige invoer"]);
        exit;
    }

    // 4. BIJLAGE VERWERKEN
    $has_attachment = false;
    if (isset($_FILES['bijlage']) && $_FILES['bijlage']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['bijlage']['tmp_name'];
        $file_name     = $_FILES['bijlage']['name'];
        $file_size     = $_FILES['bijlage']['size'];
        $file_type     = $_FILES['bijlage']['type'];
        
        // Limiet check (bijv. 10MB)
        if ($file_size > 10 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Bestand is te groot (max 10MB)"]);
            exit;
        }
        
        $handle = fopen($file_tmp_name, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));
        $has_attachment = true;
    }

    // 5. E-MAIL INSTELLINGEN & HEADERS VOOR BIJLAGE
    $to      = "mail@dirkhuizinga.nl";
    $subject = "Nieuw bericht (met bijlage): " . $onderwerp;
    $boundary = md5(time()); // Unieke scheider voor e-mail onderdelen

    // Headers
    $headers = "From: info@huizinga-genealogie.nl\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";

    // Bericht tekst
    $message = "--" . $boundary . "\r\n";
    $message .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= "Naam: $naam\nEmail: $email\nOnderwerp: $onderwerp\n\nBericht:\n$bericht\n\r\n";

    // Bestand toevoegen indien aanwezig
    if ($has_attachment) {
        $message .= "--" . $boundary . "\r\n";
        $message .= "Content-Type: " . $file_type . "; name=\"" . $file_name . "\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"\r\n\r\n";
        $message .= $encoded_content . "\r\n";
    }
    
    $message .= "--" . $boundary . "--";

    // 6. VERZENDEN
    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(["status" => "success"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Mail failure"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
}