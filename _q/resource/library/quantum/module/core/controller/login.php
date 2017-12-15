<?php
session_start();
ob_start();

$remember = (Input::get('remember') === 'on') ? true : false;
$login = $user->login(Input::get('email'), Input::get('password'), $remember);
$login ? Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/') : Session::flash('login_error', $core_language[27] . ', ' . strtolower($core_language[28])) ;