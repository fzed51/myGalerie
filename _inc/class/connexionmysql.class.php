<?php

class ConnexionMySql extends ConnexionPDO implements iConnexionPDO {
	private $host;
    private $port;
	private $dbname;
	private $user;
	private $mdp;

	public function __construct($host = '', $port = '', $dbname = '', $user = '', $mdp = '') {
		$this->host = $host;
		$this->port = $port;
		$this->dbname = $dbname;
		$this->user = $user;
		$this->mdp = $mdp;
	}

	public function setHost($host) {
	   $this->host = $host;
       return $this;
	}
    
    public function setPort($port){
        $this->port = $port;
        return $this;
    }
    
    public function setDbName($dbname){
        $this->dbname = $dbname;
        return $this;
    }
    
    public function setUser($user){
        $this->user = $user;
        return $this;
    }
    
    public function setMdp($mdp){
        $this->mdp = $mdp;
        return $this;
    }

	public function getDns() {
		$dns = '';
		$dns += '"mysql:';
		$dns += "host={$this->host};";
        if (!empty($this->port))
			$dns += "port={$this->port};";
		$dns += "dbname={$this->dbname}";
		return $dns}

	public function getUser() {
		return $this->user;
	}

	public function getMdp() {
		return $this->mdp;
	}

	public function getOption() {
		return $this->options;
	}
}

?>