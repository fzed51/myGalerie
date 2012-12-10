<?php

/**
 * MyDB
 * 
 * @package MyDB
 * @author Fabien SANCHEZ
 * @copyright 2012
 * @version 1.1
 * @filesource mydb.class.php
 * @access public
 */
class MyDB extends PDOAdapter {
	/**
	 * MyDB::__construct()
	 * 
	 * @param chaine $file chemin du fichier de config
	 * @return void
	 */
	public function __construct($file = INI_FILE) {
		MyDebug::traceFonction();
        
		if (!$settings = parse_ini_file($file, true))
			throw new exception("Impossible d'ouvrir '$file'");
        
        switch ($settings['database']['driver']){ 
        	case 'mysql':
                $myConnect = New ConnexionMySql();
                $myConnect->setHost($settings['database']['host'])
                    ->setPort(((!empty($settings['database']['port'])) ? $settings['database']['port'] : ''))
                    ->setDbName($settings['database']['schema']);
                mydebug::traceVar($myConnect->getDns(), 'dns');
                $myConnect->setUser($settings['DBuser']['username'])
                    ->setMdp($settings['DBuser']['password']);
                parent::__construct($myConnect);   
                break;
        	default :
                throw New DomainException("Le driver de la base de donnée n'est pas reconnue");
        }
	}

	private function conforme() {
		MyDebug::traceFonction();

		// Déclaration des variables locales
		$lstTable = array(
			'personne',
			'utilisateur',
			'visiteur',
			'image',
			'note',
			'colection',
			'T_photo_colec',
			'acces',
			'groupe',
			'appartien');

	}

	public function setup() {
		MyDebug::traceFonction();
		//TODO: Faire la fonction d'installation de base
		$rqSQL = myFichier::charge('./_data/structure.sql');

		// lacement de la requete
		$this->exec($rqSQL);
	}

}

?>