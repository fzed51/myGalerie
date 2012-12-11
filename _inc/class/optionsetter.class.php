<?php

abstract class OptionSetter {

	protected $listeOption = array();
	protected $options = array();

	public function setOption( /*array/string*/ $option, /*mix*/ $val = null) {
		if (is_array($option) && is_null($val)) {
			foreach ($this->option as $cle => $val) {
				$this->setOption($cle, $val);
			}
		} elseif (is_string($option) && !is_null($val)) {
			if (in_array($option, $listeOption)) {
				$this->options[$option] = $val;
			} else {
				throw new InvalidArgumentException("'{$option}' n'est pas une option valide d'une connexion.");
			}
		} else {
			throw new InvalidArgumentException('Argument(s) non valide(s)!!!');
		}
	}
}

?>