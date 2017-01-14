<?php
    require_once("enums.php");
    
    function isLogged($required = false)
    {
        $sessionVer = sessionVerification();
        if($required && !$sessionVer)
        {
            sendResponse(StatusCodes::LOGIN_NON_LOGGATO, "");
            exit;
        }
        return $sessionVer;
    }
    function getIdUtenteFromSession($failIfNotLogged = true)
    {
        if(isset($_SESSION["idUtente"]))
            return $_SESSION["idUtente"];
            
        if($failIfNotLogged)
        {
            sendResponse(StatusCodes::LOGIN_NON_LOGGATO, "");
            die();
        }
        return -1; //utente non loggato
    }
    function sessionVerification()
    {
        if(isset($_SESSION["idUtente"]) &&!empty($_SESSION["idUtente"]))
            return true;
        return false;
    }
?>