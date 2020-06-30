<?php

include 'conecta.php';

$id = $_GET["id"];
$state = $_GET["state"];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getMensalidadeRecebidas($conexao, $id)) . ']}';
	echo $lista;
}

function getMensalidadeRecebidas($conexao, $id) {
	$retorno = array();

	$sql = "select * from mensalidade_recebida  where academia={$id} order by data";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['idmensalidade_recebida'],
			'data' => $row['data'],
			'aluno' => $row['aluno'],
			'valor' => $row['valor'],
			'forma_pagamento' => $row['forma_pagamento'],
			'academia' => $row['academia'],
			'pacote' => $row['pacote'],
			'vigencia_de' => $row['vigencia_de'],
			'vigencia_ate' => $row['vigencia_ate'],
			'observacao' => $row['observacao'],
			'movimento_caixa' => $row['movimento_caixa']));
	}

	return $retorno;
}

/*

Table: mensalidade_recebida

idmensalidade_recebida int(11) AI PK 
data date 
aluno int(11) 
valor decimal(10,2) 
forma_pagamento int(11) 
academia int(11) 
pacote int(11) 
vigencia_de date 
vigencia_ate date 
observacao longtext 
movimento_caixa int(11)
*/