<?php

include 'conecta.php';

$idacademia = $_GET["idacademia"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];
$id = $_GET['id'];


/*if (isset($_GET['dataI'])) {
	$dataI = $_GET['dataI'];
} 

if (isset($_GET['dataF'])) {
	$dataF = $_GET['dataF'];
}*/

if ($state == "C") {

	if ($dataI == 0 && $dataF == 0) {
	$lista = '{"result":[' . json_encode(getFechamento($conexao, $idacademia, $id)) . ']}';
	echo $lista;
	}
	else {
	$lista = '{"result":[' . json_encode(getFechamentoData($conexao, $idacademia, $dataI, $dataF, $id)) . ']}';
	echo $lista;
	}

} 

function getFechamentoData($conexao, $idacademia, $dataI, $dataF, $id) {
	$retorno = array();

	$sql = "select * from fechamento where academia={$idacademia} and banco={$id} and data >= '{$dataI} 00:00:00' and data <= '{$dataF} 23:59:59' order by date(data) desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'academia' => $row['academia'], 
			'num' => $row['num'], 
			'data' => substr($row['data'],8,2).'/'.substr($row['data'],5,2).'/'.substr($row['data'],0,4).' '.substr($row['data'],11,8), 
			'fechamento_anterior' => substr($row['fechamento_anterior'],8,2).'/'.substr($row['fechamento_anterior'],5,2).'/'.substr($row['fechamento_anterior'],0,4).' '.substr($row['fechamento_anterior'],11,8), 
			'saldo_anterior' => $row['saldo_anterior'], 
			'credito' => $row['credito'], 
			'debito' => $row['debito'], 
			'saldo' => $row['saldo'], 
			'banco' => $row['banco'], 
			));
	}

	return $retorno;
}

function getFechamento($conexao, $idacademia, $id) {
	$retorno = array();

	$sql = "select * from fechamento where academia={$idacademia} and banco={$id} order by num desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'academia' => $row['academia'], 
			'num' => $row['num'], 
			'data' => substr($row['data'],8,2).'/'.substr($row['data'],5,2).'/'.substr($row['data'],0,4).' '.substr($row['data'],11,8), 
			'fechamento_anterior' => substr($row['fechamento_anterior'],8,2).'/'.substr($row['fechamento_anterior'],5,2).'/'.substr($row['fechamento_anterior'],0,4).' '.substr($row['fechamento_anterior'],11,8), 
			'saldo_anterior' => $row['saldo_anterior'], 
			'credito' => $row['credito'], 
			'debito' => $row['debito'], 
			'saldo' => $row['saldo'], 
			'banco' => $row['banco'], 
			));
	}

	return $retorno;
}