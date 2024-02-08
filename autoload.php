<?php

function chargerClasse( $classe )
{
    $tmp = explode( '\\',  $classe );
    $controllerPath = $tmp[1];
    $classeName = $tmp[2];
    require $controllerPath . '/' . $classeName . '.php';
}

spl_autoload_register('chargerClasse');