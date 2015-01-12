<?php

include "vendor/autoload.php";

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

Emojione\Emojione::$imageType = 'png';
Emojione\Emojione::$imagePathPNG = 'images/emoji/';

$db = new PDO('mysql:host=localhost;dbname=inforuutu;charset=utf8', DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);