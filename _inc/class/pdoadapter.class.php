<?php
class PDOAdapter extends PDO {
    public function __construct(iConnexionPDO $connex){
        try {
			parent::__construct($connex->getDns(), $connex->getUser(), $connex->getMdp());   
		}
		catch (PDOException $pdoExcep) {
			MyDebug::trace("Problème lors de la connection à la base de donnée");
            MyDebug::trace($pdoExcep->getMessage());
		}
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