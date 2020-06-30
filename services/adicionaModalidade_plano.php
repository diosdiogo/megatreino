<?php

include 'conecta.php';

$academia = $_GET["academia"];
$modalidade = $_GET["modalidade"];
$valor = $_GET["valor"];
$plano = $_GET["plano"];

echo($academia+' '+$modalidade+' '+$valor+' '+$plano);

$query = "insert into modalidade_plano (
modalidade,
academia,
plano,
valor,
deletado)

values (
{$modalidade},
{$academia},
{$plano},
{$valor},
'N')";

mysqli_query($conexao, $query);

