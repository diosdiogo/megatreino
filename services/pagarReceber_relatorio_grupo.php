<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

$pagamento = $_GET["pagamento"];

$pg1 = 'canc=';
$pg2 = $pagamento;
$pg3 = ' and';

if ($state == "C") {

	if ($pagamento == 1) {
		$lista = '{"result":[' . json_encode(getPagarReceber($conexao, $id, $dataI, $dataF)) . ']}';
		echo $lista;
	} else if ($pagamento == "CT") {

		$lista = '{"result":[' . json_encode(getPagarReceberCA($conexao, $id, $dataI, $dataF)) . ']}';
		echo $lista;

	} else if ($pagamento == "DN" or $pagamento == "SV") {

		$lista = '{"result":[' . json_encode(getPagarReceberDN($conexao, $id, $dataI, $dataF)) . ']}';
		echo $lista;

	} else if ($pagamento == "CH") {

		$lista = '{"result":[' . json_encode(getPagarReceberCH($conexao, $id, $dataI, $dataF)) . ']}';
		echo $lista;

	} else if ($pagamento == "REC") {

		$lista = '{"result":[' . json_encode(getPagarReceberREC($conexao, $id, $dataI, $dataF)) . ']}';
		echo $lista;

	}
}

function getPagarReceber($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select canc, sum(val_pago) as valpago from academia.pagar_receber where pagar_receber.academia={$id} and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}' group by canc
order by date(pagamento) desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'canc' => $row['canc'],
			'valpago' => $row['valpago']));
	}

	return $retorno;
}

function getPagarReceberCA($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select canc, sum(val_pago) as valpago from academia.pagar_receber where pagar_receber.academia={$id} and (canc='CA' or canc='DN/CT' or canc='CT') and pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'group by canc order by date(pagamento) desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'canc' => $row['canc'],
			'valpago' => $row['valpago']));
	}

	return $retorno;
}

function getPagarReceberDN($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select canc, sum(val_pago) as valpago from academia.pagar_receber where pagar_receber.academia={$id} and(canc='DN' or canc='DN/CT') and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'group by canc
order by date(pagamento) desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'canc' => $row['canc'],
			'valpago' => $row['valpago']));
	}

	return $retorno;
}

function getPagarReceberCH($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select canc, sum(val_pago) as valpago from academia.pagar_receber where pagar_receber.academia={$id} and (canc='CH') and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'group by canc
order by date(pagamento) desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'canc' => $row['canc'],
			'valpago' => $row['valpago']));
	}

	return $retorno;
}

function getPagarReceberREC($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select canc, sum(val_pago) as valpago from academia.pagar_receber where pagar_receber.academia={$id} and canc='REC' and
pagar_receber.pago = 1 and pagamento >= '{$dataI}' and pagamento <= '{$dataF}'group by canc
order by date(pagamento) desc";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'canc' => $row['canc'],
			'valpago' => $row['valpago']));
	}

	return $retorno;
}