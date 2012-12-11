<?php
class Autoloader{

	private $dossierClass;
	private $dossierInterface;
	private $dossierFonction;
	private $extensionClass;
	private $extensionInterface;
	private $extensionFonction;
    
	private $lstAutoloadFonction = Array();
    private $messageTace = Array();
    private $fonctionTrace;

	public function __construct(){
			$this->dossierClass       = './_inc/class/';
			$this->dossierInterface   = './_inc/interface/';
			$this->dossierFonction    = './_inc/function/';
			$this->extensionClass     = '.class.php';
			$this->extensionInterface = '.inter.php';
			$this->extensionFonction  = '.coll.php';
			$this->fonctionTrace      = null;
	}

	protected function autoload($class) {
		if (!class_exists($class, false)) {
			$this->trace("Chargement de la class($class).");
			$nomFichier = strtolower($class);
			// déclaration des chemins possible
			$cheminClass    = $this->dossierClass    . $nomFichier . $this->extensionClass;
			$cheminFonction = $this->dossierFonction . $nomFichier . $this->extensionFonction;
			$cheminInterface = $this->dossierInterface . $nomFichier . $this->extensionInterface;
			if (file_exists($cheminClass)) {
				require ($cheminClass);
			} elseif (file_exists($cheminInterface)) {
				require ($cheminInterface);
			} elseif (file_exists($cheminFonction)) {
				require ($cheminFonction);
			} else {
				$this->trace("Impossible de charger la class '$class' à partir de 'autoload()'!");
				return false;
			}
		}
	}
    
    public function addLoader(/*callback*/ $fonction){
        if(is_callable($fonction)){
            array_push($lstAutoloadFonction, $fonction);
        }else{
            throw new InvalidArgumentException("La fonction '$fonction' n'est pas une fonction valide");
        }
    }
    
    public function delLoader(/*callback*/ $fonction){
        $lstFonctionLoader = spl_autoload_functions();
        if(is_callable($fonction, false)){
            
            //TODO verification de l'activation de cette fonction si c'est le cas : desactivation'
            //TODO: suppression de de la fonction dans la liste
        }else{
            throw new InvalidArgumentException("La fonction '$fonction' n'est pas valide");
        }
    }
    
    private function trace(/*string*/ $message){
        if(!is_null($this->fonctionTrace)){
            call_user_func($this->fonctionTrace, $message);
        }else{
            array_push($this->messageTace, array(new DateTime(),$message));
        }
    }
    
    private function purgeTrace(){
        $this->trace("Vidage de la mémoire tampond de la trace de l'autoloader.");
        foreach($this->messageTace as $message){
            $this->trace($message[0]->format("H:i:s") . $message[1]);
        }
        $this->messageTace = array();
    }
    
    public function setFonctionTrace(/*callback*/ $fonction){
        if(is_callable($fonction)){
            $this->fonctionTrace = $fonction;
            if(count($this->messageTace)>0){
                $this->purgeTrace();
            }
        }else{
            $this->trace("La fonction '$fonction' n'est pas une fonction valide");
        }
    }

	public function register(){
			spl_autoload_register(array($this, 'autoload'));
	}
	
	public function unRegister(){
			spl_autoload_unregister(array($this, 'autoload'));
	}
	
    public function load($class){
        $this->trace("<!-- load : $class -->");
		if (!class_exists($class, false)) {
			$this->trace("Chargement de la class($class).");
			$nomFichier = strtolower($class);
			// déclaration des chemins possible
			$cheminClass     = $this->dossierClass     . $nomFichier . $this->extensionClass;
			$cheminFonction  = $this->dossierFonction  . $nomFichier . $this->extensionFonction;
			$cheminInterface = $this->dossierInterface . $nomFichier . $this->extensionInterface;
			if (file_exists($cheminClass)) {
				require ($cheminClass);
			} elseif (file_exists($cheminInterface)) {
				require ($cheminInterface);
			} elseif (file_exists($cheminFonction)) {
				require ($cheminFonction);
			} else {
				$this->trace("Impossible de charger la class '$class' à partir de 'load()'!");
				return false;
			}
		}
    }
    
}
?>