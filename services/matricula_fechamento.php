<?php
include 'conecta.php';


$id = $_GET["id"];
$numfechamento = $_GET["numfechamento"];



$lista = '{"result":[' . json_encode(matriculaMovimento_caixa_fechado($conexao, $id, $numfechamento)) . ']}';
	echo $lista;

function matriculaMovimento_caixa_fechado($conexao, $id, $numfechamento) {
	$retorno = array();

	$sql = "select movimento_caixa_fechado.id, movimento_caixa_fechado.documento, movimento_caixa_fechado.desc_documento, movimento_caixa_fechado.emissao, movimento_caixa_fechado.nome, movimento_caixa_fechado.observacao, movimento_caixa_fechado.valor, movimento_caixa_fechado.banco, movimento_caixa_fechado.cancelado, movimento_caixa_fechado.manual, movimento_caixa_fechado.colaborador, movimento_caixa_fechado.numfechamento, movimento_caixa_fechado.academia, movimento_caixa_fechado.fechamento, movimento_caixa_fechado.ocorrencia, historico.idhistorico, historico.descricao, historico.dc, historico.padrao, forma_pagamento.idforma_pagamento, forma_pagamento.descricao, if(forma_pagamento.sigla='CT','CA',if(forma_pagamento.sigla='PZ','BL',forma_pagamento.sigla)) as sigla, forma_pagamento.padrao from movimento_caixa_fechado inner join forma_pagamento on (movimento_caixa_fechado.forma_pagamento = forma_pagamento.idforma_pagamento) inner join historico on (movimento_caixa_fechado.historico = historico.idhistorico) where movimento_caixa_fechado.academia={$id} and numfechamento={$numfechamento} and idhistorico=1 order by 'DN'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array('id' => $row['id'], 'documento' => $row['documento'], 'desc_documento' => ['desc_documento'], 'emissao' => $row['emissao'],  'nome' => utf8_encode($row['nome']), 'observacao' => $row['observacao'], 'valor' => $row['valor'], 'banco' => $row['banco'], 'cancelado' => $row['cancelado'], 'manual' => $row['manual'], 'colaborador' => $row['colaborador'], 'academia' => $row['academia'],'ocorrencia' => $row['ocorrencia'], 'idhistorico' => $row['idhistorico'], 'descricao' => utf8_encode($row['descricao']), 'dc' => $row['dc'], 'padrao' => $row['padrao'], 'idforma_pagamento' => $row['idforma_pagamento'], 'descricao' => utf8_encode($row['descricao']), 'sigla' => $row['sigla'], 'padrao' => $row['padrao']));
	}

	return $retorno;
}