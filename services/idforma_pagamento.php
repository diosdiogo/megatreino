<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(buscaDadosPagamento($conexao, $id)) . ']}';
	echo $lista;
}

function buscaDadosPagamento($conexao, $id) {
	$retorno = array();

	$sql = "select idforma_pagamento from forma_pagamento where academia={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array($row['idforma_pagamento']));
	}

	return $retorno;
}