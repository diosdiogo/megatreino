<?php

include 'conecta.php';

$academia = $_GET["academia"];
$modalidade = $_GET["modalidade"];
$turma = $_GET["turma"];

echo($academia+' '+$modalidade+' '+$turma);

$query = "insert into modalidade_turma (
modalidade,
academia,
turma,
deletado)

values (
{$modalidade},
{$academia},
{$turma},
'N')";

mysqli_query($conexao, $query);

