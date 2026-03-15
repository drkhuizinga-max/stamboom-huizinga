<?php
// Jouw GitHub OAuth gegevens
$client_id = 'Ov23liH6eRN81SDwA2Ao';
$client_secret = 'cc9932da3b1b73240e18ad95ca926c94aa6df218';

// 1. Gebruiker naar GitHub sturen voor toestemming
if (!isset($_GET['code'])) {
    $url = "https://github.com/login/oauth/authorize?client_id=$client_id&scope=repo";
    header("Location: $url");
    exit;
}

// 2. Code inruilen voor een Token
$ch = curl_init("https://github.com/login/oauth/access_token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $_GET['code']
]);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

$response = json_encode(json_decode(curl_exec($ch)));
curl_close($ch);

// 3. Token terugsturen naar Decap CMS
echo "<script>
  window.opener.postMessage('authorization:github:success:$response', '*');
  window.close();
</script>";
?>