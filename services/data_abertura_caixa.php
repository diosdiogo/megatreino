<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$idbanco = $_GET["idbanco"];

//ini_set("memory_limit","256M");

	$lista = '{"result":[' . json_encode(getCaixa($conexao, $id, $idbanco)) . ']}';
	echo $lista;


function getCaixa($conexao, $id, $idbanco) {
	
	$retorno = array();

	$sql = "select data from banco where academia={$id} and id={$idbanco}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push ($retorno, array( 'data' => substr($row['data'],0,4).'-'.substr($row['data'],5,2).'-'.substr($row['data'],8,2)));
	}

	return $retorno;
}
