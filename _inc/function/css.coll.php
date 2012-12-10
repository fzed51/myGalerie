<?php

/**
 * @author Fabien SANCHEZ
 * @copyright 2012
 */

class CSS {

	private static $couleurs = array();

	public static function addCouleur($nom, $val) {
		MyDebug::traceFonction();
		// control de la validité de la couleur
		// #xxx ou #xxxxxx ou [x,x,x] ou [x,x,x,x]
		$paternHexa = "[0-9a-fA-F]";
		$paternDec255 = "[1-2]?[0-9]{1,2}";
		$paternAlpha = "1|0.[0-9]+";
		$input = array();
		$type = new FlagsEnum('HEXA3', 'HEXA6', 'RGB', 'RGBA');

		$nom = filter_var($nom, FILTER_SANITIZE_STRING);
		$val = filter_var($val, FILTER_SANITIZE_STRING);

		if (preg_march("@#($paternHexa$paternHexa)($paternHexa$paternHexa)($paternHexa$paternHexa)@")) {
		} elseif (preg_march("@#($paternHexa)($paternHexa)($paternHexa)@")) {
		} elseif (preg_march("@($paternDec255)[,;:. ]+($paternDec255)[,;:. ]+($paternDec255)@")) {
		} elseif (preg_march("@#($paternDec255)[,;:. ]+($paternDec255)[,;:. ]+($paternDec255)[,;:. ]+($paternAlpha)@")) {
		} else {
			throw new InvalidArgumentException("Le patern de la couleur n'a pas été reconnu!");
		}
	}

}

?>