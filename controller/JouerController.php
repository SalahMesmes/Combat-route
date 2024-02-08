<?php
namespace combats\controller;

use combats\controller\Controller;
use combats\model\Personnages;
use combats\model\PersonnagesManager;

class JouerController extends Controller
{
    private $persoManager;
    private $listAllPlayers;
    private $persoToPlay;
    private $listPersoToHit;

    public function __construct( array $params=[] )
    {
        $this->persoManager = new PersonnagesManager();
        if( isset(  $_SESSION['persoToPlay'] ) ) {
            $this->persoToPlay = $_SESSION['persoToPlay'];
            $this->listPersoToHit = $this->persoManager->getListPlayerToHit( $this->persoToPlay->getId() );
        }
        $this->listAllPlayers = $this->persoManager->getAllPlayers();
        parent::__construct( $params );
    }


    public function defaultAction()
    {
        $data = [
            'listAllPlayers'  => $this->listAllPlayers
        ];
        $this->render('jouer', $data);
    }

    /**
     * Use a character
     */
    public function utiliserAction() 
    {
        $data = [
            'listAllPlayers'  => $this->listAllPlayers
        ];
        if( isset( $this->vars['id'] ) ) {
            unset( $_SESSION['persoToPlay'] );
            $this->persoToPlay = $this->persoManager->getOnePlayer( $this->vars['id'] );
            $_SESSION['persoToPlay'] = $this->persoToPlay;
            $this->listPersoToHit = $this->persoManager->getListPlayerToHit( $this->vars['id'] );
            $data['persoToPlay'] = $this->persoToPlay;
            $data['listPersoToHit'] = $this->listPersoToHit;
        }
        $this->render( 'jouer', $data );
    }

    /**
     * Hit a character
     */
    public function frapperAction() 
    {
        $persoToHit = $this->persoManager->getOnePlayer( $this->vars['idhit'] );
        $retour = $persoToHit->ajoutDegats();

        // update perso who has been hit
        if( $retour === Personnages::PERSONNAGE_FRAPPE ) {
            if( $this->persoManager->updatePlayer( $persoToHit ) ) {
                $message = [
                    'type'  => 'success'
                ];
                $message['mess'] = 'Le personnage <b>' . $persoToHit->getNom() . '</b> a bien été frappé !';
                $message['mess'] .= '<br/>Il a reçu ' . Personnages::QTE_DEGATS . ' point de dégat.';
            }
        } else {
            if( $this->persoManager->deletePlayer( $persoToHit->getId() ) ) {
                $message = [
                    'type' => 'success',
                    'mess' => 'Vous avez tué le personnage : ' . $persoToHit->getNom()
                ];
            }
        }
        

        // update the player
        $this->persoToPlay->ajoutExperience();
        if( $this->persoManager->updatePlayer( $this->persoToPlay ) ) {
            $this->listPersoToHit = $this->persoManager->getListPlayerToHit( $this->persoToPlay->getId() );
            $data = [
                'persoToPlay'       => $this->persoToPlay,
                'listAllPlayers'    => $this->listAllPlayers,
                'listPersoToHit'    => $this->listPersoToHit,
                'message'           => $message
            ];
        }
        if( $this->isFetched ) {
            $data = [
                'error'         => false,
                'persoToHitId'  => $persoToHit->getId(),
                'persoDegats'   => $persoToHit->getDegats(),
                'message'   => 'Bravo ! <b>' . $persoToHit->getNom() . '</b> frappé avec succès.'
            ];
            echo json_encode($data);
        } else
            $this->render('jouer', $data);
    }
}