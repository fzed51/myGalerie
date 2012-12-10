<?php
class DefinedEnum extends Enum {

	public function __construct( /*array*/ $itms) {
		MyDebug::traceFonction();

		foreach ($itms as $name => $enum)
			$this->add($name, $enum);
	}
}

?>