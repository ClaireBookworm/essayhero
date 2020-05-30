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
$fName = htmlspecialchars_decode($_GET["fName"]);
$ftext = htmlspecialchars_decode($_GET["fText"]);
$fid = htmlspecialchars_decode($_GET["fId"]);


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
$document = $collection->findOne([
    'username' => $username
]);
$skip=false;
$arr = $document['files'];
//var_dump($arr);
for ($i = 0; $i < count($arr); $i++) {
    if($arr[$i]["fileId"] == $fid) {
        $arr[$i]["fileName"] = $fName;
        $arr[$i]["fileText"] = $ftext;
        $skip = true;
    break;
    } 
}

if (!$skip) {
    $collection->updateOne(['username' => $username],[
        '$push' =>
            [
                'files' => [
                    "fileId" => $fid,
                    "fileName" => $fName,
                    "fileText" => $ftext
                ]
            ]
        ]
    );
    echo "Created File Sucessfully";
} else {
    $collection->updateOne(
        ['username' => $username],
        ['$set' =>
            [
                'files' => $arr
            ]
        ]
    );
    echo "Edited File Sucessfully";
}

?>
