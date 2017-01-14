<?php
    require_once("config.php");
    require_once("enums.php");
    require_once("logger.php");

    $requestId = LogRequest();
    checkUserAgent();

    function getParameter($par,$required = false)
    {
        if(isset($_POST[$par]) && !empty($_POST[$par]))
            return $_POST[$par];
        else if(isset($_GET[$par]) && !empty($_GET[$par]))
            return $_GET[$par];
        if($required)
        {
            sendResponse(StatusCodes::RICHIESTA_MALFORMATA, "$par is required");
            $action = getParameter("action");
            die();
        }
        return NULL;
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
            if($_SERVER['HTTP_USER_AGENT']=="PostAppClient")
                return true;
            sendResponse(StatusCodes::INVALID_CLIENT);
            exit();
        }
        return true;
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