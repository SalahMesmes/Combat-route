<?php

namespace combats\controller;

use combats\model\Personnages;
use combats\model\PersonnagesManager;

class PersonnagesController extends Controller
{
    private $_persoManager;
    private $_listAllPlayers;
    
    public function __construct( array $params=[] )
    {
        $this->_persoManager = new PersonnagesManager();
        $this->_listAllPlayers = $this->_persoManager->getAllPlayers();
        parent::__construct( $params );
    }

    public function defaultAction()
    {
        $data = [
            'listAllPlayers'    => $this->_listAllPlayers
        ];
        $this->render( 'personnages', $data );
    }


    public function deleteAction()
    {
        $message = [
            'type' => 'danger',
            'mess' => 'Erreur lors de la suppression'
        ];
        if( isset( $this->vars['id'] ) ) {
            if( $this->_persoManager->deletePlayer( $this->vars['id']  ) ) {
                $message = [
                    'type'  => 'success',
                    'mess'  => 'Personnage supprimé'
                ];
            } 
        }
        $data = [
            'message'           => $message,
            'listAllPlayers'    => $this->_persoManager->getAllPlayers()
        ];
        $this->render( 'personnages', $data );
    }

    public function updateAction()
    {
        $data = [
            'nom'   => false,
            'listAllPlayers'  => $this->_listAllPlayers
        ];
        if( isset( $this->vars['id']  ) ) {
            if( $persoAModifier = $this->_persoManager->getOnePlayer( $this->vars['id']  ) ) {
                $data['nom'] = $persoAModifier->getNom();
                $data['idPlayer'] = $persoAModifier->getId();
            } 
        }
        $this->render( 'personnages', $data );
    }


    public function updateValideAction()
    {   
        $data = [
            'message'   => [
                'type'  => 'warning',
                'mess'  => 'Erreur lors de la mise à jour !'
            ]
        ];
        if( isset( $this->vars['id']  ) && !empty( $this->vars['nom']  ) ) {
            $perso = $this->_persoManager->getOnePlayer( $this->vars['id']  );
            $perso->setNom( $this->vars['nom']  );
            if( $this->_persoManager->updatePlayer( $perso ) ) {
                $data['message']['type'] = 'success';
                $data['message']['mess'] = 'Personnage mis à jour !';
            }
        }
        $data['listAllPlayers'] = $this->_persoManager->getAllPlayers();
        $this->render( 'personnages', $data );
    }


    public function createAction()
    {
        $data = [
            'message'   => [
                'type'  => 'warning',
                'mess'  => 'Erreur lors de la création du perso !'
            ]
        ];
        if( !empty( $this->vars['nom']  ) ) {
            $perso = new Personnages(['nom'=>$this->vars['nom'] ]);
            if( $this->_persoManager->createPlayer( $perso )) {
                $data['message']['type'] = 'success';
                $data['message']['mess'] = 'Personnage créé avec succès !';
            }
        }
        $data['listAllPlayers'] = $this->_persoManager->getAllPlayers();
        $this->render( 'personnages', $data );
    }
}    