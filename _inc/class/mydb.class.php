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
class MyDB extends PDO {
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
                $myConnect->
                break;
        	default :
                throw New DomainException("Le driver de la base de donnée n'est pas reconnue");
        }

        
        


		$dns = . ':host=' . $settings['database']['host'] . ((!empty($settings['database']['port'])) ?
			(';port=' . $settings['database']['port']) : '') . ';dbname=' . $settings['database']['schema'];
		mydebug::traceVar($dns, 'dns');

		try {
			parent::__construct($dns, $settings['DBuser']['username'], $settings['DBuser']['password']);
		}
		catch (PDEException $pdoExcep) {
			MyDebug::trace("Problème lors de la connection à la base de donnée");
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

	public function exporte() {
		MyDebug::traceFonction();
		//TODO: Faire la fonction d'export de base

	}

	public function importe($fichierSQL) {
		MyDebug::traceFonction();
		//TODO: Faire la fonction d'import de base

	}

	public function exec($rqSQL) {
		MyDebug::traceFonction();

		$retour = 0;
		try {
			// nétoyage de la requete sql
			$rqSQL = self::cleanSql($rqSQL);
			// suppression du dernier ; pour eviter une requète vide
			if (substr($rqSQL, -1) == ";")
				$rqSQL = substr($rqSQL, 0, -1);
			// séparation en plusieure requètes
			$lstRqSQL = explode(';', $rqSQL);
			// execution de chaque requète
			foreach ($lstRqSQL as $sql) {
				$sql = trim($sql);
				if (substr($rqSQL, -1) != ";")
					$rqSQL .= ';';
				$retour += parent::exec($sql);
			}
		}
		catch (PDOException $pdoExcep) {
			$codeErreur = parent::errorCode();
			MyDebug::trace("Impossible d'exécuter la requète [{$codeErreur}].");
		}

		return $retour;
	}

	public static function cleanSql($rqSQL) {
		$rqSQL = preg_replace("/--.*\n/", "\n", $rqSQL);
		$rqSQL = trim($rqSQL);
		return $rqSQL;
	}

}

?>