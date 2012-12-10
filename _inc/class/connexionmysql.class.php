<?php
class ConnexionMySql extends ConnexionPDO implements iConnexionPDO {
	private $host;
	private $dbname;
	private $user;
	private $mdp;
	
	public function __construct($host = '', $dbname = '', $user = '', $mdp = ''){
		$this->host    = $host;
		$this->dbname  = $dbname;
		$this->user    = $user;
		$this->mdp     = $mdp;
	}
	
	public function getDns(){
		return "mysql:host={$this->host};dbname={$this->dbname}";
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function getMdp(){
		return $this->mdp;
	}
	
	public function getOption(){
		return $this->options;
	}
}
?>