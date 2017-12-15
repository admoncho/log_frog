<?php
session_start();
ob_start();
$pdf->addPDF($_SESSION['ProjectPath'] . '/files/schedule/soar-1-page-1.pdf', 'all')
	->addPDF($_SESSION['ProjectPath'] . '/files/schedule/soar-1-page-2.pdf', 'all')
	->addPDF($_SESSION['ProjectPath'] . '/files/schedule/soar-1-page-3.pdf', 'all')
	->merge('file', $_SESSION['ProjectPath'] . '/files/schedule/soar-1.pdf');