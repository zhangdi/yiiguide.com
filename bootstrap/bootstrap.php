<?php

defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));

if (file_exists(ROOT_PATH . '/env.ini')) {
    $env = new Dotenv\Dotenv(ROOT_PATH, 'env.ini');
    $env->load();
}
