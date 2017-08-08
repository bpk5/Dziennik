<?php

session_start();

require_once 'controller/controller.php';
$ob = new Controller();

if (isset($_GET['akcja'])) {
    $ob->wybierzAkcje($_GET['strona'], $_GET['akcja']);
}
else {
    if (isset($_GET['strona'])) {
        $ob->wybierzStrone($_GET['strona']);
    }
    else {
        $ob->wybierzStrone('logowanie');
    }
}


