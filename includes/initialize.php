<?php

define('INCLUDES_PATH', dirname(__FILE__) . '/');
define('ROOT_PATH', dirname(INCLUDES_PATH) . '/');
define('API_PATH', ROOT_PATH . '/api/');
define('PAGES_PATH', ROOT_PATH . '/pages/');
define('PARTIALS_PATH', ROOT_PATH . '/partials/');
define('MODALS_PATH', PARTIALS_PATH . '/modals/');
define('DASHBOARD_PATH', PARTIALS_PATH . '/dashboard/');
define('FUNCTIONS_PATH', INCLUDES_PATH . '/functions/');

require_once(INCLUDES_PATH . 'session_config.php');
require_once(INCLUDES_PATH . 'sessions.php');
require_once(INCLUDES_PATH . 'db_config.php');

// // Functions
// require_once(FUNCTIONS_PATH . 'func_offices.php');
// require_once(FUNCTIONS_PATH . 'func_cities.php');
