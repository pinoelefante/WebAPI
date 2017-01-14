<?php
    session_start();
    
	require_once("connections.php");
	require_once("database.php");
    require_once("enums.php");
    require_once("functions.php");
	require_once("push_notifications.php");
	require_once("session.php");
    
    $action = getParameter("action", true);
    $responseCode = StatusCodes::FAIL;
    $responseContent = "";
    switch($action)
    {
		case "Register":
			break;
		case "Login":
			//save idUtente in $_SESSION["idUtente"]
			break;
		case "Logout":
			break;
		case "RegistraPush":
			if(isLogged(true))
			{
				$token = getParameter("token", true);
				$deviceType = getParameter("deviceOS", true);
				$deviceId = getParameter("deviceId", true);
				$responseCode = RegistraDevice($token, $deviceType,$deviceId);
			}
			break;
		case "UnregisterPush":
			if(isLogged(true))
			{
				$token = getParameter("token", true);
				$deviceType = getParameter("deviceOS", true);
				$deviceId = getParameter("deviceId", true);
				$responseCode = UnRegistraDevice($token, $deviceType,$deviceId);
			}
			break;
        default:
            $responseCode = StatusCodes::METODO_ASSENTE;
            break;
    }
    sendResponse($responseCode, $responseContent);
    
?>