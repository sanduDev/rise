<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$track_id = filter_input(INPUT_GET, 'track_id');

if($user_id && $track_id){
    if(checkUser($user_id)){
        $reponse = $bdd->prepare('INSERT INTO favorites_tracks(favorite_track_user_id, favorite_track_track_id, favorite_track_datetime) VALUES(:favorite_track_user_id, :favorite_track_track_id, :favorite_track_datetime)');
        $reponse->execute([
            'favorite_track_user_id' => $user_id,
            'favorite_track_track_id' => $track_id,
            'favorite_track_datetime' => date('Y-m-d H:i:s')
        ]);

        return true;
    }else{
        echo 'error';
    }
}