<?php

/**
 * @filesource manager.php
 * 
 * @author Fabien Sanchez
 * @version 0.9
 */

// définition des constantes
define('INI_FILE', './_ini/config.ini');
define('DATETIME_MYSQL', 'Y-m-d H:i:s');

// Initialisation de la timezone
date_default_timezone_set('Europe/Paris');

// Gestion des chargement automatique
require_once ('./_inc/class/autoloader.class.php');
$monAutoload = new Autoloader();
// Gestion du debugage
MyDebug::initialisation();

// Maj du loader
$monAutoload->setFonctionTrace('MyDebug::trace');

// gestion des erreures
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');
ini_set ('log_errors ',1);
ini_set ('html_errors ',0);
if (isset($_SERVER['SCRIPT_NAME'])){
	ini_set('error_log', './{$_SERVER['SCRIPT_NAME']}.err.log');
}else{
	ini_set('error_loge', './erreurs.log';
}

// Gestion des erreurs
function error_handler($errno, $errstr, $errfile = '', $errline = 0, array $errcontext = array()) {

	MyDebug::trace("Erreur : '$errstr'($errno) ->{$errfile}[$errline]");

	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

function exception_handler($excep) {
	MyDebug::trace("Exception : " . $excep->getMessage() . "(" . $excep->getCode() . ")[" . $excep->
		getFile() . "(" . $excep->getLine() . ")]");
}

set_error_handler("error_handler");
//set_exception_handler("exception_handler");

// Chargement des package

// gestion de la session et des droits
$sessionErrFile = "";
$sessionErrLine = 0;
if (headers_sent($sessionErrFile, $sessionErrLine)) {
	trigger_error("Le header a déjà été envoyé par $sessionErrFile ligne $sessionErrLine. \nImpossible de lancer la session!", E_USER_ERROR);
} else {
	session_start();
}

$droits = new FlagsEnum('ADMIN', 'USER', 'VISIT');

?>