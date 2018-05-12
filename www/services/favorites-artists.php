<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');

if($user_id){
	//fetch table rows from mysql db
	$reponse = $bdd->prepare('SELECT favorite_artist_artist_id FROM favorites_artists WHERE favorite_artist_user_id = :favorite_artist_user_id GROUP BY favorite_artist_artist_id');
	$reponse->execute(['favorite_artist_user_id' => $user_id]);
	$data = $reponse->fetchAll();
	if(sizeof($data) > 0){
		$arr = [];

		for($i=0;$i<sizeof($data);$i++){
			$arr[$i] = $data[$i]['favorite_artist_artist_id'];
		}

		// var_dump($arr);

		$ids = implode(',', $arr);

		// echo $ids;

		$reponse2 = $bdd->query('SELECT * FROM usa WHERE user_id IN ('.$ids.') ORDER by surname ASC');
		$data2 = $reponse2->fetchAll();

		echo json_encode($data2);
	}else{
		echo '[]';
	}
}
