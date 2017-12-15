<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

$user = new User();

$user->logout();

Redirect::to($_SESSION['HtmlDelimiter'] . '');