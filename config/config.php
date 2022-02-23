<?php

use Dotenv\Dotenv;



$dotenv = Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

$config = array();

$config['bot'] = array(
    'token' => $_ENV['KEY'],
    'trigger' => '!',
);

$config['color'] = array(
    'BLUE' => '0x0000FF',
    'RED' => '0xFF0000',
    'GREEN' => '0x00FF00'
);