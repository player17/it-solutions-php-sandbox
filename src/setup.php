<?php

/** Absolute path to project root. */
define('PROJECT_ROOT', '/var/www'.dirname(__DIR__));

require_once __DIR__.'/Conf.php';
\Chat\Conf::parseFromFile();
