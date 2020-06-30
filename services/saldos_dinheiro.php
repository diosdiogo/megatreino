<?php
include 'conecta.php';


$id = $_GET["id"];
$numfechamento = $_GET["numfechamento"];


$lista = '{"result":[' . json_encode(vendasMovimento_caixa_fechado($conexao, $id, $numfechamento)) . ']}';
	echo $lista;

function vendasMovimento_caixa_fechado($conexao, $id, $numfechamento) {
	$retorno = array();

	$sql = "select sum(movimento_caixa_fechado.valor) as total from movimento_caixa_fechado inner join forma_pagamento on (movimento_caixa_fechado.forma_pagamento = forma_pagamento.idforma_pagamento) inner join historico on (movimento_caixa_fechado.historico = historico.idhistorico) where movimento_caixa_fechado.academia={$id} and numfechamento={$numfechamento} and idforma_pagamento=1 and (idhistorico=1 or idhistorico=11 or idhistorico=63 or idhistorico=71 or idhistorico=72 or idhistorico=144)";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('total' => $row['total']));
	}

	return $retorno;
}