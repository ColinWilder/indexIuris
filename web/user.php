<?php 
session_start();
header('Content-type: application/json');

if ($_SESSION["logged-in"]=="true"){
$response = array(
"url" => "http://lichen.csd.sc.edu/indexiuris/account",
"name" => $_SESSION["username"]
);

echo "callback (".json_encode($response).");";
}
else {
$response = array(
"url" => "http://lichen.csd.sc.edu/indexiuris/login",
"name" => "Login"
);

echo "callback (".json_encode($response).");";
}
?>