<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getCartao($conexao, $id)) . ']}';
	echo $lista;
}

function getCartao($conexao, $id) {
	$retorno = array();

	$sql = "select cartao_receber.cartao as idcartao, cartao.descricao as descricao, sum(cartao_receber.valor) as valor from cartao_receber join cartao on cartao_receber.cartao=cartao.id where cartao_receber.academia={$id} group by descricao";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('idcartao' => $row['idcartao'], 'descricao' => utf8_encode($row['descricao']), 'valor' => $row['valor']));
	}

	return $retorno;
}