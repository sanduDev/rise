<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$artist_id = filter_input(INPUT_GET, 'artist_id');

if($user_id && $artist_id){
	// first we check if user exists
	if(checkUser($user_id)){
		// then, we check if user has already upvote that artist
		$checkUpVote = checkUpVote($user_id, $artist_id);
		
		if($checkUpVote == NULL){
			if(upVote($user_id, $artist_id)){
				$points = showPoints($artist_id);
				if(updatePoints($artist_id, $points)){
					// var_dump(showPoints($artist_id), showPointsC($artist_id));
					echo 'upvote_successful';
				}
			}else{
				echo 'upvoted_failed_1';
			}
		}else{
			if(remVote($user_id, $artist_id)){
				$points = showPoints($artist_id);
				if(updatePoints($artist_id, $points)){
					// var_dump(showPoints($artist_id), showPointsC($artist_id));
					echo 'upvoted_removed';
				}
			}
		}
	}else{
		echo 'upvoted_failed_3_user_does_not_exist';
	}
}