<?php
    session_start();
    
    require_once("enums.php");
    require_once("functions.php");
	require_once("database.php");
    
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
    
	function RegistraDevice($token, $device, $deviceId)
	{
		$idUtente = getIdUtenteFromSession();
		$query = "INSERT INTO push_devices (id_utente,token,deviceOS,deviceId) VALUES (?,?,?,?)";
		return dbUpdate($query,"isis",array($idUtente,$token,$device,$deviceId)) ? StatusCodes::OK : StatusCodes::FAIL;
	}
	function UnRegistraDevice($token, $device,$deviceId)
	{
		$idUtente = getIdUtenteFromSession();
		$query = "DELETE FROM push_devices WHERE id_utente=? AND token=? AND deviceOS=? AND deviceId = ?";
		return dbUpdate($query,"isis",array($idUtente,$token,$device,$deviceId), DatabaseReturns::RETURN_AFFECTED_ROWS) ? StatusCodes::OK : StatusCodes::FAIL;
	}
?>