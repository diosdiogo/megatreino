<?php

$receber = $_GET['desconto'];
$desconto = str_replace('R', '', $receber);
$desconto = str_replace('$', '', $desconto);
$desconto = str_replace(',', '', $desconto);

echo $desconto;
?>