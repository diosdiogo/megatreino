<?php

include 'conecta.php';

ini_set("memory_limit", "256M");

$id = $_GET["id"];
$state = $_GET["state"];
$dataI = $_GET['dataI'];
$dataF = $_GET['dataF'];
$filtrarbanco = $_GET['filtrarbanco'];

if ($state == "C") {
	$lista = '{"result":[' . json_encode(getMovimento_caixa_aberto($conexao, $id, $dataI, $dataF, $filtrarbanco)) . ']}';
	echo $lista;
}

function getMovimento_caixa_aberto($conexao, $id, $dataI, $dataF, $filtrarbanco) {
	$retorno = array();

	$sql = "select movimento_caixa_fechado.id as idcaixa , movimento_caixa_fechado.documento, movimento_caixa_fechado.desc_documento, movimento_caixa_fechado.cancelado, movimento_caixa_fechado.colaborador, movimento_caixa_fechado.ocorrencia, movimento_caixa_fechado.emissao, movimento_caixa_fechado.nome as nome, movimento_caixa_fechado.observacao, movimento_caixa_fechado.valor, movimento_caixa_fechado.deletado, movimento_caixa_fechado.academia, forma_pagamento.descricao, historico.descricao as descricao_historico, banco.nome as nome_banco, banco.id as id, banco.situacao from movimento_caixa_fechado inner join forma_pagamento on (movimento_caixa_fechado.forma_pagamento=forma_pagamento.idforma_pagamento) inner join historico on (movimento_caixa_fechado.historico=historico.idhistorico) inner join banco on (banco.id=movimento_caixa_fechado.banco) where movimento_caixa_fechado.academia={$id} and banco={$filtrarbanco} and emissao >= cast('{$dataI} 00:00:00' as datetime) and emissao <= cast('{$dataF} 23:59:59' as datetime) order by date(emissao) desc";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(

			'id' => $row['id'],
			'documento' => $row['documento'],
			'desc_documento' => utf8_encode($row['desc_documento']),
			'cancelado' => utf8_encode($row['cancelado']),
			'colaborador' => $row['colaborador'],
			'ocorrencia' => $row['ocorrencia'],
			'emissao' => utf8_encode(substr($row['emissao'], 8, 2) . '/' . substr($row['emissao'], 5, 2) . '/' . substr($row['emissao'], 0, 4) . '-' . substr($row['emissao'], 11, 8)),
			'nome' => $row['nome'],
			'observacao' => utf8_encode($row['observacao']),
			'valor' => $row['valor'],
			'deletado' => utf8_encode($row['deletado']),
			'academia' => $row['academia'],
			'descricao' => utf8_encode($row['descricao']),
			'descricao_historico' => utf8_encode($row['descricao_historico']),
			'nome_banco' => utf8_encode($row['nome_banco'])));
	}
	//echo '<p>' . $sql . '</p>';

	return $retorno;
}