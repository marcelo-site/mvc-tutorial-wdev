<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

require_once __DIR__ . '/includes/app.php';

use \App\Http\Router;

$app = new Router(URL);

include_once __DIR__ . str_replace("/", DIRECTORY_SEPARATOR, "/routes/pages.php");

include_once __DIR__ . str_replace("/", DIRECTORY_SEPARATOR, "/routes/admin.php");

include_once __DIR__ . str_replace("/", DIRECTORY_SEPARATOR, "/routes/api.php");

$app->run()->sendResponse();
