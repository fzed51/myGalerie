<?php

/**
 * myFichier
 * 
 * @package myFichier
 * @static
 * @author Fabien SANCHEZ
 * @copyright 2012
 * @version 1.3
 * @access public
 */
class myFichier {
	/**
	 * myFichier::listeFichier()
	 * 
     * @static
	 * @param mixed $chemin
	 * @param mixed $extention
	 * @param bool $subDir
	 * @return
	 */
	public static function listeFichier($chemin, $extention = array(), $subDir = false) {
		myDebug::traceFonction();

		// D�claration des variables locale
		$dir = null;
		$file = "";
		$exclu = array('.', '..');
		$liste = array();
		$fichierComplet = '';

		if (is_dir($chemin)) { // V�rification que le dossier existe
			if (($dir = opendir($chemin)) !== false) { // ouverture du dossier
				while (($file = readdir($dir)) !== false) { // lecture des �l�ments du dossier
					$fichierComplet = completeDossier($chemin) . $file;
					if (is_file($fichierComplet) && in_array(getExtensionFichier($fichierComplet), $extention)) { // Si l'�l�ment est un fichier non exclu
						array_push($liste, $fichierComplet);
					} else {
						if ($subDir && is_dir($fichierComplet) && !in_array($file, $exclu)) { // Si l'�l�ment est un dossier et que la recherche r�cursive est activ�e
							array_merge($liste, listeFichier($fichierComplet, $extention));
						}
					}
				}
				closedir($dir); // fermeture du dossier
			}
			return $liste;
		} else {
			throw new InvalidArgumentException("Le dossier '$chemin' n'existe pas");
		}
	}

	/**
	 * myFichier::getExtensionFichier()
	 * 
	 * @static
	 * @param chaine $file
	 * @return chaine
	 */
	public static function getExtensionFichier($file) {
		myDebug::traceFonction();

		if (is_file($file)) {
			return strtolower(pathinfo($file, PATHINFO_EXTENSION));
		} else {
			throw new InvalidArgumentException("Le fichier '$file' n'existe pas");
		}
	}

	/**
	 * myFichier::completeDossier()
	 * 
	 * @static
	 * @param chaine $chemin
	 * @return chaine
	 */
	public static function completeDossier($chemin) {
		myDebug::traceFonction();
		
		$dernierCar = "";

		if (is_dir($chemin)) {
			$dernierCar = substr($chemin, -1);
			if ($dernierCar !== '/') {
				return $chemin . '/';
			} else {
				return $chemin;
			}
		} else {
			throw new InvalidArgumentException("Le dossier '$chemin' n'existe pas");
		}
	}
    
    public static function charge($chemin){
		myDebug::traceFonction();

		if (is_file($chemin)) {			
            return file_get_contents($chemin);            
		} else {
			throw new InvalidArgumentException("Le fichier '$file' n'existe pas");
		}
        
    }
}

?>