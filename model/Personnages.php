<?php

namespace combats\model;

class Personnages
{
    private $_id;
    private $_force = 0;
    private $_experience = 0;
    private $_degats = 0;
    private $_niveau = 0;
    private $_nom = false;

    private $_limitExp;

    const FORCE_PETITE = 10;
    const FORCE_MOYENNE = 20;
    const FORCE_GRANDE = 50;

    const PERSONNAGE_TUE        = 1; 
    const PERSONNAGE_FRAPPE     = 2; 
    const QTE_DEGATS            = 1;
    const DEATH_LIMIT           = 100;
    const STEP_EXP              = 5;
    const BASE_EXP              = 40;
    


    public function __construct( array $data )
    {
       // $this->setForce( $force );
       // $this->setExperience( $experience );
       $this->hydrate( $data );
       $this->setLimitExp();
    }

    public function __toString()
    {
        return $this->getNom();
    }


    public function hydrate( array $data )
    {
        foreach( $data as $key=>$value) {
            $method = 'set' . ucfirst( $key );
            if( method_exists( $this, $method ) ) {
                $this->$method( $value );  
            }
        }
    }

    public function getId() :int
    {
        return $this->_id;
    }

    /**
     * 
     */
    public function setId( int $id )
    {
        $this->_id = $id;
    }


    public function getForce() :int
    {
        return $this->_force;
    }

    public function setForce( int $force )
    {
        $this->_force = $force ?? self::FORCE_GRANDE;
    }

    public function getExperience() :int
    {
        return $this->_experience;
    }

    public function setExperience( int $experience )
    {
        $this->_experience = $experience;
    }

    public function getDegats() :int
    {
        return $this->_degats;
    }
    public function getLimitExp()
    {
        return $this->_limitExp;
    }

    public function setDegats( int $degats )
    {
        $this->_degats = $degats;
    }

    public function getNom()
    {
        return $this->_nom;
    }
    public function setNom( $nom )
    {
        $this->_nom = $nom;
    }
    public function setNiveau( int $niveau )
    {
        $this->_niveau = $niveau;
    }
    public function getNiveau()
    {
        return $this->_niveau;
    }

    public function ajoutDegats()
    {
        $this->_degats += self::QTE_DEGATS;
        if( $this->getDegats() >= self::DEATH_LIMIT ) {
            return self::PERSONNAGE_TUE;
        } 
        return self::PERSONNAGE_FRAPPE;
    }


    public function ajoutExperience()
    {
        $this->_experience += self::STEP_EXP;
        if( $this->_experience >= $this->_limitExp ) {
            $this->_niveau++;
            $this->setExperience(0);
            $this->setLimitExp();
        }
    }

    public function setLimitExp()
    {
        $this->_limitExp = self::BASE_EXP * ($this->getNiveau() + 1);
    }

}





