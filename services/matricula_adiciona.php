
<?php

include 'conecta.php';
include 'verificaStatus.php';

$cod_atualiza = rand(10000, 9999999);
$idaluno = $_GET["idaluno"];
$nomealuno = $_GET["nomealuno"];
$idacademia = $_GET["academia"];
$ativo = $_GET["ativo"];
$modalidade = utf8_decode($_GET["modalidade"]);
$plano = utf8_decode($_GET["plano"]);
$ModalidadePlano = selectModalidadePlano($conexao, $idacademia, $modalidade, $plano);
$pagar = 'R';
$pago = $_GET["pago"];
$dataInicio = $_GET["dataInicio"];
$dataFim = $dataInicio;
$vencimento = $_GET["vencimento"];
//$valor = $_GET["valor"];
$valor = $ModalidadePlano['valor'];
$desconto = $_GET["desconto"];
$idcolaborador = $_GET["idcolaborador"];
$ocorrencia = $_GET["ocorrencia"];
$documento = $_GET["documento"];
//$modnome = utf8_decode($_GET["modnome"]);
$modnome = $ModalidadePlano['nome'];
$planonome = utf8_decode($_GET["planonome"]);

//$mes_dia = $_GET["mes_dia"];
$mes_dia = $ModalidadePlano['mes_dia'];
//$mes_diaQuant = $_GET["mes_diaQuant"];
$mes_diaQuant = $ModalidadePlano['quantidade'];

//dados iugu

if (isset($_GET['status'])) {
	$status = utf8_decode($_GET['status']);
} else {
	$status = null;
}

if (isset($_GET['retornoIugu'])) {
	$retornoIugu = $_GET['retornoIugu'];
}
if (isset($_GET['idIugu'])) {
	$idIugu = $_GET['idIugu'];
} else { $idIugu = null;}

///////////////////////////////

if ($mes_dia == "M") {
	$dataFim = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' months', strtotime($dataFim)));
} else {
	$dataFim = date('Y-m-d', strtotime('+' . $mes_diaQuant . ' day', strtotime($dataFim)));
}

$historico = $modnome . " - " . $planonome . " - Desconto: R$" . $desconto;

//$dataI = date('Y-m-d', strtotime(substr($dataInicio, 4, 11)));
//$dataF = date('Y-m-d', strtotime(substr($dataFim, 4, 11)));
//

//$valor1 = substr($valor, 2);

if ($desconto == 0) {
	$historico = $modnome . " - " . $planonome;
}

if ($desconto == null) {
	$desconto = 0;
	$historico = $modnome . " - " . $planonome;
}

$valor1 = $valor - $desconto;
$valorP = $valor1 - $desconto;
//$getMod = json_encode(getModalidade($conexao, $idacademia, $modalidade));
/*
print("ID aluno: " . $idaluno . "<br>");
print("Nome aluno: " . $nomealuno . "<br>");
print("ID Academia: " . $idacademia . "<br>");
print("ID modalidade: " . $modalidade . "<br>");
print("ID Plano: " . $plano . "<br>");
print("Pagar Receber: " . $pagar . "<br>");
print("Pago: " . $pago . "</br>");
print("Valor plano: " . $valorP . "</br>");
print("Data completa: " . $dataInicio . "<br>");
print("Data Inicio: " . $dataInicio . "<br>");
//print("Data Inicio: " . $dataF . "<br>");
print("Data Fim: " . $dataFim . "<br>");
print("Vencimento: " . $vencimento . "<br>");
print("Valor: " . $valor . "<br>");
print("Valor1: " . $valor1 . "<br>");
print("Desconto: " . $desconto . "<br>");
print("Ativo: " . $ativo . "<br>");
print("ID Consulto: " . $idcolaborador . "<br>");
print("Ocorrencia: " . $ocorrencia . "<br>");
print("Mod: " . $modnome . "<br>");
print("Plano: " . $planonome . "<br>");

print("Mes Dia: " . $mes_dia . "<br>");
print("Mes dia Quantidade: " . $mes_diaQuant . "<br>");

print("Histórico: " . $historico . "<br>");
 */
if ($pago == "0") {

// se pagamento for modo recorrente
	if (isset($_GET['pagamentoIugu'])) {

		if ($retornoIugu == 57 or $retornoIugu == 51) {

			$ativo = 0;
			//se retorno do iugo for saldo insuficiente
			insereMatriculaIugu($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataInicio, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia, $status, $idIugu);
			insereLogIuguErro($conexao, $nomealuno, $idcolaborador, $idacademia);

		} else if ($retornoIugu == 'paid') {

			//se o cartão for aprovado
			insereMatriculaIugu($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataFim, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia, $status, $idIugu);

			inserePagamentoIugu($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataInicio, $dataFim, $valor1, $desconto, $historico, $valorP, $idIugu);

			insereRegistro($conexao);

			insereLogIugu($conexao, $nomealuno, $idcolaborador, $idacademia);

		}

	} else {

		//matricula sem iugu
		insereMatricula($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataFim, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia, $status, $modnome);

		inserePagamento($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataInicio, $dataFim, $valor1, $desconto, $historico, $valorP, $idIugu);

		insereLog($conexao, $nomealuno, $idcolaborador, $idacademia);
		verifica($conexao, $idacademia, $idaluno, $cod_atualiza);
	}
}

