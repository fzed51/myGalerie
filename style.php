<?php

/**
 * @author Fabien SANCHEZ
 * @copyright 2012
 */

require_once ('./manager.php');

//$fichierCss = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_URL);

//if(!file_exists('./_css/'.$fichierCss)){
//    exit(0);
//}

//include('./_css/'.$fichierCss);

CSS::addCouleur('rouge', '#F00');

?>
*{
    margin: 0;
    padding: 0;
    }