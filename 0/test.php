<?php

$fileArray= array("/home/logistic/public_html/files/schedule/soar-1.pdf","/home/logistic/public_html/files/schedule/81-f-e-trucking-llc-coyote-logistics-llc.pdf");

$datadir = "/home/logistic/public_html/files/schedule/";

$outputName = $datadir . "merged.pdf";

$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";

# Add each pdf file to the end of the command
foreach($fileArray as $file) {
  $cmd .= $file." ";
}

$result = shell_exec($cmd);

?>