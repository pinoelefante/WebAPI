<?php
    function dbConnect()
    {
        return new SQLite3("database.sqlite");
    }
    function dbClose($db)
    {
        $db->close();
    }
    function dbUpdate($query, $returnType = DatabaseReturns::RETURN_BOOLEAN)
    {
        $db = dbConnect();
        $res = $db->exec($query);
        switch ($returnType) {
            case DatabaseReturns::RETURN_BOOLEAN:
                //è il valore salvato di default in $res
                break;
            case DatabaseReturns::RETURN_AFFECTED_ROWS:
                $res = $db->changes;
                break;
            case DatabaseReturns::RETURN_INSERT_ID:
                $res = $db->lastInsertRowID;
                break;
        }
        dbClose($db);
        return $res;
    }
    function dbSelect($query, $oneRow = false)
    {
        $db = dbConnect();
        $res = $db->query($query);
        $result = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC))
        {
            if($oneRow){
                $result = $row;
                break;
            }
            array_push($result, $row);
        }
        $res->finalize();
        dbClose($db);
        return $result;
    }
?>