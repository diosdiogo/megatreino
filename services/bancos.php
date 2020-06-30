<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getBancos($conexao, $id)) . ']}';
	echo $lista;
}

function getBancos($conexao, $id) {
	$retorno = array();

	$sql = "select * from banco where academia={$id} and deletado='N' order by nome";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'nome' => utf8_encode($row['nome']), 'saldo' => $row['saldo'], 'saldo_anterior' => $row['saldo_anterior'], 'situacao' => $row['situacao'], 'data' => substr($row['data'], 8, 2) . '/' . substr($row['data'], 5, 2) . '/' . substr($row['data'], 0, 4), 'academia' => $row['academia'], 'deletado' => $row['deletado']));
	}

	return $retorno;
}