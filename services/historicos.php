<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getHistoricos($conexao, $id)) . ']}';
	echo $lista;
}

function getHistoricos($conexao, $id) {
	$retorno = array();

	$sql = "select * from academia.historico where (academia={$id} or academia=1) and deletado='N'  order by descricao";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idhistorico' => $row['idhistorico'], 'descricao' => utf8_encode($row['descricao']), 'dc' => $row['dc'], 'academia' => $row['academia'], 'padrao' => $row['padrao'], 'codinterno' => $row['codinterno'], 'deletado' => $row['deletado']));
	}

	return $retorno;
}