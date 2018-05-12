<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$track_id = filter_input(INPUT_GET, 'track_id');

if($user_id && $track_id){
    if(checkUser($user_id)){
        $reponse = $bdd->prepare('DELETE FROM favorites_tracks WHERE favorite_track_user_id = :favorite_track_user_id AND favorite_track_track_id = :favorite_track_track_id AND favorite_track_datetime = :favorite_track_datetime');
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