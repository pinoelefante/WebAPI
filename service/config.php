<?php
    /* Database options */
    define('DBADDR', 'localhost');
    define('DBNAME', 'database_name');
    define('DBUSER', 'root');
    define('DBPASS', '');
    
    /* Encryption options */
    define('HASH_COST_TIME', 10);
	
    /* Debug options */
    define('DEBUG_ENABLE', 1);
    define('DEBUG_SAVE_REQUEST',0);
    define('DEBUG_SAVE_RESPONSE',0);
    define('DEBUG_LOG_MESSAGE',1);

    /* Connection options */
    define('CHECK_USER_AGENT', 0);
    define('REVERSE_PROXY_ENABLED', 0); //es: Cloudflare
    define('REVERSE_PROXY_REMOTE_ADDRESS', 'HTTP_CF_CONNECTING_IP');

    /* Admin options */
    define('ADMIN_EMAIL', 'email@admin.com');

    /* Push notifications options */
    define('GOOGLE_API_KEY','google-api-key-push-notification-service');
?>