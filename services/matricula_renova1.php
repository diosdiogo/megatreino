
<?php

include 'conecta.php';

if (isset($_GET["idmatricula"])) {

	$idmatricula = $_GET["idmatricula"];
}

$idaluno = $_GET["idalunor"];
$idacademia = $_GET["academiar"];
$modalidade = $_GET["modalidader"];
$plano = utf8_decode($_GET["planor"]);
$pagar = 'R';
$pago = $_GET["pagor"];
$dataInicio = $_GET["dataInicior"];
$dataFim = $_GET["dataFimr"];
$vencimento = $_GET["vencimentor"];
$valor = $_GET["valorr"];
$desconto = $_GET["descontor"];
$ativo = $_GET["ativor"];
$idcolaborador = $_GET["idcolaboradorr"];
$ocorrencia = $_GET["ocorrenciar"];
$documento = $_GET["documentor"];
$modnome = utf8_decode($_GET["modnomer"]);
$planonome = utf8_decode($_GET["planonomer"]);

$historico = $modnome . " - " . $planonome . " - Desconto: R$" . $desconto;

//$dataI = date('Y-m-d', strtotime(substr($dataInicio, 4, 11)));
//$dataF = date('Y-m-d', strtotime(substr($dataFim, 4, 11)));
//
//
$valor1 = substr($valor, 2);
//$valor1 = $valor;

if ($idmatricula == null) {
	$idmatricula = 0;
}

if ($desconto == null) {
	$desconto = 0;
	$historico = $modnome . " - " . $planonome;
}

$valorP = $valor1 + $desconto;
//$getMod = json_encode(getModalidade($conexao, $idacademia, $modalidade));

print("ID: " . $idmatricula . "<br>");
print("ID aluno: " . $idaluno . "<br>");
print("ID Academia: " . $idacademia . "<br>");
print("ID modalidade: " . $modalidade . "<br>");
print("ID Plano: " . $plano . "<br>");
print("Pagar Receber: " . $pagar . "<br>");
print("Pago: " . $pago . "</br>");
print("Valor plano: " . $valorP . "</br>");
print("Data Inicio: " . $dataInicio . "<br>");
//print("Data Inicio: " . $dataI . "<br>");
//print("Data Inicio: " . $dataF . "<br>");
print("Data Fim: " . $dataFim . "<br>");
print("Vencimento: " . $vencimento . "<br>");
print("Valor: " . $valor . "<br>");
print("Valor1: " . $valor1 . "<br>");
print("Desconto: " . $desconto . "<br>");
print("Ativo: " . $ativo . "<br>");
print("ID Consulto: " . $idcolaborador . "<br>");
print("Ocorrencia: " . $ocorrencia . "<br>");
print("Documento: " . $documento . "<br>");
print("Mod: " . $modnome . "<br>");
print("Plano: " . $planonome . "<br>");

print("Histórico: " . $historico . "<br>");

inserePagamento($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataFim, $valor1, $desconto, $historico, $valorP);

if ($pago == 0) {

	if ($idmatricula == 0) {

		insereMatricula($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataFim, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia);
	} else {
		alterarMatricula($conexao, $modalidade, $plano, $valor1, $vencimento, $desconto, $dataInicio, $dataFim, $idcolaborador, $ocorrencia, $idmatricula);

	}

}

function insereMatricula($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataFim, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia) {

	$query = "insert into matricula (
				aluno,
				modalidade,
				plano_pagamento,
				vencimento,
				data_inicio,
				data_fim,
				valor,
				desconto,
				ativo,
				colaborador,
				ocorrencia,
				academia)

				values (
				{$idaluno},
				{$modalidade},
				upper('{$plano}'),
				{$vencimento},
				'{$dataInicio}',
				'{$dataFim}',
				{$valor1},
				{$desconto},
				{$ativo},
				{$idcolaborador},
				{$ocorrencia},
				{$idacademia})";

	return mysqli_query($conexao, $query);
}

function alterarMatricula($conexao, $modalidade, $plano, $valor1, $vencimento, $desconto, $dataInicio, $dataFim, $idcolaborador, $ocorrencia, $idmatricula) {
	$query = "update matricula set
							modalidade={$modalidade},
							plano_pagamento=upper('{$plano}'),
							valor={$valor1},
							vencimento={$vencimento},
							desconto='{$desconto}',
							data_inicio='{$dataInicio}',
							data_fim='{$dataFim}',
							colaborador={$idcolaborador},
							ocorrencia='{$ocorrencia}' where id={$idmatricula}";
	return mysqli_query($conexao, $query);
}

function inserePagamento($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataFim, $valor1, $desconto, $historico, $valorP) {

	$query = "insert into pagar_receber (
					pagar_receber,
					pago,
					documento,
					aluno_fornecedor,
					colaborador,
					ocorrencia,
					academia,
					emissao,
					vencimento,
					liberadoate,
					valor,
					desconto,
					tipodoc,
					historico,
					val_origem,
					deletado)

					values (
						'{$pagar}',
						{$pago},
						{$documento},
						{$idaluno},
						{$idcolaborador},
						{$ocorrencia},
						{$idacademia},
						now(),
						'{$dataFim}',
						'{$dataFim}',
						{$valor1},
						{$desconto},
						0,
						upper('{$historico}'),
						{$valorP},
						'N')";
	print("query: " . $query . "<br>");
	return mysqli_query($conexao, $query);
}

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
/*

function inserePagamento($conexao)
/*
id int(11) AI PK
documento int(11)
pagar_receber varchar(1)
parc varchar(5)
aluno_fornecedor int(11)
colaborador int(11)
emissao date
vencimento date
liberadoate date
valor decimal(10,2)
canc varchar(80)
tipodoc int(11)
bloq varchar(20)
historico varchar(80)
obs longtext
sel varchar(1)
bloq_banco int(11)
val_origem decimal(10,2)
val_pago decimal(10,2)
desconto decimal(10,2)
pagamento date
pago tinyint(1)
academia int(11)
ocorrencia bigint(20)
historico_model int(11)
venda tinyint(1)
idassinatura_iugu varchar(255)
deletado char(1)
 */
