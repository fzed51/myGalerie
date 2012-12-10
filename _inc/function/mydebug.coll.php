<?php

include_once ('./_inc/class/browscap.class.php');

/**
 * @author Fabien SANCHEZ
 * @copyright 2012
 * @filesource mydebug.coll.php
 * @package MyDebug
 * @static
 * @version 1.2
 */
class MyDebug {

	// Fichier de sortie
	private static $fileTrace = "./trace.out.txt";
	private static $ID = '';
	private static $modeDebug = null;
	// Initialisation du bebuger
	private static $init = false;

	/**
	 * MyDebug::initialisation()
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function initialisation() {
		// Purge de la trace
		if (file_exists(self::$fileTrace)) {
			$dateModif = new DateTime(); // dernière modif
			$dateModif->setTimestamp(filemtime(self::$fileTrace));
			$maintenant = new DateTime(); // maintenant
			$inter = $maintenant->diff($dateModif); // délais depuis la modif
			$interMinute = ($inter->days * 24 + $inter->h) * 60 + $inter->i; // convertion en minute
			settype($interMinute, 'integer');
			if ($interMinute > 2)
				unlink(self::$fileTrace); // Suppression si le fichier a été modifié  il y a + de 2 minutes
		}
	}

	/**
	 * MyDebug::traceFonction()
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function traceFonction() {

		// Déclaration des variables locales
		$backtrace = debug_backtrace();
		$fonctionTest = $backtrace[1];
		$fonctionParent = "";
		$fonction = "";
		$fonctionMere = "";
		$fichier = "";
		$class = "";
		$classParent = "";
		$arguments = "";
		$out = "";
		$fileOut = "";

		// Détermination du fichier / ligne
		if (isset($fonctionTest['file']) && isset($fonctionTest['line'])) {
			$fichier = pathinfo($fonctionTest['file'], PATHINFO_BASENAME) . '[' . $fonctionTest['line'] . ']';
		} else {
			$fichier = '';
		}

		// Détermination des arguments
		$arguments = self::parametre($fonctionTest['args']);

		// Détermination de la fonction
		if (isset($fonctionTest['class']) && $fonctionTest['class'] != '') {
			$class = $fonctionTest['class'] . ':';
		} else {
			$class = '';
		}
		$fonction = $class . $fonctionTest['function'] . '(' . $arguments . ')';

		// Détermination de l'utilisation de la mémoire
		$memoire = '[' . self::octeLisible(memory_get_usage()) . '/' . self::octeLisible(memory_get_peak_usage
			()) . ']';

		// Détermination de la fonction parent (fichier / function)
		if (isset($backtrace[2])) {
			$fonctionParent = $backtrace[2];
			if (isset($fonctionParent['class']) && $fonctionParent['class'] != '') {
				$classParent = $fonctionParent['class'] . ':';
			} else {
				$classParent = '';
			}
			$fonctionMere = $classParent . $fonctionParent['function'] . '()';
			$out = "{$fichier}> {$fonctionMere}> {$fonction}  {$memoire}\n";
		} else {
			$out = "{$fichier}> {$fonction}  {$memoire}\n";
		}

		self::trace($out);
	}

	/**
	 * MyDebug::octeLisible()
	 * 
	 * @static
	 * @access public
	 * @param entier $octe
	 * @return chaine
	 */
	public static function octeLisible($octe) {

		// Initialisation des variables locales
		$lstUnit = array(
			'o',
			'ko',
			'Mo',
			'Go',
			'To',
			'Po',
			'Eo',
			'Zo',
			'Yo');
		$unit = 0;
		$retour = $octe;

		// Réduction à l'unité la plus importante
		while ($retour > 1024 && $unit < (count($lstUnit) - 1)) {
			$retour = $retour / 1024;
			$unit++;
		}

		// Affichage arrondi au millième
		return round($retour, 3) . $lstUnit[$unit];
	}

