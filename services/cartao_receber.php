<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getCartao_receber($conexao, $id)) . ']}';
	echo $lista;
}

function getCartao_receber($conexao, $id) {
	$retorno = array();

	$sql = "select cartao_receber.id, cartao_receber.emissao, cartao_receber.venc, cartao_receber.ocorrencia, cartao_receber.parcela, cartao_receber.valor as valortotal,cartao_receber.academia,cartao_receber.deletado, cartao.descricao from cartao_receber inner join cartao on (cartao_receber.cartao=cartao.id) where cartao_receber.academia={$id}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array('id' => $row['id'], 'emissao' => $row['emissao'], 'venc' => $row['venc'], 'ocorrencia' => $row['ocorrencia'], 'parcela' => $row['parcela'], 'valortotal' => $row['valortotal'], 'academia' => $row['academia'], 'deletado' => $row['deletado'], 'descricao' => utf8_encode($row['descricao'])));
	}

	return $retorno;
}