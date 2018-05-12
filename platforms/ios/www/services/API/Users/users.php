<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


require_once '../autoloader.php';

$get = filter_input(INPUT_GET, 'id');
$getSearch = filter_input(INPUT_GET, 'search');
$getFromLocationLevel = filter_input(INPUT_GET, 'level');
$getFromLocationValue = filter_input(INPUT_GET, 'value');

if($get){
    
}elseif($getSearch){
    
}elseif($getSearch){
    
}else{
    
}