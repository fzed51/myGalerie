<?php

/**
 * @author Fabien SANCHEZ
 * @copyright 2012
 */

class Reseau{
    public static IpUtilisateur(){
        mydebug::traceFonction();
        
        $pbDetect = false;
        
        if (isset($_SERVER["REMOTE_ADDR"])){
            $IP = $_SERVER["REMOTE_ADDR"];
        }else{
            $pbDetect = true;
        }
        if(!filter_var($IP, FILTER_VALIDATE_IP))$pbDetect = true;
        
        if($pbDetect){
            MyDebug::trace('Adresse ip non détectée!!!')
            return null;
        }else{
            return $IP;
        }
        
    }
    
    public static appliqueMasque($masque, $Ip){
        mydebug::traceFonction();
        
        $typeAdr = new FlagsEnum('IPv6', 'IPv4');
        $adrOut = Array();
        
        if(filter_var($IP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
            $separateur = ':';
            $nbMod = 8;
            $type = $typeAdr->IPv6;
        }elseif(filter_var($IP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $separateur = '.';
            $nbMod = 4;
            $type = $typeAdr->IPv4;
        }else{
            return null;
        }
        
        $adrMasque = explode($masque, $separateur);
        $adrIp = explode($Ip, $separateur);
        
        for($i = 0; $i < $nbMod; $i++){
            if($type == $typeAdr->IPv6){
                $mod = hexdec($adrMasque[$i]) & hexdec($adrIp[$i])
                $adrOut = array_push(dechex($mod));
            }else{
                $adrOut = array_push($adrMasque[$i] & $adrIp[$i]);
            }
        }
        
        $adrOut = join($adrOut, $separateur);
        
        return $adrOut;
        
    }
    
}

?>