<?php
require_once('manager.php');

// Déclaration des variables locales
$fichier   = null;
$chemin    = "";
$image     = null;
$lstOption = null;
$actionOk  = false;
define('OPT_MINIATURE', "#^([0-2]?[0-9]{1,3})$#");
define('OPT_CADRE', "#^([0-2]?[0-9]{1,3})[xX]([0-2]?[0-9]{1,3})$#");

// Récupération des données exterieure
$id     = filter_input(INPUT_GET, 'id'    , FILTER_SANITIZE_NUMBER_INT);
$type   = filter_input(INPUT_GET, 'type'  , FILTER_SANITIZE_STRING);
$option = filter_input(INPUT_GET, 'option', FILTER_SANITIZE_STRING);

// Récupération du chemin du fichier image
$fichier = $db->query('SELECT `Chemin` FROM `classement` WHERE `ID` = '.$id);
$chemin  = $fichier->fetchColumn();

// traitement de l'image
if($chemin !== false){
	// mise en tampon compressé de la sortie
	if(!ob_start("ob_gzhandler")) ob_start();

	// vérification des
	if(!(is_null($option) || is_null($type))){
	
		$info = pathinfo($chemin);
		
		// Retrouve les nom de fichier miniature ou recadrer et vérifie les options
		switch(strtolower($type)){
			case 'miniature':
				if (0 < preg_match(OPT_MINIATURE, $option, $lstOption)){
					$minFichier = $info['dirname'].'/miniature/m'.$option.'-'.$info['basename'];
				}else{
					trigger_error("Options non valides!", E_USER_ERROR);
				}
			break;
			case 'cadre':
				if (0 < preg_match(OPT_CADRE, $option, $lstOption)) { 
					$minFichier = $info['dirname'].'/miniature/c'.$option.'-'.$info['basename'];
				}else{
					trigger_error("Options non valides!", E_USER_ERROR);
				}
			break;
			default:
				trigger_error("Type non valide!", E_USER_ERROR);
		}
		
		if(file_exists($minFichier)){
			$image = new MyImage($minFichier);
			$image->affiche();
		}else{
		
			$image = new MyImage($chemin);
			
			switch(strtolower($type)){
				case 'miniature':
					$actionOk = $image->reSize(((int)$lstOption[1]), 'px');
				break;
				case 'cadre':
					$actionOk = $image->reDim(((int)$lstOption[1]), ((int)$lstOption[2]));
				break;
			}
			
			if($actionOk == true)$image->sauve($minFichier);
			$image->affiche();
		}
	}else{
			$image = new MyImage($chemin);
			$image->affiche();
	}
}else{
	trigger_error('Auccune image ne correspond!', E_USER_ERROR);
}

?>