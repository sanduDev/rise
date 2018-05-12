<?php
class UsersManager
{
    private $_db; // Instance de PDO

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function add($user)
    {
        $q = $this->_db->prepare('INSERT INTO rise(avatar_url, surname, email, password, neighborhood, sublocality, locality, administrative_area_level_2, administrative_area_level_1, country, youtube_sub, facebook_sub, smtelse, youtube, soundcloud, video, facebook, twitter, instagram) VALUES(:avatar_url, :surname, :email, :password, :neighborhood, :sublocality, :locality, :administrative_area_level_2, :administrative_area_level_1, :country, :youtube_sub, :facebook_sub, :smtelse, :youtube, :soundcloud, :video, :facebook, :twitter, :instagram)');

        $q->bindValue(':avatar_url', $user->avatar_url());
        $q->bindValue(':surname', $user->surname());
        $q->bindValue(':email', $user->email());
        $q->bindValue(':password', $user->password());
        $q->bindValue(':neighborhood', $user->neighborhood());
        $q->bindValue(':sublocality', $user->sublocality());
        $q->bindValue(':locality', $user->locality());
        $q->bindValue(':administrative_area_level_2', $user->administrative_area_level_2());
        $q->bindValue(':administrative_area_level_1', $user->administrative_area_level_1());
        $q->bindValue(':country', $user->country());
        $q->bindValue(':youtube_sub', $user->youtube_sub());
        $q->bindValue(':facebook_sub', $user->facebook_sub());
        $q->bindValue(':smtelse', $user->smtelse());
        $q->bindValue(':youtube', $user->youtube());
        $q->bindValue(':soundcloud', $user->soundcloud());
        $q->bindValue(':video', $user->video());
        $q->bindValue(':facebook', $user->facebook());
        $q->bindValue(':twitter', $user->twitter());
        $q->bindValue(':instagram', $user->instagram());

        $q->execute();
    }

    public function delete(User $user)
    {
        $this->_db->exec('DELETE FROM rise WHERE id = '.$user->id());
    }

    public function get($id)
    {
        $id = int($id);

        $q = $this->_db->query('SELECT * FROM rise WHERE id = '.$id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new User($donnees);
    }

    public function getList()
    {
        $rise = [];

        $q = $this->_db->query('SELECT * FROM rise ORDER BY id');

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
          $rise[] = new User($donnees);
        }

        return $rise;
    }

    public function update(User $user)
    {
        $q = $this->_db->prepare('UPDATE rise SET '
                . 'id = :id, '
                . 'avatar_url = :avatar_url, '
                . 'surname = :surname, '
                . 'email = :email, '
                . 'password = :password, '
                . 'neighborhood = :neighborhood, '
                . 'sublocality = :sublocality, '
                . 'locality = :locality, '
                . 'administrative_area_level_2 = :administrative_area_level_2, '
                . 'administrative_area_level_1 = :administrative_area_level_1, '
                . 'country = :country, '
                . 'youtube_sub = :youtube_sub, '
                . 'facebook_sub = :facebook_sub, '
                . 'smtelse = :smtelse, '
                . 'youtube = :youtube, '
                . 'soundcloud = :soundcloud, '
                . 'video = :video, '
                . 'facebook = :facebook, '
                . 'twitter = :twitter, '
                . 'instagram = :instagram '
                . 'WHERE id = :id');

        $q->bindValue(':id', $user->id());
        $q->bindValue(':avatar_url', $user->avatar_url());
        $q->bindValue(':surname', $user->surname());
        $q->bindValue(':email', $user->email());
        $q->bindValue(':password', $user->password());
        $q->bindValue(':neighborhood', $user->neighborhood());
        $q->bindValue(':sublocality', $user->sublocality());
        $q->bindValue(':locality', $user->locality());
        $q->bindValue(':administrative_area_level_2', $user->administrative_area_level_2());
        $q->bindValue(':administrative_area_level_1', $user->administrative_area_level_1());
        $q->bindValue(':country', $user->country());
        $q->bindValue(':youtube_sub', $user->youtube_sub());
        $q->bindValue(':facebook_sub', $user->facebook_sub());
        $q->bindValue(':smtelse', $user->smtelse());
        $q->bindValue(':youtube', $user->youtube());
        $q->bindValue(':soundcloud', $user->soundcloud());
        $q->bindValue(':video', $user->video());
        $q->bindValue(':facebook', $user->facebook());
        $q->bindValue(':twitter', $user->twitter());
        $q->bindValue(':instagram', $user->instagram());

        $q->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}