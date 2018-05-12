<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');

if($user_id){
	//fetch table rows from mysql db
	$reponse = $bdd->prepare('SELECT * FROM favorites_tracks WHERE favorite_track_user_id = :favorite_track_user_id GROUP BY favorite_track_permalink_url');
	$reponse->execute(['favorite_track_user_id' => $user_id]);
	$data = $reponse->fetchAll();
	if(sizeof($data) > 0){
		$arr = [];
		
		for($i=0;$i<sizeof($data);$i++){
			$arr[$i] = $data[$i]['favorite_track_artist_id'];
			$arr[$i] = $data[$i]['favorite_track_artist_surname'];
			$arr[$i] = $data[$i]['favorite_track_permalink_url'];
			$arr[$i] = $data[$i]['favorite_track_title'];
			$arr[$i] = $data[$i]['favorite_track_artwork'];
		}
		
		$ids = implode(',', $arr);
		
		echo json_encode($data);
	}else{
		echo '[]';
	}
}
