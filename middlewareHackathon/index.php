<?php 
// Allow Cross Origin Requests (other ips can request data)
header("Access-Control-Allow-Origin: *");

// Load the JWT library
require_once __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

// Random Key. Needs to be Changed Later
$key = "5tblCfidHvSbVDQiOpv5OlsxNarHeIOlsbl4EDbCQBvsHyO2fgEfUaCvU";

// Get all parameters for creating/validating user
$username = htmlspecialchars_decode($_GET["username"]);
$password = htmlspecialchars_decode($_GET["password"]);
$firstName = htmlspecialchars_decode($_GET["first"]);
$lastName = htmlspecialchars_decode($_GET["last"]);
$reason = htmlspecialchars_decode($_GET["reason"]);

// Create. The user has not been created and will be.
if ($reason == "create") {
    if (!$username || !$password || !$firstName || !$lastName) {
        echo "Not all of the parameters were found. Please ensure that you pass: username, password, first, last as well.";
        return;
    }
    createUser($username, $password, $firstName, $lastName);

// Verify. The user claims to be already in the system. Making sure that they are who they claim to be. Checking their username and password
} else if ($reason == "verify") {
    if (!$username || !$password) {
        echo "Not all of the parameters were found. Please ensure that you pass: username and password as well.";
        return;
    }
    verifyUser($username, $password);
// Something went wrong.
} else {
    echo "Invalid reason. Must be create, verify, authenticate.";
    return;
}



function createUser($username, $password, $firstName, $lastName) {
    // MONGO DB LOGIN
    $client = new MongoDB\Client('mongodb+srv://dbrunner:dHOoEPz1HWw6Ihny@cluster0-uwqwt.azure.mongodb.net/test?retryWrites=true&w=majority');
    // Select the user collection
    $collection = $client->hackathon->userdata;

    if(isTaken($username, $collection)) {
        echo "This username has been taken. Please choose another.";
        return;
    };
    $hashPass = hash("sha384",$password);

    $collection->insertOne([
        'username' => $username,
        'password' => $hashPass,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'accountCreatedAt' => time(),
        'files' => []
    ]);

    $payload = array(
        'username' => $username,
        'password' => $hashPass,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'iat' => time(),
        'eat' => strtotime("+30 days")
    );

    $jwt = JWT::encode($payload, "5tblCfidHvSbVDQiOpv5OlsxNarHeIOlsbl4EDbCQBvsHyO2fgEfUaCvU", 'HS256');
    echo $jwt;
}

function verifyUser($username, $password) {
    // MONGO DB LOGIN
    $client = new MongoDB\Client('mongodb+srv://dbrunner:dHOoEPz1HWw6Ihny@cluster0-uwqwt.azure.mongodb.net/test?retryWrites=true&w=majority');
    // Select the user collection

    $hashPass = hash("sha384",$password);

    $collection = $client->hackathon->userdata;
    $document = $collection->findOne(['username' => $username]);
    if($document['password'] == $hashPass) {
        $payload = array(
            'username' => $username,
            'password' => $hashPass,
            'firstName' => $document['firstName'],
            'lastName' => $document['lastName'],
            'iat' => time(),
            'eat' => strtotime("+30 days")
        );
    
        $jwt = JWT::encode($payload, "5tblCfidHvSbVDQiOpv5OlsxNarHeIOlsbl4EDbCQBvsHyO2fgEfUaCvU", 'HS256');
        echo $jwt;
    } else {
        echo "The username or password is inccorect.";
    }
    
}

function isTaken($username, $collection) {
    $document = $collection->findOne(['username' => $username]);
    if(is_null($document)) {
        return false;
    } else {
        return true;
    }
}
?>
