<?php
    session_start();
    
	require_once("./service/config.php");
	require_once("./service/connections.php");
	require_once("./service/database.php");
    require_once("./service/enums.php");
    require_once("./service/functions.php");
	require_once("./service/push_notifications.php");
	require_once("./service/session.php");
    
    $action = getParameter("action", true);
    $responseCode = StatusCodes::FAIL;
    $responseContent = "";
    switch($action)
    {
		case "Register":
			break;
		case "Login":
			$username = getParameter("username", true);
            $password = getParameter("password", true);
			$responseCode = login($username, $password) ? StatusCodes::OK : StatusCodes::LOGIN_ERROR;
			break;
		case "Logout":
			closeSession();
			$responseCode = StatusCodes::OK;
			break;
		case "RegistraPush":
			if(isLogged())
			{
				$token = getParameter("token", true);
				$deviceType = getParameter("deviceOS", true);
				$deviceId = getParameter("deviceId", true);
				$responseCode = RegistraDevice($token, $deviceType,$deviceId);
			}
			break;
		case "UnregisterPush":
			if(isLogged())
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

	function login($username, $password)
	{
		$query = "SELECT ".DB_USER_PASSWORD.",".DB_USER_ID." FROM ".DB_USER_TABLE." WHERE ".DB_USER_USERNAME." = ?";
        $res = dbSelect($query,"s", array($username), true);
		if($res != null && password_verify($password, $res[DB_USER_PASSWORD]))
		{
			$_SESSION[LOGIN_SESSION_PARAMETER] = $res[DB_USER_ID];
			return true;
		}
		return false;
	}
?>