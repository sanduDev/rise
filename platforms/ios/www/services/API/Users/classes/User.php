<?php

/**
 * Description of User
 *
 * @author olivierlam
 */
class User {
    private $_id;
    private $_avatar_url;
    private $_surname;
    private $_email;
    private $_password;
    private $_neighbordhood;
    private $_sublocality;
    private $_locality;
    private $_administrative_area_level_2;
    private $_administrative_area_level_1;
    private $_country;
    private $_youtube_sub;
    private $_facebook_sub;
    private $_smtelse;
    private $_youtube;
    private $_soundcloud;
    private $_video;
    private $_facebook;
    private $_twitter;
    private $_instagram;
    
    public function hydrate(array $donnees)
    {
      foreach ($donnees as $key => $value)
      {
        $method = 'set'.ucfirst($key);

        if (method_exists($this, $method))
        {
          $this->$method($value);
        }
      }
    }
    
    public function __construct(array $lizeth)
    {
        $this->hydrate($lizeth);
    }

    public function id(){return $this->_id;}
    public function avatar_url(){return $this->_avatar_url;}
    public function surname(){return $this->_surname;}
    public function email(){return $this->_email;}
    public function password(){return $this->_password;}
    public function neighborhood(){return $this->_neighborhood;}
    public function sublocality(){return $this->_sublocality;}
    public function locality(){return $this->_locality;}
    public function administrative_area_2(){return $this->_administrative_area_level_2;}
    public function administrative_area_1(){return $this->_administrative_area_level_1;}
    public function country(){return $this->_country;}
    public function youtube_sub(){return $this->_youtube_sub;}
    public function facebook_sub(){return $this->_facebook_sub;}
    public function smtelse(){return $this->_smtelse;}
    public function youtube(){return $this->_youtube;}
    public function soundcloud(){return $this->_soundcloud;}
    public function video(){return $this->_video;}
    public function facebook(){return $this->_facebook;}
    public function twitter(){return $this->_twitter;}
    public function instagram(){return $this->_instagram;}
    
    public function setId($id)
    {
        if(is_string($id)){
            $this->_id = $id;
        }
    }

    public function setAvatar_url($a)
    {
        if (is_string($a))
        {
          $this->_avatar_url = $a;
        }
    }
    
    public function setSurname($a)
    {
        if (is_string($a))
        {
          $this->_surname = $a;
        }
    }

    public function setEmail($a)
    {
        if (is_string($a))
        {
          $this->_email = $a;
        }
    }

    public function setPassword($a)
    {
        if (is_string($a))
        {
          $this->_password = $a;
        }
    }

    public function setNeighborhood($a)
    {
        if (is_string($a))
        {
          $this->_neighbordhood = $a;
        }
    }

    public function setSublocality($a)
    {
        if (is_string($a))
        {
          $this->_sublocality = $a;
        }
    }

    public function setLocality($a)
    {
        if (is_string($a))
        {
          $this->_locality = $a;
        }
    }

    public function setAdministrative_area_level_2($a)
    {
        if (is_string($a))
        {
          $this->_administrative_area_level_2 = $a;
        }
    }

    public function setAdministrative_area_level_1($a)
    {
        if (is_string($a))
        {
          $this->_administrative_area_level_1 = $a;
        }
    }

    public function setCountry($a)
    {
        if (is_string($a))
        {
          $this->_country = $a;
        }
    }

    public function setYoutube_sub($a)
    {
        if (is_string($a))
        {
          $this->_youtube_sub = $a;
        }
    }

    public function setFacebook_sub($a)
    {
        if (is_string($a))
        {
          $this->_facebook_sub = $a;
        }
    }

    public function setSmtelse($a)
    {
        if (is_string($a))
        {
          $this->_smtelse = $a;
        }
    }

    public function setYoutube($a)
    {
        if (is_string($a))
        {
          $this->_youtube = $a;
        }
    }

    public function setSoundcloud($a)
    {
        if (is_string($a))
        {
          $this->_soundcloud = $a;
        }
    }

    public function setVideo($a)
    {
        if (is_string($a))
        {
          $this->_video = $a;
        }
    }

    public function setFacebook($a)
    {
        if (is_string($a))
        {
          $this->_facebook = $a;
        }
    }

    public function setTwitter($a)
    {
        if (is_string($a))
        {
          $this->_twitter = $a;
        }
    }

    public function setInstagram($a)
    {
        if (is_string($a))
        {
          $this->_instagram = $a;
        }
    }
}
