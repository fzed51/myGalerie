<?php
abstract class ConnexionPDO {
	protected $listeOption = [
		'PDO::ATTR_PERSISTENT'
		];
	
	protected $options = array();
	
	public setOption(/*array/string*/ $option, /*mix*/ $val = null){
		if(is_array($option) && is_null($val)){
			foreach($option as $cle=>$val){
				$this->setOption($cle, $val);
			}
		}elseif(is_string($option) && !is_null($val)){
			if(in_array($option, $listeOption)){
				$this->options[$option] = $val;
			}else{
				throw New InvalidArgumentException("'{$option}' n'est pas une option valide d'une connexion.");
			}
		}else{
			throw New InvalidArgumentException('Argument(s) non valide(s)!!!');
		}
	}
}
?>