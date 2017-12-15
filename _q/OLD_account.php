<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'].'/core/init.php';
include_once($cdn . str_replace('.php', '', basename($SCRIPT_FILENAME)) . '.txt');
