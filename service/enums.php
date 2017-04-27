<?php
    abstract class StatusCodes extends BasicEnum
    {
        //ENVELOP_UNSET non è mai inviato dal server ma è utilizzato soltanto all'interno dell'applicazione
        const ENVELOP_UNSET = 0;
        const METHOD_NOT_IMPLEMENTED = -1000;

        const FAIL = -1;
        const RICHIESTA_MALFORMATA = -2;
        const METODO_ASSENTE = -3;
        const SQL_FAIL = -4;
		const INVALID_CLIENT = -5;
        
        const OK = 1;

        const LOGIN_ERROR = 10;
        const LOGIN_GIA_LOGGATO = 11;
        const LOGIN_NON_LOGGATO = 12;
    }
    abstract class DatabaseReturns extends BasicEnum
    {
        const RETURN_BOOLEAN = 1;
        const RETURN_AFFECTED_ROWS = 2;
        const RETURN_INSERT_ID = 3;
    }
    abstract class MapDistance extends BasicEnum
    {
        const METERS = 1;
        const KILOMETERS = 1000;
    }
    
    abstract class BasicEnum {
        private static $constCacheArray = NULL;

        public static function getConstants() {
            if (self::$constCacheArray == NULL) {
                self::$constCacheArray = array();
            }
            $calledClass = get_called_class();
            if (!array_key_exists($calledClass, self::$constCacheArray)) {
                $reflect = new ReflectionClass($calledClass);
                self::$constCacheArray[$calledClass] = $reflect->getConstants();
            }
            return self::$constCacheArray[$calledClass];
        }

        public static function isValidName($name, $strict = false) {
            $constants = self::getConstants();

            if ($strict) {
                return array_key_exists($name, $constants);
            }

            $keys = array_map('strtolower', array_keys($constants));
            return in_array(strtolower($name), $keys);
        }

        public static function isValidValue($value, $strict = true) {
            $values = array_values(self::getConstants());
            return in_array($value, $values, $strict);
        }
    }
?>