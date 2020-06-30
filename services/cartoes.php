<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getCartoes($conexao, $id)) . ']}';
	echo $lista;
}

function getCartoes($conexao, $id) {
	$retorno = array();

	$sql = "select * from cartao where (academia={$id} or academia=1) and deletado='N'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'descricao' => utf8_encode($row['descricao']), 'taxadeb' => $row['taxadeb'], 'taxacred' => $row['taxacred'], 'taxaparc' => $row['taxaparc'], 'academia' => $row['academia'], 'padrao' => $row['padrao'], 'deletado' => $row['deletado']));
	}

	return $retorno;
}