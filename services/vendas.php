<?php

include 'conecta.php';

$id = $_GET["id"];

$lista = '{"result":[' . json_encode(getVenda($conexao, $id)) . ']}';
echo $lista;

function getVenda($conexao, $id) {
	$retorno = array();

	$sql = "select venda.id,
			venda.aluno,
			aluno.nome,
			aluno.idaluno,
			DATE_FORMAT(venda.data, '%m/%d/%Y - %H:%i') as data,
			venda.valor,
			venda.desconto,
			venda.total,
			venda.cancelado,
			venda.academia,
			venda.colaborador,
			venda.ocorrencia,
			venda.deletado
FROM academia.venda inner join aluno on(venda.aluno=aluno.idaluno) where venda.academia={$id} and venda.deletado='N'";

	$resultado = mysqli_query($conexao, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		array_push($retorno, array(
			'id' => $row['id'],
			'data' => $row['data'],
			'valor' => $row['valor'],
			'desconto' => $row['desconto'],
			'total' => $row['total'],
			'cancelado' => $row['cancelado'],
			'academia' => $row['academia'],
			'colaborador' => $row['colaborador'],
			'ocorrencia' => $row['ocorrencia'],
			'deletado' => utf8_encode($row['deletado']),
			'nome' => utf8_encode($row['nome'])));
	}

	return $retorno;
}