function insereMatricula($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataFim, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia, $status, $modnome) {

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
academia,
status)

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
{$idacademia},
'{$status}')";

	//echo 'Inserir matricula: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script>alert('ERRO COD 001 - matricula não foi cadastrada - linhas alteradas 0');</script></br>";
		$array = array('id' => mysqli_insert_id($conexao),
		);
		$matricula = '{"result":[' . json_encode($array) . ']}';

		echo $matricula;

	} else {

		//echo ("ID inserido = \n" . mysqli_insert_id($conexao));
		$array = array('id' => mysqli_insert_id($conexao),
			'modalidade' => $modalidade,
			'modnome' => utf8_encode($modnome),
			'plano' => utf8_encode($plano),
			'vencimentoMatricula' => $vencimento,
			'dataInicio' => $dataInicio = date('d-m-Y', strtotime($dataInicio)),
			'dataFim' => $dataFim = date('d-m-Y', strtotime($dataFim)),
			'valor' => $valor1,
			'desconto' => $desconto,
			'total' => number_format(($valor1), 2, ".", "."),

		);

		$matricula = '{"result":[' . json_encode($array) . ']}';

		echo $matricula;
		//echo $query;
	}
	return $inserir;
}

function inserePagamento($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataInicio, $dataFim, $valor1, $desconto, $historico, $valorP, $idAssinaturaIugu) {

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
idassinatura_iugu,
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
'{$dataInicio}',
now(),
{$valor1},
{$desconto},
0,
upper('{$historico}'),
{$valorP},
'{$idAssinaturaIugu}',
'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 004 - Pagamento não gerado - linhas alteradas 0');</script></br>";

	}
	//echo ("ID inserido = \n" . mysqli_insert_id($conexao));

	return $inserir;

}

//Iugu
function insereMatriculaIugu($conexao, $idaluno, $modalidade, $plano, $vencimento, $dataInicio, $dataFim, $valor1, $desconto, $ativo, $idcolaborador, $ocorrencia, $idacademia, $status, $idIugu) {

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
academia,
status,
idassinatura_iugu)

values (
{$idaluno},
{$modalidade},
upper('{$plano}'),
{$vencimento},
'{$dataInicio}',
'{$dataFim}',
{$valor1},
{$desconto},
'{$ativo}',
{$idcolaborador},
{$ocorrencia},
{$idacademia},
'{$status}',
'{$idIugu}')";

	//echo 'Inserir matricula: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script>alert('ERRO COD 001 - matricula não foi cadastrada - linhas alteradas 0');</script></br>";
		echo 1;

	} else {

		//echo ("ID inserido = \n" . mysqli_insert_id($conexao));
		echo 0;
	}
	return $inserir;
}

function inserePagamentoIugu($conexao, $pagar, $pago, $documento, $idaluno, $idcolaborador, $ocorrencia, $idacademia, $dataInicio, $dataFim, $valor1, $desconto, $historico, $valorP, $idAssinaturaIugu) {

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
canc,
desconto,
tipodoc,
historico,
val_origem,
val_pago,
pagamento,
idassinatura_iugu,
deletado)

values (
'{$pagar}',
1,
{$documento},
{$idaluno},
{$idcolaborador},
{$ocorrencia},
{$idacademia},
now(),
'{$dataInicio}',
'{$dataFim}',
{$valor1},
'REC',
{$desconto},
0,
upper('{$historico}'),
{$valorP},
{$valorP},
now(),
'{$idAssinaturaIugu}',
'N')";

	//echo 'Inserir pagamento: ' . $query . '</br>';

	$inserir = mysqli_query($conexao, $query);

	if (mysqli_insert_id($conexao) == 0) {

		//echo "<script type=\"text/javascript\">alert('ERRO COD 004 - Pagamento não gerado - linhas alteradas 0');</script></br>";

	}
	echo ("ID inserido = \n" . mysqli_insert_id($conexao));
	echo $query;
	return $inserir;

}
/*

function alterarPagamentoIugu($conexao, $idaluno, $documento, $ocorrencia, $valor1,$idAssinaturaIugu) {

$query = "update pagar_receber set
parc='1/1',
pago=1,
canc='REC',
tipodoc=0,
bloq_banco=0,
val_pago={$valor1},
idassinatura_iugu='{$idAssinaturaIugu}',
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
 */
function insereRegistro($conexao) {
	$query = "insert into ocorrencia (datahora)values(now())";

	return mysqli_query($conexao, $query);
}

function insereLog($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Inserida matrícula para {$nomealuno}',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

function insereLogIuguErro($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Inserida matrícula para {$nomealuno} - RECORRENTE com erro no pagamento',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

function insereLogIugu($conexao, $nomealuno, $idcolaborador, $idacademia) {
	$query = "insert into log (data_hora,acao,colaborador,academia)values(now(),'Inserida matrícula para {$nomealuno} - RECORRENTE',{$idcolaborador},{$idacademia})";

	//echo '</br>Inserir log: ' . $query . '</br>';
	return mysqli_query($conexao, $query);
}

function selectModalidadePlano($conexao, $idacademia, $modalidade, $plano) {

	$sql = "select modalidade_plano.id, modalidade_plano.academia, modalidade_plano.modalidade, modalidade_plano.valor, modalidade_plano.plano, modalidade.id, modalidade.nome, plano.descricao, plano.mes_dia, plano.quantidade from modalidade_plano
		left join plano on (modalidade_plano.plano=plano.id)
		left join modalidade on (modalidade_plano.modalidade=modalidade.id)
		where modalidade_plano.academia=$idacademia and modalidade_plano.modalidade=$modalidade and plano.descricao='$plano'
 		and modalidade_plano.deletado='N' order by plano.descricao";

	$query = mysqli_query($conexao, $sql);

	$row = mysqli_fetch_assoc($query);

	return $row;
}