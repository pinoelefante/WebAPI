<?php
    require_once(__DIR__."/../configs/app-config.php");
    require_once("enums.php");
    require_once("logger.php");

    function dbConnect()
    {
        $mysqli = new mysqli(DBADDR, DBUSER, DBPASS, DBNAME);
        if ($mysqli->connect_errno) 
        {
            LogMessage("(".$mysqli->connect_errno.") ".$mysqli->connect_error, "mysql_connect.log");
            sendResponse(StatusCodes::SQL_FAIL);
        }
        $mysqli->set_charset("utf8");
        return $mysqli;
    }
    function dbClose($mysqli)
    {
        if($mysqli->errno)
            LogMessage("(".$mysqli->errno.") ".$mysqli->error, "mysql.log", true);
        $mysqli->close();
    }
    function dbUpdate($query,$parametersType = null,$parameters = null, $returnType = DatabaseReturns::RETURN_BOOLEAN)
    {
        $res = false;
        $dbConn = dbConnect();
        if($st = $dbConn->prepare($query))
        {
            if($parametersType!=null)
                call_user_func_array(array($st, 'bind_param'), array_merge(array($parametersType), makeValuesReferenced($parameters)));
            $res = $st->execute();
            switch($returnType)
            {
                case DatabaseReturns::RETURN_BOOLEAN:
                    //è il valore salvato di default in $res
                    break;
                case DatabaseReturns::RETURN_AFFECTED_ROWS:
                    $res = $dbConn->affected_rows;
                    break;
                case DatabaseReturns::RETURN_INSERT_ID:
                    $res = $dbConn->insert_id;
                    break;
            }
            $st->close();
        }
        dbClose($dbConn);
        return $res;
    }
    function dbSelect($query,$parametersType = null,$parameters = null,$oneRow = false)
    {
        $res = false;
        $dbConn = dbConnect();
        if($st = $dbConn->prepare($query))
        {
            if($parametersType!=null)
                call_user_func_array(array($st, 'bind_param'), array_merge(array($parametersType), makeValuesReferenced($parameters)));
            if($st->execute())
            {
                if($oneRow)
                    $res = null;
                else
                    $res = array();
                $result = $st->get_result();
                while($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    if($oneRow)
                    {
                        $res = $row;
                        break;
                    }
                    else
                        array_push($res,$row);
                }
            }
            $st->close();
        }
        dbClose($dbConn);
        return $res;
    }
	function makeValuesReferenced(&$arr)
	{ 
		$refs = array(); 
		foreach($arr as $key => $value) 
			$refs[$key] = &$arr[$key]; 
		return $refs;
	}
    function hashPassword($password)
    {
        $options = array('cost' => HASH_COST_TIME);
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    function dbSave($obj, $ignoreColumns = null, $returnType = DatabaseReturns::RETURN_INSERT_ID, $table = null)
    {
        $tableName = $table == null ? get_class($obj) : $table;
        $values = get_object_vars($obj);
        if($ignoreColumns != null && is_array($ignoreColumns))
            foreach ($ignoreColumns as $column)
                unset($values[$column]);
        $fields = implode(",", array_keys($values));
        $bindParameters = implode(",", array_fill(0, count($values), "?"));
        $bindValues = array_values($values);
        $parametersTypes = __getParametersTypesString($values);
        if(strlen($parametersTypes) != count($values))
            throw new Exception("You can't use this method with a complex object. You have to write a complete query.");
        
        $query = "INSERT INTO ".$tableName. " (".$fields.") VALUES (".$bindParameters.")";
        return dbUpdate($query, $parametersTypes, $bindValues, $returnType);
    }
    function __getParametersTypesString($values)
    {
        $types = "";
        foreach ($values as $key => $value) {
            $type = gettype($value);
            switch($type)
            {
                case "boolean":
                case "integer":
                    $types.="i";
                    break;
                case "float":
                case "double":
                    $types.="d";
                    break;
                case "string":
                    $types.="s";
                    break;
                default:
                    break;
            }
        }
        return $types;
    }
?>