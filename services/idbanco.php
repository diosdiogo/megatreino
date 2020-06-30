<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(buscaDadosBanco($conexao, $id)) . ']}';
	echo $lista;
}

function buscaDadosBanco($conexao, $id) {
	$retorno = array();

	$sql = "select id from banco where academia={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array($row['id']));
	}

	return $retorno;
}