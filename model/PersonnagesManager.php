<?php
namespace combats\model;

use combats\model\Manager;
use combats\model\Personnages;

/**
 * 
 * 
 */
Class PersonnagesManager extends Manager
{

    /**
     * Retrieve one character
     * 
     * @param int $id
     * @return Personnages
     */
    public function getOnePlayer( int $id ): ?Personnages
    {    
        $sql = "SELECT * FROM personnages WHERE id=:id";
        $req = $this->dbManager->db->prepare( $sql );
        if( $req->execute([ ':id'  => $id ]) ) {
            $persoData = $req->fetch( \PDO::FETCH_ASSOC );
            $perso = new Personnages( $persoData );
            return $perso; 
        } else {
            return false;
        }  
    }    



    public function getListPlayerToHit( int $idPerso ): array
    {
        $listPersoToHit = [];
        if( isset( $idPerso ) ) {
            $sql = "SELECT * FROM personnages WHERE id <> :id";
            $req = $this->dbManager->db->prepare( $sql );
            if( $req->execute([':id'  => $idPerso]) ) {
                $listPerso = $req->fetchAll( \PDO::FETCH_ASSOC );
                foreach ( $listPerso as $key=>$val ) {
                    $listPersoToHit[] = new Personnages( $val );
                }
            }
        }
        return $listPersoToHit;
    }



    public function getAllPlayers(): array
    {
        $sql = "SELECT id,nom FROM personnages";
        $response = $this->dbManager->db->query( $sql );
        return $response->fetchAll( \PDO::FETCH_ASSOC ); 
    }




    public function deletePlayer( int $idPerso ): bool 
    {
        if( isset( $idPerso ) ) {
            $sql = "DELETE FROM personnages WHERE id=:id";
            $req = $this->dbManager->db->prepare( $sql );
            $state = $req->execute([
                ':id'  => $idPerso
            ]);
            return $state;
        }  
        return false;       
    }



    public function updatePlayer( Personnages $Perso ): bool
    {
        if( $Perso ) {
            $sql = "UPDATE personnages SET nom=:nom, degats=:degats, niveau=:niveau, experience=:experience WHERE id=:id";
            $req = $this->dbManager->db->prepare( $sql );
            $state = $req->execute([
                ':id'  => $Perso->getId(),
                ':nom'  => $Perso->getNom(),
                ':degats' => $Perso->getDegats(),
                ':niveau' => $Perso->getNiveau(),
                ':experience' => $Perso->getExperience()
            ]);
            return $state;
        }  
        return false; 
    }


    
    public function createPlayer( Personnages $newPerso ): bool
    {
        $sql = "INSERT INTO personnages (nom, degats, experience, niveau) VALUES (:nom, :degats, :exp, :niveau)";
        $req = $this->dbManager->db->prepare( $sql );
        $state = $req->execute([
            ':nom'      => $newPerso->getNom(),
            ':degats'   => 0,
            ':exp'      => 0,
            ':niveau'   => 0
        ]);
        if( $state ) {
            $newPerso->setId( $this->dbManager->db->lastInsertId() );
        }
        return $state;
    }

}