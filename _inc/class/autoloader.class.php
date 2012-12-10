<?php
class Autoloader{

	private $dossierClass;
	private $dossierFonction;
	private $lstAutoloadFonction = Array();

	public function __construt(){
			$dossierClass     = './_inc/class/';
			$dossierFonction  = './_inc/function/';
			$extensionClass   = '.class.php';
			$extensionFonctin = '.coll.php';
	}

	public protected autoload($class) {
		if (!class_exists($class, false)) {
			MyDebug::trace("Chargement de la class($class).");
			$nomFichier = strtolower($class);
			// déclaration des chemins possible
			$cheminClass    = $this->dossierClass    . $nomFichier . $this->extensionClass;
			$cheminFonction = $this->dossierFonction . $nomFichier . $this->extensionFonctin;
			if (file_exists($cheminClass)) {
				require ($cheminClass);
			} elseif (file_exists($cheminFonction)) {
				require ($cheminFonction);
			} else {
				MyDebug::trace("Impossible de charger la class '{$class}' à partir de 'autoload()'!");
				return false;
			}
		}
	}

	public function register(){
			spl_autoload_register(array(&$this, 'autoload'));
	}
	
	public function unRegister(){
			spl_autoload_unregister(array(&$this, 'autoload'));
	}
	
}
?>