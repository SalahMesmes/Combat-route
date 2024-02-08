<?php

namespace combats\controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

abstract class Controller
{
    protected $twig;
    protected $pathView = 'view';
    protected $controller;
    protected $pathRoot;

    protected $vars = [];
    protected $action = false;
    protected $isFetched = false;

    public function __construct( array $params=[] )
    {
        $this->setParams( $params );
        $this->pathRoot = str_replace( $_SERVER['QUERY_STRING'], '', $_SERVER['REDIRECT_URL'] );
        $this->pathView = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . $this->pathView;

        // Twig initialization
        $loader = new FilesystemLoader( $this->pathView );
        $this->twig = new Environment($loader, [
            'debug' => true
        ]);
        $this->twig->addGlobal('pathRoot', $this->pathRoot );
        $this->twig->addExtension(new DebugExtension());

        // Call action
        if( $this->action ) {
            $action = $this->action . 'Action';
            $this->$action();
        } else {
            $this->defaultAction();
        }
    }


    abstract public function defaultAction();


    /**
     *  Get request parameters in $this->vars property
     *
     * @param array $params
     */
    protected function setParams( array $params=[] )
    {
        if( !empty( $params['action'] ) ) {
            $this->action = $params['action'];
        }
        if( !empty( $params['vars'] ) && is_array( $params['vars'] ) ) {
            $nb = count( $params['vars'] );
            if ($nb > 1) {
                $i = 0;
                while ($i < $nb) {
                    $this->vars[$params['vars'][$i]] = isset( $params['vars'][$i+1] ) ? $params['vars'][$i+1] : '';
                    $i += 2;
                }
            }
        }
        if( !empty( $params['request'] ) ) {
            foreach ( $params['request'] as $k=>$v ) {
                $this->vars[$k] = $v;
            }
        }
        $this->isFetched = $params['isFetched'];
    }



    protected function render( $view, $data=[] )
    {
        extract( $data );
        $filenameView = ucfirst( $view ) . 'View.twig';
        $filePath = $this->pathView . '/' . $filenameView;
        if( file_exists( $filePath ) ) { 
            echo $this->twig->render( $filenameView, $data );
        } else {
            die( "Twig view file not exist in view folder." );
        }
    }


}