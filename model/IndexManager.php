<?php

namespace combats\model;

class IndexManager extends Manager
{

    public function getNumberOfPlayer()
    {
        $sql = "SELECT COUNT(*) FROM personnages";
        $response = $this->dbManager->db->query( $sql );
        $nbPerso = current( $response->fetch() );
        return $nbPerso;
    }
}