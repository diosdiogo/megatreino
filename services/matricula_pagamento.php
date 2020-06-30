
<?php

include 'conecta.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);

$idaluno = $_GET["idaluno"];
$idacademia = $_GET["academia"];
$pago = 1;
$dataI = $_GET["dataI"];

$tipodoc = $_GET["tipodoc"];
$canc = $_GET["canc"];
$parc = '1/1';
$bloq_banco = 0;
$valor = $_GET["valor"];
$idcolaborador = $_GET["idcolaborador"];
$ocorrencia = $_GET["ocorrencia"];
$documento = $_GET["documento"];
$historico = 1;
$desc_documento = "PAGAR RECEBER";
$nomealuno = $_GET["nomealuno"];
$banco = $_GET["banco"];
$idmodalidade = $_GET["idmodalidade"];
$planonome = $_GET["planonome"];
$desconto = $_GET["desconto"];
$vencimento = $_GET["vencimento"];

$mes_dia = $_GET["mes_dia"];

$mes_diaQuant = $_GET["mes_diaQuant"];


$valor1 = substr($valor, 2);

$dataID = substr($dataI, 0, 2);
$dataIM = substr($dataI, 3, 2);
$dataIA = substr($dataI, 6, 4);

//$dataFD = substr($dataF, 0, 2);
//$dataFM = substr($dataF, 3, 2);
//$dataFA = substr($dataF, 6, 4);

//$dataIC = $dataIA . "-" . $dataIM . "-" . $dataID;
//echo $dataI;
//$dataFC = $dataFA . "-" . $dataFM . "-" . $dataFD;
//$mes = $dataIM + $mes_diaQuant;
//$dia = $dataID + $mes_diaQuant;

$dataF = date('Y-m-d', strtotime($dataI));

if ($mes_dia == "M") {
	$dataF = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' months', strtotime($dataF)));
} else {
	$dataF = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' day', strtotime($dataF)));
}

if ($canc == 'CT') {

	$canc = 'CA';

}

/*
print("Data inicio: " . $dataIC . "<br>");
print("Data fim: " . $dataFC . "<br>");

print("ID aluno: " . $idaluno . "<br>");
print("ID Academia: " . $idacademia . "<br>");
print("ID Modalidade: " . $idmodalidade . "<br>");
print("Pago: " . $pago . "</br>");
print("Data Inicio: " . $dataI . "<br>");
print("Data Fim: " . $dataF . "<br>");
print("Tipodoc: " . $tipodoc . "<br>");
print("Tipo de Documento: " . $canc . "<br>");
print("Parcelamento: " . $parc . "<br>");
print("Valor pago: " . $valor1 . "<br>");
print("Valor: " . $valor . "<br>");
print("Valor1: " . $valor1 . "<br>");
print("Vencimento: " . $vencimento . "<br>");
print("Desconto: " . $desconto . "<br>");
//print("Ativo: " . $ativo . "<br>");
print("ID Consulto: " . $idcolaborador . "<br>");
print("Ocorrencia: " . $ocorrencia . "<br>");
print("documento: " . $documento . "<br>");
print("Histórico: " . $historico . "<br>");
print("Descrição Documento: " . $desc_documento . "<br>");
print("Nome Aluno: " . $nomealuno . "<br>");
print("Banco: " . $banco . "<br>");
print("Nome do Plano: " . $planonome . "<br>");

print("Mes Dia: " . $mes_dia . "<br>");
print("Mes dia Quantidade: " . $mes_diaQuant . "<br>");
print("cod_atualiza: " . $cod_atualiza . "<br>");

//print("Histórico: " . $historico . "<br>");
*/
alterarPagamento($conexao, $idaluno, $parc, $documento, $tipodoc, $canc, $bloq_banco, $ocorrencia, $pago, $valor1);

alteraAluno($conexao, $idaluno, $cod_atualiza);

movimentoCaixaAberto($conexao, $documento, $idacademia, $historico, $tipodoc, $desc_documento, $nomealuno, $valor1, $banco, $idcolaborador, $ocorrencia);

insereLog($conexao, $nomealuno, $idcolaborador, $idacademia);

verifica($conexao, $idacademia, $idaluno,$cod_atualiza);

function alterarPagamento($conexao, $idaluno, $parc, $documento, $tipodoc, $canc, $bloq_banco, $ocorrencia, $pago, $valor1) {

	$query = "update pagar_receber set
						 parc='{$parc}',
						 pago={$pago},
						 canc='{$canc}',
						 tipodoc='{$tipodoc}',
						 bloq_banco={$bloq_banco},
						 val_pago={$valor1},
						 pagamento=now()

						 where aluno_fornecedor={$idaluno} and documento={$documento} and ocorrencia={$ocorrencia} and id>0";

	//echo '<br>' . $query . '<br>';

	$alterar = mysqli_query($conexao, $query);

	if (mysqli_affected_rows($conexao) == 0) {
		
		//echo "<script type=\"text/javascript\">alert('ERRO COD 005 - Pagamento não recebido em Dinheiro linhas alteradas');</script></br>";

		echo 1;
	}else{
		echo 0;
	}

	//echo ("ID inserido = \n" . mysqli_affected_rows($conexao));

	

	return $alterar;
}

function movimentoCaixaAberto($conexao, $documento, $idacademia, $historico, $tipodoc, $desc_documento, $nomealuno, $valor1, $banco, $idcolaborador, $ocorrencia) {

	$query = "insert into movimento_caixa_aberto(documento,
	historico,
	forma_pagamento,
	banco,
	colaborador,
	academia,
	emissao,
	desc_documento,
	nome,
	valor,
	cancelado,
	manual,
	ocorrencia,
	deletado
	)

	values({$documento},
	{$historico},
	{$tipodoc},
	{$banco},
	{$idcolaborador},
	{$idacademia},
	now(),
	'{$desc_documento}',
	'{$nomealuno}',
	{$valor1},
	'N',
	'N',
	{$ocorrencia},
	'N'
	)";

	//echo "query movimentoCaixaAberto: " . $query . "<br>";
	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 008 - Recebimento não gerado em movimentação de caixa - linhas alteradas 0');</script></br>";

	}
	//echo ("ID inserido = \n" . mysqli_insert_id($conexao));

	return $inserir;

}

function alteraAluno($conexao, $idaluno, $cod_atualiza) {
	$query = "update aluno set status='Liberado', cod_atualiza={$cod_atualiza} where idaluno={$idaluno};";
	//echo "query uptade aluno: " . $query . "<br>";
	return mysqli_query($conexao, $query);
}

function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Recebida Mensalidade de {$nomealuno}',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

/*

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
/*
Table: movimento_caixa_aberto
Columns:
id int(11) AI PK
documento int(11)
emissao datetime
historico int(11)
forma_pagamento int(11)
desc_documento varchar(70)
nome varchar(70)
observacao longtext
valor decimal(10,2)
banco int(11)
cancelado varchar(1)
manual varchar(1)
colaborador int(11)
academia int(11)
ocorrencia bigint(20)
deletado char(1)
 */