<?php

include 'conecta.php';

$id = $_GET["id"];

$lista = '{"result":[' . json_encode(getFechamento($conexao, $id)) . ']}';
	echo $lista;

function getFechamento($conexao, $id) {
	$retorno = array();

	$sql = "select * from fechamento where academia={$id} order by id desc limit 1;";

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