<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$favorite_track_permalink_url = filter_input(INPUT_GET, 'iadzalziudn');

if($user_id && $favorite_track_permalink_url){
    if(checkUser($user_id)){
        $reponse = $bdd->prepare('DELETE FROM favorites_tracks WHERE favorite_track_user_id = :favorite_track_user_id AND favorite_track_permalink_url = :favorite_track_permalink_url');
        $reponse->execute([
            'favorite_track_user_id' => $user_id,
            'favorite_track_permalink_url' => $favorite_track_permalink_url
        ]);

        return true;
    }else{
        echo 'error';
    }
}