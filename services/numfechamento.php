<?php

include 'conecta.php';

$id = $_GET["id"];

$lista = '{"result":[' . json_encode(getFechamento($conexao, $id)) . ']}';
echo $lista;

function getFechamento($conexao, $id) {
	$retorno = array();

	$sql = "select max(num) + 1 as num from fechamento where academia={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'num' => $row['num'],
		));
	}

	if ($retorno[0]['num'] == null) {
		$retorno = array(['num' => utf8_encode(1)]);
	}

	return $retorno;
}
