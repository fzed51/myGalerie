<?php

/**
 * @author Fabien SANCHEZ
 * @copyright 2012
 */

class Personne {

	private $ID;
	private $nom;
	private $prenom;
	private $email;
	private $affichage;

	private $droit;
    private $ip;

	public function __construct() {
		MyDebug::traceFonction();
        
        $option = Array("regexp"=>"@[a-zA-Z0-9*:]+@");
        
        $key = filter_input(INPUT_GET, 'key', FILTER_VALIDATE_REGEXP, $option);
        $validForm = filter_input(INPUT_POST, 'validForm', FILTER_VALIDATE_REGEXP, $option);
        $log       = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_SPECIAL_CHARS);
        $mdp       = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_SPECIAL_CHARS);
        
		//TODO: création de la personne:
		// - vérification d'une session ouverte et control de celle-ci

		$this->ID        = 1;
		$this->nom       = "Sanchez";
		$this->prenom    = "Fabien";
		$this->email     = "fzed51@yahoo.fr";
		$this->affichage = 1;
        $this->ip        = '127.0.0.1';
        
		global $droits;
		$this->droit = $droits->ADMIN;
        
        // L'utilisateur est déjà connu
        if(isset($_SESSION['personne'])){
            try{
                $tmpPersonne = unserialize($_SESSION['personne']);
                $ip = Reseau::IpUtilisateur();
                if($tmpPersonne->controlIp($ip)){
                    $this->copie($tmpPersonne);
                    unset($tmpPersonne);
                }else{
                    session_unset();
                }            
            }catch(Exception $excep){
                session_unset();
            }
        // L'utilisateur se connecte avec une clé
        }elseif(isset($key)){
            $id = Visiteur::getIdPersonneByCle($key);
            if(isset($id)){
                $this->getById($id);
                $this->droit = $droits->VISIT;
                $this->ip = Reseau::IpUtilisateur();
            }
        // L'utilisateur se connecte avec un login mot de passe
        }elseif(isset($log, $mdp, $validForm)){
            if($_SESSION['form'] == $validForm){
                $utilisateur = Utilisateur::getUtilisateurByLogin($log, $mdp);
                if(isset($utilisateur)){
                    $this->getById($utilisateur->getPersonne());
                    $this->droit = $utilisateur->getDroit();
                    $this->ip = Reseau::IpUtilisateur();
                }
            }
        }
	}
    
    public function getDroit(){
        return $this->droit;
    }
    
    public function controlIp($ip){
        mydebug::traceFonction();
        
        // TODO : control de $ip valide
        return $this->ip == $ip;
    }
    
    protected function copie(Personne &$src){
        $this->ID        = $scr->ID;
        $this->nom       = $scr->nom;
        $this->prenom    = $scr->prenom;
        $this->email     = $scr->email;
        $this->affichage = $scr->affichage;
        $this->ip        = $scr->ip;
        $this->droit     = $scr->droit;
    }
    
}

?>