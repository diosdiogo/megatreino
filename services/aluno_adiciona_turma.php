<?php

include 'conecta.php';

$academia = $_GET["academia"];
$modalidadetr = $_GET["modalidadetr"];
$idaluno = $_GET["idaluno"];
$matricula = $_GET["matricula"];

echo ($academia+' - '+$modalidadetr+' - '+$idaluno+' - '+$matricula);

$query = "insert into aluno_turma (
modalidade_turma,
aluno,
academia,
matricula)

values (
{$modalidadetr},
{$idaluno},
{$academia},
'{$matricula}')";

mysqli_query($conexao, $query);