	/**
	 * MyDebug::parametre()
	 * 
	 * @static
	 * @access public
	 * @param tableau/mix $lstArgs
	 * @return chaine
	 */
	public static function parametre($lstArgs) {

		$out = "";
		$longChaine = 25; // longueur max des string

		if (!is_array($lstArgs))
			$lstArgs = array($lstArgs);

		foreach ($lstArgs as $arg) {

			if (strlen($out) > 0)
				$out .= ', ';

			switch (getType($arg)) {
				case "boolean":
					if ($arg) {
						$out .= 'vrai';
					} else {
						$out .= 'faux';
					}
					break;
				case "integer":
					$out .= "$arg";
					break;
				case "double":
					if ($arg > -1 && $arg < 1) {
						$out .= round($arg, 3);
					} else {
						$out .= $arg;
					}
					break;
				case "string":
					if (strlen($arg) > $longChaine) {
						$out .= "'" . substr($arg, 0, ($longChaine - 6)) . "...'(" . strlen($arg) . ')';
					} else {
						$out .= "'$arg'";
					}
					break;
				case "array":
					$out .= 'Array(' . count($arg) . ')';
					break;
				case "object":
					$out .= 'Objet(' . get_class($arg) . '[' . count($arg) . '])';
					break;
				case "resource":
					$out .= 'Ressources';
					break;
				case "NULL":
					$out .= 'NULL';
					break;
				default:
					$out .= '?';
			}

		}

		return $out;
	}

	/**
	 * MyDebug::generateurID()
	 * 
	 * @static
	 * @access privé
	 * @param entier	$long longueur de l'ID
	 * @return chaine	ID
	 */
	private static function generateurID($long = 5) {
		$id = '';
		$code = 0;

		for ($i = 0; $i < $long; $i++) {
			$code = 65 + rand(0, 51);
			if ($code > 90)
				$code += 6;
			$id .= chr($code); // $code = [a-zA-Z]
		}
		return $id;
	}

	/**
	 * MyDebug::entete()
	 * 
	 * @static
	 * @access privé
	 * @return chaine
	 */
	private static function entete() {

		$enteteOut = "";
		$maintenant = null;
		$browscap = new Browscap('./_ini');
		$userInfo = null;
		// Initialisation de la trace
		$maintenant = new DateTime();
		$enteteOut .= $maintenant->format('\l\e j/m/Y \à H:i:s') . "\n";
		$enteteOut .= "Utilisateur \n";
		if (isset($_SERVER['REMOTE_ADDR'])){
			$enteteOut .= "- IP         : {$_SERVER['REMOTE_ADDR']}\n";
        }elseif(isset($_SERVER['COMPUTERNAME'])){
            $enteteOut .= "- POSTE      : {$_SERVER['COMPUTERNAME']}\n";
        }
		try {
			$userInfo = $browscap->getBrowser(null, true);
			if (isset($userInfo['Platform']))
				$enteteOut .= "- OS         : {$userInfo['Platform']}\n";
			if (isset($userInfo['Parent']))
				$enteteOut .= "- navigateur : {$userInfo['Parent']}\n";
		}
		catch (exception $excep) {
			$enteteOut .= "- info navigateur innaccessible!";
		}
		if (isset($_SERVER['SCRIPT_NAME']))
			$enteteOut .= $_SERVER['SCRIPT_NAME'] . "\n";
		if (isset($_POST) && count($_POST) > 0) {
			$enteteOut .= "- [POST]";
			$enteteOut .= " -> " . self::listeVarGlob($_POST);
			$enteteOut .= "\n";
		}
		if (isset($_GET) && count($_GET) > 0) {
			$enteteOut .= "- [GET]";
			$enteteOut .= " -> " . self::listeVarGlob($_GET);
			$enteteOut .= "\n";
		}
		if (isset($_FILES) && count($_COOKIE) > 0) {
			$enteteOut .= "- [COOKIE]";
			$enteteOut .= " -> " . self::listeVarGlob($_COOKIE);
			$enteteOut .= "\n";
		}
		if (isset($_FILES) && count($_FILES) > 0) {
			$enteteOut .= "- [FILES]";
			$enteteOut .= " -> " . $_FILES['name'] . '(' . self::octeLisible($_FILES['size']) . ')';
			$enteteOut .= "\n";
		}

		$enteteOut = self::encadre($enteteOut);
		$enteteOut = "\n" . self::ajouteIdDebutLigne(self::$ID, $enteteOut) . "\n\n";

		return $enteteOut;
	}

	/**
	 * MyDebug::trace()
	 * 
	 * @static
	 * @access public
	 * @param chaine $msg
	 * @return void
	 */
	public static function trace($msg) {
		$msgOut = '';
		if (self::getModeDebug() == true) {
			$fileOut = fopen(self::$fileTrace, 'a');
			if ($fileOut !== false) {
				if (self::$init !== true) {
					self::$ID = self::generateurID(2);
					self::$init = true;
					$msgOut .= self::entete();
				}
				$msgOut .= self::ajouteIdDebutLigne(self::$ID, $msg);
				if (substr($msgOut, -1) != "\n")
					$msgOut .= "\n";

				fwrite($fileOut, $msgOut);
				fclose($fileOut);
			}
		}
	}

