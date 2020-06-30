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

	$sql = "select * from cartao_receber where academia={$id} and deletado='N'";
	

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		
		
		array_push($retorno, array('id' => $row['id'], 'emissao' => $row['emissao'], 'cartao' => $row['cartao'], 'doc' => $row['doc'], 'valor' => $row['valor'], 'prazo' => $row['prazo'], 'taxa' => $row['taxa'], 'venc' => $row['venc'], 'vlrec' => $row['vlrec'], 'ocorrencia' => $row['ocorrencia'], 'parcela' => $row['parcela'], 'ocorrencia' => $row['ocorrencia'], 'parcela' => $row['parcela'],  'academia' => $row['academia'], 'idassinatura_iugu' => $row['idassinatura_iugu'], 'status' => $row['status'],  'deletado' => $row['deletado']));
	}

	return $retorno;
}