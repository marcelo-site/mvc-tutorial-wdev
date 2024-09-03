<?php

require_once __DIR__ . '/includes/app.php';

use \App\Http\Router;

$app = new Router(URL);

include __DIR__ . "/routes/pages.php";
include __DIR__ . "/routes/admin.php";

$app->run()->sendResponse();
