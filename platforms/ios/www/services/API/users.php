<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


require_once '../autoloader.php';

$get = filter_input(INPUT_GET, 'id');
$getSearch = filter_input(INPUT_GET, 'search');

if($get){
    
}elseif($getSearch){
    
}else{
    
}