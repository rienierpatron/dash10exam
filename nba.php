<?php
include('vendor/autoload.php');

use App\Request;
use App\Controllers\PlayerController;

$request = new Request();

$controller = new PlayerController();

echo $controller->show('nba');
