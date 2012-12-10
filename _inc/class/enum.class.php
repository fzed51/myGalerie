<?php
class Enum {

	protected $self = array();

	public function __construct( /*...*/) {
		MyDebug::traceFonction();

		$args = func_get_args();
		for ($i = 0, $n = count($args); $i < $n; $i++)
			$this->add($args[$i]);

	}

	public function __get( /*string*/ $name = null) {
		MyDebug::traceFonction();

		return $this->self[$name];
	}

	public function add( /*string*/ $name = null, /*int*/ $enum = null) {
		MyDebug::traceFonction();

		if (isset($enum))
			$this->self[$name] = $enum;
		else
			$this->self[$name] = end($this->self) + 1;
	}
    
    public function __toString(){
        $out = '';
        
        foreach($this->self as $name=>$enum){
            if(strlen($out)>0)$out .= ', ';
            $out .= "{$name} ({$enum})"; 
        }
        
        return $out;
    }
}

?>