<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

require_once 'db.php';

/*
* Collect all Details from Angular HTTP Request.
*/
$postdata = file_get_contents("php://input");
$request = json_encode($postdata);

$user_id = $request->user_id;
$artist_id = $request->artist_id;

// first, we check if user has already upvote that artist
if(!sizeof(checkUpVote($user_id, $artist_id) > 0)){
	upVote($user_id, $artist_id);
}else{
	echo "voted_already";
}