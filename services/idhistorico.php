<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(buscaDadosHistorico($conexao, $id)) . ']}';
	echo $lista;
}

function buscaDadosHistorico($conexao, $id) {
	$retorno = array();

	$sql = "select idhistorico from historico where academia={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array($row['idhistorico']));
	}

	return $retorno;
}