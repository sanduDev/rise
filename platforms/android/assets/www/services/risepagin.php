<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$level = filter_input(INPUT_GET, 'level');
$value = filter_input(INPUT_GET, 'value');

$page = filter_input(INPUT_GET, 'p');

$limit = 15;
$new = ($page - 1) * $limit;

if($new < 1){
	$new = 0;
}

if($level && $value){
	//fetch table rows from mysql db
	$reponse = $bdd->prepare('SELECT * FROM usa WHERE '.$level.' = :value ORDER BY points DESC LIMIT '.$new.', '.$limit);
	$reponse->execute([
		'value' => $value
	]);
	$data = $reponse->fetchAll(PDO::FETCH_ASSOC);


	echo json_encode($data);
}
