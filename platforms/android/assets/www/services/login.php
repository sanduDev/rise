<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';


$facebook_id = filter_input(INPUT_GET, 'facebook_id');
$name = filter_input(INPUT_GET, 'name');
$email = filter_input(INPUT_GET, 'email');
$picture = filter_input(INPUT_GET, 'picture');

$arr = [];
$arr['facebook_id'] = $facebook_id;
$arr['name'] = $name;
$arr['email'] = $email;
$arr['picture'] = $picture;

if($facebook_id && $name && $email && $picture){
    if(!checkUser($facebook_id)){
		if(subscribe($arr)){
			echo 'success';
		}else{
			echo 'error';
		}
    }else{
		echo 'user_exists';
	}
}