	/**
	 * MyDebug::encadre()
	 * 
	 * @static
	 * @access privé
	 * @param chaine $msg
	 * @param chaine(8) $cadre commence par le coin haut gauche, tourne dans le sens des aiguille d'une montre et fini par le coté gauche
	 * @return chaine
	 */
	private static function encadre($msg, $cadre = '+-+|+-+|') {
		$msgOut = '';
		$msg = trim($msg);
		$lignes = explode("\n", $msg);
		$nbLigne = count($lignes);
		$longMax = 0;
		for ($i = 0; $i < $nbLigne; $i++)
			if (strlen($lignes[$i]) > $longMax)
				$longMax = strlen($lignes[$i]);
		// ligne du haut
		$msgOut = $cadre[0];
		for ($i = 0; $i < ($longMax + 4); $i++)
			$msgOut .= $cadre[1];
		$msgOut .= $cadre[2] . "\n";
		// autre ligne
		for ($i = 0; $i < $nbLigne; $i++) {
			$msgOut .= $cadre[7] . '  ';
			$msgOut .= $lignes[$i];
			for ($j = strlen($lignes[$i]); $j < $longMax; $j++)
				$msgOut .= ' ';
			$msgOut .= '  ' . $cadre[3] . "\n";
		}
		// ligne du bas
		$msgOut .= $cadre[4];
		for ($i = 0; $i < ($longMax + 4); $i++)
			$msgOut .= $cadre[5];
		$msgOut .= $cadre[6];
		return $msgOut;
	}

	/**
	 * MyDebug::ajouteIdDebutLigne()
	 * 
	 * @static
	 * @access privé
	 * @param chaine $id
	 * @param chaine $msg
	 * @return chaine
	 */
	private static function ajouteIdDebutLigne($Id, $msg) {
		$msgOut = '';
		$separateur = ' - ';
		$separateur2 = ' \ ';
		$msg = trim($msg);
		$lignes = explode("\n", $msg);
		$nbLigne = count($lignes);
		for ($i = 0; $i < $nbLigne; $i++) {
			if ($i == 1)
				$separateur = $separateur2;
			$msgOut .= $Id . $separateur . $lignes[$i] . "\n";
		}
		return trim($msgOut);
	}

	/**
	 * MyDebug::listeVarGlob()
	 * 
	 * @static
	 * @access privé
	 * @param tableau $tableau
	 * @param entier $format -1 auto, 0 long, 1 moyen, 2 court
	 * @return
	 */
	private static function listeVarGlob($tableau, $format = -1) {

		$outLong = '';
		$outMoy = '';
		$outCourt = '';
		$lstOut = array(
			'outLong',
			'outMoy',
			'outCourt');
		$type = '';

		foreach ($tableau as $cle => $val) {
			// virgule
			foreach ($lstOut as $out) {
				if (strlen($$out) > 0)
					$$out .= ',';
			}

			// clé
			foreach ($lstOut as $out) {
				$$out .= "[$cle]";
			}

			if (is_numeric($val)) {
				// long
				$outLong .= "={$val}";
				// moyen
				$outMoy .= sprintf("~%.3e", $val);
			} else {
				// long
				$outLong .= "='{$val}'";
				// moyen
				$outMoy .= sprintf("~'%.12s...'(%d)", $val, strlen($val));
			}

			if ($format == -1) {
				if (strlen($outLong) < 70)
					return $outLong;
				if (strlen($outMoy) < 70)
					return $outMoy;
				return $outCourt;
			} else {
				if (isset($lstOut[$format]))
					return $$lstOut[$format];
				else
					return "";
			}

		}
	}

	/**
	 * MyDebug::getModeDebug()
	 * 
	 * @static
	 * @access privé
	 * @return boolean
	 */
	private static function getModeDebug() {
		// Détection de l'initialisation de la variable $modeDebug
		if (is_null(self::$modeDebug)) {
			// initialisation de $modeDebug avec INI_FILE
			if (!$settings = parse_ini_file(INI_FILE, true))
				throw new exception("Impossible d'ouvrir '$file'");

			if ($settings['ModeDebug']['actif'] == 1) {
				self::$modeDebug = true;
			} else {
				self::$modeDebug = false;
			}

		}

		return self::$modeDebug;
	}
    
    public static function traceVar(/*mixed*/ $var, /*string*/ $name = null){
        
        $trace = '';
        
        if(isset($name)){
            $trace .= "$name = ";
        }
        
        $trace .= print_r($var, true);
        
        self::trace($trace);
        
    }
    
}

?>