<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$search = filter_input(INPUT_GET, 'search');

if(!empty($search)){
	$response = $bdd->prepare('SELECT * FROM paris WHERE surname LIKE ?');
	$response->execute(["%$search%"]);
	$data = $response->fetchAll();

	$users = [];

	while($response->fetch()){
		
		$users[] = $row;
	}

	echo json_encode($data);
}else{
	echo 'Looking for something ?';
}