<?php

include 'conecta.php';
//ini_set("memory_limit","256M");
$id = $_GET["id"];
$state = $_GET["state"];
//$dataI = $_GET['dataI'];
//$dataF = $_GET['dataF'];
//$idbanco = $_GET['idbanco'];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getMovimento_caixa($conexao, $id)) . ']}';
	echo $lista;
}

function getMovimento_caixa($conexao, $id) {
	$retorno = array();

	$sql = "select movimento_caixa_aberto.id,  movimento_caixa_aberto.emissao,movimento_caixa_aberto.documento, movimento_caixa_aberto.nome,
	movimento_caixa_aberto.observacao,movimento_caixa_aberto.banco, movimento_caixa_aberto.valor,
	movimento_caixa_aberto.academia, forma_pagamento.descricao, historico.descricao as descricao_historico  from movimento_caixa_aberto
	inner join forma_pagamento on movimento_caixa_aberto.forma_pagamento=forma_pagamento.idforma_pagamento
	inner join historico on movimento_caixa_aberto.historico=historico.idhistorico inner join banco on banco.id=movimento_caixa_aberto.banco where movimento_caixa_aberto.academia={$id} order by emissao desc
 ";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push ($retorno, array(
			
			'id' => $row['id'],
			'documento'=>$row['documento'],
			'emissao' => substr($row['emissao'],8,2).'/'.substr($row['emissao'],5,2).'/'.substr($row['emissao'],0,4).'-'.substr($row['emissao'],11,8),
			'nome' => utf8_encode($row['nome']),
			'observacao' => utf8_encode($row['observacao']),
			'banco' => $row['banco'],
			'valor' => $row['valor'],
			'academia' => $row['academia'], 
			'descricao' => utf8_encode($row['descricao']),
			'descricao_historico' => utf8_encode($row['descricao_historico']),
			));
	}
//printf ($sql."<br/>");
	return $retorno;
}