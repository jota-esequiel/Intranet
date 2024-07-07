<?php 
include '../Controller/standardFunctionsController.php';

if (checkUserType('A')) {
    echo "Você é administrador!";
} else {
    echo "Você é cliente!";
}
?>