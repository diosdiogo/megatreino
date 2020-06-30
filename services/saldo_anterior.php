<?php

include 'conecta.php';

$id = $_GET["id"];

$lista = '{"result":[' . json_encode(getSaldo($conexao, $id)) . ']}';
	echo $lista;

function getSaldo($conexao, $id) {
	$retorno = array();

	$sql = "select saldo from fechamento where academia={$id} order by id desc limit 1";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'saldo' => $row['saldo'], 
			));
	}

	return $retorno;
}

