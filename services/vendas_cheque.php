<?php
include 'conecta.php';


$id = $_GET["id"];
$numfechamento = $_GET["numfechamento"];


$lista = '{"result":[' . json_encode(vendasMovimento_caixa_fechado($conexao, $id, $numfechamento)) . ']}';
	echo $lista;

function vendasMovimento_caixa_fechado($conexao, $id, $numfechamento) {
	$retorno = array();

	$sql = "select sum(movimento_caixa_fechado.valor) as total, movimento_caixa_fechado.historico, historico.descricao, movimento_caixa_fechado.valor
from movimento_caixa_fechado 
inner join forma_pagamento on (movimento_caixa_fechado.forma_pagamento = forma_pagamento.idforma_pagamento) 
inner join historico on (movimento_caixa_fechado.historico = historico.idhistorico) 
where movimento_caixa_fechado.academia={$id} and numfechamento={$numfechamento}  and idforma_pagamento=2 and dc='C' 
and (movimento_caixa_fechado.historico<>1 and movimento_caixa_fechado.historico<>72)";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('total' => $row['total'], 'historico' => $row['historico'],'descricao' => utf8_encode($row['descricao']), 'valor' => $row['valor']));
	}

	return $retorno;
}