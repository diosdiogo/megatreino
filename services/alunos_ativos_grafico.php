<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getalunos_ativos($conexao, $id)) . ']}';
	echo $lista;
}

function getalunos_ativos($conexao, $id) {
	$retorno = array();

	$sql = "select * from forma_pagamento where academia={$id} and deletado='N'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idforma_pagamento' => $row['idforma_pagamento'], 'descricao' => utf8_encode($row['descricao']), 'sigla' => $row['sigla'], 'padrao' => $row['padrao'], 'academia' => $row['academia'], 'deletado' => $row['deletado']));
	}

	return $retorno;
}