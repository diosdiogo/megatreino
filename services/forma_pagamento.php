<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getforma_pagamento($conexao, $id)) . ']}';
	echo $lista;
}

function getforma_pagamento($conexao, $idacademia) {
	$retorno = array();

	$sql = "select * from forma_pagamento where (academia=1 or academia={$idacademia}) and deletado='N'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idforma_pagamento' => $row['idforma_pagamento'], 'descricao' => utf8_encode($row['descricao']), 'sigla' => $row['sigla'], 'padrao' => $row['padrao'], 'academia' => $row['academia'], 'deletado' => $row['deletado']));
	}

	return $retorno;
}