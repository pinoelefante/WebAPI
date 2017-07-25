<?php
    session_start();
    require_once(__DIR__."/../configs/app-config.php");
    require_once("enums.php");
    require_once("connections.php");
    
    function isLogged($required = true)
    {
        $sessionVer = sessionVerification();
        if($required && !$sessionVer)
            sendResponse(StatusCodes::LOGIN_NON_LOGGATO, "");
        return $sessionVer;
    }
    function getLoginParameterFromSession($failIfNotLogged = true)
    {
        if(isset($_SESSION[LOGIN_SESSION_PARAMETER]))
            return $_SESSION[LOGIN_SESSION_PARAMETER];
            
        if($failIfNotLogged)
            sendResponse(StatusCodes::LOGIN_NON_LOGGATO, "");
        return -1; //utente non loggato
    }
    function sessionVerification()
    {
        return (isset($_SESSION[LOGIN_SESSION_PARAMETER]) && !empty($_SESSION[LOGIN_SESSION_PARAMETER]));
    }
    function closeSession()
    {
        unset($_SESSION[LOGIN_SESSION_PARAMETER]);
        setcookie("PHPSESSID", "", 1);
    }
?>