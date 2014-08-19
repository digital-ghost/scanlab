<?php
##
#  You must configure this script
##

// Database connection server
// Default is set to localhost
define('DB_SERVER', 'mongodb://localhost:27017'); 

// Your mongo database
define('DB_NAME', 'scanlab');

// Relative URL of application
define('REL_URL', '/');

// Set this to true if you want to use worker
// Worker creates caches, speeds up some functions and you must run it every 30 minutes
// example cron job: */30 * * * * /usr/bin/php /path/to/scanlab/worker.php update_cache
define('USE_WORKER', true);

// Searches will sort by timestamp or not
// Set to false if you want faster searches
define('SORT_SEARCH', false);

// This is only used to secure auth hashes
// You can change it if you know what are you doing
define('AUTH_SALT', 'lab');

// If you want to forbid user registration, set to false
define('FREE_REGISTRATION', true);

// If you want to disable searches for guests (completely private)
define('IS_PRIVATE', false);

// Maximum possible registered users (to prevent spam)
define('USER_REGISTRATION_LIMIT', 5);

// Default maximum user reports count (to prevent spam)
define('DEFAULT_ACCOUNT_LIMIT', 10000);

// Set true if you want to temporary disable site
define('SITE_OFFLINE', false);

// Set true if you want to temporary disable report inserting 
define('DISABLE_INSERT', false);

// Admin email
define('ADMIN_EMAIL', 'admin@scanlab.onion');

// Superuser
define('ROOT_USER', 'admin');

// do not enable this
define('PROMO_MODE', false);

// Advert. Set to false if you don't want to use it.
define('SL_ADVERT', false);

define('SL_VERSION', '1.0');

?>
