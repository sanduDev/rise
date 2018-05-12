<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$location = filter_input(INPUT_GET, 'location');
$location_value = filter_input(INPUT_GET, 'value');


$data = getUsersByLocation($location, $location_value);

var_dump($data);