<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

use Config\Boot;

$app = Boot::load();
$app->run();
