<?php

include 'conecta.php';

setlocale(LC_ALL, 'pt_BR.utf-8');
date_default_timezone_set('America/Bahia');

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getFormaDePagamento($conexao, $id)) . ']}';
	echo $lista;
}

function getFormaDePagamento($conexao, $id) {
	$retorno = array();

	$sql = "select * from forma_pagamento where academia=1 or academia={$id} and deletado='N' order by descricao";

	$resultado = mysqli_query($conexao, $sql);

	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array(
			'id' => $row['idforma_pagamento'],
			'descricao' => utf8_encode($row['descricao']),
			'sigla' => utf8_encode($row['sigla']),
			'padrao' => $row['padrao'],
			'academia' => $row['academia'],
			'deletado' => $row['deletado']));
	}

	return $retorno;
}

/*

idforma_pagamento int(11) AI PK
descricao varchar(45)
sigla varchar(5)
padrao tinyint(4)
academia int(11)
deletado char(1)
 */