<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$favorite_track_artist_id = filter_input(INPUT_GET, 'artist_id');
$favorite_track_artist_surname = filter_input(INPUT_GET, 'surname');
$favorite_track_permalink_url = filter_input(INPUT_GET, 'permalink_url');
$favorite_track_src = filter_input(INPUT_GET, 'src');
$favorite_track_title = filter_input(INPUT_GET, 'title');
$favorite_track_artwork = filter_input(INPUT_GET, 'artwork');

if($user_id && $favorite_track_permalink_url){
    if(checkUser($user_id)){
		if(checkTrack($user_id, $track_id){
			addFavoriteTrack($user_id, $favorite_track_artist_id, $favorite_track_artist_surname, $favorite_track_permalink_url, $favorite_track_src, $favorite_track_title, $favorite_track_artwork);
		}
    }else{
        echo 'error';
    }
}else{
	echo 'error';
}
