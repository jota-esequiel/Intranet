<?php
include_once '../Controller/standardFunctionsController.php';
// $dtIni = date("Ymd", strtotime('first day of January ' . date('Y')));
// $dtFim = date("Ymd", strtotime('last day of December ' . date("Y")));

// echo "O primeiro dia do ano é: " . convertDate($dtIni);
// echo "O última dia do ano é: " . convertDate($dtFim);

$text = "Meu nome é João Vitor E";

echo generateHash($text);
?>
