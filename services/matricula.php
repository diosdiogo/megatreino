
<?php

include 'conecta.php';

$idaluno = $_GET["idaluno"];
$idacademia = $_GET["academia"];
$modalidade = $_GET["modalidade"];
$plano = $_GET["plano"];
$dataInicio = date('Y-m-d', strtotime($_GET["dataInicio"]));

echo ($academia+' '+$modalidade+' '+$turma);

$query = "insert into matricula (
aluno,
modalidade,
plano_pagamento,
data_inicio,
academia)

values (
{$idaluno},
{$modalidade},
'{$plano}',
'{$dataInicio}',
{$idacademia})";

mysqli_query($conexao, $query);

/*
id int(11) AI PK
aluno int(11)
modalidade int(11)
plano_pagamento varchar(45)
valor varchar(45)
data_inicio date
vencimento int(11)
desconto decimal(10,2)
ativo tinyint(1)
academia int(11)
data_fim date
colaborador int(11)
ocorrencia bigint(20)

 */
