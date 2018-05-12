<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

$id = filter_input(INPUT_GET, 'user_id');
if($id){	// fetch user's data
	$user = getUser($id);

	$points = $user[0]['points'];

	$user[0]['actual_points'] = showPointsC($id);

	$gps = ['neighborhood', 'locality', 'political'];

	for($i=0;$i<sizeof($gps);$i++){
		$user[0]['rank_'.$gps[$i]] = getUserRank($points, $gps[$i], $user[0][$gps[$i]]);
		// $user[0]['rank_'.$gps[$i]] = getUsersByLocation($gps[$i], $user[0][$gps[$i]]);
	}

	echo json_encode($user);
}
