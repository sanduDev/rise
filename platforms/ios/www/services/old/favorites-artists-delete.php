<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$artist_id = filter_input(INPUT_GET, 'artist_id');

if($user_id && $artist_id){
    if(checkUser($user_id)){
        $reponse = $bdd->prepare('DELETE FROM favorites_artists WHERE favorite_artist_user_id = :favorite_artist_user_id AND favorite_artist_artist_id = :favorite_artist_artist_id AND favorite_artist_datetime = :favorite_artist_datetime');
        $reponse->execute([
            'favorite_artist_user_id' => $user_id,
            'favorite_artist_artist_id' => $artist_id,
            'favorite_artist_datetime' => date('Y-m-d H:i:s')
        ]);

        return true;
    }else{
        echo 'error';
    }
}