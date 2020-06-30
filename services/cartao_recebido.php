<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getCartao_recebido($conexao, $id, $dataI, $dataF)) . ']}';
	echo $lista;
}

function getCartao_recebido($conexao, $id, $dataI, $dataF) {
	$retorno = array();

	$sql = "select cartao_recebido.id, cartao_recebido.emissao, cartao_recebido.venc,cartao_recebido.ocorrencia,cartao_recebido.valor,cartao_recebido.parcela, cartao_recebido.academia,cartao_recebido.cartao, cartao.id,cartao.descricao from cartao_recebido inner join cartao on (cartao_recebido.cartao  = cartao.id)  where cartao_recebido.academia={$id} and emissao >= '{$dataI}' and emissao <= '{$dataF}' order by date(emissao) desc ";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {

		array_push($retorno, array('id' => $row['id'], 'emissao' => $row['emissao'], 'venc' => $row['venc'], 'ocorrencia' => $row['ocorrencia'], 'valor' => $row['valor'], 'parcela' => $row['parcela'], 'descricao' => utf8_encode($row['descricao'])));
	}

	return $retorno;
}