<?php

/**
 * @author Fabien SANCHEZ
 * @copyright 2012
 */

class FlagsEnum extends Enum {

	public function add( /*string*/ $name = null, /*int*/ $enum = null) {
		MyDebug::traceFonction();

		if (isset($enum))
			trigger_error("La valeur du flag '{$name}' n'existe pas prise en compte!",
				E_USER_NOTICE);

		$enum = pow(2, count($this->self));
		$this->self[$name] = $enum;
	}

	public function setMulti( /*****/) {
		MyDebug::traceFonction();

		$args = func_get_args();
		$retour = 0;
		for ($i = 0, $n = count($args); $i < $n; $i++)
			if (isset($this->self[$args[$i]]))
				$retour = $retour | $this->self[$args[$i]];
			else
				trigger_error("Le flag '{$args[$i]}' n'existe pas!", E_USER_NOTICE);

		return $retour;

	}

	public function getMulti($flags) {
		MyDebug::traceFonction();

		$lstFlagName = array();

		foreach ($this->self as $cle => $val) {
			if (($flags % 2) == 1)
				array_push($lstFlagName, $cle);
			$flags = floor($flags / 2);
		}

		return $lstFlagName;

	}

	public function checkIn( /*int*/ $multiflag, /*string*/ $flag) {
		MyDebug::traceFonction();

		if (in_array($flag , $this->self)) {
			if (0 != ($multiflag & $flag)) {
				return true;
			}

		} else {
			trigger_error("Le flag '$flag' n'existe pas!", E_USER_NOTICE);
		}

		return false;

	}

}

?>