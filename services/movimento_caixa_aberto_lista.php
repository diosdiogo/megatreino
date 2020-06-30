<?php

include 'conecta.php';


$idacademia = $_POST["idacademia"];
$banco = $_POST["banco"];

$lista = '{"result":[' . json_encode(getMovimento_caixa_aberto($conexao, $idacademia, $banco)) . ']}';
	echo $lista;

function getMovimento_caixa_aberto($conexao, $idacademia, $banco) {
	$retorno = array();

	$sql = "select * from movimento_caixa_aberto where academia={$idacademia} and banco={$banco}";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push ($retorno, array(
			
			'id' => $row['id'],
			'documento' => $row['documento'],
			'emissao' => $row['emissao'],
			'historico' => $row['historico'],
			'forma_pagamento' => $row['forma_pagamento'],
			'desc_documento' => $row['desc_documento'],
			'nome' => utf8_encode($row['nome']),
			'observacao' => utf8_encode($row['observacao']),
			'valor' => $row['valor'],
			'banco' => $row['banco'],
			'cancelado' => $row['cancelado'],
			'manual' => $row['manual'],
			'colaborador' => $row['colaborador'],
			'academia' => $row['academia'],
			'ocorrencia' => $row['ocorrencia'],
			'deletado' => $row['deletado'] 
			));
	}

	return $retorno;
}