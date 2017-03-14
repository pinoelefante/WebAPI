<?php
    require_once("config.php");
    require_once("enums.php");
    require_once("logger.php");

    $requestId = LogRequest();
    checkUserAgent();

    function getParameter($par,$required = false, $maxLenght = -1)
    {
        $parameter = NULL;
        if(isset($_POST[$par]) && !empty($_POST[$par]))
            $parameter = $_POST[$par];
        else if(isset($_GET[$par]) && !empty($_GET[$par]))
            $parameter = $_GET[$par];
        if($required && $parameter == NULL)
        {
            sendResponse(StatusCodes::RICHIESTA_MALFORMATA, "$par is required");
            die();
        }
        if($maxLenght > 0 && $parameter!=NULL && strlen($parameter) > $maxLenght)
        {
            sendResponse(StatusCodes::RICHIESTA_MALFORMATA, "$par max lenght is $maxLenght");
            die();
        }
        return $parameter;
    }
    function sendResponse($response, $content = "")
    {
        $array = array('response' => $response, 
                       'time' => date("Y-m-d H:i:s"),
                       'content' => empty($content) ? "" : $content );
        header('Content-Type: application/json');
        $responseJson = json_encode($array);
        LogResponse($responseJson, $GLOBALS['requestId']);
        echo $responseJson;

        if($response<0)
        {
            $debug = GetDebugMessage();
            $corpoMail = "E' stata rilevata una richiesta fallita al server ($response). Ecco la richiesta\n\n<br><br>$debug";
            sendEmailAdmin("[PostApp] Richiesta fallita",$corpoMail);
        }
    }
    function sendPOSTRequest($url, $data)
    {
        //$url = 'http://server.com/path';
        //$data = array('key1' => 'value1', 'key2' => 'value2');

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) 
        { 
            /* Handle error */ 
        }

        var_dump($result);
    }
    function checkUserAgent()
    {
        if(CHECK_USER_AGENT)
        {
            if($_SERVER['HTTP_USER_AGENT']==CLIENT_USER_AGENT)
                return true;
            sendResponse(StatusCodes::INVALID_CLIENT);
            exit();
        }
        return true;
    }
    function GetUserIP()
    {
        //https://www.cloudflare.com/ips/
        //http://stackoverflow.com/questions/14985518/cloudflare-and-logging-visitor-ip-addresses-via-in-php
        $user_ip = REVERSE_PROXY_ENABLED ? $_SERVER[REVERSE_PROXY_REMOTE_ADDRESS] : $_SERVER['REMOTE_ADDR'];
        return $user_ip;
    }
    function sendEmail($destinatario, $oggetto, $corpo)
    {
        //mail($destinatario, $oggetto, $corpo);
    }
    function sendEmailAdmin($oggetto, $corpo)
    {
        //mail(ADMIN_EMAIL, $oggetto, $corpo);
    }
?>