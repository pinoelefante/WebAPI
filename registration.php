<?php
    /* WARNING: Do non include in this page connections.php or files that using it
       or set $GLOBALS["IGNORE_AUTH"]
    */
    $GLOBALS["IGNORE_AUTH"] = 1;

    require_once("./configs/app-config.php");
    require_once("./service/database.php");
    
    $username = $_POST["username"];
    $password = $_POST["password"];

    if(Register($username, $password))
    {
        unset($GLOBALS["IGNORE_AUTH"]);
        DoLogin($username, $password);
    }
    else 
        header('HTTP/1.0 403 Forbidden');

    function Register($username, $password)
    {
        if(strlen(trim($password))<8)
            return false;
        $query = "INSERT INTO ".AUTH_USER_TABLE." (".AUTH_USERNAME.",".AUTH_PASSWORD.") VALUES (?,?)";
        $passHash = hashPassword(trim($password));
        return dbUpdate($query, "ss", array($username, $passHash));
    }
    function DoLogin($username, $password)
    {
		$process = curl_init($_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/authentication.php?action=Login");
		curl_setopt($process, CURLOPT_USERPWD, $username.":".$password);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		$return = curl_exec($process);
		curl_close($process);
        header('Content-Type: application/json');
		echo $return;
    }
?>