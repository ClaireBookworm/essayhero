<?php 
// Allow Cross Origin Requests (other ips can request data)
header("Access-Control-Allow-Origin: *");

// Load the JWT library
require_once __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

// Random Key. Needs to be Changed Later
$key = "5tblCfidHvSbVDQiOpv5OlsxNarHeIOlsbl4EDbCQBvsHyO2fgEfUaCvU";

// Get all parameters for creating/validating user
$jwt = htmlspecialchars_decode($_GET["jwt"]);
try{
$decoded = JWT::decode($jwt, $key, array('HS256'));
} catch (Exception $e)  {
    echo "Error: 201. This key has been tampered with.";
    return;
}

$decoded_array = (array) $decoded;
$username = $decoded_array["username"];

$client = new MongoDB\Client('mongodb+srv://dbrunner:dHOoEPz1HWw6Ihny@cluster0-uwqwt.azure.mongodb.net/test?retryWrites=true&w=majority');
// Select the user collection
$collection = $client->hackathon->userdata;
$document = $collection->findOne(['username' => $username]);

echo json_encode($document["files"]);
?>
